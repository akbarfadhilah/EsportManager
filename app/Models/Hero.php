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

    public const GAMEPLAY_OPTIONS = [
        'Assassin (cepat)',
        'Fighter',
        'Support',
        'Tank',
        'Damage dealer (pemberi damage tinggi)',
        'Tank Support',
        'Fighter support',
    ];

    public const ROLE_OPTIONS = [
        'Good for Jungler',
        'Good for Goldlane',
        'Good for ExpLane',
        'Good for Roam',
        'Good for Midlane',
        'NotBad for Jungler',
        'NotBad for Goldlane',
        'NotBad for ExpLane',
        'NotBad for Roam',
        'NotBad for Midlane',
        'Bad for Goldlane',
        'Bad for Roam',
    ];

    protected $fillable = ['nama_hero', 'targeting', 'gaya_permainan', 'roles', 'deskripsi', 'foto'];

    protected $casts = [
        'roles' => 'array',
    ];

    public function lineups()
    {
        return $this->hasMany(RoundLineup::class);
    }
}