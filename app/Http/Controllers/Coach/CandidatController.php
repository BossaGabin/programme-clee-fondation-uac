<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Interview;

class CandidatController extends Controller
{
    public function show(User $candidat)
    {
        // Vérifier que ce candidat appartient bien à ce coach
        $assignment = auth()->user()->assignments()
            ->where('candidat_id', $candidat->id)
            ->where('status', 'active')
            ->first();

        abort_if(!$assignment, 403);

        $candidat->load([
            'candidatProfile',
            'needAssignment',
            'followUpSteps',
            'professionalProject',
        ]);

        // Entretien du candidat
        $interview = Interview::whereHas('appointment', function ($q) use ($candidat) {
            $q->whereHas('coachAssignment', function ($q2) use ($candidat) {
                $q2->where('candidat_id', $candidat->id);
            });
        })->with('scores.competence')->first();

        // dd( $interview );

        return view('coach.candidats.show', compact('candidat', 'assignment', 'interview'));
    }
}