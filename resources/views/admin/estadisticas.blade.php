<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Estadísticas del Sistema
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Estadísticas por Tipo de Trámite -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Trámites Más Solicitados (Últimos 30 días)</h3>
                    <div class="space-y-4">
                        @foreach($tramiteStats as $stat)
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm font-medium text-gray-700">{{ $stat->nombre }}</span>
                                    <span class="text-sm font-semibold text-indigo-600">{{ $stat->turnos_count }} turnos</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    @php
                                        $maxTurnos = $tramiteStats->max('turnos_count') ?: 1;
                                        $percentage = ($stat->turnos_count / $maxTurnos) * 100;
                                    @endphp
                                    <div class="bg-indigo-600 h-3 rounded-full" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Estadísticas por Cajero -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Rendimiento de Cajeros (Hoy)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        @foreach($cajeroStats as $cajero)
                            <div class="border border-gray-200 rounded-lg p-4 text-center">
                                <div class="mb-3">
                                    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto">
                                        <span class="text-2xl font-bold text-indigo-600">{{ substr($cajero->name, 0, 2) }}</span>
                                    </div>
                                </div>
                                <p class="text-sm font-medium text-gray-900 mb-1">{{ $cajero->name }}</p>
                                <p class="text-xs text-gray-500 mb-3">
                                    {{ $cajero->caja ? 'Caja ' . $cajero->caja->numero : 'Sin caja' }}
                                </p>
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <p class="text-3xl font-bold text-indigo-600">{{ $cajero->turnos_count }}</p>
                                    <p class="text-xs text-gray-600">turnos atendidos</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Información Adicional -->
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-white text-center">
                    <svg class="mx-auto h-16 w-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <h3 class="text-2xl font-bold mb-2">Métricas en Tiempo Real</h3>
                    <p class="text-indigo-100">Las estadísticas se actualizan automáticamente</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
