<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'coach_assignment_id', 'scheduled_date', 'scheduled_time',
        'mode', 'location', 'meeting_link', 'status'
    ];

    public function coachAssignment()
    {
        return $this->belongsTo(CoachAssignment::class);
    }

    // Accès rapide au candidat et coach via l'assignment
    public function candidat()
    {
        return $this->coachAssignment->candidat;
    }

    public function interview()
    {
        return $this->hasOne(Interview::class);
    }
}