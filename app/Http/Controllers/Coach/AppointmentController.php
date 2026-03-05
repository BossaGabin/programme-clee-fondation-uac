<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Mail\EntretienAnnuleMail;
use App\Mail\EntretienReporteMail;
use App\Mail\EntretienProgrammeMail;
use App\Models\Appointment;
use App\Models\CoachAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::whereHas('coachAssignment', function ($q) {
            $q->where('coach_id', auth()->id());
        })
            ->with('coachAssignment.candidat')
            ->where('status', 'scheduled')
            ->get();

        return view('coach.appointments.index', compact('appointments'));
    }

    public function create(CoachAssignment $assignment)
    {
        abort_if($assignment->coach_id !== auth()->id(), 403);

        // Vérifier qu'il n'existe pas déjà un entretien schedulé pour ce candidat
        $existant = Appointment::where('coach_assignment_id', $assignment->id)
            ->where('status', 'scheduled')
            ->first();

        return view('coach.appointments.create', compact('assignment', 'existant'));
    }

    public function store(Request $request, CoachAssignment $assignment)
    {
        abort_if($assignment->coach_id !== auth()->id(), 403);

        // Bloquer si un entretien schedulé existe déjà
        $existant = Appointment::where('coach_assignment_id', $assignment->id)
            ->where('status', 'scheduled')
            ->first();

        if ($existant) {
            return redirect()->back()
                ->with('error', 'Un entretien est déjà programmé pour ce candidat. Veuillez l\'annuler ou le reporter avant d\'en créer un nouveau.');
        }

        $request->validate([
            'scheduled_date' => 'required|date|after_or_equal:today',
            'scheduled_time' => 'required',
            'mode'           => 'required|in:presentiel,en_ligne',
            'location'       => 'required_if:mode,presentiel|nullable|string',
            'meeting_link'   => 'required_if:mode,en_ligne|nullable|url',
        ]);

        Appointment::create([
            'coach_assignment_id' => $assignment->id,
            'scheduled_date'      => $request->scheduled_date,
            'scheduled_time'      => $request->scheduled_time,
            'mode'                => $request->mode,
            'location'            => $request->location,
            'meeting_link'        => $request->meeting_link,
            'status'              => 'scheduled',
        ]);

        $candidat = $assignment->candidat;
        $coach    = auth()->user();

        Mail::to($candidat->email)->send(new EntretienProgrammeMail(
            $candidat->name,
            $coach->name,
            \Carbon\Carbon::parse($request->scheduled_date)->format('d/m/Y'),
            \Carbon\Carbon::parse($request->scheduled_time)->format('H:i'),
            $request->mode,
            $request->location,
            $request->meeting_link
        ));

        return redirect()->route('coach.appointments.index')
            ->with('success', 'Entretien programmé avec succès.');
    }

    // Annuler
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

    // Reporter — afficher le formulaire
    public function editReport(Appointment $appointment)
    {
        abort_if($appointment->coachAssignment->coach_id !== auth()->id(), 403);

        return view('coach.appointments.report', compact('appointment'));
    }

    // Reporter — enregistrer
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