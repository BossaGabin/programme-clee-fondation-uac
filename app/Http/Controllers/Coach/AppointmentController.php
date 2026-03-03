<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Mail\EntretienProgrammeMail;
use App\Models\Appointment;
use App\Models\CoachAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AppointmentController extends Controller
{
    // Liste des entretiens programmés du coach
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
        // Vérifier que cet assignment appartient au coach connecté
        abort_if($assignment->coach_id !== auth()->id(), 403);

        return view('coach.appointments.create', compact('assignment'));
    }

    public function store(Request $request, CoachAssignment $assignment)
    {
        abort_if($assignment->coach_id !== auth()->id(), 403);

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

    public function destroy(Appointment $appointment)
    {
        abort_if($appointment->coachAssignment->coach_id !== auth()->id(), 403);
        $appointment->update(['status' => 'cancelled']);
        return redirect()->route('coach.appointments.index')
            ->with('success', 'Entretien annulé.');
    }
}
