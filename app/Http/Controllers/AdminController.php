<?php

namespace App\Http\Controllers;

use App\Models\Turno;
use App\Models\Caja;
use App\Models\TipoTramite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        // Estadísticas generales
        $turnosHoy = Turno::whereDate('created_at', Carbon::today())->count();
        $turnosAtendidos = Turno::whereDate('created_at', Carbon::today())
            ->where('estado', 'atendido')
            ->count();
        $turnosPendientes = Turno::where('estado', 'pendiente')->count();
        $cajasActivas = Caja::where('activa', true)->count();

        return view('admin.dashboard', compact(
            'turnosHoy',
            'turnosAtendidos',
            'turnosPendientes',
            'cajasActivas'
        ));
    }

    public function reportes()
    {
        $fecha = request('fecha', Carbon::today()->format('Y-m-d'));

        $turnos = Turno::whereDate('created_at', $fecha)
            ->with(['tipoTramite', 'caja', 'cajero'])
            ->get();

        $estadisticas = [
            'total' => $turnos->count(),
            'atendidos' => $turnos->where('estado', 'atendido')->count(),
            'cancelados' => $turnos->where('estado', 'cancelado')->count(),
            'pendientes' => $turnos->where('estado', 'pendiente')->count(),
            'tiempo_promedio' => $turnos->where('estado', 'atendido')->avg('tiempo_atencion'),
        ];

        return view('admin.reportes', compact('turnos', 'estadisticas', 'fecha'));
    }

    public function estadisticas()
    {
        // Estadísticas por tipo de trámite
        $tramiteStats = TipoTramite::withCount(['turnos' => function ($query) {
            $query->whereDate('created_at', '>=', Carbon::now()->subDays(30));
        }])->get();

        // Estadísticas por cajero
        $cajeroStats = User::where('rol', 'cajero')
            ->withCount(['turnos' => function ($query) {
                $query->whereDate('created_at', Carbon::today())
                    ->where('estado', 'atendido');
            }])
            ->get();

        return view('admin.estadisticas', compact('tramiteStats', 'cajeroStats'));
    }

    // CRUD Tipos de Trámite
    public function tiposTramite()
    {
        // Este método se implementaría como resource controller
        // Por ahora solo retorna vista básica
        $tipos = TipoTramite::orderBy('nombre')->get();
        return view('admin.tipos-tramite.index', compact('tipos'));
    }

    // CRUD Cajas
    public function cajas()
    {
        $cajas = Caja::orderBy('numero')->get();
        return view('admin.cajas.index', compact('cajas'));
    }

    // CRUD Usuarios
    public function usuarios()
    {
        $usuarios = User::with('caja')->orderBy('name')->get();
        $cajas = Caja::where('activa', true)->get();
        return view('admin.usuarios.index', compact('usuarios', 'cajas'));
    }
}
