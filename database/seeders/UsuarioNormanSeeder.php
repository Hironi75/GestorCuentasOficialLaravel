<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsuarioNormanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('usuarios')->insert([
            'id' => uniqid(),
            'usuario' => 'UsuarioNorman',
            'contrasenia' => 'admin123',
            // 'created_at' => now(),
            // 'updated_at' => now(),
        ]);
    }
}
