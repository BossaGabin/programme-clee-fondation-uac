<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NeedAssignment extends Model
{
    protected $fillable = [
        'candidat_id', 'coach_id', 'interview_id', 'type',
        'description', 'duration', 'program_start_date', 'program_end_date'
    ];

    protected $casts = [
        'program_start_date' => 'date',
        'program_end_date'   => 'date',
    ];

    public function candidat()
    {
        return $this->belongsTo(User::class, 'candidat_id');
    }

    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    public function interview()
    {
        return $this->belongsTo(Interview::class);
    }
}
