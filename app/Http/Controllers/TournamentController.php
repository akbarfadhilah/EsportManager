<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\TournamentSlot;
use App\Models\EsportTeam;
use App\Models\MatchModel;
use App\Models\MatchParticipant;
use App\Models\TeamStatistic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TournamentController extends Controller
{
    private const TIERS = ['S+ Tier', 'S Tier', 'A Tier', 'B Tier', 'C Tier', 'National Invitation', 'International Invitation'];
    private const FORMATS = ['standard' => 'Standar (Round Robin 2 Pekan)', 'knockout' => 'Knockout (Gugur)'];
    private const BO_FORMATS = ['BO3', 'BO5', 'BO7'];

    public function index()
    {
        $tournaments = Tournament::all();
        return view('tournaments.index', compact('tournaments'));
    }

    public function create()
    {
        $tiers = self::TIERS;
        $formats = self::FORMATS;
        $boFormats = self::BO_FORMATS;
        $teams = EsportTeam::all();
        return view('tournaments.create', compact('tiers', 'formats', 'boFormats', 'teams'));
    }

    public function show(Tournament $tournament)
    {
        $tournament->load('slots.esportTeam.players', 'awards', 'prizes', 'matches.participants');

        $matchReadiness = $this->calculateMatchReadiness($tournament);

        return view('tournaments.show', compact('tournament', 'matchReadiness'));
    }

    /**
     * Calculate match readiness based on tournament format.
     * Standard  : double round robin → every pair meets twice (2 weeks).
     * Knockout  : ends automatically when the final is completed.
     */
    private function calculateMatchReadiness(Tournament $tournament)
    {
        $teamIds = $tournament->slots
            ->whereNotNull('esport_team_id')
            ->pluck('esport_team_id')
            ->unique()
            ->values();

        $teamCount = $teamIds->count();
        $totalMatches = $tournament->matches->count();
        $completedMatches = $tournament->matches->where('status', 'completed')->count();

        if ($tournament->format === 'knockout') {
            $ready = $tournament->status === 'completed';
            return [
                'ready' => $ready,
                'message' => $ready
                    ? 'Turnamen selesai.'
                    : "Progress bracket: {$completedMatches} / {$totalMatches} pertandingan.",
                'totalMatches' => $totalMatches,
                'completedMatches' => $completedMatches,
                'requiredMatches' => $totalMatches,
                'teamCount' => $teamCount,
                'missingPairs' => [],
                'unequalTeams' => [],
            ];
        }

        if ($teamCount < 2) {
            return [
                'ready' => false,
                'message' => 'Minimal 2 tim diperlukan untuk tournament.',
                'totalMatches' => 0,
                'completedMatches' => 0,
                'requiredMatches' => 0,
                'teamCount' => $teamCount,
                'missingPairs' => [],
                'unequalTeams' => [],
            ];
        }

        // Double round robin: every pair meets TWICE (n*(n-1) matches)
        $requiredMatches = $teamCount * ($teamCount - 1);

        $encounters = [];
        $matchCounts = [];
        foreach ($teamIds as $id) {
            $matchCounts[$id] = 0;
        }

        foreach ($tournament->matches->where('status', 'completed') as $match) {
            $participants = $match->participants;
            if ($participants->count() === 2) {
                $t1 = $participants[0]->esport_team_id;
                $t2 = $participants[1]->esport_team_id;
                $key = min($t1, $t2) . '-' . max($t1, $t2);
                $encounters[$key] = ($encounters[$key] ?? 0) + 1;
                if (isset($matchCounts[$t1])) $matchCounts[$t1]++;
                if (isset($matchCounts[$t2])) $matchCounts[$t2]++;
            }
        }

        $missingPairs = [];
        $teamIdArray = $teamIds->toArray();
        for ($i = 0; $i < count($teamIdArray); $i++) {
            for ($j = $i + 1; $j < count($teamIdArray); $j++) {
                $id1 = $teamIdArray[$i];
                $id2 = $teamIdArray[$j];
                $key = min($id1, $id2) . '-' . max($id1, $id2);

                if (($encounters[$key] ?? 0) < 2) {
                    $team1 = EsportTeam::find($id1);
                    $team2 = EsportTeam::find($id2);
                    $missingPairs[] = ($team1->nama_tim ?? '?') . ' vs ' . ($team2->nama_tim ?? '?');
                }
            }
        }

        $unfinishedMatches = $tournament->matches->whereIn('status', ['pending', 'in_progress'])->count();

        $ready = count($missingPairs) === 0 && $unfinishedMatches === 0 && $completedMatches >= $requiredMatches;

        $message = '';
        if (!$ready) {
            $parts = [];
            if ($unfinishedMatches > 0) {
                $parts[] = $unfinishedMatches . ' match masih berlangsung/pending';
            }
            if (count($missingPairs) > 0) {
                $parts[] = count($missingPairs) . ' pasangan tim belum bertemu dua kali';
            }
            $message = implode('. ', $parts) . '.';
        }

        return [
            'ready' => $ready,
            'message' => $message,
            'totalMatches' => $tournament->matches->count(),
            'completedMatches' => $completedMatches,
            'requiredMatches' => $requiredMatches,
            'teamCount' => $teamCount,
            'missingPairs' => $missingPairs,
            'unequalTeams' => [],
            'unfinishedMatches' => $unfinishedMatches,
        ];
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_tournament' => 'required|string|max:255',
            'penyelenggara' => 'required|string|max:255',
            'deskripsi_tournament' => 'required|string',
            'logo_tournament' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'negara_penyelenggara' => 'required|string|max:255',
            'tempat_final' => 'required|string|max:255',
            'tier' => 'required|in:S+ Tier,S Tier,A Tier,B Tier,C Tier,National Invitation,International Invitation',
            'format' => 'required|in:standard,knockout',
            'bo_format' => 'required|in:BO3,BO5,BO7',
            'slot_teams' => 'nullable|array',
            'award_names' => 'nullable|array',
            'award_prizes' => 'nullable|array',
            'prize_amounts' => 'nullable|array',
            'prize_ranks' => 'nullable|array',
        ]);

        $slotTeams = array_values(array_filter($request->slot_teams ?? []));
        if (count($slotTeams) < 4 || count($slotTeams) % 4 !== 0) {
            return back()->withInput()->withErrors([
                'slot_teams' => 'Jumlah tim harus kelipatan 4 (4, 8, 12, 16, 20, dst). Saat ini terisi ' . count($slotTeams) . ' tim.'
            ]);
        }

        if ($request->hasFile('logo_tournament')) {
            $validated['logo_tournament'] = $request->file('logo_tournament')->store('logos/tournaments', 'public');
        }

        // Hapus key array dari slot agar tidak tersimpan di kolom
        unset($validated['slot_teams'], $validated['award_names'], $validated['award_prizes'], $validated['prize_amounts'], $validated['prize_ranks']);

        $totalHadiah = array_sum(array_filter($request->prize_amounts ?? []));
        $validated['total_hadiah'] = $totalHadiah;
        $validated['jumlah_slot'] = count($slotTeams);

        $tournament = Tournament::create($validated);

        for ($i = 1; $i <= count($slotTeams); $i++) {
            TournamentSlot::create([
                'tournament_id' => $tournament->id,
                'slot_number' => $i,
                'esport_team_id' => $slotTeams[$i - 1],
            ]);
        }

        $this->createAwards($tournament, $request);
        $this->createPrizes($tournament, $request);

        // Auto-generate the full schedule
        $this->generateSchedule($tournament);

        return redirect()->route('tournaments.show', $tournament)->with('success', 'Tournament berhasil ditambahkan & jadwal dibuat otomatis!');
    }

    public function edit(Tournament $tournament)
    {
        $tiers = self::TIERS;
        $formats = self::FORMATS;
        $boFormats = self::BO_FORMATS;
        $teams = EsportTeam::all();
        $tournament->load('slots.esportTeam', 'awards', 'prizes');
        return view('tournaments.edit', compact('tournament', 'tiers', 'formats', 'boFormats', 'teams'));
    }

    public function update(Request $request, Tournament $tournament)
    {
        $validated = $request->validate([
            'nama_tournament' => 'required|string|max:255',
            'penyelenggara' => 'required|string|max:255',
            'deskripsi_tournament' => 'required|string',
            'logo_tournament' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'negara_penyelenggara' => 'required|string|max:255',
            'tempat_final' => 'required|string|max:255',
            'tier' => 'required|in:S+ Tier,S Tier,A Tier,B Tier,C Tier,National Invitation,International Invitation',
            'format' => 'required|in:standard,knockout',
            'bo_format' => 'required|in:BO3,BO5,BO7',
            'slot_teams' => 'nullable|array',
            'award_names' => 'nullable|array',
            'award_prizes' => 'nullable|array',
            'prize_amounts' => 'nullable|array',
            'prize_ranks' => 'nullable|array',
        ]);

        $slotTeams = array_values(array_filter($request->slot_teams ?? []));
        if (count($slotTeams) < 4 || count($slotTeams) % 4 !== 0) {
            return back()->withInput()->withErrors([
                'slot_teams' => 'Jumlah tim harus kelipatan 4 (4, 8, 12, 16, 20, dst). Saat ini terisi ' . count($slotTeams) . ' tim.'
            ]);
        }

        if ($request->hasFile('logo_tournament')) {
            if ($tournament->logo_tournament) {
                Storage::disk('public')->delete($tournament->logo_tournament);
            }
            $validated['logo_tournament'] = $request->file('logo_tournament')->store('logos/tournaments', 'public');
        }

        unset($validated['slot_teams'], $validated['award_names'], $validated['award_prizes'], $validated['prize_amounts'], $validated['prize_ranks']);

        $validated['total_hadiah'] = array_sum(array_filter($request->prize_amounts ?? []));
        $validated['jumlah_slot'] = count($slotTeams);

        $tournament->update($validated);

        // Replace slots
        $tournament->slots()->delete();
        for ($i = 1; $i <= count($slotTeams); $i++) {
            TournamentSlot::create([
                'tournament_id' => $tournament->id,
                'slot_number' => $i,
                'esport_team_id' => $slotTeams[$i - 1],
            ]);
        }

        $tournament->awards()->delete();
        $this->createAwards($tournament, $request);

        $tournament->prizes()->delete();
        $this->createPrizes($tournament, $request);

        // Reset schedule & statistics, then regenerate
        $tournament->matches()->delete();
        TeamStatistic::where('tournament_id', $tournament->id)->delete();
        $this->generateSchedule($tournament);

        return redirect()->route('tournaments.show', $tournament)->with('success', 'Tournament berhasil diubah & jadwal dibangun ulang!');
    }

    public function grandFinal(Request $request, Tournament $tournament)
    {
        if ($tournament->status === 'completed') {
            return back()->with('error', 'Tournament already completed.');
        }

        $request->validate([
            'bo_format' => 'required|in:BO3,BO5,BO7'
        ]);

        $standings = TeamStatistic::where('tournament_id', $tournament->id)
            ->orderByDesc('points')
            ->limit(2)
            ->get();

        if ($standings->count() < 2) {
            return back()->with('error', 'Tidak cukup tim untuk Grand Final.');
        }

        $team1Id = $standings[0]->esport_team_id;
        $team2Id = $standings[1]->esport_team_id;

        $nextMatchNumber = $tournament->matches()->max('match_number') + 1;
        $maxDay = $tournament->matches()->max('round_index') ?? 0;

        $match = MatchModel::create([
            'tournament_id' => $tournament->id,
            'match_number' => $nextMatchNumber,
            'round_index' => $maxDay + 1,
            'bo_format' => $request->bo_format,
            'status' => 'pending',
            'is_grand_final' => true
        ]);

        MatchParticipant::create(['match_id' => $match->id, 'esport_team_id' => $team1Id, 'rounds_won' => 0]);
        MatchParticipant::create(['match_id' => $match->id, 'esport_team_id' => $team2Id, 'rounds_won' => 0]);

        $team1Name = EsportTeam::find($team1Id)->nama_tim;
        $team2Name = EsportTeam::find($team2Id)->nama_tim;

        return redirect()->route('matches.show', $match)
            ->with('success', '🏆 Grand Final dibuat! ' . $team1Name . ' vs ' . $team2Name . ' (' . $match->bo_format . ')');
    }

    public function complete(Tournament $tournament)
    {
        if ($tournament->status === 'completed') {
            return back()->with('error', 'Tournament already completed.');
        }

        $tournament->completeTournament();

        return redirect()->route('tournaments.show', $tournament)->with('success', 'Tournament completed! Prizes distributed successfully.');
    }

    public function destroy(Tournament $tournament)
    {
        if ($tournament->logo_tournament) {
            Storage::disk('public')->delete($tournament->logo_tournament);
        }
        $tournament->delete();
        return redirect()->route('tournaments.index')->with('success', 'Tournament berhasil dihapus!');
    }

    /* ------------------------------------------------------------------
       Schedule generators
       ------------------------------------------------------------------ */

    private function generateSchedule(Tournament $tournament)
    {
        $teamIds = $tournament->slots()->whereNotNull('esport_team_id')->orderBy('slot_number')->pluck('esport_team_id');

        foreach ($teamIds as $teamId) {
            TeamStatistic::firstOrCreate(
                ['tournament_id' => $tournament->id, 'esport_team_id' => $teamId],
                ['matches_played' => 0, 'matches_won' => 0, 'rounds_played' => 0, 'rounds_won' => 0, 'points' => 0, 'win_rate' => 50.00]
            );
        }

        if ($tournament->format === 'knockout') {
            $this->generateKnockout($tournament);
        } else {
            $this->generateStandard($tournament);
        }
    }

    /**
     * Standard format → Double round robin.
     * Each pair meets twice over 2 pekan; day = round_index, n/2 matches per day.
     */
    private function generateStandard(Tournament $tournament)
    {
        $teamIds = $tournament->slots()
            ->whereNotNull('esport_team_id')
            ->orderBy('slot_number')
            ->pluck('esport_team_id')
            ->shuffle()
            ->values()
            ->toArray();

        $n = count($teamIds);
        $rounds = $this->roundRobinRounds($teamIds);

        $matchNumber = 1;
        $day = 1;

        // Leg 1 (pekan 1)
        foreach ($rounds as $pairs) {
            foreach ($pairs as [$a, $b]) {
                $this->createStandardMatch($tournament, $matchNumber++, $day, $a, $b);
            }
            $day++;
        }

        // Leg 2 (pekan 2, reversed home/away)
        foreach ($rounds as $pairs) {
            foreach ($pairs as [$a, $b]) {
                $this->createStandardMatch($tournament, $matchNumber++, $day, $b, $a);
            }
            $day++;
        }
    }

    private function createStandardMatch(Tournament $tournament, int $matchNumber, int $day, int $teamA, int $teamB)
    {
        $match = MatchModel::create([
            'tournament_id' => $tournament->id,
            'match_number' => $matchNumber,
            'round_index' => $day,
            'bo_format' => $tournament->bo_format,
            'status' => 'pending',
            'is_grand_final' => false,
        ]);

        MatchParticipant::create(['match_id' => $match->id, 'esport_team_id' => $teamA, 'rounds_won' => 0]);
        MatchParticipant::create(['match_id' => $match->id, 'esport_team_id' => $teamB, 'rounds_won' => 0]);
    }

    /**
     * Knockout format → Single elimination bracket (byes handled automatically).
     */
    private function generateKnockout(Tournament $tournament)
    {
        $teamIds = $tournament->slots()
            ->whereNotNull('esport_team_id')
            ->orderBy('slot_number')
            ->pluck('esport_team_id')
            ->shuffle()
            ->values()
            ->toArray();

        // Slots are either a direct team or the winner of an earlier match.
        $slots = array_map(fn($id) => ['type' => 'team', 'id' => $id], $teamIds);

        $roundIndex = 1;
        $matchNumber = 1;
        $lastMatchId = null;

        while (count($slots) > 1) {
            $nextSlots = [];
            for ($i = 0; $i < count($slots); $i += 2) {
                if (isset($slots[$i + 1])) {
                    $match = MatchModel::create([
                        'tournament_id' => $tournament->id,
                        'match_number' => $matchNumber++,
                        'round_index' => $roundIndex,
                        'bo_format' => $tournament->bo_format,
                        'status' => 'pending',
                        'is_grand_final' => false,
                    ]);
                    $this->createKnockoutParticipant($match, $slots[$i]);
                    $this->createKnockoutParticipant($match, $slots[$i + 1]);
                    $lastMatchId = $match->id;
                    $nextSlots[] = ['type' => 'match', 'id' => $match->id];
                } else {
                    // Bye: winner-less slot advances directly
                    $nextSlots[] = $slots[$i];
                }
            }
            $roundIndex++;
            $slots = $nextSlots;
        }

        // The final match determines the champion
        if ($lastMatchId) {
            MatchModel::where('id', $lastMatchId)->update(['is_grand_final' => true]);
        }
    }

    private function createKnockoutParticipant(MatchModel $match, array $slot)
    {
        if ($slot['type'] === 'team') {
            MatchParticipant::create(['match_id' => $match->id, 'esport_team_id' => $slot['id'], 'rounds_won' => 0]);
        } else {
            MatchParticipant::create(['match_id' => $match->id, 'previous_match_id' => $slot['id'], 'rounds_won' => 0]);
        }
    }

    /**
     * Circle method: produces n-1 rounds where every pair meets exactly once.
     */
    private function roundRobinRounds(array $teamIds): array
    {
        $n = count($teamIds);
        $list = $teamIds;
        $rounds = [];

        for ($r = 0; $r < $n - 1; $r++) {
            $pairs = [];
            for ($i = 0; $i < $n / 2; $i++) {
                $pairs[] = [$list[$i], $list[$n - 1 - $i]];
            }
            $rounds[] = $pairs;

            // Rotate: keep first fixed, move last to position 1
            $first = array_shift($list);
            $last = array_pop($list);
            array_splice($list, 1, 0, [$last]);
            array_unshift($list, $first);
        }

        return $rounds;
    }

    private function createAwards(Tournament $tournament, Request $request)
    {
        if (!$request->has('award_names')) {
            return;
        }
        foreach ($request->award_names as $index => $name) {
            $prize = $request->award_prizes[$index] ?? null;
            if (!empty($name) && !empty($prize)) {
                \App\Models\TournamentAward::create([
                    'tournament_id' => $tournament->id,
                    'award_name' => $name,
                    'prize_amount' => $prize,
                ]);
            }
        }
    }

    private function createPrizes(Tournament $tournament, Request $request)
    {
        if (!$request->has('prize_ranks')) {
            return;
        }
        foreach ($request->prize_ranks as $index => $rank) {
            $amount = $request->prize_amounts[$index] ?? null;
            if (!empty($rank) && !empty($amount)) {
                \App\Models\TournamentPrize::create([
                    'tournament_id' => $tournament->id,
                    'rank' => $rank,
                    'prize_amount' => $amount,
                ]);
            }
        }
    }
}