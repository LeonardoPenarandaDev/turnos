<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            TipoTramiteSeeder::class,
            CajaSeeder::class,
            UserSeeder::class,
        ]);

        $this->command->info('✓ Base de datos poblada exitosamente!');
        $this->command->newLine();
        $this->command->info('Credenciales de acceso:');
        $this->command->info('Admin: admin@cucuta.gov.co / admin123');
        $this->command->info('Cajero: maria.gonzalez@cucuta.gov.co / cajero123');
    }
}
