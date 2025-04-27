<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Categoria;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        /* User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]); */

        User::create([
            'name' => 'Daniel Alberto Canaviri Mena',
            'email' => 'daniel@gmail.com',
            'password' => Hash::make('12345678')
        ]);

        User::create([
            'name' => 'Jessica Savedra Mendoza',
            'email' => 'jessica@gmail.com',
            'password' => Hash::make('12345678')
        ]);

        User::create([
            'name' => 'Marcelo Suarez Salgado',
            'email' => 'marcelo@gmail.com',
            'password' => Hash::make('12345678')
        ]);

        Categoria::create(['nombre' => 'Alimentación', 'tipo' => 'gasto']);
        Categoria::create(['nombre' => 'Otros Ingresos', 'tipo' => 'ingreso']);
        Categoria::create(['nombre' => 'Transporte', 'tipo' => 'gasto']);
        Categoria::create(['nombre' => 'Salud', 'tipo' => 'gasto']);
        Categoria::create(['nombre' => 'Sueldos', 'tipo' => 'ingreso']);

    }
}
