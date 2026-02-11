<?php

namespace App\Http\Controllers;

use App\Models\Turno;
use App\Models\Caja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CajeroController extends Controller
{
    public function index()
    {
        $cajero = Auth::user();
        $caja = $cajero->caja;

        if (!$caja) {
            return redirect()->route('dashboard')->with('error', 'No tiene una caja asignada. Contacte al administrador.');
        }

        // Turno actual en atención en esta caja
        $turnoActual = Turno::where('caja_id', $caja->id)
            ->where('estado', 'en_atencion')
            ->with('tipoTramite')
            ->first();

        // Cola de turnos pendientes
        $turnosPendientes = Turno::pendientes()
            ->with('tipoTramite')
            ->limit(10)
            ->get();

        // Estadísticas del día
        $turnosAtendidosHoy = Turno::where('user_id', $cajero->id)
            ->whereDate('created_at', Carbon::today())
            ->where('estado', 'atendido')
            ->count();

        $tiempoPromedioHoy = Turno::where('user_id', $cajero->id)
            ->whereDate('created_at', Carbon::today())
            ->where('estado', 'atendido')
            ->whereNotNull('tiempo_atencion')
            ->avg('tiempo_atencion');

        $cajasDisponibles = Caja::where('activa', true)
            ->where('id', '!=', $caja->id)
            ->orderBy('numero')
            ->get();

        return view('cajero.panel', compact(
            'caja',
            'turnoActual',
            'turnosPendientes',
            'turnosAtendidosHoy',
            'tiempoPromedioHoy',
            'cajasDisponibles'
        ));
    }

    public function llamarSiguiente()
    {
        $cajero = Auth::user();
        $caja = $cajero->caja;

        // Verificar que no haya turno en atención
        $turnoEnAtencion = Turno::where('caja_id', $caja->id)
            ->where('estado', 'en_atencion')
            ->first();

        if ($turnoEnAtencion) {
            return response()->json([
                'success' => false,
                'message' => 'Debe finalizar el turno actual antes de llamar otro'
            ], 400);
        }

        // Obtener siguiente turno pendiente
        $turno = Turno::pendientes()->first();

        if (!$turno) {
            return response()->json([
                'success' => false,
                'message' => 'No hay turnos pendientes'
            ], 404);
        }

        // Actualizar turno directamente a en_atencion
        $turno->update([
            'estado' => 'en_atencion',
            'caja_id' => $caja->id,
            'user_id' => $cajero->id,
            'hora_llamado' => now(),
            'hora_inicio_atencion' => now()
        ]);

        // Broadcast evento para pantalla pública
        event(new \App\Events\TurnoLlamado($turno));

        return response()->json([
            'success' => true,
            'turno' => $turno->load('tipoTramite')
        ]);
    }

    public function repetirTurno($id)
    {
        $turno = Turno::findOrFail($id);

        $this->authorize('call', $turno);

        // Broadcast evento para repetir llamado
        event(new \App\Events\TurnoLlamado($turno));

        return response()->json([
            'success' => true,
            'message' => 'Turno repetido',
            'turno' => $turno->load('tipoTramite', 'caja')
        ]);
    }

    public function iniciarAtencion($id)
    {
        $cajero = Auth::user();
        $turno = Turno::findOrFail($id);

        $this->authorize('attend', $turno);

        if ($turno->estado !== 'llamado') {
            return response()->json([
                'success' => false,
                'message' => 'El turno debe estar en estado "llamado" para iniciar atención'
            ], 400);
        }

        $turno->update([
            'estado' => 'en_atencion',
            'hora_inicio_atencion' => now()
        ]);

        return response()->json([
            'success' => true,
            'turno' => $turno
        ]);
    }

    public function finalizarAtencion(Request $request, $id)
    {
        $turno = Turno::findOrFail($id);

        $this->authorize('finish', $turno);

        $request->validate([
            'observaciones' => 'nullable|string|max:500'
        ]);

        $horaFin = now();
        $tiempoAtencion = $turno->hora_inicio_atencion
            ? $turno->hora_inicio_atencion->diffInSeconds($horaFin)
            : 0;

        $turno->update([
            'estado' => 'atendido',
            'hora_fin_atencion' => $horaFin,
            'tiempo_atencion' => $tiempoAtencion,
            'observaciones' => $request->observaciones
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Turno finalizado correctamente'
        ]);
    }

    public function cancelarTurno(Request $request, $id)
    {
        $turno = Turno::findOrFail($id);

        $this->authorize('cancel', $turno);

        $request->validate([
            'motivo' => 'required|string|min:10|max:500'
        ]);

        $turno->update([
            'estado' => 'cancelado',
            'observaciones' => $request->motivo,
            'hora_fin_atencion' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Turno cancelado'
        ]);
    }

    public function transferirTurno(Request $request, $id)
    {
        $turno = Turno::findOrFail($id);

        $this->authorize('transfer', $turno);

        $request->validate([
            'caja_id' => 'required|exists:cajas,id,activa,1'
        ]);

        $turno->update([
            'caja_id' => $request->caja_id,
            'user_id' => null,
            'estado' => 'pendiente',
            'hora_llamado' => null,
            'hora_inicio_atencion' => null
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Turno transferido'
        ]);
    }

    public function reporteDiario()
    {
        $cajero = Auth::user();
        $fecha = request('fecha', Carbon::today()->format('Y-m-d'));

        $turnos = Turno::where('user_id', $cajero->id)
            ->whereDate('created_at', $fecha)
            ->where('estado', 'atendido')
            ->with('tipoTramite')
            ->get();

        $totalAtendidos = $turnos->count();
        $tiempoPromedio = $turnos->avg('tiempo_atencion');
        $tiempoTotal = $turnos->sum('tiempo_atencion');

        return view('cajero.reporte', compact(
            'turnos',
            'totalAtendidos',
            'tiempoPromedio',
            'tiempoTotal',
            'fecha'
        ));
    }
}
