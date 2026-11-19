<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TournamentAward extends Model
{
    use HasFactory;

    protected $fillable = [
        'tournament_id',
        'award_name',
        'prize_amount'
    ];

    protected $casts = [
        'prize_amount' => 'decimal:2'
    ];

    /**
     * Relationship: belongs to Tournament
     */
    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }
}
