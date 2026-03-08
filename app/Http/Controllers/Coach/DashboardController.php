<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\CoachAssignment;
use App\Models\Interview;
use App\Models\InterviewScore;
use App\Models\FollowUpStep;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $coach  = auth()->user();
        $statut = $request->get('statut');

        // ── 1. Assignments — 1 seule requête ────────────────────────
        $allAssignments = $coach->assignments()
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->with([
                'candidat.candidatProfile',
                'candidat.needAssignment',
                'candidat.followUpSteps',
            ])
            ->get();

        // Filtrage en PHP — pas de requête supplémentaire
        $assignments = $statut
            ? $allAssignments->filter(
                fn($a) => $a->candidat->needAssignment?->type === $statut
            )
            : $allAssignments;

        // ── 2. Compteurs — calculés depuis la collection déjà chargée
        $compteurs = [
            'tous'             => $allAssignments->count(),
            'stage'            => $allAssignments->filter(fn($a) => $a->candidat->needAssignment?->type === 'stage')->count(),
            'insertion_emploi' => $allAssignments->filter(fn($a) => $a->candidat->needAssignment?->type === 'insertion_emploi')->count(),
            'auto_emploi'      => $allAssignments->filter(fn($a) => $a->candidat->needAssignment?->type === 'auto_emploi')->count(),
            'formation'        => $allAssignments->filter(fn($a) => $a->candidat->needAssignment?->type === 'formation')->count(),
        ];

        // ── 3. Entretiens programmés — 1 requête ────────────────────
        $entretiens = Appointment::whereHas('coachAssignment', fn($q) => $q->where('coach_id', $coach->id))
            ->where('status', 'scheduled')
            ->count();

        $assignmentsAvecEntretien = Appointment::whereHas('coachAssignment', fn($q) => $q->where('coach_id', $coach->id))
            ->whereIn('status', ['scheduled', 'completed'])
            ->pluck('coach_assignment_id')
            ->toArray();

        // ── 4. Interviews — 1 seule requête + filtrage PHP ──────────
        $allInterviews = Interview::whereHas('appointment.coachAssignment', fn($q) => $q->where('coach_id', $coach->id))
            ->where('status', 'completed')
            ->orderBy('completed_at', 'desc')
            ->with([
                'appointment.coachAssignment.candidat.candidatProfile',
                'appointment.coachAssignment.candidat.needAssignment',
                'appointment.coachAssignment.coach',
            ])
            ->get();

        $interviewsAujourdhui = $allInterviews->filter(
            fn($i) => Carbon::parse($i->completed_at)->isToday()
        );

        $interviewsSemaine = $allInterviews->filter(
            fn($i) => Carbon::parse($i->completed_at)->isBetween(
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            )
        );

        $interviewsMois = $allInterviews->filter(
            fn($i) => Carbon::parse($i->completed_at)->month === Carbon::now()->month
                && Carbon::parse($i->completed_at)->year  === Carbon::now()->year
        );

        // ── 5. Graphique orientations — depuis compteurs déjà calculés
        $orientationsChart = [
            'labels' => ['Stage', 'Insertion emploi', 'Auto-emploi', 'Formation'],
            'data'   => [
                $compteurs['stage'],
                $compteurs['insertion_emploi'],
                $compteurs['auto_emploi'],
                $compteurs['formation'],
            ],
        ];


        // ── 7. Graphique suivi étapes — 2 requêtes simples ──────────
        $allSteps = FollowUpStep::whereHas('candidat.candidatAssignment', fn($q) => $q->where('coach_id', $coach->id))
            ->get();

        $stepsChart = [
            'labels' => ['Terminées', 'En cours'],
            'data'   => [
                $allSteps->where('status', 'completed')->count(),
                $allSteps->where('status', 'in_progress')->count(),
            ],
        ];

        // ── 8. Évolution des affectations ───────────────────────────
        $periode       = $request->get('periode', 'jour');
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
            'labels'  => $evolution->keys()->values()->toArray(),
            'data'    => $evolution->values()->toArray(),
            'periode' => $periode,
        ];

        return view('coach.dashboard', compact(
            'assignments',
            'statut',
            'compteurs',
            'entretiens',
            'assignmentsAvecEntretien',
            'interviewsAujourdhui',
            'interviewsSemaine',
            'interviewsMois',
            'orientationsChart',
            'stepsChart',
            'evolutionChart',
        ));
    }
}
