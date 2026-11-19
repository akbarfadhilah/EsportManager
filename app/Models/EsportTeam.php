<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EsportTeam extends Model
{
    protected $fillable = [
        'nama_tim', 'deskripsi_tim', 'logo_esport', 'tempat_kota_gh',
        'total_kekayaan', 'winrate', 'penghargaan',
        'region_id', 'pemilik_id', 'ceo_id', 'pelatih_utama_id',
        'pelatih_kedua_id', 'manager_id', 'analis_id'
    ];

    public function players()
    {
        return $this->hasMany(Player::class);
    }

    public function tournamentSlots()
    {
        return $this->hasMany(TournamentSlot::class);
    }

    // New relationships for Region and Staff
    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function pemilik()
    {
        return $this->belongsTo(Player::class, 'pemilik_id');
    }

    public function ceo()
    {
        return $this->belongsTo(Player::class, 'ceo_id');
    }

    public function pelatihUtama()
    {
        return $this->belongsTo(Player::class, 'pelatih_utama_id');
    }

    public function pelatihKedua()
    {
        return $this->belongsTo(Player::class, 'pelatih_kedua_id');
    }

    public function manager()
    {
        return $this->belongsTo(Player::class, 'manager_id');
    }

    public function analis()
    {
        return $this->belongsTo(Player::class, 'analis_id');
    }
}
