<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Caja;

class CajaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cajas = [
            [
                'numero' => 1,
                'nombre' => 'Caja 1 - Atención General',
                'activa' => true
            ],
            [
                'numero' => 2,
                'nombre' => 'Caja 2 - Pagos e Impuestos',
                'activa' => true
            ],
            [
                'numero' => 3,
                'nombre' => 'Caja 3 - Licencias y Permisos',
                'activa' => true
            ],
            [
                'numero' => 4,
                'nombre' => 'Caja 4 - Registros Civiles',
                'activa' => true
            ],
            [
                'numero' => 5,
                'nombre' => 'Caja 5 - Información',
                'activa' => false
            ]
        ];

        foreach ($cajas as $caja) {
            Caja::create($caja);
        }
    }
}
