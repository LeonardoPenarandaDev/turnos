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
        $tramiteStats = TipoTramite::withCount(['turnos' => function ($query) {
            $query->whereDate('created_at', '>=', Carbon::now()->subDays(30));
        }])->get();

        $cajeroStats = User::where('rol', 'cajero')
            ->withCount(['turnos' => function ($query) {
                $query->whereDate('created_at', Carbon::today())
                    ->where('estado', 'atendido');
            }])
            ->get();

        return view('admin.estadisticas', compact('tramiteStats', 'cajeroStats'));
    }

    // ==================== TIPOS DE TRÁMITE ====================

    public function tiposTramite()
    {
        $tipos = TipoTramite::orderBy('nombre')->get();
        return view('admin.tipos-tramite.index', compact('tipos'));
    }

    public function storeTipoTramite(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:tipos_tramite,nombre',
            'descripcion' => 'nullable|string|max:500',
        ]);

        TipoTramite::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'activo' => true,
        ]);

        return redirect()->route('admin.tipos-tramite.index')->with('success', 'Tipo de tramite creado.');
    }

    public function updateTipoTramite(Request $request, $id)
    {
        $tipo = TipoTramite::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255|unique:tipos_tramite,nombre,' . $id,
            'descripcion' => 'nullable|string|max:500',
            'activo' => 'required|boolean',
        ]);

        $tipo->update($request->only('nombre', 'descripcion', 'activo'));

        return redirect()->route('admin.tipos-tramite.index')->with('success', 'Tipo de tramite actualizado.');
    }

    public function destroyTipoTramite($id)
    {
        $tipo = TipoTramite::findOrFail($id);

        if ($tipo->turnos()->exists()) {
            return redirect()->route('admin.tipos-tramite.index')
                ->with('error', 'No se puede eliminar: tiene turnos asociados. Desactivelo en su lugar.');
        }

        $tipo->delete();

        return redirect()->route('admin.tipos-tramite.index')->with('success', 'Tipo de tramite eliminado.');
    }

    // ==================== CAJAS ====================

    public function cajas()
    {
        $cajas = Caja::orderBy('numero')->get();
        return view('admin.cajas.index', compact('cajas'));
    }

    public function storeCaja(Request $request)
    {
        $request->validate([
            'numero' => 'required|integer|min:1|unique:cajas,numero',
            'nombre' => 'required|string|max:255',
        ]);

        Caja::create([
            'numero' => $request->numero,
            'nombre' => $request->nombre,
            'activa' => true,
        ]);

        return redirect()->route('admin.cajas.index')->with('success', 'Caja creada.');
    }

    public function updateCaja(Request $request, $id)
    {
        $caja = Caja::findOrFail($id);

        $request->validate([
            'numero' => 'required|integer|min:1|unique:cajas,numero,' . $id,
            'nombre' => 'required|string|max:255',
            'activa' => 'required|boolean',
        ]);

        $caja->update($request->only('numero', 'nombre', 'activa'));

        return redirect()->route('admin.cajas.index')->with('success', 'Caja actualizada.');
    }

    public function destroyCaja($id)
    {
        $caja = Caja::findOrFail($id);

        if ($caja->turnos()->exists()) {
            return redirect()->route('admin.cajas.index')
                ->with('error', 'No se puede eliminar: tiene turnos asociados. Desactivela en su lugar.');
        }

        $caja->delete();

        return redirect()->route('admin.cajas.index')->with('success', 'Caja eliminada.');
    }

    // ==================== USUARIOS ====================

    public function usuarios()
    {
        $usuarios = User::with('caja')->orderBy('name')->get();
        $cajas = Caja::where('activa', true)->orderBy('numero')->get();
        return view('admin.usuarios.index', compact('usuarios', 'cajas'));
    }

    public function storeUsuario(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'rol' => 'required|in:admin,cajero',
            'caja_id' => 'nullable|exists:cajas,id',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => $request->rol,
            'caja_id' => $request->caja_id,
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario creado.');
    }

    public function updateUsuario(Request $request, $id)
    {
        $usuario = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
            'rol' => 'required|in:admin,cajero',
            'caja_id' => 'nullable|exists:cajas,id',
        ]);

        $data = $request->only('name', 'email', 'rol', 'caja_id');

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $usuario->update($data);

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario actualizado.');
    }

    public function destroyUsuario($id)
    {
        $usuario = User::findOrFail($id);

        if ($usuario->id == auth()->id()) {
            return redirect()->route('admin.usuarios.index')
                ->with('error', 'No puede eliminarse a si mismo.');
        }

        if ($usuario->turnos()->exists()) {
            return redirect()->route('admin.usuarios.index')
                ->with('error', 'No se puede eliminar: tiene turnos asociados.');
        }

        $usuario->delete();

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario eliminado.');
    }
}