<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestión de Cajas
            </h2>
            <button class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                Agregar Nueva Caja
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($cajas as $caja)
                            <div class="border rounded-lg p-6 {{ $caja->activa ? 'border-green-300 bg-green-50' : 'border-gray-300 bg-gray-50' }}">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h3 class="text-2xl font-bold text-gray-900">Caja {{ $caja->numero }}</h3>
                                        <p class="text-sm text-gray-600">{{ $caja->nombre }}</p>
                                    </div>
                                    @if($caja->activa)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                            Activa
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-200 text-gray-800">
                                            Inactiva
                                        </span>
                                    @endif
                                </div>

                                <div class="flex space-x-2">
                                    <button class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded text-sm">
                                        Editar
                                    </button>
                                    <button class="flex-1 {{ $caja->activa ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white py-2 px-4 rounded text-sm">
                                        {{ $caja->activa ? 'Desactivar' : 'Activar' }}
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
