<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestion de Usuarios
            </h2>
            <button onclick="document.getElementById('modal-crear-usuario').classList.remove('hidden')"
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                Agregar Usuario
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
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rol</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Caja Asignada</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($usuarios as $usuario)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                                <span class="text-indigo-600 font-semibold">{{ substr($usuario->name, 0, 2) }}</span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $usuario->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $usuario->email }}</td>
                                    <td class="px-6 py-4">
                                        @if($usuario->rol === 'admin')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">Administrador</span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Cajero</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $usuario->caja ? 'Caja ' . $usuario->caja->numero : '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium">
                                        <button onclick="editarUsuario({{ $usuario->id }}, '{{ addslashes($usuario->name) }}', '{{ addslashes($usuario->email) }}', '{{ $usuario->rol }}', '{{ $usuario->caja_id }}')"
                                            class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</button>
                                        @if($usuario->id !== auth()->id())
                                            <form method="POST" action="{{ route('admin.usuarios.destroy', $usuario->id) }}" class="inline"
                                                onsubmit="return confirm('Eliminar este usuario?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                                            </form>
                                        @endif
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
    <div id="modal-crear-usuario" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
            <h3 class="text-lg font-semibold mb-4">Nuevo Usuario</h3>
            <form method="POST" action="{{ route('admin.usuarios.store') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                    <input type="text" name="name" required class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" required class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contrasena</label>
                    <input type="password" name="password" required minlength="6" class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rol</label>
                    <select name="rol" required class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="cajero">Cajero</option>
                        <option value="admin">Administrador</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Caja Asignada</label>
                    <select name="caja_id" class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Sin caja</option>
                        @foreach($cajas as $caja)
                            <option value="{{ $caja->id }}">Caja {{ $caja->numero }} - {{ $caja->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="document.getElementById('modal-crear-usuario').classList.add('hidden')"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded">Cancelar</button>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Editar -->
    <div id="modal-editar-usuario" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
            <h3 class="text-lg font-semibold mb-4">Editar Usuario</h3>
            <form id="form-editar-usuario" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                    <input type="text" name="name" id="edit-user-name" required class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="edit-user-email" required class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contrasena (dejar vacio para no cambiar)</label>
                    <input type="password" name="password" minlength="6" class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rol</label>
                    <select name="rol" id="edit-user-rol" required class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="cajero">Cajero</option>
                        <option value="admin">Administrador</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Caja Asignada</label>
                    <select name="caja_id" id="edit-user-caja" class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Sin caja</option>
                        @foreach($cajas as $caja)
                            <option value="{{ $caja->id }}">Caja {{ $caja->numero }} - {{ $caja->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="document.getElementById('modal-editar-usuario').classList.add('hidden')"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded">Cancelar</button>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Actualizar</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function editarUsuario(id, name, email, rol, cajaId) {
            document.getElementById('form-editar-usuario').action = `/admin/usuarios/${id}`;
            document.getElementById('edit-user-name').value = name;
            document.getElementById('edit-user-email').value = email;
            document.getElementById('edit-user-rol').value = rol;
            document.getElementById('edit-user-caja').value = cajaId || '';
            document.getElementById('modal-editar-usuario').classList.remove('hidden');
        }
    </script>
    @endpush
</x-app-layout>