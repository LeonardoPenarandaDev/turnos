<x-guest-layout>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">
                    Sistema de Turnos
                </h1>
                <p class="text-xl text-gray-600">
                    Alcaldía de Cúcuta
                </p>
            </div>

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Formulario -->
            <div class="bg-white shadow-2xl rounded-2xl overflow-hidden">
                <div class="bg-indigo-600 px-6 py-4">
                    <h2 class="text-2xl font-bold text-white">Solicitar Turno</h2>
                </div>

                <form method="POST" action="{{ route('turno.store') }}" class="p-6 space-y-6">
                    @csrf

                    <!-- Tipo de Documento -->
                    <div>
                        <label for="tipo_documento" class="block text-sm font-medium text-gray-700 mb-2">
                            Tipo de Documento *
                        </label>
                        <select
                            id="tipo_documento"
                            name="tipo_documento"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('tipo_documento') border-red-500 @enderror"
                        >
                            <option value="">Seleccione...</option>
                            <option value="CC" {{ old('tipo_documento') == 'CC' ? 'selected' : '' }}>Cédula de Ciudadanía</option>
                            <option value="TI" {{ old('tipo_documento') == 'TI' ? 'selected' : '' }}>Tarjeta de Identidad</option>
                            <option value="CE" {{ old('tipo_documento') == 'CE' ? 'selected' : '' }}>Cédula de Extranjería</option>
                            <option value="PAS" {{ old('tipo_documento') == 'PAS' ? 'selected' : '' }}>Pasaporte</option>
                        </select>
                        @error('tipo_documento')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Número de Documento -->
                    <div>
                        <label for="numero_documento" class="block text-sm font-medium text-gray-700 mb-2">
                            Número de Documento *
                        </label>
                        <input
                            type="text"
                            id="numero_documento"
                            name="numero_documento"
                            value="{{ old('numero_documento') }}"
                            required
                            placeholder="Ej: 1234567890"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('numero_documento') border-red-500 @enderror"
                        >
                        @error('numero_documento')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nombre Completo -->
                    <div>
                        <label for="nombre_completo" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre Completo *
                        </label>
                        <input
                            type="text"
                            id="nombre_completo"
                            name="nombre_completo"
                            value="{{ old('nombre_completo') }}"
                            required
                            placeholder="Ej: Juan Pérez García"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('nombre_completo') border-red-500 @enderror"
                        >
                        @error('nombre_completo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tipo de Trámite -->
                    <div>
                        <label for="tipo_tramite_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Tipo de Trámite *
                        </label>
                        <select
                            id="tipo_tramite_id"
                            name="tipo_tramite_id"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('tipo_tramite_id') border-red-500 @enderror"
                        >
                            <option value="">Seleccione el trámite...</option>
                            @foreach($tiposTramite as $tipo)
                                <option value="{{ $tipo->id }}" {{ old('tipo_tramite_id') == $tipo->id ? 'selected' : '' }}>
                                    {{ $tipo->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('tipo_tramite_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Botón Submit -->
                    <div class="pt-4">
                        <button
                            type="submit"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-6 rounded-lg transition duration-200 transform hover:scale-105 shadow-lg"
                        >
                            Generar Turno
                        </button>
                    </div>
                </form>
            </div>

            <!-- Información adicional -->
            <div class="mt-6 text-center text-gray-600 text-sm">
                <p>* Campos obligatorios</p>
                <p class="mt-2">Por favor conserve su comprobante para ser atendido</p>
            </div>
        </div>
    </div>
</x-guest-layout>
