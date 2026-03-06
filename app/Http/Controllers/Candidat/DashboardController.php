<?php

namespace App\Http\Controllers\Candidat;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Interview;
use App\Models\ProfessionalProject;
use Barryvdh\DomPDF\Facade\Pdf;

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
            'parcourChart'
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
}
