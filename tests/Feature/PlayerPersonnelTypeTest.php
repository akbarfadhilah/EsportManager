<?php

namespace Tests\Feature;

use App\Models\Player;
use App\Models\Region;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlayerPersonnelTypeTest extends TestCase
{
    use RefreshDatabase;

    public function test_add_personnel_button_opens_common_and_athlete_choices(): void
    {
        $response = $this->get(route('players.index'));

        $response->assertOk()
            ->assertSee('Tambah personel apa dulu?')
            ->assertSee(route('players.create', ['type' => 'common']), false)
            ->assertSee(route('players.create', ['type' => 'athlete']), false)
            ->assertSee(route('players.import'), false)
            ->assertSee('Bagaimana ingin menambahkan atlet?');
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

    public function test_import_preview_saves_athletes_only_after_confirmation_and_allows_optional_reserves(): void
    {
        Region::create(['nama_negara' => 'Indonesia']);
        $this->get(route('players.import'))
            ->assertOk()
            ->assertSee('Import dari Excel')
            ->assertSee('Pilih file');

        $response = $this->post(route('players.import.preview'), $this->importRequestData());

        $response->assertOk()
            ->assertSee('Periksa data sebelum disimpan')
            ->assertSee('atlet siap diimpor');
        $this->assertDatabaseCount('players', 0);

        $this->post(route('players.import.store'))->assertRedirect(route('players.index'));

        $player = Player::where('nickname_game', 'ImportPlayer')->firstOrFail();
        $this->assertSame('Atlet', $player->kategori_personel);
        $this->assertSame('2000-01-02', $player->tanggal_lahir->format('Y-m-d'));
        $this->assertSame([], $player->role_cadangan);
        $this->assertSame(['Midlane', 'Jungler', 'Explane', 'Roam'], $player->role_kurang_kompeten);
        $this->assertSame('', $player->deskripsi_pemain);
        $this->assertSame('None', $player->jabatan);
    }

    public function test_import_rejects_dates_that_do_not_match_dd_mm_yyyy_calendar_dates(): void
    {
        Region::create(['nama_negara' => 'Indonesia']);
        $data = $this->importRequestData();
        $rows = json_decode($data['rows'], true);
        $rows[0][2] = '31/02/2000';
        $data['rows'] = json_encode($rows);

        $this->post(route('players.import.preview'), $data)
            ->assertStatus(422)
            ->assertSee('format DD/MM/YYYY');

        $this->assertDatabaseCount('players', 0);
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

    private function importRequestData(): array
    {
        $headers = [
            'Real name', 'Nickname', 'Birth date', 'Region', 'Role utama 1',
            'Role cadangan 1', 'Role cadangan 2', 'Gaya permainan 1', 'Gaya permainan 2',
            'Gaya targeting 1', 'Gaya targeting 2', 'Gaya targeting 3', 'Deskripsi',
        ];

        return [
            'headers' => json_encode($headers),
            'rows' => json_encode([[
                'Test Athlete', 'ImportPlayer', '02/01/2000', 'Indonesia', 'Goldlane',
                '', '', 'Petarung', 'Tank', 'Dekat', 'Sedang', 'Jauh', '',
            ]]),
            'mapping' => json_encode(array_combine([
                'nama_asli', 'nickname_game', 'tanggal_lahir', 'region', 'role_utama',
                'role_cadangan_1', 'role_cadangan_2', 'gaya_permainan_1', 'gaya_permainan_2',
                'targeting_urut_1', 'targeting_urut_2', 'targeting_urut_3', 'deskripsi_pemain',
            ], range(0, 12))),
        ];
    }
}
