<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\EsportTeam;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    public function create()
    {
        $teams = EsportTeam::all();
        $regions = Region::all();
        $jabatans = [
            'Player', 'Owner', 'CEO', 'Head Coach', 'Assistant Coach', 
            'Manager', 'Analyst', 'Ketua Persatuan Game', 'Pelatih Timnas'
        ];
        return view('players.create', compact('teams', 'regions', 'jabatans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_asli' => 'required|string|max:255',
            'nickname_game' => 'required|string|max:255',
            'deskripsi_pemain' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'region_id' => 'nullable|exists:regions,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('photos/players', 'public');
        }

        // Keanggotaan tim esport diisi otomatis lewat tab Teams.
        // Jabatan baru selalu "None" (Free Agent) sampai ditempatkan di Tim / Region.
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
        return view('players.edit', compact('player', 'teams', 'regions', 'jabatans'));
    }

    public function update(Request $request, Player $player)
    {
        $validated = $request->validate([
            'nama_asli' => 'required|string|max:255',
            'nickname_game' => 'required|string|max:255',
            'deskripsi_pemain' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'region_id' => 'nullable|exists:regions,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

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

    public function destroy(Player $player)
    {
        if ($player->photo) {
            Storage::disk('public')->delete($player->photo);
        }
        $player->delete();
        return redirect()->route('players.index')->with('success', 'Personnel berhasil dihapus!');
    }
}
