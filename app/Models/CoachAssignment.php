<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProgressionUpdate;


class CoachAssignment extends Model
{
    protected $fillable = [
        'diagnostic_request_id',
        'candidat_id',
        'coach_id',
        'assigned_by',
        'status',
        'expires_at',
        'rejected_reason',
        'accepted_at',
        'appointment_deadline',
    ];

    protected $casts = [
        'expires_at'           => 'datetime',
        'accepted_at'          => 'datetime',
        'appointment_deadline' => 'datetime',
    ];

    public function diagnosticRequest()
    {
        return $this->belongsTo(DiagnosticRequest::class);
    }

    public function candidat()
    {
        return $this->belongsTo(User::class, 'candidat_id');
    }

    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
    public function appointmentProposal()
    {
        return $this->hasOne(AppointmentProposal::class);
    }

    // Dans app/Models/CoachAssignment.php


    public function progressionUpdates()
    {
        return $this->hasMany(ProgressionUpdate::class)->orderBy('created_at', 'asc');
    }

    public function currentScores(): array
    {
        // Récupérer l'interview du candidat
        $interview = $this->appointments()
            ->whereHas('interview', fn($q) => $q->where('status', 'completed'))
            ->with('interview.scores.competence')
            ->first()
            ?->interview;

        // Scores initiaux depuis interview_scores
        $initial = ['bloc_a' => 0, 'bloc_b' => 0, 'bloc_c' => 0, 'bloc_d' => 0, 'bloc_e' => 0];

        if ($interview) {
            foreach ($interview->scores as $score) {
                $map = [1 => 'bloc_a', 2 => 'bloc_b', 3 => 'bloc_c', 4 => 'bloc_d', 5 => 'bloc_e'];
                $key = $map[$score->competence->order] ?? null;
                if ($key) $initial[$key] = $score->note;
            }
        }

        // current part TOUJOURS de l'initial — jamais de 0
        $current = $initial;

        // Appliquer les mises à jour par dessus
        foreach ($this->progressionUpdates as $update) {
            foreach (['bloc_a', 'bloc_b', 'bloc_c', 'bloc_d', 'bloc_e'] as $bloc) {
                if (!is_null($update->$bloc)) {
                    $current[$bloc] = $update->$bloc;
                }
            }
        }

        return [
            'initial'     => $initial,
            'current'     => $current,
            'progression' => [
                'bloc_a' => $current['bloc_a'] - $initial['bloc_a'],
                'bloc_b' => $current['bloc_b'] - $initial['bloc_b'],
                'bloc_c' => $current['bloc_c'] - $initial['bloc_c'],
                'bloc_d' => $current['bloc_d'] - $initial['bloc_d'],
                'bloc_e' => $current['bloc_e'] - $initial['bloc_e'],
            ],
            'total_initial'     => array_sum($initial),
            'total_current'     => array_sum($current),  
            'total_progression' => array_sum($current) - array_sum($initial), 
            'interview'         => $interview,
        ];
    }
}
