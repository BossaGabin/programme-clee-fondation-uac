<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiagnosticRequest;
use App\Models\NeedAssignment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $stats = [
            'total_candidats'    => User::where('role', 'candidat')->count(),
            'total_coachs'       => User::where('role', 'coach')->count(),
            'demandes_pending'   => DiagnosticRequest::where('status', 'pending')->count(),
            'demandes_validated' => DiagnosticRequest::where('status', 'validated')->count(),
            'demandes_rejected'  => DiagnosticRequest::where('status', 'rejected')->count(),
            'par_besoin' => [
                'stage'            => NeedAssignment::where('type', 'stage')->count(),
                'insertion_emploi' => NeedAssignment::where('type', 'insertion_emploi')->count(),
                'auto_emploi'      => NeedAssignment::where('type', 'auto_emploi')->count(),
                'formation'        => NeedAssignment::where('type', 'formation')->count(),
            ],
        ];

        $demandes_recentes = DiagnosticRequest::where('status', 'pending')
            ->with('candidat')
            ->latest()
            ->take(5)
            ->get();

        // ── DONNÉES GRAPHIQUES ──

        // 1. Donut — Répartition des orientations
        $orientationsChart = [
            'labels' => ['Stage', 'Insertion emploi', 'Auto-emploi', 'Formation'],
            'data'   => [
                $stats['par_besoin']['stage'],
                $stats['par_besoin']['insertion_emploi'],
                $stats['par_besoin']['auto_emploi'],
                $stats['par_besoin']['formation'],
            ],
        ];

        // 2. Donut — Statut des demandes de diagnostic
        $demandesChart = [
            'labels' => ['En attente', 'Validées', 'Rejetées'],
            'data'   => [
                $stats['demandes_pending'],
                $stats['demandes_validated'],
                $stats['demandes_rejected'],
            ],
        ];

        // 3. Bar vertical — Candidats par coach
        $candidatsParCoach = User::where('role', 'coach')
            ->withCount(['assignments' => fn($q) => $q->where('status', 'active')])
            ->get();

        $candidatsParCoachChart = [
            'labels' => $candidatsParCoach->pluck('name')->toArray(),
            'data'   => $candidatsParCoach->pluck('assignments_count')->toArray(),
        ];

        // 4. Line — Évolution des inscriptions candidats par période
        $periode = $request->get('periode', 'mois');

        $inscriptionsQuery = User::where('role', 'candidat');

        $inscriptions = match ($periode) {
            'jour'    => $inscriptionsQuery
                ->selectRaw('DATE(created_at) as label, COUNT(*) as total')
                ->where('created_at', '>=', Carbon::now()->subDays(30))
                ->groupByRaw('DATE(created_at)')
                ->orderBy('label')
                ->pluck('total', 'label'),

            'semaine' => $inscriptionsQuery
                ->selectRaw('YEARWEEK(created_at, 1) as label, COUNT(*) as total')
                ->where('created_at', '>=', Carbon::now()->subWeeks(12))
                ->groupByRaw('YEARWEEK(created_at, 1)')
                ->orderBy('label')
                ->pluck('total', 'label'),

            'annee'   => $inscriptionsQuery
                ->selectRaw('YEAR(created_at) as label, COUNT(*) as total')
                ->groupByRaw('YEAR(created_at)')
                ->orderBy('label')
                ->pluck('total', 'label'),

            default   => $inscriptionsQuery
                ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as label, COUNT(*) as total')
                ->where('created_at', '>=', Carbon::now()->subMonths(12))
                ->groupByRaw('DATE_FORMAT(created_at, "%Y-%m")')
                ->orderBy('label')
                ->pluck('total', 'label'),
        };

        $inscriptionsChart = [
            'labels'  => $inscriptions->keys()->values()->toArray(),
            'data'    => $inscriptions->values()->toArray(),
            'periode' => $periode,
        ];

        return view('admin.dashboard', compact(
            'stats',
            'demandes_recentes',
            'orientationsChart',
            'demandesChart',
            'candidatsParCoachChart',
            'inscriptionsChart'
        ));

        // return view('admin.dashboard', compact('stats', 'demandes_recentes'));
    }
}
