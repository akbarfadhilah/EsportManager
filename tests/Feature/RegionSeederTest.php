<?php

namespace Tests\Feature;

use App\Models\Region;
use Database\Seeders\RegionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegionSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_seeds_all_localized_countries_with_their_flags_and_empty_staff(): void
    {
        $seeder = new RegionSeeder;
        $seeder->run();
        $seeder->run();

        $this->assertSame(18, Region::count());

        foreach (Region::all() as $region) {
            $this->assertNull($region->ketua_persatuan_game_id);
            $this->assertNull($region->pelatih_timnas_id);
            $this->assertFileExists(public_path($region->foto_negara));
            $this->assertSame(asset($region->foto_negara), $region->foto_negara_url);
        }
    }
}
