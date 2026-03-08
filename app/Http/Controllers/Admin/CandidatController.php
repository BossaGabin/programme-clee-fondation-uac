<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CandidatController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'candidat')->orderBy('created_at','desc')
            ->with(['candidatProfile', 'candidatAssignment.coach', 'needAssignment']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('statut')) {
            $query->whereHas('needAssignment', function ($q) use ($request) {
                $q->where('type', $request->statut);
            });
        }

        $candidats = $query->get();
        // dd($candidats );


        return view('admin.candidats.index', compact('candidats'));
    }

    // public function show(User $candidat)
    // {
    //     $candidat->load([
    //         'candidatProfile',
    //         'candidatAssignment.coach.coachProfile',
    //         'needAssignment',
    //         'followUpSteps',
    //         'diagnosticRequests',
    //         'professionalProject',
    //         'interviews.scores.competence',
    //     ]);

    //     return view('admin.candidats.show', compact('candidat'));
    // }

    public function show(User $candidat)
    {
        $candidat->load([
            'candidatProfile',
            'candidatAssignment.coach.coachProfile',
            'needAssignment',
            'followUpSteps',
            'diagnosticRequests',
            'professionalProject',
        ]);

        // Récupérer l'entretien via les appointments du candidat
        $interview = \App\Models\Interview::whereHas('appointment', function ($q) use ($candidat) {
            $q->whereHas('coachAssignment', function ($q2) use ($candidat) {
                $q2->where('candidat_id', $candidat->id);
            });
        })->with('scores.competence')->first();

        return view('admin.candidats.show', compact('candidat', 'interview'));
    }

    // public function exportPdf(User $candidat)
    // {
    //     $candidat->load([
    //         'candidatProfile',
    //         'candidatAssignment.coach.coachProfile',
    //         'needAssignment',
    //         'followUpSteps',
    //         'diagnosticRequests',
    //         'professionalProject',
    //         'interviews.scores.competence',
    //     ]);

    //     $pdf = Pdf::loadView('admin.candidats.pdf', compact('candidat'));

    //     return $pdf->download('fiche-candidat-' . str($candidat->name)->slug() . '.pdf');
    // }

    public function exportPdf(User $candidat)
    {
        $candidat->load([
            'candidatProfile',
            'candidatAssignment.coach.coachProfile',
            'needAssignment',
            'followUpSteps',
            'diagnosticRequests',
            'professionalProject',
        ]);

        // Récupérer l'entretien manuellement
        $interview = \App\Models\Interview::whereHas('appointment', function ($q) use ($candidat) {
            $q->whereHas('coachAssignment', function ($q2) use ($candidat) {
                $q2->where('candidat_id', $candidat->id);
            });
        })->with('scores.competence')->first();

        $pdf = Pdf::loadView('admin.candidats.pdf', compact('candidat', 'interview'));

        return $pdf->download('fiche-candidat-' . str($candidat->name)->slug() . '.pdf');
    }
}
