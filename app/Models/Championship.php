<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Championship extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
    ];

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'championship_histories', 'championship_id', 'team_id')
            ->withPivot(['pro_goals', 'pro_goals_total', 'own_goals', 'own_goals_total', 'points', 'winner', 'opposing_team_id', 'place']);
    }
}
