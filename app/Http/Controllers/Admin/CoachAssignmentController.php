<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CoachAssignment;
use App\Models\DiagnosticRequest;
use App\Models\User;
use App\Mail\DiagnosticValidatedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CoachAssignmentController extends Controller
{
    public function store(Request $request, DiagnosticRequest $demande)
    {
        $request->validate([
            'coach_id' => 'required|exists:users,id',
        ], [
            'coach_id.required' => 'Veuillez sélectionner un coach.',
            'coach_id.exists'   => 'Coach introuvable.',
        ]);

        $coach = User::findOrFail($request->coach_id);

        // Créer l'affectation
        CoachAssignment::create([
            'diagnostic_request_id' => $demande->id,
            'candidat_id'           => $demande->candidat_id,
            'coach_id'              => $request->coach_id,
            'assigned_by'           => auth()->id(),
            'status'                => 'active',
        ]);

        // Envoyer le mail au candidat
        Mail::to($demande->candidat->email)->send(
            new DiagnosticValidatedMail(
                candidatName: $demande->candidat->name,
                coachName:    $coach->name
            )
        );

        return redirect()->route('admin.demandes.show', $demande)
            ->with('success', 'Coach affecté et candidat notifié par email.');
    }
}