<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Nom du produit avec un index aléatoire
            'name' => 'Product_' . rand(1, 100) . ' - ' . $this->faker->safeColorName(),

            // Prix entre 1.00 et 999.99
            'price' => $this->faker->randomFloat(2, 1, 999),

            // Texte aléatoire pour la description
            'description' => $this->faker->paragraph(),

            // Image : une chaîne de caractères simple pour l'instant
            'image' => 'default.jpg',

            // Clé étrangère aléatoire pour la catégorie
            'category_id' => Category::all()->random()->id,

            // Clé étrangère aléatoire pour l'utilisateur/vendeur
            'user_id' => User::all()->random()->id,
        ];
    }
}
