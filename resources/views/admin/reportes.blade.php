<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Reportes Diarios
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filtro de Fecha -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('admin.reportes') }}" class="flex items-center space-x-4">
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

            <!-- Estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-600 mb-1">Total Turnos</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $estadisticas['total'] }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-600 mb-1">Atendidos</p>
                    <p class="text-3xl font-bold text-green-600">{{ $estadisticas['atendidos'] }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-600 mb-1">Cancelados</p>
                    <p class="text-3xl font-bold text-red-600">{{ $estadisticas['cancelados'] }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-600 mb-1">Tiempo Promedio</p>
                    <p class="text-3xl font-bold text-blue-600">
                        {{ $estadisticas['tiempo_promedio'] ? gmdate("i:s", $estadisticas['tiempo_promedio']) : '00:00' }}
                    </p>
                </div>
            </div>

            <!-- Tabla de Turnos -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Detalle de Turnos</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Código</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trámite</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Caja</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cajero</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tiempo</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($turnos as $turno)
                                    <tr>
                                        <td class="px-4 py-3 text-sm font-semibold text-gray-900">{{ $turno->codigo }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $turno->tipoTramite->nombre }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">
                                            {{ $turno->caja ? 'Caja ' . $turno->caja->numero : '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600">
                                            {{ $turno->cajero->name ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($turno->estado === 'atendido')
                                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Atendido</span>
                                            @elseif($turno->estado === 'cancelado')
                                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Cancelado</span>
                                            @else
                                                <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">{{ ucfirst($turno->estado) }}</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600">
                                            {{ $turno->tiempo_atencion ? gmdate("i:s", $turno->tiempo_atencion) : '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                            No hay turnos para esta fecha
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
