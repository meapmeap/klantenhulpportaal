<?php

namespace Database\Seeders;

use App\Models\Reactie;
use Illuminate\Database\Seeder;

class ReactieSeeder extends Seeder
{
    public function run(): void
    {
        Reactie::factory(100)->create();
    }
}
