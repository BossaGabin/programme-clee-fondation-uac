<?php

namespace App\Http\Controllers\Candidat;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Interview;
use App\Models\ProfessionalProject;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\AppointmentProposal;
use App\Mail\EntretienProgrammeMail;
use Illuminate\Support\Facades\Mail;
 use App\Mail\EntretienConfirmeCoachMail;

class DashboardController extends Controller
{
    public function index()
    {
        $candidat = auth()->user()->load([
            'candidatProfile',
            'diagnosticRequests',
            'candidatAssignment.coach.coachProfile',
            'needAssignment',
            'followUpSteps',
            'professionalProject',
        ]);

        $profile = $candidat->candidatProfile;
        $demande = $candidat->diagnosticRequests()->latest()->first();

        $steps = $candidat->followUpSteps()
            ->orderBy('created_at', 'asc')
            ->get();

        $compteurs = [
            'steps_total'     => $steps->count(),
            'steps_completed' => $steps->where('status', 'completed')->count(),
            'steps_progress'  => $steps->where('status', 'in_progress')->count(),
        ];

            // Proposition d'horaires en attente de choix
        $proposalEnAttente = AppointmentProposal::whereHas('coachAssignment', function ($q) use ($candidat) {
            $q->where('candidat_id', $candidat->id);
        })
        ->where('status', 'pending')
        ->with('coachAssignment.coach')
        ->first();

        // Prochain entretien programmé
        $entretien = Appointment::whereHas('coachAssignment', function ($q) use ($candidat) {
            $q->where('candidat_id', $candidat->id);
        })->where('status', 'scheduled')
            ->orderBy('scheduled_date')
            ->first();

        // Résultats d'entretien si déjà passé
        $interview = Interview::whereHas('appointment', function ($q) use ($candidat) {
            $q->whereHas('coachAssignment', function ($q2) use ($candidat) {
                $q2->where('candidat_id', $candidat->id);
            });
        })->with('scores.competence')->first();

        // ── DONNÉES GRAPHIQUES ──

        // 1. Radar compétences
        $radarChart = ['labels' => [], 'data' => []];
        if ($interview) {
            foreach ($interview->scores->sortBy('competence.order') as $score) {
                $radarChart['labels'][] = $score->competence->name;
                $radarChart['data'][]   = $score->note;
            }
        }

        // 2. Donut progression parcours
        $parcourChart = [
            'labels' => ['Terminées', 'En cours'],
            'data'   => [
                $compteurs['steps_completed'],
                $compteurs['steps_progress'],
            ],
        ];

        return view('candidat.dashboard', compact(
            'candidat',
            'profile',
            'demande',
            'steps',
            'compteurs',
            'entretien',
            'interview',
            'radarChart',
            'parcourChart',
            'proposalEnAttente'
        ));

        // return view('candidat.dashboard', compact(
        //     'candidat',
        //     'profile',
        //     'demande',
        //     'steps',
        //     'compteurs',
        //     'entretien',
        //     'interview'
        // ));
    }



    public function exportPdfCandidat()
    {
        $candidat = auth()->user();
        $project = ProfessionalProject::where('candidat_id', $candidat->id)->firstOrFail();
        $pdf = Pdf::loadView('coach.projects.pdf', compact('candidat', 'project'));
        return $pdf->download('projet-professionnel-' . str()->slug($candidat->name) . '.pdf');
    }

    // ─────────────────────────────────────────────────
    // Le candidat confirme un horaire
    // ─────────────────────────────────────────────────
    public function confirmAppointment(AppointmentProposal $proposal, int $choix)
    {
        // Vérifier que c'est bien ce candidat
        abort_if(
            $proposal->coachAssignment->candidat_id !== auth()->id(),
            403
        );

        // Vérifier que la proposition est encore en attente
        if ($proposal->status !== 'pending') {
            return redirect()->route('candidat.dashboard')
                ->with('error', 'Cette proposition n\'est plus disponible. Contactez votre coach.');
        }

        // Vérifier que le choix est valide
        if (!in_array($choix, [1, 2, 3])) {
            return redirect()->route('candidat.dashboard')
                ->with('error', 'Choix invalide.');
        }

        // Récupérer la date et heure choisies
        $date  = $proposal->{'date_'  . $choix};
        $heure = $proposal->{'heure_' . $choix};

        // Marquer la proposition comme confirmée
        $proposal->update([
            'status'         => 'confirmed',
            'choix_candidat' => $choix,
        ]);

        // Créer l'entretien
        Appointment::create([
            'coach_assignment_id' => $proposal->coach_assignment_id,
            'scheduled_date'      => $date,
            'scheduled_time'      => $heure,
            'mode'                => $proposal->mode,
            'location'            => $proposal->location,
            'meeting_link'        => $proposal->meeting_link,
            'status'              => 'scheduled',
        ]);

        $candidat = $proposal->coachAssignment->candidat;
        $coach    = $proposal->coachAssignment->coach;

        $dateFormatee  = \Carbon\Carbon::parse($date)->format('d/m/Y');
        $heureFormatee = \Carbon\Carbon::parse($heure)->format('H:i');


        // Mail de confirmation au candidat
        Mail::to($candidat->email)->send(new EntretienProgrammeMail(
            candidatName:   $candidat->name,
            coachName:      $coach->name,
            date:           $dateFormatee,
            heure:          $heureFormatee,
            mode:           $proposal->mode,
            location:       $proposal->location,
            meetingLink:    $proposal->meeting_link,
            plateforme:     $proposal->plateforme_enligne,
            numeroWhatsapp: $proposal->numero_whatsapp,
            numeroAppel:    $proposal->numero_appel,
        ));

        // Mail de confirmation au coach — message différent
        Mail::to($coach->email)->send(new EntretienConfirmeCoachMail(
            coachName:      $coach->name,
            candidatName:   $candidat->name,
            date:           $dateFormatee,
            heure:          $heureFormatee,
            mode:           $proposal->mode,
            location:       $proposal->location,
            meetingLink:    $proposal->meeting_link,
            plateforme:     $proposal->plateforme_enligne,
            numeroWhatsapp: $proposal->numero_whatsapp,
            numeroAppel:    $proposal->numero_appel,
        ));

        return redirect()->route('candidat.dashboard')
            ->with('success', 'Entretien confirmé pour le ' . $dateFormatee . ' à ' . $heureFormatee . '. Vous recevrez un email de confirmation.');
    }
}
