<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TurnoController;
use App\Http\Controllers\CajeroController;
use App\Http\Controllers\PantallaPublicaController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

// Página principal - redirige a solicitar turno
Route::get('/', function () {
    return redirect()->route('turno.create');
});

// Rutas públicas para solicitar turnos
Route::prefix('turnos')->group(function () {
    Route::get('/solicitar', [TurnoController::class, 'create'])->name('turno.create');
    Route::post('/', [TurnoController::class, 'store'])->middleware('throttle:turnos')->name('turno.store');
    Route::get('/{codigo}', [TurnoController::class, 'show'])->name('turno.comprobante');
    Route::post('/validar-duplicado', [TurnoController::class, 'validarDuplicado'])->middleware('throttle:turnos')->name('turno.validar');
});

// Pantalla pública para mostrar turnos llamados
Route::get('/pantalla-publica', [PantallaPublicaController::class, 'index'])->name('pantalla.publica');
Route::get('/api/turnos-actualizados', [PantallaPublicaController::class, 'getTurnosActualizados'])->middleware('throttle:api-publica')->name('pantalla.actualizar');

// Dashboard para usuarios autenticados
Route::get('/dashboard', function () {
    $user = auth()->user();

    if (session('error')) {
        return view('dashboard');
    }

    if ($user->rol === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($user->rol === 'cajero' && $user->caja_id) {
        return redirect()->route('cajero.panel');
    }

    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas para cajeros (requiere autenticación y rol cajero)
Route::middleware(['auth', 'verified', 'role:cajero,admin'])->prefix('cajero')->name('cajero.')->group(function () {
    Route::get('/', [CajeroController::class, 'index'])->name('panel');
    Route::post('/llamar-siguiente', [CajeroController::class, 'llamarSiguiente'])->name('llamar');
    Route::post('/turno/{id}/repetir', [CajeroController::class, 'repetirTurno'])->name('repetir');
    Route::post('/turno/{id}/iniciar', [CajeroController::class, 'iniciarAtencion'])->name('iniciar');
    Route::post('/turno/{id}/finalizar', [CajeroController::class, 'finalizarAtencion'])->name('finalizar');
    Route::post('/turno/{id}/cancelar', [CajeroController::class, 'cancelarTurno'])->name('cancelar');
    Route::post('/turno/{id}/transferir', [CajeroController::class, 'transferirTurno'])->name('transferir');
    Route::get('/reporte', [CajeroController::class, 'reporteDiario'])->name('reporte');
});

// Rutas de administración (requiere autenticación y rol admin)
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');

    // Gestión de Tipos de Trámite
    Route::get('/tipos-tramite', [AdminController::class, 'tiposTramite'])->name('tipos-tramite.index');

    // Gestión de Cajas
    Route::get('/cajas', [AdminController::class, 'cajas'])->name('cajas.index');

    // Gestión de Usuarios/Cajeros
    Route::get('/usuarios', [AdminController::class, 'usuarios'])->name('usuarios.index');

    // Reportes y estadísticas
    Route::get('/reportes', [AdminController::class, 'reportes'])->name('reportes');
    Route::get('/estadisticas', [AdminController::class, 'estadisticas'])->name('estadisticas');
});

// Rutas de perfil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
