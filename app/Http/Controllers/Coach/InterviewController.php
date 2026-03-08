<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Competence;
use App\Models\Interview;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Carbon\Carbon;

class InterviewController extends Controller
{
    public function start(Appointment $appointment)
    {
        abort_if($appointment->coachAssignment->coach_id !== auth()->id(), 403);

        $interview = $appointment->interview ?? Interview::create([
            'appointment_id' => $appointment->id,
            'status'         => 'in_progress',
        ]);

        // $appointment->update(['status' => 'in_progress']);

        $competences = Competence::orderBy('order')->get();

        return view('coach.interviews.start', compact('appointment', 'interview', 'competences'));
    }

    // public function store(Request $request, Appointment $appointment)
    // {
    //     abort_if($appointment->coachAssignment->coach_id !== auth()->id(), 403);

    //     $request->validate([
    //         'blocs'        => 'required|array',
    //         'strengths'    => 'required|string',
    //         'weaknesses'   => 'required|string',
    //         'coach_summary' => 'nullable|string',
    //     ]);

    //     $interview = $appointment->interview;

    //     // Calculer le score de chaque bloc et enregistrer
    //     $totalGeneral = 0;

    //     foreach ($request->blocs as $blocKey => $questions) {
    //         $competenceId = $request->bloc_ids[$blocKey] ?? null;
    //         if (!$competenceId) continue;

    //         // Sommer les réponses du bloc
    //         $scoreBloc = array_sum(array_map('intval', $questions));

    //         // S'assurer que le score ne dépasse pas 20
    //         $scoreBloc = min($scoreBloc, 20);
    //         $totalGeneral += $scoreBloc;

    //         $interview->scores()->updateOrCreate(
    //             ['competence_id' => $competenceId],
    //             [
    //                 'note'    => $scoreBloc,
    //                 'comment' => null,
    //             ]
    //         );
    //     }

    //     // Note finale = total / 5
    //     $noteFinale = round($totalGeneral / 5);

    //     // Orientation automatique
    //     $orientation = match (true) {
    //         $noteFinale <= 7  => 'Renforcement compétences (formation de base)',
    //         $noteFinale <= 11 => 'Stage / immersion professionnelle',
    //         $noteFinale <= 15 => 'Insertion emploi accompagnée',
    //         default           => 'Insertion rapide / autonomie',
    //     };

    //     $interview->update([
    //         'status'        => 'completed',
    //         'total_score'   => $totalGeneral,
    //         'strengths'     => $request->strengths,
    //         'weaknesses'    => $request->weaknesses,
    //         'coach_summary' => $request->coach_summary ?? $orientation,
    //         'completed_at'  => now(),
    //     ]);

    //     $appointment->update(['status' => 'completed']);

    //     // return redirect()->route('coach.interviews.report', $interview)
    //     //     ->with('success', 'Entretien terminé. Voici le rapport.');

    //     $candidat = $appointment->coachAssignment->candidat;

