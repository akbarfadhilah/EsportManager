<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = [
        'nama_asli', 
        'nickname_game', 
        'deskripsi_pemain', 
        'tanggal_lahir',
        'esport_team_id', 
        'photo', 
        'region_id',
        'jabatan',
        'winrate'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'winrate' => 'decimal:2'
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
