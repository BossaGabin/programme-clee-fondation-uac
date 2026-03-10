<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CandidatProfile extends Model
{
    protected $fillable = [
        'user_id', 'date_of_birth', 'gender', 'address',
        'niveau_etude', 'domaine_formation', 'experience_years',
        'situation_actuelle', 'cv_path', 'profile_completion','situation_autre'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Calcul automatique du pourcentage de complétion
    public function calculateCompletion(): int
    {
        $fields = [
            'date_of_birth'     => 20,
            'gender'            => 10,
            'address'           => 10,
            'niveau_etude'      => 20,
            'domaine_formation' => 20,
            'experience_years'  => 10,
            'situation_actuelle'=> 10,
        ];

        $total = 0;
        foreach ($fields as $field => $weight) {
            if (!is_null($this->$field) && $this->$field !== '') {
                $total += $weight;
            }
        }

        $this->profile_completion = $total;
        $this->save();

        return $total;
    }
}

