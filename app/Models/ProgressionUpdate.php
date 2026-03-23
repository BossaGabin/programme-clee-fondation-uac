<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgressionUpdate extends Model
{
    protected $fillable = [
        'coach_assignment_id',
        'bloc_a',
        'bloc_b',
        'bloc_c',
        'bloc_d',
        'bloc_e',
        'note_seance',
    ];

    public function coachAssignment()
    {
        return $this->belongsTo(CoachAssignment::class);
    }

    // Score total de cette séance (uniquement les blocs renseignés)
    public function scoresTotal(): int
    {
        return ($this->bloc_a ?? 0)
             + ($this->bloc_b ?? 0)
             + ($this->bloc_c ?? 0)
             + ($this->bloc_d ?? 0)
             + ($this->bloc_e ?? 0);
    }
}