<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            'Brunei Darussalam' => 'Flag_of_Brunei-256x128.png',
            'Mesir' => 'Flag_of_Egypt-256x171.png',
            'Inggris' => 'Flag_of_England-256x154.png',
            'Indonesia' => 'Flag_of_Indonesia-256x171.png',
            'Irlandia' => 'Flag_of_Ireland-256x128.png',
            'Jepang' => 'Flag_of_Japan-256x171.png',
            'Malaysia' => 'Flag_of_Malaysia-256x128.png',
            'Meksiko' => 'Flag_of_Mexico-256x146.png',
            'Tiongkok' => 'Flag_of_Peoples_Republic_of_China-256x171.png',
            'Filipina' => 'Flag_of_Philippines-256x128.png',
            'Qatar' => 'Flag_of_Qatar-256x100.png',
            'Rusia' => 'Flag_of_Russia-256x171.png',
            'Arab Saudi' => 'Flag_of_Saudi_Arabia-256x171.png',
            'Singapura' => 'Flag_of_Singapore-256x171.png',
            'Thailand' => 'Flag_of_Thailand-256x171.png',
            'Turki' => 'Flag_of_Turkey-256x171.png',
            'Uni Emirat Arab' => 'Flag_of_United_Arab_Emirates-256x128.png',
            'Amerika Serikat' => 'Flag_of_United_States-256x135.png',
        ];

        foreach ($countries as $name => $filename) {
            Region::updateOrCreate(
                ['nama_negara' => $name],
                [
                    'foto_negara' => 'Bendera/'.$filename,
                    'ketua_persatuan_game_id' => null,
                    'pelatih_timnas_id' => null,
                ],
            );
        }
    }
}
