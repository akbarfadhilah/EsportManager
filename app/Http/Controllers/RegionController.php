<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\Player;
use App\Services\PlayerStatusService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RegionController extends Controller
{
    public function index()
    {
        $regions = Region::with(['ketua', 'pelatihTimnas'])->get();
        return view('regions.index', compact('regions'));
    }

    public function create()
    {
        $staff = Player::orderBy('nama_asli')->get();
        $takenPlayerIds = Player::where('jabatan', '!=', 'None')->pluck('id');
        return view('regions.create', [
            'ketuas' => $staff,
            'pelatihTimnas' => $staff,
            'takenPlayerIds' => $takenPlayerIds,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_negara' => 'required|string|max:255',
            'ketua_persatuan_game_id' => 'nullable|exists:players,id',
            'pelatih_timnas_id' => 'nullable|exists:players,id',
            'foto_negara' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('foto_negara')) {
            $validated['foto_negara'] = $request->file('foto_negara')->store('photos/regions', 'public');
        }

        // Satu personnel hanya boleh menempati satu jabatan:
        // lepaskan dari semua posisi tim/region lain sebelum di-assign ke region ini.
        app(PlayerStatusService::class)->clearAssignments([
            $validated['ketua_persatuan_game_id'] ?? null,
            $validated['pelatih_timnas_id'] ?? null,
        ]);

        $region = Region::create($validated);
        app(PlayerStatusService::class)->syncAll();

        return redirect()->route('regions.index')->with('success', 'Region berhasil ditambahkan!');
    }

    public function edit(Region $region)
    {
        $staff = Player::orderBy('nama_asli')->get();
        $taken = Player::where('jabatan', '!=', 'None')->pluck('id');
        $own = collect([
            $region->ketua_persatuan_game_id,
            $region->pelatih_timnas_id,
        ])->filter()->unique();
        return view('regions.edit', [
            'region' => $region,
            'ketuas' => $staff,
            'pelatihTimnas' => $staff,
            'takenPlayerIds' => $taken->diff($own),
        ]);
    }

    public function update(Request $request, Region $region)
    {
        $validated = $request->validate([
            'nama_negara' => 'required|string|max:255',
            'ketua_persatuan_game_id' => 'nullable|exists:players,id',
            'pelatih_timnas_id' => 'nullable|exists:players,id',
            'foto_negara' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('foto_negara')) {
            if ($region->foto_negara) {
                Storage::disk('public')->delete($region->foto_negara);
            }
            $validated['foto_negara'] = $request->file('foto_negara')->store('photos/regions', 'public');
        }

        app(PlayerStatusService::class)->clearAssignments([
            $validated['ketua_persatuan_game_id'] ?? null,
            $validated['pelatih_timnas_id'] ?? null,
        ]);

        $region->update($validated);
        app(PlayerStatusService::class)->syncAll();

        return redirect()->route('regions.index')->with('success', 'Region berhasil diperbarui!');
    }

    public function destroy(Region $region)
    {
        if ($region->foto_negara) {
            Storage::disk('public')->delete($region->foto_negara);
        }
        $region->delete();

        // Pemain yang kehilangan jabatan region → dihitung ulang (None / Free Agent).
        app(PlayerStatusService::class)->syncAll();

        return redirect()->route('regions.index')->with('success', 'Region berhasil dihapus!');
    }
}