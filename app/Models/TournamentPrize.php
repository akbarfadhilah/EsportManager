<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TournamentPrize extends Model
{
    protected $fillable = ['tournament_id', 'rank', 'prize_amount'];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }
}
