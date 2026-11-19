<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    protected $fillable = [
        'nama_tournament', 'penyelenggara', 'total_hadiah', 'deskripsi_tournament',
        'logo_tournament', 'jumlah_slot', 'negara_penyelenggara', 'tempat_final', 'tier',
        'format', 'bo_format', 'status'
    ];

    public function slots()
    {
        return $this->hasMany(TournamentSlot::class);
    }

    public function awards()
    {
        return $this->hasMany(TournamentAward::class);
    }

    public function prizes()
    {
        return $this->hasMany(TournamentPrize::class);
    }

    public function matches()
    {
        return $this->hasMany(MatchModel::class);
    }

    public function statistics()
    {
        return $this->hasMany(TeamStatistic::class);
    }

    /**
     * Complete tournament and distribute prizes
     */
    public function completeTournament()
    {
        if ($this->status === 'completed') {
            return;
        }

        // Get initial standings by points
        $standings = $this->statistics->sortByDesc('points')->values();

        // Check for Grand Final
        $grandFinal = $this->matches()->where('is_grand_final', true)->where('status', 'completed')->first();

        if ($grandFinal && $grandFinal->winner_team_id) {
            $grandFinalWinnerId = $grandFinal->winner_team_id;
            // Find loser ID
            $participant = $grandFinal->participants->where('esport_team_id', '!=', $grandFinalWinnerId)->first();
            $grandFinalLoserId = $participant ? $participant->esport_team_id : null;

            // Remove GF participants from standings list to re-insert at top
            $filteredStandings = $standings->filter(function ($stat) use ($grandFinalWinnerId, $grandFinalLoserId) {
                return $stat->esport_team_id != $grandFinalWinnerId && $stat->esport_team_id != $grandFinalLoserId;
            });

            // Get stat objects for winner and loser
            $winnerStat = $standings->firstWhere('esport_team_id', $grandFinalWinnerId);
            $loserStat = $standings->firstWhere('esport_team_id', $grandFinalLoserId);

            // Create new standings collection: [Winner, Loser, ...Others]
            $newStandings = collect([]);
            if ($winnerStat) $newStandings->push($winnerStat);
            if ($loserStat) $newStandings->push($loserStat);
            
            // Merge remaining teams
            $standings = $newStandings->merge($filteredStandings);
        }
        
        // Loop through prizes and distribute
        foreach ($this->prizes as $prize) {
            // Rank is 1-based, array index is 0-based
            $teamStat = $standings->get($prize->rank - 1);
            
            if ($teamStat && $teamStat->team) {
                $team = $teamStat->team;
                
                // 1. Increase Team Wealth
                $team->total_kekayaan += $prize->prize_amount;
                $team->save();
            }
        }

        // Record the champion's achievement automatically
        $championStat = $standings->first();
        if ($championStat && $championStat->team) {
            $champion = $championStat->team;
            $achievement = '🏆 Juara: ' . $this->nama_tournament;
            $champion->penghargaan = trim(($champion->penghargaan ?? '') . "\n" . $achievement);
            $champion->save();
        }

        $this->update(['status' => 'completed']);
    }

    /**
     * Get the winner of the tournament
     */
    public function getWinnerAttribute()
    {
        if ($this->status !== 'completed') {
            return null;
        }

        // Priority 1: Grand Final Winner
        $grandFinal = $this->matches()->where('is_grand_final', true)->where('status', 'completed')->first();
        if ($grandFinal && $grandFinal->winner_team_id) {
            return EsportTeam::find($grandFinal->winner_team_id);
        }

        // Priority 2: Top of Standings
        $topStat = $this->statistics()->orderByDesc('points')->first();
        return $topStat ? $topStat->team : null;
    }
}
