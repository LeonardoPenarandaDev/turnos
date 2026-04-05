<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Estadísticas del Sistema
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Filtro de Periodo -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-wrap items-end gap-4">
                        <!-- Botones de periodo rápido -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Periodo</label>
                            <div class="flex rounded-lg overflow-hidden border border-gray-300">
                                <a href="{{ route('admin.estadisticas', ['periodo' => 'hoy']) }}"
                                   class="px-4 py-2 text-sm font-medium {{ $periodo === 'hoy' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
                                    Hoy
                                </a>
                                <a href="{{ route('admin.estadisticas', ['periodo' => 'semana']) }}"
                                   class="px-4 py-2 text-sm font-medium border-l border-gray-300 {{ $periodo === 'semana' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
                                    Esta Semana
                                </a>
                                <a href="{{ route('admin.estadisticas', ['periodo' => 'mes']) }}"
                                   class="px-4 py-2 text-sm font-medium border-l border-gray-300 {{ $periodo === 'mes' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
                                    Este Mes
                                </a>
                            </div>
                        </div>

                        <!-- Rango personalizado -->
                        <form method="GET" action="{{ route('admin.estadisticas') }}" class="flex items-end gap-3">
                            <input type="hidden" name="periodo" value="personalizado">
                            <div>
                                <label for="desde" class="block text-sm font-medium text-gray-700 mb-2">Desde</label>
                                <input type="date" id="desde" name="desde"
                                       value="{{ $periodo === 'personalizado' ? $desde->format('Y-m-d') : '' }}"
                                       class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label for="hasta" class="block text-sm font-medium text-gray-700 mb-2">Hasta</label>
                                <input type="date" id="hasta" name="hasta"
                                       value="{{ $periodo === 'personalizado' ? $hasta->format('Y-m-d') : '' }}"
                                       class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg text-sm">
                                Filtrar
                            </button>
                        </form>
                    </div>

                    <p class="text-sm text-gray-500 mt-3">
                        Mostrando datos:
                        <span class="font-medium text-gray-700">
                            @if($periodo === 'hoy')
                                {{ $desde->translatedFormat('l, d \d\e F \d\e Y') }}
                            @else
                                {{ $desde->format('d/m/Y') }} — {{ $hasta->format('d/m/Y') }}
                            @endif
                        </span>
                    </p>
                </div>
            </div>

            <!-- Resumen General -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-600 mb-1">Total Turnos</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $resumen['total'] }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-600 mb-1">Atendidos</p>
                    <p class="text-3xl font-bold text-green-600">{{ $resumen['atendidos'] }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-600 mb-1">Cancelados</p>
                    <p class="text-3xl font-bold text-red-600">{{ $resumen['cancelados'] }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-600 mb-1">Tiempo Promedio</p>
                    <p class="text-3xl font-bold text-blue-600">
                        {{ $resumen['tiempo_promedio'] ? gmdate("i:s", (int) $resumen['tiempo_promedio']) : '00:00' }}
                    </p>
                </div>
            </div>

            <!-- Rendimiento por Cajero -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Rendimiento por Cajero</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cajero</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Caja</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Atendidos</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Cancelados</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Tiempo Promedio</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Tiempo Total</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase" style="min-width: 200px;">Rendimiento</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @php
                                    $maxAtendidos = $cajeroStats->max('atendidos') ?: 1;
                                @endphp
                                @forelse($cajeroStats as $cajero)
                                    <tr>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                                                    <span class="text-xs font-bold text-indigo-600">{{ strtoupper(substr($cajero->name, 0, 2)) }}</span>
                                                </div>
                                                <span class="text-sm font-medium text-gray-900">{{ $cajero->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600">
                                            {{ $cajero->caja ? 'Caja ' . $cajero->caja->numero : 'Sin caja' }}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                                {{ $cajero->atendidos }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-semibold {{ $cajero->cancelados > 0 ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-600' }}">
                                                {{ $cajero->cancelados }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-center text-sm text-gray-700 font-medium">
                                            {{ $cajero->tiempo_promedio ? gmdate("i:s", (int) $cajero->tiempo_promedio) : '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-center text-sm text-gray-700 font-medium">
                                            @if($cajero->tiempo_total)
                                                @php
                                                    $h = floor($cajero->tiempo_total / 3600);
                                                    $m = floor(($cajero->tiempo_total % 3600) / 60);
                                                    $s = $cajero->tiempo_total % 60;
                                                @endphp
                                                {{ $h > 0 ? $h . 'h ' : '' }}{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}m {{ str_pad($s, 2, '0', STR_PAD_LEFT) }}s
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                                <div class="bg-indigo-600 h-2.5 rounded-full" style="width: {{ ($cajero->atendidos / $maxAtendidos) * 100 }}%"></div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                            No hay cajeros registrados
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Trámites Más Solicitados -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Trámites Más Solicitados</h3>
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
        </div>
    </div>
</x-app-layout>
