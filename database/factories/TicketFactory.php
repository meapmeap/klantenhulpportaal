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
    public function definition(): array
    {
        return [
            'titel' => fake()->sentence(4),
            'categorie_id' => Categorie::inRandomOrder()->first()->id,
            'status' => fake()->randomElement(['Nieuw', 'In behandeling', 'Afgehandeld']),
            'user_id' => User::where('rol', 'admin')->inRandomOrder()->first()->id,
            'created_by' => User::where('rol', 'user')->inRandomOrder()->first()->id
        ];
    }
}
