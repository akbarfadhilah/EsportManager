<?php

namespace App\Http\Controllers;

use App\Models\Hero;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class HeroController extends Controller
{
    public function index()
    {
        $heroes = Hero::withCount('lineups')->orderBy('nama_hero')->get();
        return view('heroes.index', compact('heroes'));
    }

    public function create()
    {
        return view('heroes.create', $this->formOptions());
    }

    public function show(Hero $hero)
    {
        return redirect()->route('heroes.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_hero' => 'required|string|max:255',
            'targeting' => ['required', 'string', Rule::in(Hero::TARGETING_OPTIONS)],
            'gaya_permainan' => ['required', 'string', Rule::in(Hero::GAMEPLAY_OPTIONS)],
            'roles' => ['required', 'array', 'min:1', 'max:3'],
            'roles.*' => ['required', 'string', 'distinct', Rule::in(Hero::ROLE_OPTIONS)],
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('photos/heroes', 'public');
        }

        Hero::create($validated);

        return redirect()->route('heroes.index')->with('success', 'Hero berhasil ditambahkan!');
    }

    public function edit(Hero $hero)
    {
        return view('heroes.edit', array_merge(['hero' => $hero], $this->formOptions()));
    }

    public function update(Request $request, Hero $hero)
    {
        $validated = $request->validate([
            'nama_hero' => 'required|string|max:255',
            'targeting' => ['required', 'string', Rule::in(Hero::TARGETING_OPTIONS)],
            'gaya_permainan' => ['required', 'string', Rule::in(Hero::GAMEPLAY_OPTIONS)],
            'roles' => ['required', 'array', 'min:1', 'max:3'],
            'roles.*' => ['required', 'string', 'distinct', Rule::in(Hero::ROLE_OPTIONS)],
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($hero->foto) {
                Storage::disk('public')->delete($hero->foto);
            }
            $validated['foto'] = $request->file('foto')->store('photos/heroes', 'public');
        }

        $hero->update($validated);

        return redirect()->route('heroes.index')->with('success', 'Hero berhasil diubah!');
    }

    public function destroy(Hero $hero)
    {
        if ($hero->foto) {
            Storage::disk('public')->delete($hero->foto);
        }
        $hero->delete();

        return redirect()->route('heroes.index')->with('success', 'Hero berhasil dihapus!');
    }

    private function formOptions(): array
    {
        return [
            'targetingOptions' => Hero::TARGETING_OPTIONS,
            'gameplayOptions' => Hero::GAMEPLAY_OPTIONS,
            'roleOptions' => Hero::ROLE_OPTIONS,
        ];
    }
}