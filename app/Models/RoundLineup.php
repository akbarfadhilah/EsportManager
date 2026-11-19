<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoundLineup extends Model
{
    protected $fillable = ['round_id', 'player_id', 'hero_id', 'esport_team_id'];

    public function round()
    {
        return $this->belongsTo(Round::class);
    }

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function hero()
    {
        return $this->belongsTo(Hero::class);
    }

    public function team()
    {
        return $this->belongsTo(EsportTeam::class, 'esport_team_id');
    }
}