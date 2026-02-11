<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Panel de Cajero - {{ $caja->nombre }}
            </h2>
            <div class="flex items-center space-x-4">
                <span class="text-sm text-gray-600">{{ auth()->user()->name }}</span>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                    <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                    Caja {{ $caja->numero }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Estadísticas del día -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Atendidos Hoy</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $turnosAtendidosHoy }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-purple-100 rounded-md p-3">
                                <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Tiempo Promedio</p>
                                <p class="text-2xl font-semibold text-gray-900">
                                    {{ $tiempoPromedioHoy ? gmdate("i:s", $tiempoPromedioHoy) : '00:00' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-yellow-100 rounded-md p-3">
                                <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">En Espera</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $turnosPendientes->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Panel de atención -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Turno Actual</h3>

                            @if($turnoActual)
                                <!-- Turno en atención -->
                                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg p-6 text-white mb-4">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <p class="text-sm opacity-90">Atendiendo ahora</p>
                                            <p class="text-6xl font-bold mt-2">{{ $turnoActual->codigo }}</p>
                                        </div>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white bg-opacity-30">
                                            En Atención
                                        </span>
                                    </div>
                                    @if($turnoActual->prioridad !== 'normal')
                                        <div class="mb-3">
                                            @if($turnoActual->prioridad === 'embarazada')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-pink-200 text-pink-900">🤰 Preferencial - Embarazada</span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-amber-200 text-amber-900">🧓 Preferencial - Adulto Mayor</span>
                                            @endif
                                        </div>
                                    @endif
                                    <div class="space-y-2 text-sm">
                                        <p><strong>Trámite:</strong> {{ $turnoActual->tipoTramite->nombre }}</p>
                                        <p><strong>Documento:</strong> {{ $turnoActual->tipo_documento }} {{ $turnoActual->numero_documento }}</p>
                                        @if($turnoActual->nombre_completo)
                                            <p><strong>Nombre:</strong> {{ $turnoActual->nombre_completo }}</p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Acciones del turno actual -->
                                <div class="grid grid-cols-2 gap-3">
                                    <button
                                        onclick="repetirLlamado({{ $turnoActual->id }})"
                                        class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center"
                                    >
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path>
                                        </svg>
                                        Repetir Llamado
                                    </button>

                                    <button
                                        onclick="finalizarAtencion({{ $turnoActual->id }})"
                                        class="bg-green-500 hover:bg-green-600 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center"
                                    >
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Finalizar
                                    </button>

                                    <button
                                        onclick="cancelarTurno({{ $turnoActual->id }})"
                                        class="bg-red-500 hover:bg-red-600 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center"
                                    >
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Cancelar
                                    </button>

                                    <button
                                        onclick="transferirTurno({{ $turnoActual->id }})"
                                        class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center"
                                    >
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                        </svg>
                                        Transferir
                                    </button>
                                </div>
                            @else
                                <!-- Sin turno actual -->
                                <div class="text-center py-12">
                                    <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    <p class="text-gray-500 mb-6">No hay turno en atención</p>

                                    @if($turnosPendientes->count() > 0)
                                        <button
                                            onclick="llamarSiguiente()"
                                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-8 rounded-lg transition duration-200 transform hover:scale-105 shadow-lg"
                                        >
                                            <svg class="inline-block w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                                            </svg>
                                            Llamar Siguiente Turno
                                        </button>
                                    @else
                                        <p class="text-sm text-gray-500">No hay turnos pendientes</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Cola de turnos pendientes -->
                <div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Turnos en Espera</h3>

                            @if($turnosPendientes->count() > 0)
                                <div class="space-y-2">
                                    @foreach($turnosPendientes as $turno)
                                        <div class="border rounded-lg p-3 hover:bg-gray-50 transition {{ $turno->prioridad !== 'normal' ? 'border-pink-300 bg-pink-50' : 'border-gray-200' }}">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <p class="font-bold text-lg text-indigo-600">
                                                        {{ $turno->codigo }}
                                                        @if($turno->prioridad === 'embarazada')
                                                            <span class="text-sm">🤰</span>
                                                        @elseif($turno->prioridad === 'tercera_edad')
                                                            <span class="text-sm">🧓</span>
                                                        @endif
                                                    </p>
                                                    <p class="text-sm text-gray-600">{{ $turno->tipoTramite->nombre }}</p>
                                                </div>
                                                <span class="text-xs text-gray-500">
                                                    {{ $turno->hora_solicitud->format('H:i') }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8 text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                    </svg>
                                    <p class="text-sm">No hay turnos pendientes</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        const defaultHeaders = {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        };

        async function fetchJSON(url, options = {}) {
            const response = await fetch(url, {
                ...options,
                headers: { ...defaultHeaders, ...options.headers }
            });

            if (!response.ok) {
                const data = await response.json().catch(() => ({}));
                throw new Error(data.message || `Error ${response.status}`);
            }

            return response.json();
        }

        async function llamarSiguiente() {
            try {
                const data = await fetchJSON('{{ route("cajero.llamar") }}', { method: 'POST' });
                window.location.reload();
            } catch (error) {
                alert(error.message || 'Error al llamar turno');
            }
        }

        async function repetirLlamado(turnoId) {
            try {
                await fetchJSON(`/cajero/turno/${turnoId}/repetir`, { method: 'POST' });
                alert('Llamado repetido en pantalla publica');
            } catch (error) {
                alert(error.message || 'Error al repetir llamado');
            }
        }

        async function finalizarAtencion(turnoId) {
            const observaciones = prompt('Observaciones (opcional):');
            if (observaciones === null) return;

            try {
                await fetchJSON(`/cajero/turno/${turnoId}/finalizar`, {
                    method: 'POST',
                    body: JSON.stringify({ observaciones })
                });
                window.location.reload();
            } catch (error) {
                alert(error.message || 'Error al finalizar atencion');
            }
        }

        async function cancelarTurno(turnoId) {
            const motivo = prompt('Motivo de cancelacion:');
            if (!motivo || motivo.length < 10) {
                alert('Debe ingresar un motivo de al menos 10 caracteres');
                return;
            }

            if (!confirm('Esta seguro de cancelar este turno?')) return;

            try {
                await fetchJSON(`/cajero/turno/${turnoId}/cancelar`, {
                    method: 'POST',
                    body: JSON.stringify({ motivo })
                });
                window.location.reload();
            } catch (error) {
                alert(error.message || 'Error al cancelar turno');
            }
        }

        async function transferirTurno(turnoId) {
            const cajas = @json($cajasDisponibles);
            if (cajas.length === 0) {
                alert('No hay otras cajas activas disponibles para transferir.');
                return;
            }

            let opciones = cajas.map(c => `${c.id}: ${c.nombre}`).join('\n');
            const cajaId = prompt(`Seleccione el ID de la caja destino:\n${opciones}`);

            if (!cajaId) return;

            const cajaValida = cajas.find(c => c.id == cajaId);
            if (!cajaValida) {
                alert('Caja no valida.');
                return;
            }

            if (!confirm(`Transferir turno a ${cajaValida.nombre}?`)) return;

            try {
                await fetchJSON(`/cajero/turno/${turnoId}/transferir`, {
                    method: 'POST',
                    body: JSON.stringify({ caja_id: parseInt(cajaId) })
                });
                window.location.reload();
            } catch (error) {
                alert(error.message || 'Error al transferir turno');
            }
        }

        // Auto-refresh cada 30 segundos
        setTimeout(() => {
            window.location.reload();
        }, 30000);
    </script>
    @endpush
</x-app-layout>
