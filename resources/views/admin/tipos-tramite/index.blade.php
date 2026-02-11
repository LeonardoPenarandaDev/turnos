<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Tipos de Tramite
            </h2>
            <button onclick="document.getElementById('modal-crear-tipo').classList.remove('hidden')"
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                Agregar Nuevo
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
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Descripcion</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($tipos as $tipo)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $tipo->id }}</td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $tipo->nombre }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ Str::limit($tipo->descripcion, 50) }}</td>
                                    <td class="px-6 py-4">
                                        @if($tipo->activo)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Activo</span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Inactivo</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium">
                                        <button onclick="editarTipo({{ $tipo->id }}, '{{ addslashes($tipo->nombre) }}', '{{ addslashes($tipo->descripcion) }}', {{ $tipo->activo ? 'true' : 'false' }})"
                                            class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</button>
                                        <form method="POST" action="{{ route('admin.tipos-tramite.destroy', $tipo->id) }}" class="inline"
                                            onsubmit="return confirm('Eliminar este tipo de tramite?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Crear -->
    <div id="modal-crear-tipo" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
            <h3 class="text-lg font-semibold mb-4">Nuevo Tipo de Tramite</h3>
            <form method="POST" action="{{ route('admin.tipos-tramite.store') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                    <input type="text" name="nombre" required class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descripcion</label>
                    <textarea name="descripcion" rows="3" class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="document.getElementById('modal-crear-tipo').classList.add('hidden')"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded">Cancelar</button>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Editar -->
    <div id="modal-editar-tipo" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
            <h3 class="text-lg font-semibold mb-4">Editar Tipo de Tramite</h3>
            <form id="form-editar-tipo" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                    <input type="text" name="nombre" id="edit-tipo-nombre" required class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descripcion</label>
                    <textarea name="descripcion" id="edit-tipo-descripcion" rows="3" class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                    <select name="activo" id="edit-tipo-activo" class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="document.getElementById('modal-editar-tipo').classList.add('hidden')"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded">Cancelar</button>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Actualizar</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function editarTipo(id, nombre, descripcion, activo) {
            document.getElementById('form-editar-tipo').action = `/admin/tipos-tramite/${id}`;
            document.getElementById('edit-tipo-nombre').value = nombre;
            document.getElementById('edit-tipo-descripcion').value = descripcion;
            document.getElementById('edit-tipo-activo').value = activo ? '1' : '0';
            document.getElementById('modal-editar-tipo').classList.remove('hidden');
        }
    </script>
    @endpush
</x-app-layout>