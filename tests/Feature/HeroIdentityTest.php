<?php

namespace Tests\Feature;

use App\Models\Hero;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HeroIdentityTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_saves_hero_identity_fields(): void
    {
        $response = $this->post(route('heroes.store'), [
            'nama_hero' => 'Test Hero',
            'targeting' => 'Kombinasi Dekat-Sedang',
            'gaya_permainan' => 'Petarung',
            'jenis_serangan' => 'Physical',
            'area_serangan' => 'Area',
            'pola_serangan' => 'DPS',
            'role_utama' => 'Midlane',
            'role_pendukung' => ['Jungler'],
            'role_tidak_direkomendasikan' => ['Goldlane', 'Explane', 'Roam'],
        ]);

        $response->assertRedirect(route('heroes.index'));

        $hero = Hero::where('nama_hero', 'Test Hero')->firstOrFail();
        $this->assertSame('Kombinasi Dekat-Sedang', $hero->targeting);
        $this->assertSame('Petarung', $hero->gaya_permainan);
        $this->assertSame('Physical', $hero->jenis_serangan);
        $this->assertSame('Area', $hero->area_serangan);
        $this->assertSame('DPS', $hero->pola_serangan);
        $this->assertSame('Midlane', $hero->role_utama);
        $this->assertSame(['Jungler'], $hero->role_pendukung);
        $this->assertSame(['Goldlane', 'Explane', 'Roam'], $hero->role_tidak_direkomendasikan);
        $this->assertSame(['Midlane', 'Jungler', 'Goldlane', 'Explane', 'Roam'], $hero->roles);

        $this->get(route('heroes.index'))
            ->assertOk()
            ->assertSee('Gaya Serangan')
            ->assertSee('Jenis Serangan')
            ->assertSee('Area Serangan')
            ->assertSee('Pola Serangan')
            ->assertSee('Utama: Midlane')
            ->assertSee('Pendukung: Jungler')
            ->assertSee('Tidak direkomendasikan: Goldlane');
    }

    public function test_create_form_shows_the_new_attack_fields_and_role_stages(): void
    {
        $response = $this->get(route('heroes.create'));

        $response->assertOk()
            ->assertSee('Gaya serangan')
            ->assertSee('Jenis Serangan')
            ->assertSee('Area Serangan')
            ->assertSee('Pola Serangan')
            ->assertSee('Tahap 1. Pilih Role Utama')
            ->assertSee('Tahap 2. Role Pendukung')
            ->assertSee('Tahap 3. Role yang tidak direkomendasikan')
            ->assertSee('Adaptive damage')
            ->assertSee('Penembak Jitu')
            ->assertDontSee('Assassin (cepat)')
            ->assertDontSee('Good for ');
    }

    public function test_it_rejects_an_attack_style_outside_the_allowed_options(): void
    {
        $response = $this->from(route('heroes.create'))->post(route('heroes.store'), [
            'nama_hero' => 'Legacy Style Hero',
            'targeting' => 'Dekat',
            'gaya_permainan' => 'Fighter',
            'jenis_serangan' => 'Physical',
            'area_serangan' => '1 Target',
            'pola_serangan' => 'DPS',
            'role_utama' => 'Jungler',
            'role_tidak_direkomendasikan' => ['Goldlane'],
        ]);

        $response->assertRedirect(route('heroes.create'));
        $response->assertSessionHasErrors('gaya_permainan');
    }

    public function test_it_allows_skipping_support_roles_but_requires_main_and_not_recommended_roles(): void
    {
        $response = $this->post(route('heroes.store'), [
            'nama_hero' => 'Solo Hero',
            'targeting' => 'Dekat',
            'gaya_permainan' => 'Assasin',
            'jenis_serangan' => 'Magis',
            'area_serangan' => '1 Target',
            'pola_serangan' => 'Burst',
            'role_utama' => 'Jungler',
            'role_tidak_direkomendasikan' => ['Goldlane'],
        ]);

        $response->assertRedirect(route('heroes.index'));

        $hero = Hero::where('nama_hero', 'Solo Hero')->firstOrFail();
        $this->assertSame([], $hero->role_pendukung);
        $this->assertSame(['Goldlane'], $hero->role_tidak_direkomendasikan);
    }

    public function test_identity_fields_and_main_and_not_recommended_roles_are_required(): void
    {
        $response = $this->from(route('heroes.create'))->post(route('heroes.store'), [
            'nama_hero' => 'Test Hero',
        ]);

        $response->assertRedirect(route('heroes.create'));
        $response->assertSessionHasErrors([
            'targeting',
            'gaya_permainan',
            'jenis_serangan',
            'area_serangan',
            'pola_serangan',
            'role_utama',
            'role_tidak_direkomendasikan',
        ]);
    }

    public function test_it_rejects_duplicate_roles_between_stages_and_more_than_two_support_roles(): void
    {
        $response = $this->from(route('heroes.create'))->post(route('heroes.store'), [
            'nama_hero' => 'Test Hero',
            'targeting' => 'Dekat',
            'gaya_permainan' => 'Tank',
            'jenis_serangan' => 'True Damage',
            'area_serangan' => 'Area',
            'pola_serangan' => 'Poke',
            'role_utama' => 'Midlane',
            'role_pendukung' => ['Jungler', 'Roam', 'Goldlane'],
            'role_tidak_direkomendasikan' => ['Midlane'],
        ]);

        $response->assertRedirect(route('heroes.create'));
        $response->assertSessionHasErrors([
            'role_pendukung',
            'role_tidak_direkomendasikan.0',
        ]);
    }
}
