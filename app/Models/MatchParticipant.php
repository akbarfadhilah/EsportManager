<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'match_id',
        'esport_team_id',
        'previous_match_id',
        'rounds_won'
    ];

    /**
     * Relationship: belongs to Match
     */
    public function match()
    {
        return $this->belongsTo(MatchModel::class, 'match_id');
    }

    /**
     * Relationship: belongs to EsportTeam
     */
    public function team()
    {
        return $this->belongsTo(EsportTeam::class, 'esport_team_id');
    }
}
