<?php

namespace App\Http\Controllers\Coach;

use App\Helpers\DateHelper;
use App\Http\Controllers\Controller;
use App\Models\CoachAssignment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CoachAssignmentController extends Controller
{
    public function index()
    {
        $pendingAssignments = CoachAssignment::where('coach_id', auth()->id())
            ->where('status', 'pending')
            ->where('expires_at', '>', now())
            ->with('candidat')
            ->latest()
            ->get();

        return view('coach.assignments.index', compact('pendingAssignments'));
    }

    public function accept(CoachAssignment $assignment)
    {
        abort_if($assignment->coach_id !== auth()->id(), 403);

        if ($assignment->status !== 'pending') {
            return redirect()->route('coach.dashboard')
                ->with('error', 'Cette demande n\'est plus disponible.');
        }

        if ($assignment->expires_at < now()) {
            $assignment->update(['status' => 'expired']);
            return redirect()->route('coach.dashboard')
                ->with('error', 'Cette demande a expiré.');
        }

        $appointmentDeadline = DateHelper::addBusinessDays(now(), 7);

        $assignment->update([
            'status'               => 'active',
            'accepted_at'          => now(),
            'expires_at'           => null,
            'appointment_deadline' => $appointmentDeadline,
        ]);

        // Mail au candidat uniquement — coach déjà au courant car c'est lui qui valide
        Mail::to($assignment->candidat->email)->send(
            new \App\Mail\DiagnosticValidatedMail(
                candidatName: $assignment->candidat->name,
                coachName:    $assignment->coach->name
            )
        );

        return redirect()->route('coach.dashboard')
            ->with('success', 'Vous avez accepté le suivi de ' . $assignment->candidat->name . '. Vous avez jusqu\'au ' . $appointmentDeadline->format('d/m/Y') . ' pour programmer l\'entretien.');
    }

    public function rejectForm(CoachAssignment $assignment)
    {
        abort_if($assignment->coach_id !== auth()->id(), 403);

        if ($assignment->status !== 'pending') {
            return redirect()->route('coach.dashboard')
                ->with('error', 'Cette demande n\'est plus disponible.');
        }

        if ($assignment->expires_at < now()) {
            $assignment->update(['status' => 'expired']);
            return redirect()->route('coach.dashboard')
                ->with('error', 'Cette demande a expiré.');
        }

        return view('coach.assignments.reject', compact('assignment'));
    }

    public function reject(Request $request, CoachAssignment $assignment)
    {
        abort_if($assignment->coach_id !== auth()->id(), 403);

        if ($assignment->status !== 'pending') {
            return redirect()->route('coach.dashboard')
                ->with('error', 'Cette demande n\'est plus disponible.');
        }

        $request->validate([
            'reason' => 'required|string|min:10|max:500',
        ], [
            'reason.required' => 'Veuillez indiquer une raison.',
            'reason.min'      => 'La raison doit contenir au moins 10 caractères.',
        ]);

        $assignment->update([
            'status'          => 'rejected',
            'rejected_reason' => $request->reason,
        ]);

        // Mail uniquement à l'admin qui a fait l'affectation
        $adminQuiAffecte = User::find($assignment->assigned_by);
        Mail::to($adminQuiAffecte->email)->send(
            new \App\Mail\CoachRejectedMail(
                coachName:    $assignment->coach->name,
                candidatName: $assignment->candidat->name,
                reason:       $request->reason
            )
        );

        return redirect()->route('coach.dashboard')
            ->with('info', 'Vous avez refusé cette affectation. L\'administrateur en a été informé.');
    }
}