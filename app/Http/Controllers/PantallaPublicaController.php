<?php

namespace App\Http\Controllers;

use App\Models\Turno;
use Illuminate\Http\Request;

class PantallaPublicaController extends Controller
{
    public function index()
    {
        // Turno actualmente en atención o llamado
        $turnoActual = Turno::whereIn('estado', ['llamado', 'en_atencion'])
            ->with(['tipoTramite', 'caja'])
            ->orderBy('hora_llamado', 'desc')
            ->first();

        // Últimos 5 turnos llamados
        $ultimosTurnos = Turno::whereIn('estado', ['llamado', 'en_atencion', 'atendido'])
            ->with(['tipoTramite', 'caja'])
            ->whereNotNull('hora_llamado')
            ->orderBy('hora_llamado', 'desc')
            ->limit(5)
            ->get();

        return view('publica.pantalla', compact('turnoActual', 'ultimosTurnos'));
    }

    public function getTurnosActualizados()
    {
        $turnoActual = Turno::whereIn('estado', ['llamado', 'en_atencion'])
            ->with(['tipoTramite', 'caja'])
            ->orderBy('hora_llamado', 'desc')
            ->first();

        $ultimosTurnos = Turno::whereIn('estado', ['llamado', 'en_atencion', 'atendido'])
            ->with(['tipoTramite', 'caja'])
            ->whereNotNull('hora_llamado')
            ->orderBy('hora_llamado', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'turnoActual' => $turnoActual,
            'ultimosTurnos' => $ultimosTurnos
        ]);
    }
}
