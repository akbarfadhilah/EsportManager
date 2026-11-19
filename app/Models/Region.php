<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Region extends Model
{
    protected $fillable = [
        'nama_negara',
        'ketua_persatuan_game_id',
        'pelatih_timnas_id',
        'foto_negara',
    ];

    public function ketua(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'ketua_persatuan_game_id');
    }

    public function pelatihTimnas(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'pelatih_timnas_id');
    }
}
