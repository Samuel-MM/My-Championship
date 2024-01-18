<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChampionshipHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        "team_id",
        "pro_goals",
        "pro_goals_total",
        "own_goals",
        "own_goals_total",
        "points",
        "winner",
        "opposing_team_id",
        "place",
        "championship_id"
    ];

}
