<?php

namespace Database\Factories;

use App\Models\Reactie;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Reactie>
 */
class ReactieFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'bericht' =>fake()->text(),
            'user_id' => User::inRandomOrder()->first()->id,
            'ticket_id' => Ticket::inRandomOrder()->first()->id
        ];
    }
}
