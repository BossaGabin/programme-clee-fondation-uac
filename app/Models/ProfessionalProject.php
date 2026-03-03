<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfessionalProject extends Model
{
    protected $fillable = [
        'candidat_id', 'coach_id', 'titre_projet', 'secteur_cible',
        'poste_vise', 'description', 'objectif_court_terme', 'objectif_long_terme'
    ];

    public function candidat()
    {
        return $this->belongsTo(User::class, 'candidat_id');
    }

    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }
}