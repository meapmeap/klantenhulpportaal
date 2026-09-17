<?php

namespace Database\Seeders;

use App\Models\Notitie;
use Illuminate\Database\Seeder;

class NotitieSeeder extends Seeder
{
    public function run(): void
    {
        Notitie::factory(100)->create();
    }
}
