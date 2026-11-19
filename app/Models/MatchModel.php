<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchModel extends Model
{
    use HasFactory;

    protected $table = 'matches';

    protected $fillable = [
        'tournament_id',
        'match_number',
        'round_index',
        'bo_format',
        'status',
        'winner_team_id',
        'is_grand_final'
    ];

    /**
     * Relationship: belongs to Tournament
     */
    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    /**
     * Relationship: has many MatchParticipants
     */
    public function participants()
    {
        return $this->hasMany(MatchParticipant::class, 'match_id');
    }

    /**
     * Relationship: has many Rounds
     */
    public function rounds()
    {
        return $this->hasMany(Round::class, 'match_id');
    }

    /**
     * Relationship: belongs to EsportTeam (winner)
     */
    public function winner()
    {
        return $this->belongsTo(EsportTeam::class, 'winner_team_id');
    }

    /**
     * Get max rounds for this BO format
     */
    public function getMaxRoundsAttribute()
    {
        return match($this->bo_format) {
            'BO3' => 3,
            'BO5' => 5,
            'BO7' => 7,
            default => 3
        };
    }

    /**
     * Get rounds needed to win
     */
    public function getRoundsToWinAttribute()
    {
        return match($this->bo_format) {
            'BO3' => 2,
            'BO5' => 3,
            'BO7' => 4,
            default => 2
        };
    }
}
