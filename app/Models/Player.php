<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    public const PERSONNEL_TYPE_COMMON = 'Common';

    public const PERSONNEL_TYPE_ATHLETE = 'Atlet';

    public const ATHLETE_ROLE_OPTIONS = [
        'Goldlane',
        'Midlane',
        'Jungler',
        'Explane',
        'Roam',
    ];

    public const ATHLETE_GAMEPLAY_OPTIONS = [
        'Assasin',
        'Petarung',
        'Suport',
        'Tank',
        'Alteleri',
        'Penembak Jitu',
    ];

    public const TARGETING_ORDER_OPTIONS = [
        'Dekat',
        'Sedang',
        'Jauh',
    ];

    protected $fillable = [
        'nama_asli', 
        'nickname_game', 
        'deskripsi_pemain', 
        'tanggal_lahir',
        'esport_team_id', 
        'photo', 
        'region_id',
        'jabatan',
        'winrate',
        'kategori_personel',
        'role_utama',
        'role_cadangan',
        'role_kurang_kompeten',
        'gaya_permainan',
        'targeting_urut',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'winrate' => 'decimal:2',
        'role_cadangan' => 'array',
        'role_kurang_kompeten' => 'array',
        'gaya_permainan' => 'array',
        'targeting_urut' => 'array',
    ];

    public function esportTeam()
    {
        return $this->belongsTo(EsportTeam::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    /**
     * "Current team" penampilan:
     *  - punya tim esport → klubnya (link)
     *  - menjabat di region → "REGION" (link ke regions)
     *  - lainnya → Free Agent
     */
    public function getCurrentTeamAttribute()
    {
        if ($this->esport_team_id && $this->esportTeam) {
            return [
                'type' => 'team',
                'label' => $this->esportTeam->nama_tim,
                'route' => 'teams.show',
                'param' => $this->esportTeam->id,
            ];
        }

        if (in_array($this->jabatan, ['Ketua Persatuan Game', 'Pelatih Timnas'])) {
            return [
                'type' => 'region',
                'label' => 'REGION',
                'route' => 'regions.index',
                'param' => null,
            ];
        }

        return [
            'type' => 'free',
            'label' => 'Free Agent',
            'route' => null,
            'param' => null,
        ];
    }
}
