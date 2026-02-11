<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoTramite;

class TipoTramiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = [
            [
                'nombre' => 'Pago de Impuesto Predial',
                'descripcion' => 'Pago anual del impuesto predial unificado',
                'activo' => true
            ],
            [
                'nombre' => 'Certificado de Paz y Salvo',
                'descripcion' => 'Solicitud de certificado de paz y salvo de impuestos',
                'activo' => true
            ],
            [
                'nombre' => 'Licencia de Construcción',
                'descripcion' => 'Solicitud y trámite de licencia de construcción',
                'activo' => true
            ],
            [
                'nombre' => 'Registro Civil',
                'descripcion' => 'Expedición de registros civiles de nacimiento, matrimonio y defunción',
                'activo' => true
            ],
            [
                'nombre' => 'Certificado de Estratificación',
                'descripcion' => 'Expedición de certificado de estrato socioeconómico',
                'activo' => true
            ],
            [
                'nombre' => 'Información General',
                'descripcion' => 'Consultas e información general sobre trámites',
                'activo' => true
            ],
            [
                'nombre' => 'Quejas y Reclamos',
                'descripcion' => 'Atención de quejas, reclamos y sugerencias',
                'activo' => true
            ],
            [
                'nombre' => 'Pago de Servicios Públicos',
                'descripcion' => 'Pago de servicios públicos municipales',
                'activo' => true
            ]
        ];

        foreach ($tipos as $tipo) {
            TipoTramite::create($tipo);
        }
    }
}
