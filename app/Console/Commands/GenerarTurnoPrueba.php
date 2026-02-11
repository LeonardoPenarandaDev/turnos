<?php

namespace App\Console\Commands;

use App\Models\Turno;
use App\Models\TipoTramite;
use Illuminate\Console\Command;

class GenerarTurnoPrueba extends Command
{
    protected $signature = 'turno:generar {cantidad=1}';
    protected $description = 'Genera turnos de prueba rápidamente';

    public function handle()
    {
        $cantidad = (int) $this->argument('cantidad');

        $tiposTramite = TipoTramite::where('activo', true)->pluck('id')->toArray();

        if (empty($tiposTramite)) {
            $this->error('No hay tipos de trámite activos. Ejecuta: php artisan db:seed');
            return 1;
        }

        $nombres = [
            'Juan Pérez García',
            'María López Rodríguez',
            'Carlos Martínez Sánchez',
            'Ana González Torres',
            'Luis Ramírez Castro',
            'Diana Herrera Mora',
            'Pedro Vargas Silva',
            'Laura Ortiz Jiménez'
        ];

        $this->info("Generando {$cantidad} turno(s) de prueba...");

        for ($i = 0; $i < $cantidad; $i++) {
            $numeroDocumento = rand(10000000, 99999999);

            $turno = Turno::create([
                'codigo' => Turno::generarCodigo(),
                'tipo_documento' => 'CC',
                'numero_documento' => $numeroDocumento,
                'nombre_completo' => $nombres[array_rand($nombres)],
                'tipo_tramite_id' => $tiposTramite[array_rand($tiposTramite)],
                'estado' => 'pendiente',
                'hora_solicitud' => now()
            ]);

            $this->line("✓ Turno generado: <fg=green>{$turno->codigo}</> - {$turno->nombre_completo}");
        }

        $this->info("\n✓ {$cantidad} turno(s) generado(s) exitosamente!");
        $this->info("Ahora puedes llamarlos desde el panel de cajero.");

        return 0;
    }
}
