<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_name',
    ];

    public function championships()
    {
        return $this->belongsToMany(Championship::class, 'championship_histories', 'team_id', 'championship_id')
            ->withPivot(['pro_goals', 'pro_goals_total', 'own_goals', 'own_goals_total', 'points', 'winner', 'opposing_team_id', 'place']);
    }
}
