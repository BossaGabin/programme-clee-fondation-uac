<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppointmentProposal extends Model
{
    protected $fillable = [
        'coach_assignment_id',
        'date_1', 'heure_1',
        'date_2', 'heure_2',
        'date_3', 'heure_3',
        'mode',
        'location',
        'plateforme_enligne',
        'numero_whatsapp',
        'numero_appel',
        'meeting_link',
        'status',
        'choix_candidat',
    ];

    public function coachAssignment()
    {
        return $this->belongsTo(CoachAssignment::class);
    }
}