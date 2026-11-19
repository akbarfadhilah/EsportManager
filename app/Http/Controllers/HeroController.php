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
        $validated = $this->validatedHeroData($request);

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
        $validated = $this->validatedHeroData($request);

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
            'attackStyleOptions' => Hero::ATTACK_STYLE_OPTIONS,
            'attackTypeOptions' => Hero::ATTACK_TYPE_OPTIONS,
            'attackAreaOptions' => Hero::ATTACK_AREA_OPTIONS,
            'attackPatternOptions' => Hero::ATTACK_PATTERN_OPTIONS,
            'roleOptions' => Hero::ROLE_OPTIONS,
        ];
    }

    private function validatedHeroData(Request $request): array
    {
        $mainRole = $request->input('role_utama');
        $supportRoles = $request->input('role_pendukung', []);
        $supportRoles = is_array($supportRoles) ? $supportRoles : [];

        $validated = $request->validate([
            'nama_hero' => 'required|string|max:255',
            'targeting' => ['required', 'string', Rule::in(Hero::TARGETING_OPTIONS)],
            'gaya_permainan' => ['required', 'string', Rule::in(Hero::ATTACK_STYLE_OPTIONS)],
            'jenis_serangan' => ['required', 'string', Rule::in(Hero::ATTACK_TYPE_OPTIONS)],
            'area_serangan' => ['required', 'string', Rule::in(Hero::ATTACK_AREA_OPTIONS)],
            'pola_serangan' => ['required', 'string', Rule::in(Hero::ATTACK_PATTERN_OPTIONS)],
            'role_utama' => ['required', 'string', Rule::in(Hero::ROLE_OPTIONS)],
            'role_pendukung' => ['nullable', 'array', 'max:2'],
            'role_pendukung.*' => [
                'required',
                'string',
                'distinct',
                Rule::in(Hero::ROLE_OPTIONS),
                Rule::notIn([$mainRole]),
            ],
            'role_tidak_direkomendasikan' => ['required', 'array', 'min:1', 'max:4'],
            'role_tidak_direkomendasikan.*' => [
                'required',
                'string',
                'distinct',
                Rule::in(Hero::ROLE_OPTIONS),
                Rule::notIn(array_merge([$mainRole], $supportRoles)),
            ],
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $validated['role_pendukung'] = $validated['role_pendukung'] ?? [];
        $validated['roles'] = array_values(array_merge(
            [$validated['role_utama']],
            $validated['role_pendukung'],
            $validated['role_tidak_direkomendasikan'],
        ));

        return $validated;
    }
}