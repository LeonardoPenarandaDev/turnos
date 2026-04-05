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

        // Turno actual en atención en esta caja (puede ser de otro cajero del turno anterior)
        $turnoActual = Turno::where('caja_id', $caja->id)
            ->where('estado', 'en_atencion')
            ->with('tipoTramite', 'cajero')
            ->first();

        // Tramites que este cajero puede atender
        $tramiteIds = $cajero->tiposTramite()->pluck('tipos_tramite.id')->toArray();

        // Cola de turnos pendientes del día (solo de tramites asignados)
        $query = Turno::hoy()->pendientes()->with('tipoTramite');

        if (!empty($tramiteIds)) {
            $query->whereIn('tipo_tramite_id', $tramiteIds);
        } else {
            // Sin tramites asignados = no puede atender nada
            $query->whereRaw('1 = 0');
        }

        $turnosPendientes = $query->limit(10)->get();

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

        // Tramites que este cajero puede atender
        $tramiteIds = $cajero->tiposTramite()->pluck('tipos_tramite.id')->toArray();

        if (empty($tramiteIds)) {
            return response()->json([
                'success' => false,
                'message' => 'No tiene tramites asignados. Contacte al administrador.'
            ], 400);
        }

        // Obtener siguiente turno pendiente del día (solo de tramites asignados)
        $turno = Turno::hoy()->pendientes()
            ->whereIn('tipo_tramite_id', $tramiteIds)
            ->first();

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

    public function llamarTurnoEspecifico($id)
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

        $turno = Turno::findOrFail($id);

        if ($turno->estado !== 'pendiente') {
            return response()->json([
                'success' => false,
                'message' => 'Este turno ya no está pendiente'
            ], 400);
        }

        // Verificar que el cajero tenga asignado este tipo de tramite
        $tramiteIds = $cajero->tiposTramite()->pluck('tipos_tramite.id')->toArray();
        if (!in_array($turno->tipo_tramite_id, $tramiteIds)) {
            return response()->json([
                'success' => false,
                'message' => 'No tiene permiso para atender este tipo de tramite'
            ], 400);
        }

        $turno->update([
            'estado' => 'en_atencion',
            'caja_id' => $caja->id,
            'user_id' => $cajero->id,
            'hora_llamado' => now(),
            'hora_inicio_atencion' => now()
        ]);

        event(new \App\Events\TurnoLlamado($turno));

        return response()->json([
            'success' => true,
            'turno' => $turno->load('tipoTramite')
        ]);
    }

    public function repetirTurno($id)
    {
        $cajero = Auth::user();
        $turno = Turno::findOrFail($id);

        $esSuTurno = $turno->user_id == $cajero->id || $turno->caja_id == $cajero->caja_id;

        if ($cajero->rol !== 'admin' && !$esSuTurno) {
            return response()->json([
                'success' => false,
                'message' => 'No tiene permiso para repetir este turno'
            ], 403);
        }

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

        if ($turno->user_id != $cajero->id) {
            return response()->json([
                'success' => false,
                'message' => 'No tiene permiso para atender este turno'
            ], 403);
        }

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
        $cajero = Auth::user();
        $turno = Turno::findOrFail($id);

        $esAdmin = $cajero->rol === 'admin';
        $esSuTurno = $turno->user_id == $cajero->id || $turno->caja_id == $cajero->caja_id;

        if ($turno->estado !== 'en_atencion') {
            return response()->json([
                'success' => false,
                'message' => 'El turno no está en atención'
            ], 400);
        }

        if (!$esAdmin && !$esSuTurno) {
            return response()->json([
                'success' => false,
                'message' => 'No tiene permiso para finalizar este turno'
            ], 403);
        }

        $request->validate([
            'observaciones' => 'nullable|string|max:500'
        ]);

        $horaFin = now();
        $tiempoAtencion = $turno->hora_inicio_atencion
            ? $turno->hora_inicio_atencion->diffInSeconds($horaFin)
            : 0;

        $updateData = [
            'estado' => 'atendido',
            'hora_fin_atencion' => $horaFin,
            'tiempo_atencion' => $tiempoAtencion,
            'observaciones' => $request->observaciones
        ];

        // Si otro cajero de la misma caja finaliza el turno, actualizar user_id
        if ($turno->user_id != $cajero->id) {
            $updateData['user_id'] = $cajero->id;
        }

        $turno->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Turno finalizado correctamente'
        ]);
    }

    public function cancelarTurno(Request $request, $id)
    {
        $cajero = Auth::user();
        $turno = Turno::findOrFail($id);

        $esAdmin = $cajero->rol === 'admin';
        $esSuTurno = $turno->user_id == $cajero->id || $turno->caja_id == $cajero->caja_id;

        if (!$esAdmin && (!$esSuTurno || !in_array($turno->estado, ['llamado', 'en_atencion']))) {
            return response()->json([
                'success' => false,
                'message' => 'No tiene permiso para cancelar este turno'
            ], 403);
        }

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
        $cajero = Auth::user();
        $turno = Turno::findOrFail($id);

        $esAdmin = $cajero->rol === 'admin';
        $esSuTurno = $turno->user_id == $cajero->id || $turno->caja_id == $cajero->caja_id;

        if (!$esAdmin && (!$esSuTurno || !in_array($turno->estado, ['llamado', 'en_atencion']))) {
            return response()->json([
                'success' => false,
                'message' => 'No tiene permiso para transferir este turno'
            ], 403);
        }

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
