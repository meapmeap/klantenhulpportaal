<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorieSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('Categorieen')->insert([ 
            [ 
                'naam' => 'Hardware', 
                'beschrijving' => 'Problemen met hardware', 
                'created_at' => now(), 
                'updated_at' => now(), 
            ], 
            [ 
                'naam' => 'Software', 
                'beschrijving' => 'Problemen met software', 
                'created_at' => now(), 
                'updated_at' => now(), 
            ], 
            [ 
                'naam' => 'Netwerk', 
                'beschrijving' => 'Netwerk problemen', 
                'created_at' => now(), 
                'updated_at' => now(), 
            ], 
            [ 
                'naam' => 'Accounts', 
                'beschrijving' => 'Problemen met accounts', 
                'created_at' => now(), 
                'updated_at' => now(), 
            ], 
            [ 
                'naam' => 'Beveiliging', 
                'beschrijving' => 'Problemen met veiligheid', 
                'created_at' => now(), 
                'updated_at' => now(), 
            ], 
            [ 
                'naam' => 'E-mail', 
                'beschrijving' => 'Problemen met e-mail', 
                'created_at' => now(), 
                'updated_at' => now(), 
            ], 
            [ 
                'naam' => 'Overig', 
                'beschrijving' => 'Problemen die niet binnen een andere categorie vallen.', 
                'created_at' => now(), 
                'updated_at' => now(), 
            ], 
        ]);
    }
}