    //     return redirect()->route('coach.needs.create', $candidat)
    //         ->with('success', 'Entretien terminé. Assignez maintenant l\'orientation du candidat.');
    // }
    public function store(Request $request, Appointment $appointment)
    {
        abort_if($appointment->coachAssignment->coach_id !== auth()->id(), 403);

        $request->validate([
            'blocs'         => 'required|array',
            'strengths'     => 'required|string',
            'weaknesses'    => 'required|string',
            'coach_summary' => 'nullable|string',
        ]);

        $interview = $appointment->interview;

        // Calculer le score de chaque bloc et enregistrer
        $totalGeneral = 0;

        foreach ($request->blocs as $blocKey => $questions) {
            $competenceId = $request->bloc_ids[$blocKey] ?? null;
            if (!$competenceId) continue;

            $scoreBloc = array_sum(array_map('intval', $questions));
            $scoreBloc = min($scoreBloc, 20);
            $totalGeneral += $scoreBloc;

            $interview->scores()->updateOrCreate(
                ['competence_id' => $competenceId],
                ['note' => $scoreBloc, 'comment' => null]
            );
        }

        // Note finale = total / 5
        $noteFinale = round($totalGeneral / 5);

        // Orientation automatique selon le score
        $orientationType = match (true) {
            $noteFinale <= 9  => 'formation',
            $noteFinale <= 14 => 'stage',
            $noteFinale <= 17 => 'insertion_emploi',
            default           => 'auto_emploi',
        };

        $orientationLabel = match ($orientationType) {
            'formation'       => 'Renforcement compétences (formation de base)',
            'stage'           => 'Stage / immersion professionnelle',
            'insertion_emploi' => 'Insertion emploi accompagnée',
            'auto_emploi'     => 'Insertion rapide / autonomie',
        };

        $interview->update([
            'status'        => 'completed',
            'total_score'   => $totalGeneral,
            'strengths'     => $request->strengths,
            'weaknesses'    => $request->weaknesses,
            'coach_summary' => $request->coach_summary ?? $orientationLabel,
            'completed_at'  => now(),
        ]);

        $appointment->update(['status' => 'completed']);

        $candidat = $appointment->coachAssignment->candidat;

        // Enregistrement automatique de l'orientation dans NeedAssignment
        \App\Models\NeedAssignment::updateOrCreate(
            [
                'candidat_id' => $candidat->id,
                'interview_id' => $interview->id,
            ],
            [
                'coach_id'    => auth()->id(),
                'type'        => $orientationType,
                'description' => $orientationLabel,
            ]
        );

        return redirect()->route('coach.interviews.report', $interview)
            ->with('success', 'Entretien terminé. L\'orientation a été assignée automatiquement.');
    }

    public function report(Interview $interview)
    {
        $interview->load('scores.competence', 'appointment.coachAssignment.candidat.candidatProfile');
        return view('coach.interviews.report', compact('interview'));
    }

    public function exportPdf(Interview $interview)
    {
        $interview->load('scores.competence', 'appointment.coachAssignment.candidat.candidatProfile');
        $pdf = Pdf::loadView('coach.interviews.pdf', compact('interview'));
        $candidatName = $interview->appointment->coachAssignment->candidat->name;
        return $pdf->download("rapport-entretien-" . str()->slug($candidatName) . ".pdf");
    }

    public function reportByCandidat(User $candidat)
    {
        $interview = Interview::whereHas('appointment', function ($q) use ($candidat) {
            $q->whereHas('coachAssignment', function ($q2) use ($candidat) {
                $q2->where('candidat_id', $candidat->id);
            });
        })->with('scores.competence', 'appointment.coachAssignment.candidat.candidatProfile')
            ->first();

        if (!$interview) {
            return redirect()->back()->with('error', 'Aucun entretien passé pour ce candidat.');
        }

        return view('coach.interviews.report', compact('interview'));
    }

    public function termine(Appointment $appointment)
    {
        // abort_if($appointment->coachAssignment->coach_id !== auth()->id(), 403);
        $coach = auth()->user();

        // ── Candidats ayant passé l'entretien ──

        // ✅ 1 seule requête pour tout charger — on filtre ensuite en PHP
        $allInterviews = Interview::whereHas(
            'appointment.coachAssignment',
            fn($q) => $q->where('coach_id', $coach->id)
        )
            ->where('status', 'completed')
            ->orderBy('completed_at', 'desc')
            ->with([
                'appointment.coachAssignment.candidat.candidatProfile',
                'appointment.coachAssignment.candidat.needAssignment', 
                'appointment.coachAssignment.coach',                   
            ])
            ->get();

        // ✅ Filtrage en PHP — pas de requêtes supplémentaires
        $interviewsAujourdhui = $allInterviews->filter(
            fn($i) => \Carbon\Carbon::parse($i->completed_at)->isToday()
        );

        $interviewsSemaine = $allInterviews->filter(
            fn($i) => \Carbon\Carbon::parse($i->completed_at)->isBetween(
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            )
        );

        $interviewsMois = $allInterviews->filter(
            fn($i) => \Carbon\Carbon::parse($i->completed_at)->month === Carbon::now()->month
                && \Carbon\Carbon::parse($i->completed_at)->year  === Carbon::now()->year
        );

        return view('coach.interviews.termine', compact(
            'interviewsAujourdhui',
            'interviewsSemaine',
            'interviewsMois'
        ));
    }
}
