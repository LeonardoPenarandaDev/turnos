<?php

namespace App\Http\Controllers;

use App\Models\Turno;
use Illuminate\Http\Request;

class PantallaPublicaController extends Controller
{
    public function index()
    {
        // Turno actualmente en atención o llamado (hoy)
        $turnoActual = Turno::hoy()
            ->whereIn('estado', ['llamado', 'en_atencion'])
            ->with(['tipoTramite', 'caja'])
            ->orderBy('hora_llamado', 'desc')
            ->first();

        // Últimos 5 turnos llamados (hoy)
        $ultimosTurnos = Turno::hoy()
            ->whereIn('estado', ['llamado', 'en_atencion', 'atendido'])
            ->with(['tipoTramite', 'caja'])
            ->whereNotNull('hora_llamado')
            ->orderBy('hora_llamado', 'desc')
            ->limit(5)
            ->get();

        // Próximos turnos en cola (hoy)
        $proximosTurnos = Turno::hoy()
            ->pendientes()
            ->with('tipoTramite')
            ->limit(5)
            ->get();

        return view('publica.pantalla', compact('turnoActual', 'ultimosTurnos', 'proximosTurnos'));
    }

    public function getTurnosActualizados()
    {
        $turnoActual = Turno::hoy()
            ->whereIn('estado', ['llamado', 'en_atencion'])
            ->with(['tipoTramite', 'caja'])
            ->orderBy('hora_llamado', 'desc')
            ->first();

        // Todos los turnos llamados/en atención (para detectar nuevos en el frontend)
        $turnosLlamados = Turno::hoy()
            ->whereIn('estado', ['llamado', 'en_atencion'])
            ->with(['tipoTramite', 'caja'])
            ->orderBy('hora_llamado', 'desc')
            ->get();

        $ultimosTurnos = Turno::hoy()
            ->whereIn('estado', ['llamado', 'en_atencion', 'atendido'])
            ->with(['tipoTramite', 'caja'])
            ->whereNotNull('hora_llamado')
            ->orderBy('hora_llamado', 'desc')
            ->limit(5)
            ->get();

        $proximosTurnos = Turno::hoy()
            ->pendientes()
            ->with('tipoTramite')
            ->limit(5)
            ->get();

        return response()->json([
            'turnoActual' => $turnoActual,
            'turnosLlamados' => $turnosLlamados,
            'ultimosTurnos' => $ultimosTurnos,
            'proximosTurnos' => $proximosTurnos
        ]);
    }

    /**
     * Genera y sirve audio TTS usando Google Translate.
     * Compatible con navegadores WebOS (HTML5 Audio).
     */
    public function tts(\Illuminate\Http\Request $request)
    {
        $texto = $request->query('texto', '');

        if (empty($texto) || strlen($texto) > 200) {
            abort(400, 'Texto inválido');
        }

        // Clave de caché basada en el texto
        $cacheKey = 'tts_' . md5($texto);
        $cachePath = storage_path('app/tts/' . $cacheKey . '.mp3');

        // Usar archivo cacheado si existe y tiene menos de 24h
        if (file_exists($cachePath) && (time() - filemtime($cachePath)) < 86400) {
            return response()->file($cachePath, [
                'Content-Type' => 'audio/mpeg',
                'Cache-Control' => 'public, max-age=86400',
            ]);
        }

        // Crear directorio si no existe
        if (!is_dir(storage_path('app/tts'))) {
            mkdir(storage_path('app/tts'), 0755, true);
        }

        // URL de Google Translate TTS
        $url = 'https://translate.google.com/translate_tts?' . http_build_query([
            'ie'     => 'UTF-8',
            'q'      => $texto,
            'tl'     => 'es',
            'client' => 'tw-ob',
        ]);

        // Descargar audio con contexto HTTP
        $context = stream_context_create([
            'http' => [
                'header'  => "User-Agent: Mozilla/5.0 (compatible)\r\n",
                'timeout' => 6,
            ],
            'ssl' => [
                'verify_peer'      => false,
                'verify_peer_name' => false,
            ],
        ]);

        $audio = @file_get_contents($url, false, $context);

        if ($audio === false || strlen($audio) < 100) {
            // Fallback: respuesta vacía (el cliente mostrará solo beep)
            abort(503, 'TTS no disponible');
        }

        // Guardar en caché
        file_put_contents($cachePath, $audio);

        return response($audio, 200, [
            'Content-Type'  => 'audio/mpeg',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}
