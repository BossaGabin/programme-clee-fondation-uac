<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
