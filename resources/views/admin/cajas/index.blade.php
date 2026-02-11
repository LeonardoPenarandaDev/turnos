<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestion de Cajas
            </h2>
            <button onclick="document.getElementById('modal-crear-caja').classList.remove('hidden')"
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                Agregar Nueva Caja
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">{{ session('error') }}</div>
            @endif

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
                                            <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>Activa
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-200 text-gray-800">Inactiva</span>
                                    @endif
                                </div>

                                <div class="flex space-x-2">
                                    <button onclick="editarCaja({{ $caja->id }}, {{ $caja->numero }}, '{{ addslashes($caja->nombre) }}', {{ $caja->activa ? 'true' : 'false' }})"
                                        class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded text-sm">
                                        Editar
                                    </button>
                                    <form method="POST" action="{{ route('admin.cajas.destroy', $caja->id) }}" class="flex-1"
                                        onsubmit="return confirm('Eliminar esta caja?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded text-sm">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Crear -->
    <div id="modal-crear-caja" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
            <h3 class="text-lg font-semibold mb-4">Nueva Caja</h3>
            <form method="POST" action="{{ route('admin.cajas.store') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Numero</label>
                    <input type="number" name="numero" min="1" required class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                    <input type="text" name="nombre" required placeholder="Ej: Caja 6 - Atencion Especial" class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="document.getElementById('modal-crear-caja').classList.add('hidden')"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded">Cancelar</button>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Editar -->
    <div id="modal-editar-caja" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
            <h3 class="text-lg font-semibold mb-4">Editar Caja</h3>
            <form id="form-editar-caja" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Numero</label>
                    <input type="number" name="numero" id="edit-caja-numero" min="1" required class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                    <input type="text" name="nombre" id="edit-caja-nombre" required class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                    <select name="activa" id="edit-caja-activa" class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="1">Activa</option>
                        <option value="0">Inactiva</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="document.getElementById('modal-editar-caja').classList.add('hidden')"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded">Cancelar</button>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Actualizar</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function editarCaja(id, numero, nombre, activa) {
            document.getElementById('form-editar-caja').action = `/admin/cajas/${id}`;
            document.getElementById('edit-caja-numero').value = numero;
            document.getElementById('edit-caja-nombre').value = nombre;
            document.getElementById('edit-caja-activa').value = activa ? '1' : '0';
            document.getElementById('modal-editar-caja').classList.remove('hidden');
        }
    </script>
    @endpush
</x-app-layout>