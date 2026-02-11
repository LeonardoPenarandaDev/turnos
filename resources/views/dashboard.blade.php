<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    Bienvenido, {{ auth()->user()->name }}.

                    @if(auth()->user()->rol === 'cajero' && !auth()->user()->caja_id)
                        <p class="mt-4 text-yellow-700 bg-yellow-50 p-4 rounded-lg">
                            No tiene una caja asignada. Contacte al administrador para que le asigne una caja.
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>