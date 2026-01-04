<?php

namespace Database\Seeders;

use App\Models\Card;
use App\Models\Collection;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Création d'un admin
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);
        // Création d'un membre
        User::factory()->create([
            'name' => 'Jean Collectionneur',
            'email' => 'member@cardverse.com',
            'role' => 'member',
        ]);

        // Création d'une collection et d'une carte
        $coll = Collection::create(['name' => 'Pokémon 151', 'category' => 'Pokémon', 'release_date' => '2023-09-22']);
        Card::create([
            'collection_id' => $coll->id,
            'name' => 'Mewtwo',
            'image_url' => 'https://assets.pokemon.com/static-assets/content-assets/cms2-fr-fr/img/cards/web/SV3PT5/SV3PT5_FR_150.png',
            'rarity' => 'Secret Rare',
        ]);
    }
}
