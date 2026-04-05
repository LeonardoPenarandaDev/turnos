<x-guest-layout>
    <div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-100 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl mx-auto">
            <!-- Comprobante -->
            <div class="bg-white shadow-2xl rounded-2xl overflow-hidden print-ticket">
                <!-- Header (solo pantalla) -->
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-8 text-center print-hide-header">
                    <div class="inline-block bg-white rounded-full p-4 mb-4">
                        <svg class="w-16 h-16 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold text-white mb-2">¡Turno Generado!</h1>
                    <p class="text-green-100">Alcaldía de Cúcuta</p>
                </div>

                <!-- Código de Turno -->
                <div style="background-color: #1e1b4b; padding: 2rem 0;">
                    <div class="text-center">
                        <p class="text-indigo-300 text-lg mb-2">Su número de turno es:</p>
                        <div class="font-extrabold text-white tracking-wider" style="font-size: 7rem; line-height: 1;">
                            {{ $turno->codigo }}
                        </div>
                    </div>
                </div>

                <!-- Información del Turno -->
                <div class="p-6 space-y-4">
                    <div class="flex justify-between items-center py-3 border-b">
                        <span class="text-gray-600 font-medium">Documento:</span>
                        <span class="font-semibold text-gray-900">{{ $turno->tipo_documento }} {{ $turno->numero_documento }}</span>
                    </div>

                    <div class="flex justify-between items-center py-3 border-b">
                        <span class="text-gray-600 font-medium">Nombre:</span>
                        <span class="font-semibold text-gray-900">{{ $turno->nombre_completo }}</span>
                    </div>

                    <div class="flex justify-between items-center py-3 border-b">
                        <span class="text-gray-600 font-medium">Trámite:</span>
                        <span class="font-semibold text-gray-900">{{ $turno->tipoTramite->nombre }}</span>
                    </div>

                    <div class="flex justify-between items-center py-3 border-b">
                        <span class="text-gray-600 font-medium">Hora de Solicitud:</span>
                        <span class="font-semibold text-gray-900">{{ $turno->hora_solicitud->format('h:i A') }}</span>
                    </div>

                    <div class="flex justify-between items-center py-3 border-b">
                        <span class="text-gray-600 font-medium">Estado:</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            {{ ucfirst($turno->estado) }}
                        </span>
                    </div>

                    @if($turno->prioridad !== 'normal')
                        <div class="flex justify-between items-center py-3 border-b">
                            <span class="text-gray-600 font-medium">Prioridad:</span>
                            @if($turno->prioridad === 'embarazada')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-pink-100 text-pink-800">
                                    Mujer Embarazada
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-amber-100 text-amber-800">
                                    Adulto Mayor
                                </span>
                            @endif
                        </div>
                    @endif

                    <!-- Información de Espera -->
                    <div class="bg-blue-50 rounded-lg p-4 mt-6 no-print">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-blue-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h3 class="font-semibold text-blue-900 mb-1">Información de Espera</h3>
                                <p class="text-blue-700 text-sm">
                                    Hay <strong>{{ $turnosAdelante }}</strong> turnos adelante.
                                </p>
                                <p class="text-blue-700 text-sm">
                                    Tiempo estimado de espera: <strong>{{ $tiempoEstimado }} minutos</strong>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Instrucciones -->
                    <div class="bg-gray-50 rounded-lg p-4 mt-4 no-print">
                        <h3 class="font-semibold text-gray-900 mb-2">Instrucciones:</h3>
                        <ul class="list-disc list-inside text-gray-700 text-sm space-y-1">
                            <li>Conserve este comprobante</li>
                            <li>Esté atento a la pantalla de llamados</li>
                            <li>Cuando escuche su número, diríjase a la caja indicada</li>
                            <li>Si no se presenta en 3 llamados, su turno será cancelado</li>
                        </ul>
                    </div>
                </div>

                <!-- Botones -->
                <div class="px-6 pb-6 space-y-3 no-print">
                    <button
                        onclick="window.print()"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200"
                    >
                        Imprimir Comprobante
                    </button>

                    <a
                        href="{{ route('turno.create') }}"
                        class="block w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-6 rounded-lg text-center transition duration-200"
                    >
                        Solicitar Nuevo Turno
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Estilos para impresión -->
    <style>
        @media print {
            @page {
                size: 80mm 90mm;
                margin: 0;
            }
            @page :header { display: none; }
            @page :footer { display: none; }
            body, html {
                margin: 0 !important;
                padding: 0 !important;
                background: white !important;
                width: 80mm !important;
                max-height: 90mm !important;
                overflow: hidden !important;
            }
            body * {
                visibility: hidden;
            }
            .print-ticket, .print-ticket * {
                visibility: visible !important;
            }
            .print-ticket {
                position: absolute;
                left: 0;
                top: 0;
                width: 76mm !important;
                max-height: 88mm !important;
                overflow: hidden !important;
                box-shadow: none !important;
                border-radius: 0 !important;
                font-size: 10px !important;
            }
            .no-print {
                display: none !important;
            }
            .print-hide-header {
                display: none !important;
            }
            /* Codigo de turno */
            .print-ticket [style*="background-color: #1e1b4b"] {
                padding: 4mm 0 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .print-ticket [style*="font-size: 7rem"] {
                font-size: 2.5rem !important;
            }
            .print-ticket .text-lg {
                font-size: 11px !important;
            }
            /* Compactar info */
            .print-ticket .p-6 {
                padding: 2mm !important;
            }
            .print-ticket .space-y-4 > * + * {
                margin-top: 1px !important;
            }
            .print-ticket .py-3 {
                padding-top: 1px !important;
                padding-bottom: 1px !important;
            }
            .print-ticket .border-b {
                border-bottom-width: 0.5px !important;
            }
            .print-ticket .font-semibold,
            .print-ticket .font-medium {
                font-size: 10px !important;
            }
        }
    </style>

    <script>
        // Quitar titulo para que no aparezca en la impresion
        var tituloOriginal = document.title;

        window.addEventListener('beforeprint', function () {
            document.title = ' ';
        });

        window.addEventListener('afterprint', function () {
            document.title = tituloOriginal;
            window.location.href = '{{ route("turno.create") }}';
        });

        // Imprimir automáticamente al cargar la página
        window.addEventListener('load', function () {
            setTimeout(function () {
                document.title = ' ';
                window.print();
            }, 500);
        });

        // Fallback: si afterprint no se dispara, redirigir después de 15 segundos
        setTimeout(function () {
            window.location.href = '{{ route("turno.create") }}';
        }, 15000);
    </script>
</x-guest-layout>
