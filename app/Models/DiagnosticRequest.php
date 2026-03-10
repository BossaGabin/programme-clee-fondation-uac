<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiagnosticRequest extends Model
{
    protected $fillable = [
    'candidat_id',
    'status',
    'parcours_professionnel',
    'mode_entretien',        // ← ajouter
    'plateforme_enligne',    // ← ajouter
    'numero_whatsapp',       // ← ajouter
    'numero_appel',          // ← ajouter
    'note_admin',
    'validated_at',
];

    protected $casts = ['validated_at' => 'datetime'];

    public function candidat()
    {
        return $this->belongsTo(User::class, 'candidat_id');
    }

    public function assignment()
    {
        return $this->hasOne(CoachAssignment::class);
    }
}
