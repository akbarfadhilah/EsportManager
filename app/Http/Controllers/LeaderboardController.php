<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\TeamStatistic;
use App\Models\MatchModel;
use App\Models\Round;
use App\Models\RoundLineup;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    /**
     * Display tournament leaderboard
     */
    public function show($tournamentId)
    {
        $tournament = Tournament::findOrFail($tournamentId);
        
        // Get statistics ordered by points (descending), then win_rate
        $statistics = TeamStatistic::with('team')
            ->where('tournament_id', $tournamentId)
            ->orderBy('points', 'desc')
            ->orderBy('win_rate', 'desc')
            ->get();

        return view('leaderboard.show', compact('tournament', 'statistics'));
    }

    /**
     * Player Contribution: nr | nickname | total MVP | asal tim
     */
    public function playerContribution($tournamentId)
    {
        $tournament = Tournament::findOrFail($tournamentId);

        $rounds = Round::whereHas('match', fn($q) => $q->where('tournament_id', $tournamentId))
            ->whereNotNull('mvp_player_id')
            ->with('lineups')
            ->get();

        $contributions = [];

        foreach ($rounds as $round) {
            $lineup = $round->lineups->where('player_id', $round->mvp_player_id)->first();
            $teamId = $lineup ? $lineup->esport_team_id : null;

            if (!isset($contributions[$round->mvp_player_id])) {
                $contributions[$round->mvp_player_id] = ['count' => 0, 'team_id' => $teamId];
            }
            $contributions[$round->mvp_player_id]['count']++;
        }

        // Sort by MVP count descending
        uasort($contributions, fn($a, $b) => $b['count'] <=> $a['count']);

        $rows = [];
        $number = 1;
        foreach ($contributions as $playerId => $info) {
            $player = \App\Models\Player::with('esportTeam')->find($playerId);
            if (!$player) {
                continue;
            }
            $team = $info['team_id'] ? \App\Models\EsportTeam::find($info['team_id']) : $player->esportTeam;
            $rows[] = [
                'number' => $number++,
                'player' => $player,
                'mvp_count' => $info['count'],
                'team' => $team,
            ];
        }

        return view('leaderboard.player_contribution', compact('tournament', 'rows'));
    }

    /**
     * Show team's match history in tournament
     */
    public function teamHistory($tournamentId, $teamId)
    {
        $tournament = Tournament::findOrFail($tournamentId);
        $team = \App\Models\EsportTeam::findOrFail($teamId);
        
        // Get all matches where this team participated
        $matches = MatchModel::where('tournament_id', $tournamentId)
            ->whereHas('participants', function($query) use ($teamId) {
                $query->where('esport_team_id', $teamId);
            })
            ->with(['participants.team', 'winner', 'rounds'])
            ->orderBy('match_number')
            ->get();

        // Get team statistics
        $stats = TeamStatistic::where('tournament_id', $tournamentId)
            ->where('esport_team_id', $teamId)
            ->first();

        return view('leaderboard.team_history', compact('tournament', 'team', 'matches', 'stats'));
    }
}
