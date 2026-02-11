<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usuario Administrador
        User::create([
            'name' => 'Administrador Sistema',
            'email' => 'admin@cucuta.gov.co',
            'password' => Hash::make('admin123'),
            'rol' => 'admin',
            'caja_id' => null,
            'email_verified_at' => now()
        ]);

        // Cajeros
        $cajeros = [
            [
                'name' => 'María González',
                'email' => 'maria.gonzalez@cucuta.gov.co',
                'password' => Hash::make('cajero123'),
                'rol' => 'cajero',
                'caja_id' => 1
            ],
            [
                'name' => 'Carlos Ramírez',
                'email' => 'carlos.ramirez@cucuta.gov.co',
                'password' => Hash::make('cajero123'),
                'rol' => 'cajero',
                'caja_id' => 2
            ],
            [
                'name' => 'Ana Martínez',
                'email' => 'ana.martinez@cucuta.gov.co',
                'password' => Hash::make('cajero123'),
                'rol' => 'cajero',
                'caja_id' => 3
            ],
            [
                'name' => 'Luis Pérez',
                'email' => 'luis.perez@cucuta.gov.co',
                'password' => Hash::make('cajero123'),
                'rol' => 'cajero',
                'caja_id' => 4
            ]
        ];

        foreach ($cajeros as $cajero) {
            $cajero['email_verified_at'] = now();
            User::create($cajero);
        }
    }
}
