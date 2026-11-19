<?php

namespace App\Http\Controllers;

use App\Models\EsportTeam;
use App\Models\Player;
use App\Models\Region;
use App\Services\PlayerStatusService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EsportTeamController extends Controller
{
    private const PLAYER_SLOTS = 10;

    private function staffLists()
    {
        return ['players' => Player::with('esportTeam')->orderBy('nama_asli')->get()];
    }

    /**
     * Personel yang sudah memegang jabatan (di tim/region/roster) tidak boleh
     * dipilih lagi di form tim lain.
     */
    private function takenPlayerIds(): \Illuminate\Support\Collection
    {
        return Player::where('jabatan', '!=', 'None')->pluck('id');
    }

    private function ownPlayerIds(EsportTeam $team): \Illuminate\Support\Collection
    {
        $ids = collect([
            $team->pemilik_id, $team->ceo_id, $team->pelatih_utama_id,
            $team->pelatih_kedua_id, $team->manager_id, $team->analis_id,
        ])->filter()->merge(
            $team->players()->where('jabatan', 'Player')->pluck('id')
        );

        return $ids->unique();
    }

    /**
     * All selected player ids across every slot of a team form.
     */
    private function selectedPlayerIds(Request $request): array
    {
        return array_filter(array_merge(
            [
                $request->input('pemilik_id'),
                $request->input('ceo_id'),
                $request->input('pelatih_utama_id'),
                $request->input('pelatih_kedua_id'),
                $request->input('manager_id'),
                $request->input('analis_id'),
            ],
            $request->input('players', [])
        ));
    }

    public function index()
    {
        $teams = EsportTeam::withCount('players')
            ->with(['region', 'pemilik', 'pelatihUtama'])
            ->get();
        return view('teams.index', compact('teams'));
    }

    public function create()
    {
        $regions = Region::all();
        $staff = $this->staffLists();
        return view('teams.create', array_merge([
            'regions' => $regions,
            'playerSlots' => self::PLAYER_SLOTS,
            'takenPlayerIds' => $this->takenPlayerIds(),
        ], $staff));
    }

    public function show(EsportTeam $team)
    {
        $team->load(['players', 'region', 'pemilik', 'ceo', 'pelatihUtama', 'pelatihKedua', 'manager', 'analis']);
        return view('teams.show', compact('team'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_tim' => 'required|string|max:255',
            'deskripsi_tim' => 'required|string',
            'logo_esport' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tempat_kota_gh' => 'required|string|max:255',

            'region_id' => 'nullable|exists:regions,id',
            'pemilik_id' => 'nullable|exists:players,id',
            'ceo_id' => 'nullable|exists:players,id',
            'pelatih_utama_id' => 'nullable|exists:players,id',
            'pelatih_kedua_id' => 'nullable|exists:players,id',
            'manager_id' => 'nullable|exists:players,id',
            'analis_id' => 'nullable|exists:players,id',
            'players' => 'nullable|array',
            'players.*' => 'nullable|exists:players,id',
        ]);

        $rosterIds = array_values(array_filter($request->input('players', [])));
        $allSelected = $this->selectedPlayerIds($request);

        // Satu personnel hanya boleh menempati SATU jabatan.
        if (count(array_unique($allSelected)) !== count($allSelected)) {
            return back()->withInput()->withErrors([
                'players' => 'Satu personnel tidak boleh diisi di lebih dari 1 jabatan dalam satu tim.'
            ]);
        }

        if ($request->hasFile('logo_esport')) {
            $validated['logo_esport'] = $request->file('logo_esport')->store('logos/teams', 'public');
        }

        // Total worth diisi otomatis (dibangun dari kejuaraan), bukan manual.
        $validated['total_kekayaan'] = 0;
        $validated['penghargaan'] = null;

        $service = app(PlayerStatusService::class);
        $service->clearAssignments($allSelected);

        $team = EsportTeam::create($validated);

        foreach ($rosterIds as $playerId) {
            Player::where('id', $playerId)->update(['jabatan' => 'Player', 'esport_team_id' => $team->id]);
        }

        $service->syncAll();

        return redirect()->route('teams.index')->with('success', 'Tim Esport berhasil ditambahkan!');
    }

    public function edit(EsportTeam $team)
    {
        $regions = Region::all();
        $staff = $this->staffLists();
        $roster = $team->players()->where('jabatan', 'Player')->orderBy('nickname_game')->get();
        return view('teams.edit', array_merge([
            'team' => $team,
            'regions' => $regions,
            'playerSlots' => self::PLAYER_SLOTS,
            'roster' => $roster,
            'takenPlayerIds' => $this->takenPlayerIds()->diff($this->ownPlayerIds($team)),
        ], $staff));
    }

    public function update(Request $request, EsportTeam $team)
    {
        $validated = $request->validate([
            'nama_tim' => 'required|string|max:255',
            'deskripsi_tim' => 'required|string',
            'logo_esport' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tempat_kota_gh' => 'required|string|max:255',

            'region_id' => 'nullable|exists:regions,id',
            'pemilik_id' => 'nullable|exists:players,id',
            'ceo_id' => 'nullable|exists:players,id',
            'pelatih_utama_id' => 'nullable|exists:players,id',
            'pelatih_kedua_id' => 'nullable|exists:players,id',
            'manager_id' => 'nullable|exists:players,id',
            'analis_id' => 'nullable|exists:players,id',
            'players' => 'nullable|array',
            'players.*' => 'nullable|exists:players,id',
        ]);

        $rosterIds = array_values(array_filter($request->input('players', [])));
        $allSelected = $this->selectedPlayerIds($request);

        if (count(array_unique($allSelected)) !== count($allSelected)) {
            return back()->withInput()->withErrors([
                'players' => 'Satu personnel tidak boleh diisi di lebih dari 1 jabatan dalam satu tim.'
            ]);
        }

        if ($request->hasFile('logo_esport')) {
            if ($team->logo_esport) {
                Storage::disk('public')->delete($team->logo_esport);
            }
            $validated['logo_esport'] = $request->file('logo_esport')->store('logos/teams', 'public');
        }

        // Total worth & penghargaan diisi otomatis oleh sistem, jangan ditimpa manual.
        unset($validated['total_kekayaan'], $validated['penghargaan'], $validated['players']);

        $service = app(PlayerStatusService::class);
        $service->clearAssignments($allSelected);

        $team->update($validated);

        // Kosongkan roster lama tim ini, lalu isi ulang dari form.
        Player::where('esport_team_id', $team->id)->where('jabatan', 'Player')->update(['esport_team_id' => null]);
        foreach ($rosterIds as $playerId) {
            Player::where('id', $playerId)->update(['jabatan' => 'Player', 'esport_team_id' => $team->id]);
        }

        $service->syncAll();

        return redirect()->route('teams.index')->with('success', 'Tim Esport berhasil diubah!');
    }

    public function destroy(EsportTeam $team)
    {
        if ($team->logo_esport) {
            Storage::disk('public')->delete($team->logo_esport);
        }
        $team->delete();

        // Sinkronkan kembali jabatan pemain yang kehilangan timnya.
        app(PlayerStatusService::class)->syncAll();

        return redirect()->route('teams.index')->with('success', 'Tim Esport berhasil dihapus!');
    }
}