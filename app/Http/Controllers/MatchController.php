<?php

namespace App\Http\Controllers;

use App\Models\MatchModel;
use App\Models\MatchParticipant;
use App\Models\Round;
use App\Models\RoundLineup;
use App\Models\Player;
use App\Models\TeamStatistic;
use App\Models\Tournament;
use App\Models\Hero;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    /**
     * Display the auto-generated schedule board grouped by day/round.
     */
    public function index($tournamentId)
    {
        $tournament = Tournament::with(['matches.participants.team', 'matches.winner', 'slots.esportTeam'])
            ->findOrFail($tournamentId);

        $days = collect($tournament->matches)
            ->groupBy('round_index')
            ->sortKeys()
            ->map(function ($matches) {
                return $matches->sortBy('match_number')->values();
            });

        $teams = $tournament->slots->whereNotNull('esport_team_id')->pluck('esportTeam');

        return view('matches.index', compact('tournament', 'days', 'teams'));
    }

    /**
     * Show a single match with round-by-round lineup + hero selection.
     */
    public function show(MatchModel $match)
    {
        $match->load([
            'participants.team.players',
            'rounds.winner',
            'rounds.mvp',
            'rounds.lineups.player',
            'rounds.lineups.hero',
            'rounds.lineups.team',
            'tournament',
        ]);

        $heroes = Hero::orderBy('nama_hero')->get();

        $team1 = $match->participants[0] ?? null;
        $team2 = $match->participants[1] ?? null;

        $team1Players = $team1 && $team1->esport_team_id
            ? Player::where('esport_team_id', $team1->esport_team_id)->where('jabatan', 'Player')->orderBy('nickname_game')->get()
            : collect();

        $team2Players = $team2 && $team2->esport_team_id
            ? Player::where('esport_team_id', $team2->esport_team_id)->where('jabatan', 'Player')->orderBy('nickname_game')->get()
            : collect();

        $playable = $team1 && $team2 && $team1->esport_team_id && $team2->esport_team_id && $match->status !== 'completed';

        return view('matches.show', compact('match', 'heroes', 'team1', 'team2', 'team1Players', 'team2Players', 'playable'));
    }

    /**
     * Record a round: user picks 5 players + 5 heroes per team (heroes & players
     * unique within the round). Winner is auto-picked by weighted random using
     * the combined PLAYER winrates. MVP is random from the winning lineup.
     */
    public function recordRound(Request $request, MatchModel $match)
    {
        if ($match->status === 'completed') {
            return back()->with('error', 'This match is already completed!');
        }

        $participants = $match->participants;
        $team1 = $participants[0] ?? null;
        $team2 = $participants[1] ?? null;

        if (!$team1 || !$team2 || !$team1->esport_team_id || !$team2->esport_team_id) {
            return back()->with('error', 'Pertandingan belum siap: lawan belum lengkap.');
        }

        $playerIdsA = $request->input('team1_players', []);
        $playerIdsB = $request->input('team2_players', []);
        $heroIdsA = $request->input('team1_heroes', []);
        $heroIdsB = $request->input('team2_heroes', []);

        $errors = [];

        if (count($playerIdsA) !== 5 || count($playerIdsB) !== 5) {
            $errors['lineup'] = 'Setiap tim wajib memilih tepat 5 pemain.';
        }

        if (count($heroIdsA) !== 5 || count($heroIdsB) !== 5) {
            $errors['lineup'] = $errors['lineup'] ?? 'Setiap tim wajib memilih tepat 5 hero.';
        }

        $allPlayers = array_merge($playerIdsA, $playerIdsB);
        $allHeroes = array_merge($heroIdsA, $heroIdsB);

        if (in_array('', $allPlayers, true) || in_array('', $allHeroes, true) || in_array(null, $allPlayers, true) || in_array(null, $allHeroes, true)) {
            $errors['lineup'] = $errors['lineup'] ?? 'Semua pemain dan hero harus dipilih.';
        }

        if (!$errors && count(array_unique($allPlayers)) !== count($allPlayers)) {
            $errors['lineup'] = $errors['lineup'] ?? 'Setiap pemain hanya boleh diturunkan 1 kali dalam satu ronde.';
        }

        if (!$errors && count(array_unique($allHeroes)) !== count($allHeroes)) {
            $errors['lineup'] = $errors['lineup'] ?? 'Setiap hero harus berbeda dalam satu ronde (10 hero unik).';
        }

        if (!$errors) {
            // Every player must belong to their team & must be a "Player"
            foreach ([[$team1, $playerIdsA], [$team2, $playerIdsB]] as [$team, $ids]) {
                foreach ($ids as $pid) {
                    $player = Player::find($pid);
                    if (!$player || $player->esport_team_id !== $team->esport_team_id || $player->jabatan !== 'Player') {
                        $errors['lineup'] = 'Ada pemain yang tidak sah untuk tim ' . ($team->team->nama_tim ?? '?') . '.';
                        break 2;
                    }
                }
            }
        }

        if (!$errors) {
            $validHeroes = Hero::whereIn('id', $allHeroes)->count();
            if ($validHeroes !== count($allHeroes)) {
                $errors['lineup'] = 'Ada hero yang tidak valid.';
            }
        }

        if ($errors) {
            return back()->withInput()->withErrors($errors);
        }

        // Combined PLAYER winrate decides the weighted random winner.
        $players = Player::whereIn('id', $allPlayers)->get()->keyBy('id');
        $sumA = array_sum(array_map(fn($id) => (float) $players[$id]->winrate, $playerIdsA));
        $sumB = array_sum(array_map(fn($id) => (float) $players[$id]->winrate, $playerIdsB));

        $total = $sumA + $sumB;
        if ($total <= 0) {
            $total = 100;
            $sumA = 50;
        }

        $random = mt_rand(1, 10000) / 100;
        $threshold = ($sumA / $total) * 100;
        $winnerTeamId = $random <= $threshold ? $team1->esport_team_id : $team2->esport_team_id;

        $winningPlayerIds = $winnerTeamId === $team1->esport_team_id ? $playerIdsA : $playerIdsB;
        $losingPlayerIds  = $winnerTeamId === $team1->esport_team_id ? $playerIdsB : $playerIdsA;
        $mvpPlayerId = $winningPlayerIds[array_rand($winningPlayerIds)];

        if ($match->status === 'pending') {
            $match->update(['status' => 'in_progress']);
        }

        $roundNumber = $match->rounds()->count() + 1;

        $round = Round::create([
            'match_id' => $match->id,
            'round_number' => $roundNumber,
            'winner_team_id' => $winnerTeamId,
            'mvp_player_id' => $mvpPlayerId,
        ]);

        // Store lineups: player + hero per team
        foreach ([[$team1, $playerIdsA, $heroIdsA], [$team2, $playerIdsB, $heroIdsB]] as [$team, $pids, $hids]) {
            foreach (array_combine($pids, $hids) as $pid => $hid) {
                RoundLineup::create([
                    'round_id' => $round->id,
                    'player_id' => $pid,
                    'hero_id' => $hid,
                    'esport_team_id' => $team->esport_team_id,
                ]);
            }
        }

        // Adjust player winrates: winner +1, loser -1 (clamped 1..99)
        foreach ($winningPlayerIds as $pid) {
            $this->adjustPlayerWinrate($pid, +1);
        }
        foreach ($losingPlayerIds as $pid) {
            $this->adjustPlayerWinrate($pid, -1);
        }

        // Update scoreboard
        $winnerParticipant = $match->participants()->where('esport_team_id', $winnerTeamId)->first();
        $winnerParticipant->increment('rounds_won');

        $winnerName = $winnerParticipant->team->nama_tim;
        $mvpNick = $players[$mvpPlayerId]->nickname_game;

        if ($winnerParticipant->rounds_won >= $match->rounds_to_win) {
            $this->completeMatch($match, $winnerTeamId);
            return redirect()->route('matches.show', $match)
                ->with('success', "Match completed! Pemenang: {$winnerName} (MVP ronde terakhir: {$mvpNick}).");
        }

        return back()->with('success', "Round {$roundNumber} dimenangkan oleh {$winnerName}. MVP round: {$mvpNick}!");
    }

    private function adjustPlayerWinrate(int $playerId, float $delta)
    {
        $player = Player::find($playerId);
        if (!$player) {
            return;
        }
        $newRate = max(1, min(99, (float) $player->winrate + $delta));
        Player::where('id', $playerId)->update(['winrate' => round($newRate, 2)]);
    }

    /**
     * Complete a match, update tournament statistics, advance knock-out
     * winners to the next bracket match and auto-complete the final.
     */
    private function completeMatch(MatchModel $match, $winnerTeamId)
    {
        $match->update([
            'status' => 'completed',
            'winner_team_id' => $winnerTeamId,
        ]);

        $participants = $match->participants;
        $totalRounds = $match->rounds()->count();

        foreach ($participants as $participant) {
            if (!$participant->esport_team_id) {
                continue;
            }

            $roundsWon = $participant->rounds_won;
            $isWinner = $participant->esport_team_id == $winnerTeamId;
            $points = $roundsWon + ($isWinner ? 2 : -2);

            $stats = TeamStatistic::firstOrCreate(
                ['tournament_id' => $match->tournament_id, 'esport_team_id' => $participant->esport_team_id],
                ['matches_played' => 0, 'matches_won' => 0, 'rounds_played' => 0, 'rounds_won' => 0, 'points' => 0, 'win_rate' => 50.00]
            );

            $stats->matches_played++;
            $stats->rounds_played += $totalRounds;
            $stats->rounds_won += $roundsWon;
            $stats->points += $points;
            if ($isWinner) {
                $stats->matches_won++;
            }
            $stats->win_rate = $stats->matches_played ? round($stats->matches_won / $stats->matches_played * 100, 2) : 50.00;
            $stats->save();
        }

        $tournament = $match->tournament;

        // Knock-out: advance the winner into the next bracket match
        if ($tournament->format === 'knockout') {
            $parents = MatchParticipant::where('previous_match_id', $match->id)->get();
            foreach ($parents as $parent) {
                $parent->update(['esport_team_id' => $winnerTeamId]);
                TeamStatistic::firstOrCreate(
                    ['tournament_id' => $tournament->id, 'esport_team_id' => $winnerTeamId],
                    ['matches_played' => 0, 'matches_won' => 0, 'rounds_played' => 0, 'rounds_won' => 0, 'points' => 0, 'win_rate' => 50.00]
                );
            }
        }

        // Grand final (either format) finishes the tournament automatically
        if ($match->is_grand_final) {
            $tournament->completeTournament();
        }
    }

    /**
     * Delete a match
     */
    public function destroy(MatchModel $match)
    {
        if ($match->status === 'completed') {
            return back()->with('error', 'Cannot delete completed match!');
        }

        $match->delete();
        return redirect()->route('matches.index', $match->tournament_id)->with('success', 'Match deleted!');
    }
}