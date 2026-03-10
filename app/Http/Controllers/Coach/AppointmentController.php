<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Mail\EntretienAnnuleMail;
use App\Mail\EntretienReporteMail;
use App\Mail\EntretienProgrammeMail;
use App\Mail\AppointmentProposalMail;
use App\Models\Appointment;
use App\Models\AppointmentProposal;
use App\Models\CoachAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AppointmentController extends Controller
{
    // ─────────────────────────────────────────────────
    // Liste des entretiens programmés
    // ─────────────────────────────────────────────────
    public function index()
    {
        // Entretiens confirmés par le candidat
        $appointments = Appointment::whereHas('coachAssignment', function ($q) {
            $q->where('coach_id', auth()->id());
        })
            ->with('coachAssignment.candidat')
            ->where('status', 'scheduled')
            ->get();

        // Propositions en attente de choix du candidat
        $proposals = AppointmentProposal::whereHas('coachAssignment', function ($q) {
            $q->where('coach_id', auth()->id());
        })
            ->with('coachAssignment.candidat')
            ->where('status', 'pending')
            ->get();

        return view('coach.appointments.index', compact('appointments', 'proposals'));
    }

    // ─────────────────────────────────────────────────
    // Formulaire — proposer 3 horaires
    // ─────────────────────────────────────────────────
    public function create(CoachAssignment $assignment)
    {
        abort_if($assignment->coach_id !== auth()->id(), 403);

        if ($assignment->status !== 'active') {
            return redirect()->route('coach.dashboard')
                ->with('error', 'Cette affectation n\'est pas encore confirmée.');
        }

        $proposalExistante = AppointmentProposal::where('coach_assignment_id', $assignment->id)
            ->where('status', 'pending')
            ->first();

        $existant = Appointment::where('coach_assignment_id', $assignment->id)
            ->where('status', 'scheduled')
            ->first();

        return view('coach.appointments.create', compact('assignment', 'proposalExistante', 'existant'));
    }

    // ─────────────────────────────────────────────────
    // Enregistrer la proposition de 3 horaires
    // ─────────────────────────────────────────────────
    public function store(Request $request, CoachAssignment $assignment)
    {
        abort_if($assignment->coach_id !== auth()->id(), 403);

        if ($assignment->status !== 'active') {
            return redirect()->route('coach.dashboard')
                ->with('error', 'Cette affectation n\'est pas encore confirmée.');
        }

        $proposalExistante = AppointmentProposal::where('coach_assignment_id', $assignment->id)
            ->where('status', 'pending')
            ->first();

        if ($proposalExistante) {
            return redirect()->back()
                ->with('error', 'Vous avez déjà envoyé une proposition au candidat. Attendez sa réponse.');
        }

        $existant = Appointment::where('coach_assignment_id', $assignment->id)
            ->where('status', 'scheduled')
            ->first();

        if ($existant) {
            return redirect()->back()
                ->with('error', 'Un entretien est déjà programmé pour ce candidat.');
        }

        $demande = $assignment->diagnosticRequest;

        $request->validate([
            'date_1'       => 'required|date|after_or_equal:today',
            'heure_1'      => 'required',
            'date_2'       => 'required|date|after_or_equal:today',
            'heure_2'      => 'required',
            'date_3'       => 'required|date|after_or_equal:today',
            'heure_3'      => 'required',
            'location'     => 'required_if:mode_entretien,presentiel|nullable|string',
            'meeting_link' => 'nullable|url',
        ], [
            'date_1.required'      => 'La date du 1er horaire est obligatoire.',
            'heure_1.required'     => 'L\'heure du 1er horaire est obligatoire.',
            'date_2.required'      => 'La date du 2ème horaire est obligatoire.',
            'heure_2.required'     => 'L\'heure du 2ème horaire est obligatoire.',
            'date_3.required'      => 'La date du 3ème horaire est obligatoire.',
            'heure_3.required'     => 'L\'heure du 3ème horaire est obligatoire.',
            'location.required_if' => 'Le lieu est obligatoire pour un entretien en présentiel.',
        ]);

        $proposal = AppointmentProposal::create([
            'coach_assignment_id' => $assignment->id,
            'date_1'              => $request->date_1,
            'heure_1'             => $request->heure_1,
            'date_2'              => $request->date_2,
            'heure_2'             => $request->heure_2,
            'date_3'              => $request->date_3,
            'heure_3'             => $request->heure_3,
            'mode'                => $demande->mode_entretien,
            'plateforme_enligne'  => $demande->plateforme_enligne,
            'numero_whatsapp'     => $demande->numero_whatsapp,
            'numero_appel'        => $demande->numero_appel,
            'meeting_link'        => $demande->plateforme_enligne === 'google_meet'
                                        ? $request->meeting_link
                                        : null,
            'location'            => $demande->mode_entretien === 'presentiel'
                                        ? $request->location
                                        : null,
            'status'              => 'pending',
        ]);

        $candidat = $assignment->candidat;
        $coach    = auth()->user();

        Mail::to($candidat->email)->send(
            new AppointmentProposalMail(
                candidatName: $candidat->name,
                coachName:    $coach->name,
                proposal:     $proposal
            )
        );

        return redirect()->route('coach.appointments.index')
            ->with('success', 'Proposition envoyée à ' . $candidat->name . '. Il a reçu les 3 horaires par email.');
    }

    // ─────────────────────────────────────────────────
    // Annuler un entretien
    // ─────────────────────────────────────────────────
    public function destroy(Appointment $appointment)
    {
        abort_if($appointment->coachAssignment->coach_id !== auth()->id(), 403);

        $appointment->update(['status' => 'cancelled']);

        $candidat = $appointment->coachAssignment->candidat;
        $coach    = auth()->user();

        Mail::to($candidat->email)->send(new EntretienAnnuleMail(
            $candidat->name,
            $coach->name,
            \Carbon\Carbon::parse($appointment->scheduled_date)->format('d/m/Y'),
            \Carbon\Carbon::parse($appointment->scheduled_time)->format('H:i'),
        ));

        return redirect()->route('coach.appointments.index')
            ->with('success', 'Entretien annulé. Le candidat a été informé par email.');
    }

    // ─────────────────────────────────────────────────
    // Reporter — afficher le formulaire
    // ─────────────────────────────────────────────────
    public function editReport(Appointment $appointment)
    {
        abort_if($appointment->coachAssignment->coach_id !== auth()->id(), 403);

        return view('coach.appointments.report', compact('appointment'));
    }

    // ─────────────────────────────────────────────────
    // Reporter — enregistrer
    // ─────────────────────────────────────────────────
    public function report(Request $request, Appointment $appointment)
    {
        abort_if($appointment->coachAssignment->coach_id !== auth()->id(), 403);

        $request->validate([
            'scheduled_date' => 'required|date|after:today',
            'scheduled_time' => 'required',
            'mode'           => 'required|in:presentiel,en_ligne',
            'location'       => 'required_if:mode,presentiel|nullable|string',
            'meeting_link'   => 'required_if:mode,en_ligne|nullable|url',
        ]);

        $ancienneDate  = \Carbon\Carbon::parse($appointment->scheduled_date)->format('d/m/Y');
        $ancienneHeure = \Carbon\Carbon::parse($appointment->scheduled_time)->format('H:i');

        $appointment->update([
            'scheduled_date' => $request->scheduled_date,
            'scheduled_time' => $request->scheduled_time,
            'mode'           => $request->mode,
            'location'       => $request->location,
            'meeting_link'   => $request->meeting_link,
            'status'         => 'scheduled',
        ]);

        $candidat = $appointment->coachAssignment->candidat;
        $coach    = auth()->user();

        Mail::to($candidat->email)->send(new EntretienReporteMail(
            $candidat->name,
            $coach->name,
            $ancienneDate,
            $ancienneHeure,
            \Carbon\Carbon::parse($request->scheduled_date)->format('d/m/Y'),
            \Carbon\Carbon::parse($request->scheduled_time)->format('H:i'),
            $request->mode,
            $request->location,
            $request->meeting_link
        ));

        return redirect()->route('coach.appointments.index')
            ->with('success', 'Entretien reporté. Le candidat a été informé par email.');
    }
}