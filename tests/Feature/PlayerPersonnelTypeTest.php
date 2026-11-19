<?php

namespace Tests\Feature;

use App\Models\Player;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlayerPersonnelTypeTest extends TestCase
{
    use RefreshDatabase;

    public function test_add_personnel_button_opens_common_and_athlete_choices(): void
    {
        $response = $this->get(route('players.index'));

        $response->assertOk()
            ->assertSee('tambah personel apa dulu nih bang?')
            ->assertSee(route('players.create', ['type' => 'common']), false)
            ->assertSee(route('players.create', ['type' => 'athlete']), false);
    }

    public function test_common_personnel_uses_the_existing_form_without_athlete_fields(): void
    {
        $this->get(route('players.create', ['type' => 'common']))
            ->assertOk()
            ->assertDontSee('Data Atlet');

        $response = $this->post(route('players.store'), $this->personnelData([
            'kategori_personel' => 'Common',
        ]));

        $response->assertRedirect(route('players.index'));

        $player = Player::where('nickname_game', 'CommonPlayer')->firstOrFail();
        $this->assertSame('Common', $player->kategori_personel);
        $this->assertNull($player->role_utama);
        $this->assertNull($player->gaya_permainan);
        $this->assertNull($player->targeting_urut);
    }

    public function test_athlete_profile_requires_and_saves_all_ranked_details(): void
    {
        $this->get(route('players.create', ['type' => 'athlete']))
            ->assertOk()
            ->assertSee('Data Atlet')
            ->assertSee('Role Kurang Kompeten (otomatis)')
            ->assertSee('Gaya Targeting');

        $response = $this->post(route('players.store'), $this->athleteData());

        $response->assertRedirect(route('players.index'));

        $player = Player::where('nickname_game', 'AthletePlayer')->firstOrFail();
        $this->assertSame('Atlet', $player->kategori_personel);
        $this->assertSame('Goldlane', $player->role_utama);
        $this->assertSame(['Midlane'], $player->role_cadangan);
        $this->assertSame(['Jungler', 'Explane', 'Roam'], $player->role_kurang_kompeten);
        $this->assertSame(['Petarung', 'Tank'], $player->gaya_permainan);
        $this->assertSame(['Dekat', 'Jauh', 'Sedang'], $player->targeting_urut);
    }

    public function test_athlete_profile_requires_a_reserve_role_two_styles_and_unique_targeting_ranks(): void
    {
        $response = $this->from(route('players.create', ['type' => 'athlete']))->post(
            route('players.store'),
            $this->athleteData([
                'role_cadangan' => [],
                'gaya_permainan' => ['Petarung'],
                'targeting_urut' => ['Dekat', 'Dekat', 'Jauh'],
            ]),
        );

        $response->assertRedirect(route('players.create', ['type' => 'athlete']));
        $response->assertSessionHasErrors([
            'role_cadangan',
            'gaya_permainan',
            'targeting_urut.0',
            'targeting_urut.1',
        ]);
    }

    public function test_changing_an_athlete_to_common_preserves_athlete_details(): void
    {
        $this->post(route('players.store'), $this->athleteData());
        $player = Player::where('nickname_game', 'AthletePlayer')->firstOrFail();

        $response = $this->put(route('players.update', $player), $this->personnelData([
            'kategori_personel' => 'Common',
            'nickname_game' => 'CommonPlayer',
        ]));

        $response->assertRedirect(route('players.index'));

        $player->refresh();
        $this->assertSame('Common', $player->kategori_personel);
        $this->assertSame('Goldlane', $player->role_utama);
        $this->assertSame(['Midlane'], $player->role_cadangan);
        $this->assertSame(['Petarung', 'Tank'], $player->gaya_permainan);
        $this->assertSame(['Dekat', 'Jauh', 'Sedang'], $player->targeting_urut);
    }

    public function test_common_personnel_can_be_completed_as_an_athlete_from_edit(): void
    {
        $this->post(route('players.store'), $this->personnelData([
            'kategori_personel' => 'Common',
        ]));
        $player = Player::where('nickname_game', 'CommonPlayer')->firstOrFail();

        $this->get(route('players.edit', $player))
            ->assertOk()
            ->assertSee('name="role_utama" value="Goldlane"', false)
            ->assertSee('disabled', false);

        $response = $this->put(route('players.update', $player), $this->athleteData([
            'nickname_game' => 'ConvertedAthlete',
        ]));

        $response->assertRedirect(route('players.index'));

        $player->refresh();
        $this->assertSame('Atlet', $player->kategori_personel);
        $this->assertSame('Goldlane', $player->role_utama);
        $this->assertSame(['Jungler', 'Explane', 'Roam'], $player->role_kurang_kompeten);
    }

    private function athleteData(array $overrides = []): array
    {
        return $this->personnelData(array_merge([
            'kategori_personel' => 'Atlet',
            'nickname_game' => 'AthletePlayer',
            'role_utama' => 'Goldlane',
            'role_cadangan' => ['Midlane'],
            'gaya_permainan' => ['Petarung', 'Tank'],
            'targeting_urut' => ['Dekat', 'Jauh', 'Sedang'],
        ], $overrides));
    }

    private function personnelData(array $overrides = []): array
    {
        return array_merge([
            'nama_asli' => 'Test Person',
            'nickname_game' => 'CommonPlayer',
            'deskripsi_pemain' => 'Test description',
            'tanggal_lahir' => '2000-01-01',
            'region_id' => null,
        ], $overrides);
    }
}
