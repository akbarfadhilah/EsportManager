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
            'gaya_permainan' => 'Fighter',
            'roles' => ['Good for Jungler', 'NotBad for ExpLane'],
        ]);

        $response->assertRedirect(route('heroes.index'));

        $hero = Hero::where('nama_hero', 'Test Hero')->firstOrFail();
        $this->assertSame('Kombinasi Dekat-Sedang', $hero->targeting);
        $this->assertSame('Fighter', $hero->gaya_permainan);
        $this->assertSame(['Good for Jungler', 'NotBad for ExpLane'], $hero->roles);
    }

    public function test_targeting_gameplay_and_roles_are_required(): void
    {
        $response = $this->from(route('heroes.create'))->post(route('heroes.store'), [
            'nama_hero' => 'Test Hero',
        ]);

        $response->assertRedirect(route('heroes.create'));
        $response->assertSessionHasErrors(['targeting', 'gaya_permainan', 'roles']);
    }

    public function test_it_rejects_more_than_three_roles(): void
    {
        $response = $this->from(route('heroes.create'))->post(route('heroes.store'), [
            'nama_hero' => 'Test Hero',
            'targeting' => 'Dekat',
            'gaya_permainan' => 'Tank',
            'roles' => [
                'Good for Jungler',
                'Good for Goldlane',
                'Good for ExpLane',
                'Good for Roam',
            ],
        ]);

        $response->assertRedirect(route('heroes.create'));
        $response->assertSessionHasErrors('roles');
    }
}
