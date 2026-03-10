<?php

namespace App\Jobs;

use App\Models\CoachAssignment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ExpireCoachAssignments implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        // Récupérer toutes les affectations en attente expirées
        CoachAssignment::where('status', 'pending')
            ->where('expires_at', '<', now())
            ->with(['coach', 'candidat'])
            ->each(function ($assignment) {

                // Mettre à jour le statut
                $assignment->update(['status' => 'expired']);

                // Notifier uniquement l'admin qui a fait l'affectation
                $adminQuiAffecte = User::find($assignment->assigned_by);

                if ($adminQuiAffecte) {
                    Mail::to($adminQuiAffecte->email)->send(
                        new \App\Mail\CoachAssignmentExpiredMail(
                            coachName:    $assignment->coach->name,
                            candidatName: $assignment->candidat->name
                        )
                    );
                }
            });
    }
}