<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=1920, height=1080, initial-scale=1.0">
    <title>Pantalla Pública - Turnos</title>
    @vite(['resources/css/app.css'])
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        html, body {
            overflow: hidden;
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            font-size: 16px;
        }
        /* Layout 16:9 forzado */
        .pantalla-tv {
            width: 100%;
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.03); }
        }
        .pulse-animation {
            animation: pulse 2s ease-in-out infinite;
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .slide-in {
            animation: slideIn 0.4s ease-out;
        }
        @keyframes marquee {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }
        .marquee-text {
            animation: marquee 25s linear infinite;
            white-space: nowrap;
        }
        /* Turno card en la lista */
        .turno-card {
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(4px);
            border-radius: 0.75rem;
            padding: 0.6rem 0.8rem;
            transition: background 0.3s;
        }
        .turno-card:hover {
            background: rgba(255,255,255,0.25);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-900 via-indigo-900 to-purple-900 text-white">
    <div class="pantalla-tv">

        <!-- Header -->
        <div class="bg-white text-gray-900 shadow-lg flex-shrink-0" style="height: 10vh; min-height: 70px;">
            <div class="flex justify-between items-center h-full px-[1.5vw]">
                <div class="flex items-center">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo" style="height: 9vh; min-height: 60px; width: auto;">
                </div>
                <div class="text-center">
                    <p class="text-[4vw] font-bold text-indigo-600 leading-none" id="current-time"></p>
                </div>
                <div class="text-right">
                    <p class="text-[1.5vw] font-semibold text-gray-700" id="current-date"></p>
                </div>
            </div>
        </div>

        <!-- Contenido Principal: 3 columnas -->
        <div class="flex-1 flex min-h-0 overflow-hidden" style="gap: 8px; padding: 8px;">

            <!-- Columna Izquierda: Turnos Anteriores -->
            <div class="flex flex-col min-h-0 flex-shrink-0" style="width: 20%;">
                <div class="bg-white bg-opacity-10 backdrop-blur-lg rounded-2xl flex flex-col h-full overflow-hidden border border-white border-opacity-10">
                    <div class="bg-gradient-to-r from-indigo-600 to-indigo-500 rounded-t-2xl px-4 py-[1.2vh] flex-shrink-0">
                        <h2 class="text-[1.3vw] font-bold text-center uppercase tracking-wider">Turnos Anteriores</h2>
                    </div>
                    <div class="flex-1 overflow-y-auto p-[0.5vw] space-y-[0.8vh]" id="ultimos-turnos">
                        @if($ultimosTurnos->count() > 0)
                            @foreach($ultimosTurnos as $turno)
                                <div class="turno-card slide-in">
                                    <div class="flex items-center justify-between">
                                        <div class="text-left flex-1">
                                            <p class="text-[2.2vw] font-bold leading-none">{{ $turno->codigo }}</p>
                                            @if($turno->prioridad === 'embarazada')
                                                <span class="inline-block text-[0.7vw] bg-pink-500 text-white px-2 py-0.5 rounded-full mt-1">Preferencial</span>
                                            @elseif($turno->prioridad === 'tercera_edad')
                                                <span class="inline-block text-[0.7vw] bg-amber-500 text-white px-2 py-0.5 rounded-full mt-1">Preferencial</span>
                                            @elseif($turno->prioridad === 'discapacidad')
                                                <span class="inline-block text-[0.7vw] bg-blue-400 text-white px-2 py-0.5 rounded-full mt-1">Preferencial</span>
                                            @endif
                                        </div>
                                        <div class="text-right">
                                            <p class="text-[0.7vw] opacity-60 uppercase">Caja</p>
                                            <p class="text-[2vw] font-bold leading-none">{{ $turno->caja?->numero ?? '-' }}</p>
                                        </div>
                                    </div>
                                    @if($turno->nombre_completo)
                                        <p class="text-[1.8vw] opacity-60 mt-1 truncate">{{ $turno->nombre_completo }}</p>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <div class="flex items-center justify-center h-full">
                                <p class="text-[1.1vw] opacity-40 text-center">Sin turnos</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Columna Central: Turno Actual + Video -->
            <div class="flex-1 flex flex-col min-h-0 min-w-0 overflow-hidden" style="gap: 8px;">

                <!-- Turno Actual -->
                <div class="flex-shrink-0" style="height: 20vh;">
                    <!-- Estado: turno visible -->
                    <div id="turno-actual-display" class="bg-white rounded-2xl shadow-2xl h-full flex items-center justify-center pulse-animation" style="padding: 1vh 2vw; {{ $turnoActual ? '' : 'display:none' }}">
                        <div class="w-full">
                            <p class="text-gray-400 text-[0.9vw] uppercase tracking-widest text-center mb-[0.5vh]">Turno Actual</p>
                            <div class="flex items-center justify-center gap-[3vw]">
                                <div class="text-indigo-600 font-black leading-none" id="turno-actual" style="font-size: 5.5vw;">
                                    {{ $turnoActual?->codigo ?? '' }}
                                </div>
                                <div class="text-gray-300 font-light leading-none" style="font-size: 5vw;">|</div>
                                <div class="font-black leading-none" style="font-size: 5.5vw;">
                                    <span class="text-gray-400">Módulo </span><span class="text-indigo-600" id="caja-actual">{{ $turnoActual?->caja?->numero ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="text-center">
                                <p class="text-gray-500 font-bold" id="nombre-actual" style="font-size: 4vw;">
                                    {{ $turnoActual?->nombre_completo ?? '' }}
                                </p>
                                <div class="mb-[0.3vh]" id="prioridad-actual">
                                    @if($turnoActual && $turnoActual->prioridad === 'embarazada')
                                        <span class="inline-flex items-center px-4 py-1 rounded-full text-[0.75vw] font-semibold bg-pink-100 text-pink-800">Preferencial - Embarazada</span>
                                    @elseif($turnoActual && $turnoActual->prioridad === 'tercera_edad')
                                        <span class="inline-flex items-center px-4 py-1 rounded-full text-[0.75vw] font-semibold bg-amber-100 text-amber-800">Preferencial - Adulto Mayor</span>
                                    @elseif($turnoActual && $turnoActual->prioridad === 'discapacidad')
                                        <span class="inline-flex items-center px-4 py-1 rounded-full text-[0.75vw] font-semibold bg-blue-100 text-blue-800">Preferencial - Discapacidad</span>
                                    @endif
                                </div>
                                <p class="text-gray-500 text-[1vw]" id="tramite-actual">
                                    {{ $turnoActual?->tipoTramite?->nombre ?? '-' }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Estado: esperando -->
                    <div id="turno-esperando" class="bg-white rounded-2xl shadow-2xl h-full flex items-center justify-center" style="padding: 1vh 2vw; {{ $turnoActual ? 'display:none' : '' }}">
                        <div class="text-center">
                            <svg class="mx-auto text-gray-300 mb-[1vh]" style="width:4vw;height:4vw;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-gray-400 text-[1.5vw] font-light">En espera del próximo turno...</p>
                        </div>
                    </div>
                </div>

                <!-- Video Publicitario -->
                <div class="flex-1 min-h-0">
                    <div class="bg-black rounded-2xl h-full overflow-hidden" id="video-container">
                        <video id="video-publicitario" class="w-full h-full object-contain" autoplay muted loop playsinline>
                            <source src="{{ asset('videos/publicidad.mp4') }}" type="video/mp4">
                        </video>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Próximos Turnos -->
            <div class="flex flex-col min-h-0 flex-shrink-0" style="width: 20%; gap: 8px;">
                <!-- Próximos Turnos -->
                <div class="flex-1 flex flex-col min-h-0">
                    <div class="bg-white bg-opacity-10 backdrop-blur-lg rounded-2xl flex flex-col h-full overflow-hidden border border-white border-opacity-10">
                        <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-t-2xl px-4 py-[1.2vh] flex-shrink-0">
                            <h2 class="text-[1.3vw] font-bold text-center uppercase tracking-wider">Próximos Turnos</h2>
                        </div>
                        <div class="flex-1 overflow-y-auto p-[0.5vw] space-y-[0.8vh]" id="proximos-turnos">
                            @if($proximosTurnos->count() > 0)
                                @foreach($proximosTurnos as $turno)
                                    <div class="turno-card slide-in">
                                        <div class="flex items-center justify-between">
                                            <div class="text-left flex-1">
                                                <p class="text-[2.2vw] font-bold leading-none">{{ $turno->codigo }}</p>
                                                @if($turno->prioridad === 'embarazada')
                                                    <span class="inline-block text-[0.7vw] bg-pink-500 text-white px-2 py-0.5 rounded-full mt-1">Preferencial</span>
                                                @elseif($turno->prioridad === 'tercera_edad')
                                                    <span class="inline-block text-[0.7vw] bg-amber-500 text-white px-2 py-0.5 rounded-full mt-1">Preferencial</span>
                                                @elseif($turno->prioridad === 'discapacidad')
                                                    <span class="inline-block text-[0.7vw] bg-blue-400 text-white px-2 py-0.5 rounded-full mt-1">Preferencial</span>
                                                @endif
                                            </div>
                                        </div>
                                        @if($turno->nombre_completo)
                                            <p class="text-[0.8vw] opacity-60 mt-1 truncate">{{ $turno->nombre_completo }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <div class="flex items-center justify-center h-full">
                                    <p class="text-[1.1vw] opacity-40 text-center">Sin turnos en espera</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Footer con Marquee -->
        <div class="bg-black bg-opacity-60 flex-shrink-0 overflow-hidden" style="height: 4vh; min-height: 32px; display: flex; align-items: center;">
            <p class="marquee-text text-[1.1vw] font-medium">
                Bienvenidos a la Alcaldía de San José de Cúcuta &nbsp;&mdash;&nbsp; Por favor, esté atento al llamado de su turno &nbsp;&mdash;&nbsp; Recuerde tener sus documentos a la mano para agilizar su trámite
            </p>
        </div>
    </div>

    <!-- Banner pequeño de audio (no bloquea la pantalla) -->
    <div id="audio-banner" class="fixed top-0 left-0 right-0 z-50 bg-red-600 text-white text-center py-2 text-lg font-bold cursor-pointer" style="display:none;" onclick="activarAudio()">
        Toque aqui o presione cualquier tecla para activar el audio
    </div>

    <!-- Audio para TTS del servidor (fallback para WebOS) -->
    <audio id="tts-audio" preload="none"></audio>

    <script>
        // ============================================================
        // AUDIO - Compatible con WebOS (LG TV) y navegadores modernos
        // ============================================================
        let audioActivado = false;
        let audioCtx = null;
        const ttsAudio = document.getElementById('tts-audio');

        // --- Activar audio (necesita interacción del usuario en Chrome) ---
        function activarAudio() {
            if (audioActivado) return;

            try {
                // Crear o reanudar AudioContext
                if (!audioCtx) {
                    audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                }
                if (audioCtx.state === 'suspended') {
                    audioCtx.resume();
                }

                // Desbloquear <audio> reproduciéndolo brevemente
                ttsAudio.src = 'data:audio/wav;base64,UklGRiQAAABXQVZFZm10IBAAAAABAAEARKwAAIhYAQACABAAZGF0YQAAAAA=';
                ttsAudio.play().then(() => {
                    ttsAudio.pause();
                    ttsAudio.currentTime = 0;
                }).catch(() => {});

                // Desbloquear speechSynthesis con utterance vacía
                if (window.speechSynthesis) {
                    const silencio = new SpeechSynthesisUtterance('');
                    silencio.volume = 0;
                    window.speechSynthesis.speak(silencio);
                }

                audioActivado = true;
                document.getElementById('audio-banner').style.display = 'none';
                console.log('[Audio] Activado correctamente');

                // Remover listeners
                document.removeEventListener('click', activarAudio);
                document.removeEventListener('touchstart', activarAudio);
                document.removeEventListener('keydown', activarAudio);
            } catch(e) {
                console.warn('[Audio] Error al activar:', e);
            }
        }

        // --- Registrar listeners para activar audio con interacción ---
        document.addEventListener('click', activarAudio);
        document.addEventListener('touchstart', activarAudio);
        document.addEventListener('keydown', activarAudio);

        // --- Intentar auto-activar (funciona en WebOS y algunos navegadores) ---
        try {
            audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            if (audioCtx.state === 'running') {
                // AudioContext arrancó sin restricciones (WebOS, algunos navegadores)
                audioActivado = true;
                console.log('[Audio] Auto-activado (sin restricciones)');
            } else {
                audioCtx.resume().then(() => {
                    if (audioCtx.state === 'running') {
                        audioActivado = true;
                        console.log('[Audio] Auto-activado después de resume()');
                    }
                }).catch(() => {});
            }
        } catch(e) {}

        // Mostrar banner si audio no se activó después de 3 segundos
        setTimeout(() => {
            if (!audioActivado) {
                document.getElementById('audio-banner').style.display = 'block';
                console.log('[Audio] Mostrando banner - se requiere interacción');
            }
        }, 3000);

        // Pre-cargar voces de speechSynthesis
        if (window.speechSynthesis) {
            window.speechSynthesis.getVoices();
            window.speechSynthesis.addEventListener('voiceschanged', () => {
                console.log('[Audio] Voces cargadas:', window.speechSynthesis.getVoices().length);
            });
        }

        // --- Generar beep con Web Audio API ---
        function generarBeep() {
            if (!audioCtx || audioCtx.state !== 'running') return Promise.resolve();
            return new Promise((resolve) => {
                try {
                    const osc = audioCtx.createOscillator();
                    const gain = audioCtx.createGain();
                    osc.connect(gain);
                    gain.connect(audioCtx.destination);

                    osc.frequency.setValueAtTime(880, audioCtx.currentTime);
                    osc.frequency.setValueAtTime(1100, audioCtx.currentTime + 0.15);
                    osc.frequency.setValueAtTime(880, audioCtx.currentTime + 0.30);

                    gain.gain.setValueAtTime(0.5, audioCtx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.45);

                    osc.start(audioCtx.currentTime);
                    osc.stop(audioCtx.currentTime + 0.45);
                    osc.onended = resolve;
                } catch(e) {
                    resolve();
                }
            });
        }

        // --- Reproducir voz con speechSynthesis ---
        function hablarConSpeechSynthesis(texto) {
            return new Promise((resolve, reject) => {
                if (!window.speechSynthesis) { reject('no disponible'); return; }

                window.speechSynthesis.cancel();

                const utter = new SpeechSynthesisUtterance(texto);
                utter.lang = 'es-CO';
                utter.rate = 0.85;
                utter.pitch = 1.0;
                utter.volume = 1.0;

                const voices = window.speechSynthesis.getVoices();
                const vozEs = voices.find(v => v.lang === 'es-CO') ||
                              voices.find(v => v.lang === 'es-MX') ||
                              voices.find(v => v.lang.startsWith('es'));
                if (vozEs) utter.voice = vozEs;

                utter.onend = resolve;
                utter.onerror = (e) => {
                    console.warn('[Audio] speechSynthesis error:', e.error);
                    reject(e.error);
                };

                window.speechSynthesis.speak(utter);
                console.log('[Audio] Hablando con speechSynthesis');

                // Timeout de seguridad: si no termina en 10s, resolver igual
                setTimeout(resolve, 10000);
            });
        }

        // --- Reproducir voz TTS desde el servidor (fallback para WebOS) ---
        function reproducirTTSServidor(texto) {
            return new Promise((resolve) => {
                const ttsUrl = '{{ route("pantalla.tts") }}?texto=' + encodeURIComponent(texto);
                console.log('[Audio] Usando TTS servidor:', ttsUrl);

                ttsAudio.oncanplaythrough = function() {
                    ttsAudio.oncanplaythrough = null;
                    ttsAudio.play().then(resolve).catch(() => resolve());
                };
                ttsAudio.onerror = function() {
                    console.warn('[Audio] Error cargando TTS servidor');
                    resolve();
                };
                ttsAudio.src = ttsUrl;
                ttsAudio.load();

                // Timeout de seguridad
                setTimeout(resolve, 8000);
            });
        }

        // ============================================================
        // COLA DE ANUNCIOS - Garantiza que todos los turnos suenen
        // incluso si 2 módulos llaman al tiempo
        // ============================================================
        let colaAnuncios = [];
        let anunciando = false;
        let turnosAnunciados = new Set();

        // Pre-cargar IDs de turnos ya existentes (no anunciar al cargar)
        @if($turnoActual)
            turnosAnunciados.add({{ $turnoActual->id }});
        @endif
        @foreach($ultimosTurnos as $turno)
            turnosAnunciados.add({{ $turno->id }});
        @endforeach

        function encolarAnuncio(turno) {
            if (turnosAnunciados.has(turno.id)) return;
            turnosAnunciados.add(turno.id);
            colaAnuncios.push(turno);
            console.log('[Cola] Turno encolado:', turno.codigo, '| Cola:', colaAnuncios.length);
            procesarColaAnuncios();
        }

        async function procesarColaAnuncios() {
            if (anunciando || colaAnuncios.length === 0) return;

            anunciando = true;

            while (colaAnuncios.length > 0) {
                const turno = colaAnuncios.shift();
                console.log('[Cola] Procesando turno:', turno.codigo, '| Restantes:', colaAnuncios.length);

                // Mostrar este turno en la pantalla mientras se anuncia
                mostrarTurnoEnDisplay(turno);

                const modulo = turno.caja ? turno.caja.numero : null;

                try {
                    await anunciarTurnoAsync(turno.codigo, modulo);
                } catch(e) {
                    console.error('[Cola] Error anunciando:', e);
                }

                // Pausa entre anuncios para que no se mezclen
                if (colaAnuncios.length > 0) {
                    await new Promise(r => setTimeout(r, 2000));
                }
            }

            anunciando = false;
        }

        // --- Mostrar turno en el display central ---
        function mostrarTurnoEnDisplay(turno) {
            const displayEl = document.getElementById('turno-actual-display');
            const esperandoEl = document.getElementById('turno-esperando');

            if (displayEl) displayEl.style.display = '';
            if (esperandoEl) esperandoEl.style.display = 'none';

            const turnoActualEl = document.getElementById('turno-actual');
            const cajaActualEl = document.getElementById('caja-actual');
            const nombreActualEl = document.getElementById('nombre-actual');
            const tramiteActualEl = document.getElementById('tramite-actual');
            const prioridadEl = document.getElementById('prioridad-actual');

            if (turnoActualEl) turnoActualEl.textContent = turno.codigo;
            if (cajaActualEl) cajaActualEl.textContent = turno.caja ? turno.caja.numero : '-';
            if (nombreActualEl) nombreActualEl.textContent = turno.nombre_completo || '';
            if (tramiteActualEl) tramiteActualEl.textContent = turno.tipo_tramite ? turno.tipo_tramite.nombre : '-';

            if (prioridadEl) {
                if (turno.prioridad === 'embarazada') {
                    prioridadEl.innerHTML = '<span class="inline-flex items-center px-4 py-1 rounded-full text-[0.75vw] font-semibold bg-pink-100 text-pink-800">Preferencial - Embarazada</span>';
                } else if (turno.prioridad === 'tercera_edad') {
                    prioridadEl.innerHTML = '<span class="inline-flex items-center px-4 py-1 rounded-full text-[0.75vw] font-semibold bg-amber-100 text-amber-800">Preferencial - Adulto Mayor</span>';
                } else if (turno.prioridad === 'discapacidad') {
                    prioridadEl.innerHTML = '<span class="inline-flex items-center px-4 py-1 rounded-full text-[0.75vw] font-semibold bg-blue-100 text-blue-800">Preferencial - Discapacidad</span>';
                } else {
                    prioridadEl.innerHTML = '';
                }
            }
        }

        // --- Anunciar turno (async - espera a que termine) ---
        async function anunciarTurnoAsync(codigo, modulo) {
            const codigoHablado = codigo.replace(/([A-Za-z]+)([0-9]+)/, '$1 $2');
            let texto = `Turno ${codigoHablado}`;
            if (modulo !== null && modulo !== undefined) {
                texto += `, diríjase al módulo ${modulo}`;
            }

            console.log('[Audio] Anunciando:', texto, '| audioActivado:', audioActivado);

            await generarBeep();
            await new Promise(r => setTimeout(r, 300));

            try {
                await hablarConSpeechSynthesis(texto);
            } catch(e) {
                console.log('[Audio] Fallback a TTS servidor');
                await reproducirTTSServidor(texto);
            }
        }

        // ============================================================
        // RELOJ
        // ============================================================
        function updateDateTime() {
            const now = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            document.getElementById('current-date').textContent = now.toLocaleDateString('es-CO', options);
            document.getElementById('current-time').textContent = now.toLocaleTimeString('es-CO', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        }

        updateDateTime();
        setInterval(updateDateTime, 1000);

        // ============================================================
        // POLLING DE TURNOS
        // ============================================================
        async function actualizarTurnos() {
            try {
                const response = await fetch('{{ route("pantalla.actualizar") }}');
                const data = await response.json();

                // Detectar TODOS los turnos nuevos llamados (resuelve el problema
                // de 2 módulos llamando al tiempo)
                if (data.turnosLlamados && data.turnosLlamados.length > 0) {
                    // Ordenar por hora_llamado ASC para anunciar en orden cronológico
                    const ordenados = [...data.turnosLlamados].sort((a, b) => {
                        return new Date(a.hora_llamado) - new Date(b.hora_llamado);
                    });
                    ordenados.forEach(turno => {
                        encolarAnuncio(turno);
                    });
                }

                // Actualizar display del turno actual (solo si no hay anuncio en curso)
                if (!anunciando) {
                    if (data.turnoActual) {
                        mostrarTurnoEnDisplay(data.turnoActual);
                    } else {
                        // Mostrar estado de espera
                        const displayEl = document.getElementById('turno-actual-display');
                        const esperandoEl = document.getElementById('turno-esperando');
                        if (displayEl) displayEl.style.display = 'none';
                        if (esperandoEl) esperandoEl.style.display = '';
                    }
                }

                // Actualizar últimos turnos
                if (data.ultimosTurnos) {
                    const container = document.getElementById('ultimos-turnos');
                    container.innerHTML = '';

                    if (data.ultimosTurnos.length > 0) {
                        data.ultimosTurnos.forEach(turno => {
                            const div = document.createElement('div');
                            div.className = 'turno-card slide-in';

                            let prioridadBadge = '';
                            if (turno.prioridad === 'embarazada') {
                                prioridadBadge = '<span class="inline-block text-[0.7vw] bg-pink-500 text-white px-2 py-0.5 rounded-full mt-1">Preferencial</span>';
                            } else if (turno.prioridad === 'tercera_edad') {
                                prioridadBadge = '<span class="inline-block text-[0.7vw] bg-amber-500 text-white px-2 py-0.5 rounded-full mt-1">Preferencial</span>';
                            } else if (turno.prioridad === 'discapacidad') {
                                prioridadBadge = '<span class="inline-block text-[0.7vw] bg-blue-400 text-white px-2 py-0.5 rounded-full mt-1">Preferencial</span>';
                            }

                            let nombreHtml = turno.nombre_completo ? `<p class="text-[0.8vw] opacity-60 mt-1 truncate">${turno.nombre_completo}</p>` : '';

                            div.innerHTML = `
                                <div class="flex items-center justify-between">
                                    <div class="text-left flex-1">
                                        <p class="text-[2.2vw] font-bold leading-none">${turno.codigo}</p>
                                        ${prioridadBadge}
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[0.7vw] opacity-60 uppercase">Caja</p>
                                        <p class="text-[2vw] font-bold leading-none">${turno.caja ? turno.caja.numero : '-'}</p>
                                    </div>
                                </div>
                                ${nombreHtml}
                            `;
                            container.appendChild(div);
                        });
                    } else {
                        container.innerHTML = '<div class="flex items-center justify-center h-full"><p class="text-[1.1vw] opacity-40 text-center">Sin turnos</p></div>';
                    }
                }

                // Actualizar próximos turnos
                if (data.proximosTurnos) {
                    const container = document.getElementById('proximos-turnos');
                    container.innerHTML = '';

                    if (data.proximosTurnos.length > 0) {
                        data.proximosTurnos.forEach(turno => {
                            const div = document.createElement('div');
                            div.className = 'turno-card slide-in';

                            let prioridadBadge = '';
                            if (turno.prioridad === 'embarazada') {
                                prioridadBadge = '<span class="inline-block text-[0.7vw] bg-pink-500 text-white px-2 py-0.5 rounded-full mt-1">Preferencial</span>';
                            } else if (turno.prioridad === 'tercera_edad') {
                                prioridadBadge = '<span class="inline-block text-[0.7vw] bg-amber-500 text-white px-2 py-0.5 rounded-full mt-1">Preferencial</span>';
                            } else if (turno.prioridad === 'discapacidad') {
                                prioridadBadge = '<span class="inline-block text-[0.7vw] bg-blue-400 text-white px-2 py-0.5 rounded-full mt-1">Preferencial</span>';
                            }

                            let nombreHtml = turno.nombre_completo ? `<p class="text-[0.8vw] opacity-60 mt-1 truncate">${turno.nombre_completo}</p>` : '';

                            div.innerHTML = `
                                <div class="flex items-center justify-between">
                                    <div class="text-left flex-1">
                                        <p class="text-[2.2vw] font-bold leading-none">${turno.codigo}</p>
                                        ${prioridadBadge}
                                    </div>
                                </div>
                                ${nombreHtml}
                            `;
                            container.appendChild(div);
                        });
                    } else {
                        container.innerHTML = '<div class="flex items-center justify-center h-full"><p class="text-[1.1vw] opacity-40 text-center">Sin turnos en espera</p></div>';
                    }
                }
            } catch (error) {
                console.error('Error al actualizar turnos:', error);
            }
        }

        // Actualizar cada 3 segundos
        setInterval(actualizarTurnos, 3000);

        // Primera actualización inmediata
        setTimeout(actualizarTurnos, 1000);

        // Recargar página cada 5 minutos (solo si no hay anuncio en curso)
        setTimeout(function recargar() {
            if (!anunciando && colaAnuncios.length === 0) {
                window.location.reload();
            } else {
                setTimeout(recargar, 30000);
            }
        }, 300000);
    </script>
</body>
</html>
