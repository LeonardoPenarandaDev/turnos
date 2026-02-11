<?php

namespace App\Http\Controllers;

use App\Models\Turno;
use App\Models\TipoTramite;
use App\Http\Requests\StoreTurnoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Carbon\Carbon;

class TurnoController extends Controller
{
    public function create()
    {
        $tiposTramite = TipoTramite::where('activo', true)->orderBy('nombre')->get();
        return view('turnos.solicitar', compact('tiposTramite'));
    }

    public function store(StoreTurnoRequest $request)
    {
        $validated = $request->validated();

        // Validar duplicado (mismo documento en el día)
        $turnoExistente = Turno::where('numero_documento', $validated['numero_documento'])
            ->where('tipo_documento', $validated['tipo_documento'])
            ->whereDate('created_at', Carbon::today())
            ->whereIn('estado', ['pendiente', 'llamado', 'en_atencion'])
            ->first();

        if ($turnoExistente) {
            return back()
                ->withInput()
                ->with('error', 'Ya tiene un turno activo hoy: ' . $turnoExistente->codigo);
        }

        // Crear turno usando transacción
        try {
            $turno = \DB::transaction(function () use ($validated) {
                return Turno::create([
                    'codigo' => Turno::generarCodigo(),
                    'tipo_documento' => $validated['tipo_documento'],
                    'numero_documento' => $validated['numero_documento'],
                    'nombre_completo' => $validated['nombre_completo'],
                    'tipo_tramite_id' => $validated['tipo_tramite_id'],
                    'estado' => 'pendiente',
                    'hora_solicitud' => now()
                ]);
            });
        } catch (\Exception $e) {
            \Log::error('Error al crear turno: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Error al generar el turno. Por favor, intente nuevamente.');
        }

        return redirect()->route('turno.comprobante', $turno->codigo)
            ->with('success', 'Turno generado exitosamente');
    }

    public function show($codigo)
    {
        $turno = Turno::with('tipoTramite')->where('codigo', $codigo)->firstOrFail();
        
        // Calcular turnos pendientes antes
        $turnosAdelante = Turno::where('estado', 'pendiente')
            ->where('id', '<', $turno->id)
            ->count();

        $tiempoEstimado = $turnosAdelante * 5; // 5 minutos por turno estimado

        return view('turnos.comprobante', compact('turno', 'turnosAdelante', 'tiempoEstimado'));
    }

    public function validarDuplicado(Request $request)
    {
        $existe = Turno::where('numero_documento', $request->numero_documento)
            ->where('tipo_documento', $request->tipo_documento)
            ->whereDate('created_at', Carbon::today())
            ->whereIn('estado', ['pendiente', 'llamado', 'en_atencion'])
            ->exists();

        return response()->json(['existe' => $existe]);
    }
}
