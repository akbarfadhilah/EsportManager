<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Round extends Model
{
    use HasFactory;

    protected $fillable = [
        'match_id',
        'round_number',
        'winner_team_id',
        'mvp_player_id'
    ];

    /**
     * Relationship: belongs to Match
     */
    public function match()
    {
        return $this->belongsTo(MatchModel::class, 'match_id');
    }

    /**
     * Relationship: belongs to EsportTeam (winner)
     */
    public function winner()
    {
        return $this->belongsTo(EsportTeam::class, 'winner_team_id');
    }

    /**
     * Relationship: MVP player of this round
     */
    public function mvp()
    {
        return $this->belongsTo(Player::class, 'mvp_player_id');
    }

    /**
     * Relationship: lineups (players + heroes) used in this round
     */
    public function lineups()
    {
        return $this->hasMany(RoundLineup::class);
    }
}
