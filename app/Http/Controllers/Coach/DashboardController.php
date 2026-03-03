<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $coach = auth()->user();

        $query = $coach->assignments()->orderBy('created_at','desc')
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

        return view('coach.dashboard', compact('assignments', 'statut', 'compteurs', 'entretiens','interview'));
    }
}