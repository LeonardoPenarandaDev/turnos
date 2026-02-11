<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Reporte Diario
            </h2>
            <a href="{{ route('cajero.panel') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                Volver al Panel
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filtro de Fecha -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('cajero.reporte') }}" class="flex items-center space-x-4">
                        <div class="flex-1">
                            <label for="fecha" class="block text-sm font-medium text-gray-700 mb-2">Seleccionar Fecha</label>
                            <input
                                type="date"
                                id="fecha"
                                name="fecha"
                                value="{{ $fecha }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                            >
                        </div>
                        <div class="pt-6">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg">
                                Filtrar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Resumen -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-600 mb-1">Total Atendidos</p>
                    <p class="text-3xl font-bold text-green-600">{{ $totalAtendidos }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-600 mb-1">Tiempo Promedio</p>
                    <p class="text-3xl font-bold text-blue-600">
                        {{ $tiempoPromedio ? gmdate("i:s", $tiempoPromedio) : '00:00' }}
                    </p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-600 mb-1">Tiempo Total</p>
                    <p class="text-3xl font-bold text-purple-600">
                        {{ $tiempoTotal ? gmdate("H:i:s", $tiempoTotal) : '00:00:00' }}
                    </p>
                </div>
            </div>

            <!-- Tabla de Turnos -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Detalle de Turnos Atendidos</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Codigo</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tramite</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Documento</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tiempo</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Observaciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($turnos as $turno)
                                    <tr>
                                        <td class="px-4 py-3 text-sm font-semibold text-gray-900">{{ $turno->codigo }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $turno->tipoTramite->nombre }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $turno->tipo_documento }} {{ $turno->numero_documento }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">
                                            {{ $turno->tiempo_atencion ? gmdate("i:s", $turno->tiempo_atencion) : '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $turno->observaciones ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                            No hay turnos atendidos para esta fecha
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>