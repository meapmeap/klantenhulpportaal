<?php

namespace Database\Factories;

use App\Models\Notitie;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Notitie>
 */
class NotitieFactory extends Factory
{
    public function definition(): array
    {
        return [
            'bericht' =>fake()->text(),
            'user_id' => User::inRandomOrder()->first()->id,
            'ticket_id' => Ticket::inRandomOrder()->first()->id
        ];
    }
}
