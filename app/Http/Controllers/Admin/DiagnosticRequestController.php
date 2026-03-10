<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiagnosticRequest;
use App\Models\User;
use Illuminate\Http\Request;

class DiagnosticRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = DiagnosticRequest::with('candidat')->latest();

        if ($request->filled('search')) {
            $query->whereHas('candidat', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $demandes = $query->paginate(15);

        return view('admin.demandes.index', compact('demandes'));
    }

    // public function show(DiagnosticRequest $demande)
    // {
    //     $demande->load([
    //         'candidat.candidatProfile',
    //         'candidat.candidatAssignment.coach.coachProfile',
    //     ]);
    //     $coachs = User::where('role', 'coach')
    //         ->withCount(['assignments' => fn($q) => $q->where('status', 'active')])
    //         ->with('coachProfile')
    //         ->get();

    //     return view('admin.demandes.show', compact('demande', 'coachs'));
    // }

    public function show(DiagnosticRequest $demande)
    {
        $demande->load([
            'candidat.candidatProfile',
            'candidat.candidatAssignment.coach.coachProfile',
        ]);

        $coachs = User::where('role', 'coach')
            ->withCount(['assignments' => fn($q) => $q->where('status', 'active')])
            ->with('coachProfile')
            ->get();

        return view('admin.demandes.show', compact('demande', 'coachs'));
    }

    public function validated(Request $request, DiagnosticRequest $demande)
    {
        $demande->update([
            'status'       => 'validated',
            'note_admin'   => $request->note_admin,
            'validated_at' => now(),
        ]);

        return redirect()->route('admin.demandes.show', $demande)
            ->with('success', 'Demande validée avec succès.');
    }

    public function reject(Request $request, DiagnosticRequest $demande)
    {
        $request->validate([
            'note_admin' => 'required|string',
        ], [
            'note_admin.required' => 'Le motif du rejet est obligatoire.',
        ]);

        $demande->update([
            'status'     => 'rejected',
            'note_admin' => $request->note_admin,
        ]);

        return redirect()->route('admin.demandes.index')
            ->with('success', 'Demande rejetée.');
    }
}
