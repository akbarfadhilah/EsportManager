<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\EsportTeam;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PlayerController extends Controller
{
    public function index(Request $request)
    {
        $regions = Region::all();
        $query = Player::with(['esportTeam', 'region']);

        if ($request->filled('region_id')) {
            $query->where('region_id', $request->region_id);
        }

        $players = $query->get();
        $selectedRegion = $request->region_id;

        return view('players.index', compact('players', 'regions', 'selectedRegion'));
    }

    public function create(Request $request)
    {
        $type = $request->query('type', 'common');
        abort_unless(in_array($type, ['common', 'athlete'], true), 404);

        $teams = EsportTeam::all();
        $regions = Region::all();
        $jabatans = [
            'Player', 'Owner', 'CEO', 'Head Coach', 'Assistant Coach', 
            'Manager', 'Analyst', 'Ketua Persatuan Game', 'Pelatih Timnas'
        ];
        $personnelType = $type === 'athlete'
            ? Player::PERSONNEL_TYPE_ATHLETE
            : Player::PERSONNEL_TYPE_COMMON;

        return view('players.create', compact('teams', 'regions', 'jabatans', 'personnelType'));
    }

    public function store(Request $request)
    {
        $validated = $this->validatedPersonnelData($request);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('photos/players', 'public');
        }

        // Keanggotaan tim esport diisi otomatis lewat tab Teams.
        $validated['jabatan'] = 'None';
        $validated['esport_team_id'] = null;

        Player::create($validated);

        return redirect()->route('players.index')->with('success', 'Personnel berhasil ditambahkan!');
    }

    public function show(Player $player)
    {
        $player->load(['esportTeam', 'region']);
        return view('players.show', compact('player'));
    }

    public function edit(Player $player)
    {
        $teams = EsportTeam::all();
        $regions = Region::all();
        $jabatans = [
            'Player', 'Owner', 'CEO', 'Head Coach', 'Assistant Coach', 
            'Manager', 'Analyst', 'Ketua Persatuan Game', 'Pelatih Timnas'
        ];
        $hasAthleteData = filled($player->role_utama)
            || ! empty($player->role_cadangan)
            || ! empty($player->role_kurang_kompeten)
            || ! empty($player->gaya_permainan)
            || ! empty($player->targeting_urut);

        return view('players.edit', compact('player', 'teams', 'regions', 'jabatans', 'hasAthleteData'));
    }

    public function update(Request $request, Player $player)
    {
        $validated = $this->validatedPersonnelData($request);

        if ($request->hasFile('photo')) {
            if ($player->photo) {
                Storage::disk('public')->delete($player->photo);
            }
            $validated['photo'] = $request->file('photo')->store('photos/players', 'public');
        }

        // Jabatan & keanggotaan tim tidak diubah dari form ini (diatur sistem lewat Tim/Region).
        $player->update($validated);

        return redirect()->route('players.index')->with('success', 'Personnel berhasil diubah!');
    }

    private function validatedPersonnelData(Request $request): array
    {
        $isAthlete = $request->input('kategori_personel') === Player::PERSONNEL_TYPE_ATHLETE;
        $mainRole = $request->input('role_utama');
        $mainRole = is_string($mainRole) ? [$mainRole] : [];

        $validated = $request->validate([
            'nama_asli' => 'required|string|max:255',
            'nickname_game' => 'required|string|max:255',
            'deskripsi_pemain' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'region_id' => 'nullable|exists:regions,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'kategori_personel' => [
                'required',
                Rule::in([Player::PERSONNEL_TYPE_COMMON, Player::PERSONNEL_TYPE_ATHLETE]),
            ],
            'role_utama' => $isAthlete
                ? ['required', 'string', Rule::in(Player::ATHLETE_ROLE_OPTIONS)]
                : ['sometimes', 'nullable', 'string', Rule::in(Player::ATHLETE_ROLE_OPTIONS)],
            'role_cadangan' => $isAthlete
                ? ['required', 'array', 'min:1', 'max:2']
                : ['sometimes', 'nullable', 'array', 'max:2'],
            'role_cadangan.*' => [
                'required',
                'string',
                'distinct',
                Rule::in(Player::ATHLETE_ROLE_OPTIONS),
                Rule::notIn($mainRole),
            ],
            'gaya_permainan' => $isAthlete
                ? ['required', 'array', 'size:2']
                : ['sometimes', 'nullable', 'array', 'size:2'],
            'gaya_permainan.*' => [
                'required',
                'string',
                'distinct',
                Rule::in(Player::ATHLETE_GAMEPLAY_OPTIONS),
            ],
            'targeting_urut' => $isAthlete
                ? ['required', 'array', 'size:3']
                : ['sometimes', 'nullable', 'array', 'size:3'],
            'targeting_urut.*' => [
                'required',
                'string',
                'distinct',
                Rule::in(Player::TARGETING_ORDER_OPTIONS),
            ],
        ]);

        if (isset($validated['role_utama'])) {
            $validated['role_cadangan'] = $validated['role_cadangan'] ?? [];
            $validated['role_kurang_kompeten'] = array_values(array_diff(
                Player::ATHLETE_ROLE_OPTIONS,
                array_merge([$validated['role_utama']], $validated['role_cadangan']),
            ));
        }

        return $validated;
    }

    public function destroy(Player $player)
    {
        if ($player->photo) {
            Storage::disk('public')->delete($player->photo);
        }
        $player->delete();
        return redirect()->route('players.index')->with('success', 'Personnel berhasil dihapus!');
    }
}
