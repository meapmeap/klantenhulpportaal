<?php

namespace Database\Factories;

use App\Models\Categorie;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'titel' => fake()->sentence(4),
            'categorie_id' => Categorie::inRandomOrder()->first()->id,
            'status' => fake()->randomElement(['Nieuw', 'Toegewezen', 'Klaar', 'Probleem']),
            'user_id' => User::where('rol', 'administrator')->inRandomOrder()->first()->id,
            'created_by' => User::where('rol', 'user')->inRandomOrder()->first()->id
        ];
    }
}
