<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InterviewScore extends Model
{
    protected $fillable = ['interview_id', 'competence_id', 'note', 'comment'];

    public function interview()
    {
        return $this->belongsTo(Interview::class);
    }

    public function competence()
    {
        return $this->belongsTo(Competence::class);
    }
}