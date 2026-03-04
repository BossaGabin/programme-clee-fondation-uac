<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
     use SoftDeletes;
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'avatar',
        'password',
        'role',
        'otp_code',
        'otp_expires_at',
        'email_verified_at',
        'is_active'
    ];

    protected $hidden = ['password', 'remember_token', 'otp_code'];

    protected $casts = [
        'otp_expires_at'    => 'datetime',
        'email_verified_at' => 'datetime',
        'is_active'         => 'boolean',
    ];

    // Helpers rôles
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
    public function isCoach(): bool
    {
        return $this->role === 'coach';
    }
    public function isCandidat(): bool
    {
        return $this->role === 'candidat';
    }

    // Relations
    public function coachProfile()
    {
        return $this->hasOne(CoachProfile::class);
    }

    public function candidatProfile()
    {
        return $this->hasOne(CandidatProfile::class);
    }

    // Candidat
    public function diagnosticRequests()
    {
        return $this->hasMany(DiagnosticRequest::class, 'candidat_id');
    }

    // Coach : candidats affectés
    public function assignments()
    {
        return $this->hasMany(CoachAssignment::class, 'coach_id');
    }

    // Candidat : son affectation coach
    public function candidatAssignment()
    {
        return $this->hasOne(CoachAssignment::class, 'candidat_id');
    }

    public function followUpSteps()
    {
        return $this->hasMany(FollowUpStep::class, 'candidat_id')->orderBy('created_at', 'asc');
    }
    // Besoin professionnel du candidat
    public function needAssignment()
    {
        return $this->hasOne(NeedAssignment::class, 'candidat_id');
    }

    // Projet professionnel du candidat
    public function professionalProject()
    {
        return $this->hasOne(ProfessionalProject::class, 'candidat_id');
    }

    // Notifier à Laravel d'utiliser ta notification personnalisée pour le mot de passe oublié
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
