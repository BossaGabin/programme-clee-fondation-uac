<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\NeedAssignment;
use App\Models\User;
use App\Models\Interview;
use Illuminate\Http\Request;

class NeedAssignmentController extends Controller
{
    public function create(User $candidat)
    {
        $interview = Interview::whereHas('appointment.coachAssignment', function ($q) use ($candidat) {
            $q->where('candidat_id', $candidat->id);
        })->where('status', 'completed')->latest()->first();

        return view('coach.needs.create', compact('candidat', 'interview'));
    }

    public function store(Request $request, User $candidat)
    {
        $request->validate([
            'type'               => 'required|in:stage,insertion_emploi,formation,auto_emploi',
            'description'        => 'nullable|string',
            'duration'           => 'nullable|string|max:100',
            'program_start_date' => 'nullable|date',
            'program_end_date'   => 'nullable|date|after_or_equal:program_start_date',
        ]);

        // Récupérer l'interview du candidat
        $interview = Interview::whereHas('appointment.coachAssignment', function ($q) use ($candidat) {
            $q->where('candidat_id', $candidat->id);
        })->where('status', 'completed')->latest()->firstOrFail();

        NeedAssignment::create([
            'candidat_id'        => $candidat->id,
            'coach_id'           => auth()->id(),
            'interview_id'       => $interview->id,
            'type'               => $request->type,
            'description'        => $request->description,
            'duration'           => $request->duration,
            'program_start_date' => $request->program_start_date,
            'program_end_date'   => $request->program_end_date,
        ]);

        // return redirect()->route('coach.candidats.show', $candidat)
        //     ->with('success', 'Besoin enregistré. Vous pouvez maintenant commencer le suivi.');
        return redirect()->route('coach.interviews.report', $interview)
            ->with('success', 'Orientation assignée. Vous pouvez consulter et exporter le rapport PDF.');
    }
}
