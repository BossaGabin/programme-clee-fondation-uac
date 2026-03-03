<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\DiagnosticRequest;
use App\Models\NeedAssignment;

class DashboardController extends Controller
{
    public function index()
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

        return view('admin.dashboard', compact('stats', 'demandes_recentes'));
    }
}