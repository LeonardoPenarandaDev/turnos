<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pantalla Pública - Turnos</title>
    @vite(['resources/css/app.css'])
    <style>
        body {
            overflow: hidden;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        .pulse-animation {
            animation: pulse 2s ease-in-out infinite;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-900 to-indigo-900 text-white">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <div class="bg-white text-gray-900 py-6 px-8 shadow-lg">
            <div class="flex justify-between items-center max-w-7xl mx-auto">
                <div>
                    <h1 class="text-3xl font-bold">Alcaldía de Cúcuta</h1>
                    <p class="text-gray-600">Sistema de Gestión de Turnos</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-600">Fecha</p>
                    <p class="text-xl font-semibold" id="current-date"></p>
                    <p class="text-2xl font-bold text-indigo-600" id="current-time"></p>
                </div>
            </div>
        </div>

        <!-- Contenido Principal -->
        <div class="flex-1 p-8">
            <div class="max-w-7xl mx-auto">
                <!-- Turno Actual -->
                @if($turnoActual)
                    <div class="bg-white rounded-3xl shadow-2xl p-12 mb-8 pulse-animation">
                        <div class="text-center">
                            <p class="text-gray-600 text-2xl mb-4">Turno Actual</p>
                            <div class="text-indigo-600 text-9xl font-black mb-6" id="turno-actual">
                                {{ $turnoActual->codigo }}
                            </div>
                            <p class="text-gray-500 text-2xl mb-6" id="nombre-actual">
                                {{ $turnoActual->nombre_completo ?? '' }}
                            </p>
                            <div class="flex justify-center items-center space-x-8 text-gray-900">
                                <div class="bg-indigo-100 rounded-xl px-8 py-4">
                                    <p class="text-gray-600 text-lg mb-1">Caja</p>
                                    <p class="text-5xl font-bold text-indigo-600" id="caja-actual">
                                        {{ $turnoActual->caja?->numero ?? '-' }}
                                    </p>
                                </div>
                                <div class="text-left">
                                    <p class="text-gray-600 text-xl mb-2">Trámite</p>
                                    <p class="text-3xl font-semibold" id="tramite-actual">
                                        {{ $turnoActual->tipoTramite?->nombre ?? '-' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-white rounded-3xl shadow-2xl p-12 mb-8 text-center">
                        <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-gray-600 text-3xl">En espera del próximo turno...</p>
                    </div>
                @endif

                <!-- Últimos Turnos Llamados -->
                <div class="bg-white bg-opacity-10 backdrop-blur-lg rounded-3xl p-8">
                    <h2 class="text-3xl font-bold mb-6 text-center">Últimos Turnos Llamados</h2>

                    @if($ultimosTurnos->count() > 0)
                        <div class="grid grid-cols-5 gap-4" id="ultimos-turnos">
                            @foreach($ultimosTurnos as $turno)
                                <div class="bg-white bg-opacity-20 rounded-xl p-6 text-center">
                                    <p class="text-5xl font-bold mb-2">{{ $turno->codigo }}</p>
                                    <p class="text-base opacity-80 mb-1">{{ $turno->nombre_completo ?? '' }}</p>
                                    <p class="text-lg opacity-90">Caja {{ $turno->caja?->numero ?? '-' }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center text-2xl opacity-75">No hay turnos recientes</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="bg-black bg-opacity-30 py-4 px-8 text-center">
            <p class="text-xl">Por favor, esté atento al llamado de su turno</p>
        </div>
    </div>

    <!-- Audio para notificación -->
    <audio id="notification-sound" preload="auto">
        <source src="data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSuBzvLZiTYIGGG18+WZVRMKUKzl8K5dGAg2ltnyxHUpBSl+zPDeijkJFmS58OWcTxALUqjo8K1bGAgxlN3xxXMoBSaAzfHaizsKGGC38OScTQ8LTKXk8LJgGgc0mtvyw3ElBSiAzfHajDcJGGC38OSaTRALTqvm8LFeGAgvm9rzwXMoBSZ/y/HcizsKF160+PmgShUMTKnn8bVdFgo5odv1xG4hBSJ6ye/glEQPClWs5O+kWhoMLqXh8r1tIQUsfszx3oYxCRVhu+/mnEwQC1Gs5O+qUxoLM5Td8sRzJQUpg87x2ok0CBdfu+/lmU8PC0+p5fCqVRkLM5Tc8sNzJQUogM3w2oo2CRdduuvktFYaCzOU3fLDcyUFKIDN8NqLNgkXXbbs5bNXGgoxlN3xxXMoBSiAzPDbizYJF1247eeyWBkLMZTe8MVwKQUngMvw3Yk3CRVduuznslgZCjGU3vDGbykFJ3/K8d6JOQoWYrbt5rJXGAowlNzwxnApBSd+yvHej" type="audio/wav">
    </audio>

    <script>
        // Actualizar fecha y hora
        function updateDateTime() {
            const now = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            document.getElementById('current-date').textContent = now.toLocaleDateString('es-CO', options);
            document.getElementById('current-time').textContent = now.toLocaleTimeString('es-CO', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        }

        updateDateTime();
        setInterval(updateDateTime, 1000);

        // Variables para detectar cambios
        let ultimoTurnoCodigo = '{{ $turnoActual?->codigo ?? "" }}';
        let actualizandoVisual = false;

        // Actualizar turnos desde el servidor
        async function actualizarTurnos() {
            try {
                const response = await fetch('{{ route("pantalla.actualizar") }}');
                const data = await response.json();

                console.log('Datos recibidos:', data); // Debug

                // Actualizar turno actual
                if (data.turnoActual) {
                    const nuevoTurnoCodigo = data.turnoActual.codigo;

                    // Si cambió el turno, reproducir sonido y animar
                    if (nuevoTurnoCodigo !== ultimoTurnoCodigo && ultimoTurnoCodigo !== '') {
                        reproducirSonido();
                        mostrarAnimacionNuevoTurno();
                    }

                    ultimoTurnoCodigo = nuevoTurnoCodigo;

                    // Actualizar DOM solo si los elementos existen
                    const turnoActualEl = document.getElementById('turno-actual');
                    const cajaActualEl = document.getElementById('caja-actual');
                    const tramiteActualEl = document.getElementById('tramite-actual');
                    const nombreActualEl = document.getElementById('nombre-actual');

                    if (turnoActualEl) turnoActualEl.textContent = data.turnoActual.codigo;
                    if (nombreActualEl) nombreActualEl.textContent = data.turnoActual.nombre_completo || '';
                    if (cajaActualEl) cajaActualEl.textContent = data.turnoActual.caja ? data.turnoActual.caja.numero : '-';
                    if (tramiteActualEl) tramiteActualEl.textContent = data.turnoActual.tipo_tramite ? data.turnoActual.tipo_tramite.nombre : '-';

                    // Si la página muestra "en espera", recargar para mostrar el turno
                    if (!turnoActualEl) {
                        window.location.reload();
                    }
                } else {
                    // No hay turno actual
                    ultimoTurnoCodigo = '';
                }

                // Actualizar últimos turnos
                if (data.ultimosTurnos && data.ultimosTurnos.length > 0) {
                    const container = document.getElementById('ultimos-turnos');
                    container.innerHTML = '';

                    data.ultimosTurnos.forEach(turno => {
                        const div = document.createElement('div');
                        div.className = 'bg-white bg-opacity-20 rounded-xl p-6 text-center';
                        div.innerHTML = `
                            <p class="text-5xl font-bold mb-2">${turno.codigo}</p>
                            <p class="text-base opacity-80 mb-1">${turno.nombre_completo || ''}</p>
                            <p class="text-lg opacity-90">Caja ${turno.caja ? turno.caja.numero : '-'}</p>
                        `;
                        container.appendChild(div);
                    });
                }
            } catch (error) {
                console.error('Error al actualizar turnos:', error);
            }
        }

        function reproducirSonido() {
            const audio = document.getElementById('notification-sound');
            audio.play().catch(e => console.log('No se pudo reproducir el sonido:', e));
        }

        function mostrarAnimacionNuevoTurno() {
            const turnoActualEl = document.getElementById('turno-actual');
            if (turnoActualEl && turnoActualEl.parentElement) {
                turnoActualEl.parentElement.parentElement.parentElement.style.animation = 'none';
                setTimeout(() => {
                    turnoActualEl.parentElement.parentElement.parentElement.style.animation = 'pulse 2s ease-in-out infinite';
                }, 10);
            }
        }

        // Actualizar cada 3 segundos
        setInterval(actualizarTurnos, 3000);

        // Primera actualización inmediata
        setTimeout(actualizarTurnos, 1000);

        // Recargar página completamente cada 5 minutos para evitar memory leaks
        setTimeout(() => {
            window.location.reload();
        }, 300000);
    </script>
</body>
</html>
