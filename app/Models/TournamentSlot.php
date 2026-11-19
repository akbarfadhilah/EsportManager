<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TournamentSlot extends Model
{
    protected $fillable = ['tournament_id', 'slot_number', 'esport_team_id', 'hadiah_slot'];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function esportTeam()
    {
        return $this->belongsTo(EsportTeam::class);
    }
}
