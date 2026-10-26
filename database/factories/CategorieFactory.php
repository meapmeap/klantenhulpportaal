<?php

namespace Database\Factories;

use App\Models\Categorie;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Categorie>
 */
class CategorieFactory extends Factory
{
    public function definition(): array
    {
        return [
            'naam' => fake()->randomElement(['Hardware', 'Software', 'Netwerk', 'Accounts', 'Beveiliging', 'E-mail', 'Overig']), 
            'beschrijving' => fake()->paragraph()
        ];
    }
}
