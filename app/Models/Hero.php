<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hero extends Model
{
    public const TARGETING_OPTIONS = [
        'Dekat',
        'Sedang',
        'Jauh',
        'Kombinasi Dekat-Sedang',
        'Kombinasi Dekat-Jauh',
        'Kombinasi Sedang-Jauh',
    ];

    public const ATTACK_STYLE_OPTIONS = [
        'Assasin',
        'Petarung',
        'Suport',
        'Tank',
        'Alteleri',
        'Penembak Jitu',
    ];

    public const ROLE_OPTIONS = [
        'Goldlane',
        'Midlane',
        'Jungler',
        'Explane',
        'Roam',
    ];

    public const ATTACK_TYPE_OPTIONS = [
        'Magis',
        'Physical',
        'True Damage',
        'Adaptive damage',
    ];

    public const ATTACK_AREA_OPTIONS = [
        '1 Target',
        'Area',
    ];

    public const ATTACK_PATTERN_OPTIONS = [
        'Poke',
        'Burst',
        'DPS',
        'DoT',
    ];

    protected $fillable = [
        'nama_hero',
        'targeting',
        'gaya_permainan',
        'jenis_serangan',
        'area_serangan',
        'pola_serangan',
        'role_utama',
        'role_pendukung',
        'role_tidak_direkomendasikan',
        'roles',
        'deskripsi',
        'foto',
    ];

    protected $casts = [
        'roles' => 'array',
        'role_pendukung' => 'array',
        'role_tidak_direkomendasikan' => 'array',
    ];

    public function lineups()
    {
        return $this->hasMany(RoundLineup::class);
    }
}