<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FollowUpStep extends Model
{
    protected $fillable = [
        'candidat_id', 'coach_id', 'title',
        'description', 'status', 'completed_date', 'result'
    ];

    protected $casts = ['completed_date' => 'date'];

    public function candidat()
    {
        return $this->belongsTo(User::class, 'candidat_id');
    }

    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }
}