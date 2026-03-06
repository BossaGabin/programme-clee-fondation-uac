<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\CoachAssignment;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $coach = auth()->user();

        $query = $coach->assignments()->orderBy('created_at', 'desc')
            ->where('status', 'active')
            ->with([
                'candidat.candidatProfile',
                'candidat.needAssignment',
                'candidat.followUpSteps',
            ]);

        if ($request->filled('statut')) {
            $query->whereHas('candidat.needAssignment', function ($q) use ($request) {
                $q->where('type', $request->statut);
            });
        }

        $assignments = $query->get();
        $statut      = $request->get('statut');

        $compteurs = [
            'tous'             => $coach->assignments()->where('status', 'active')->count(),
            'stage'            => $coach->assignments()->where('status', 'active')
                ->whereHas('candidat.needAssignment', fn($q) => $q->where('type', 'stage'))->count(),
            'insertion_emploi' => $coach->assignments()->where('status', 'active')
                ->whereHas('candidat.needAssignment', fn($q) => $q->where('type', 'insertion_emploi'))->count(),
            'auto_emploi'      => $coach->assignments()->where('status', 'active')
                ->whereHas('candidat.needAssignment', fn($q) => $q->where('type', 'auto_emploi'))->count(),
            'formation'        => $coach->assignments()->where('status', 'active')
                ->whereHas('candidat.needAssignment', fn($q) => $q->where('type', 'formation'))->count(),
        ];

        $entretiens = Appointment::whereHas('coachAssignment', fn($q) => $q->where('coach_id', $coach->id))
            ->where('status', 'scheduled')
            ->count();
        $interview = Appointment::whereHas('coachAssignment', fn($q) => $q->where('coach_id', $coach->id))
            ->get();
        // ->count();
        // dd($entretiens);


        // ── DONNÉES GRAPHIQUES ──

        // 1. Donut orientations
        $orientationsChart = [
            'labels' => ['Stage', 'Insertion emploi', 'Auto-emploi', 'Formation'],
            'data'   => [
                $compteurs['stage'],
                $compteurs['insertion_emploi'],
                $compteurs['auto_emploi'],
                $compteurs['formation'],
            ],
        ];

        // 2. Bar horizontal — score moyen par compétence
        $scoresChart = \App\Models\InterviewScore::whereHas('interview.appointment.coachAssignment', function ($q) use ($coach) {
            $q->where('coach_id', $coach->id);
        })
            ->with('competence')
            ->get()
            ->groupBy('competence.name')
            ->map(fn($scores) => round($scores->avg('note'), 1));

        $scoresChart = [
            'labels' => $scoresChart->keys()->values()->toArray(),
            'data'   => $scoresChart->values()->toArray(),
        ];

        // 3. Donut suivi étapes
        $stepsChart = [
            'labels' => ['Terminées', 'En cours'],
            'data'   => [
                \App\Models\FollowUpStep::whereHas('candidat.candidatAssignment', fn($q) => $q->where('coach_id', $coach->id))
                    ->where('status', 'completed')->count(),
                \App\Models\FollowUpStep::whereHas('candidat.candidatAssignment', fn($q) => $q->where('coach_id', $coach->id))
                    ->where('status', 'in_progress')->count(),
            ],
        ];


        // ── Évolution des affectations ──
        $periode = $request->get('periode', 'mois'); // défaut : mois

        $evolutionQuery = CoachAssignment::where('coach_id', $coach->id);

        $evolution = match ($periode) {
            'jour'    => $evolutionQuery->selectRaw('DATE(created_at) as label, COUNT(*) as total')
                ->where('created_at', '>=', Carbon::now()->subDays(30))
                ->groupByRaw('DATE(created_at)')
                ->orderBy('label')
                ->pluck('total', 'label'),

            'semaine' => $evolutionQuery->selectRaw('YEARWEEK(created_at, 1) as label, COUNT(*) as total')
                ->where('created_at', '>=', Carbon::now()->subWeeks(12))
                ->groupByRaw('YEARWEEK(created_at, 1)')
                ->orderBy('label')
                ->pluck('total', 'label'),

            'annee'   => $evolutionQuery->selectRaw('YEAR(created_at) as label, COUNT(*) as total')
                ->groupByRaw('YEAR(created_at)')
                ->orderBy('label')
                ->pluck('total', 'label'),

            default   => $evolutionQuery->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as label, COUNT(*) as total')
                ->where('created_at', '>=', Carbon::now()->subMonths(12))
                ->groupByRaw('DATE_FORMAT(created_at, "%Y-%m")')
                ->orderBy('label')
                ->pluck('total', 'label'),
        };

        $evolutionChart = [
            'labels' => $evolution->keys()->values()->toArray(),
            'data'   => $evolution->values()->toArray(),
            'periode' => $periode,
        ];

        return view('coach.dashboard', compact(
            'assignments',
            'statut',
            'compteurs',
            'entretiens',
            'interview',
            'orientationsChart',
            'scoresChart',
            'stepsChart',
            'evolutionChart'
        ));


        // return view('coach.dashboard', compact('assignments', 'statut', 'compteurs', 'entretiens','interview'));
    }
}
