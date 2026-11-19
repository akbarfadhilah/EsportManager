<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamStatistic extends Model
{
    use HasFactory;

    protected $fillable = [
        'tournament_id',
        'esport_team_id',
        'matches_played',
        'matches_won',
        'rounds_played',
        'rounds_won',
        'points',
        'win_rate'
    ];

    protected $casts = [
        'win_rate' => 'decimal:2'
    ];

    /**
     * Relationship: belongs to Tournament
     */
    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    /**
     * Relationship: belongs to EsportTeam
     */
    public function team()
    {
        return $this->belongsTo(EsportTeam::class, 'esport_team_id');
    }
}
