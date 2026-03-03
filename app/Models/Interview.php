<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    protected $fillable = [
        'appointment_id', 'status', 'total_score',
        'strengths', 'weaknesses', 'coach_summary', 'completed_at'
    ];

    protected $casts = ['completed_at' => 'datetime'];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function scores()
    {
        return $this->hasMany(InterviewScore::class);
    }

    public function professionalProject()
    {
        return $this->hasOne(ProfessionalProject::class);
    }

    public function needAssignment()
    {
        return $this->hasOne(NeedAssignment::class);
    }

    // Calcul et sauvegarde du total
    public function calculateTotalScore(): int
    {
        $total = $this->scores()->sum('note');
        $this->total_score = $total;
        $this->save();
        return $total;
    }
}