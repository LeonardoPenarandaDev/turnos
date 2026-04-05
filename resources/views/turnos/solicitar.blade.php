<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Solicitar Turno</title>
    @vite(['resources/css/app.css'])
    <style>
        * { box-sizing: border-box; }
        html, body {
            overflow: hidden;
            width: 100vw;
            height: 100vh;
            margin: 0;
            padding: 0;
            touch-action: manipulation;
            -webkit-user-select: none;
            user-select: none;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', system-ui, sans-serif;
        }
        input, select { font-size: 16px !important; }

        /* Teclas estilo iOS/Android */
        .kb-key {
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 1px 3px rgba(0,0,0,0.3);
            -webkit-tap-highlight-color: transparent;
            transition: transform 0.05s, background 0.05s;
        }
        .kb-key:active {
            background: #d1d5db;
            transform: scale(0.95);
        }
        .kb-key-dark {
            background: #4b5563;
            color: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.4);
        }
        .kb-key-dark:active {
            background: #6b7280;
            transform: scale(0.95);
        }
        .kb-key-accent {
            background: #4f46e5;
            color: #fff;
            box-shadow: 0 1px 3px rgba(79,70,229,0.4);
        }
        .kb-key-accent:active {
            background: #6366f1;
            transform: scale(0.95);
        }

        .field-active {
            border-color: #4f46e5 !important;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.25) !important;
            background: #f5f3ff !important;
        }

        /* Prioridad cards */
        .prio-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 8px 16px;
            background: #fff;
            cursor: pointer;
            transition: all 0.15s;
        }
        .prio-card.active-normal { border-color: #6366f1; background: #eef2ff; box-shadow: 0 0 0 2px #6366f1; }
        .prio-card.active-embarazada { border-color: #ec4899; background: #fdf2f8; box-shadow: 0 0 0 2px #ec4899; }
        .prio-card.active-tercera_edad { border-color: #f59e0b; background: #fffbeb; box-shadow: 0 0 0 2px #f59e0b; }
        .prio-card.active-discapacidad { border-color: #3b82f6; background: #eff6ff; box-shadow: 0 0 0 2px #3b82f6; }
    </style>
</head>
<body class="bg-white">
    <div class="w-screen h-screen flex flex-col">

        <!-- ===== HEADER ===== -->
        <div class="flex-shrink-0 bg-gradient-to-r from-indigo-700 to-indigo-500 text-white px-8 py-3 flex items-center justify-between shadow-lg">
            <div>
                <h1 class="text-2xl font-bold tracking-tight">Solicitar Turno</h1>
                <p class="text-indigo-200 text-xs">Alcaldía de San José de Cúcuta</p>
            </div>
            <div class="text-right">
                <p class="text-2xl font-bold tabular-nums" id="reloj"></p>
            </div>
        </div>

        <!-- Mensajes flash -->
        @if(session('success'))
            <div id="flash-msg" class="flex-shrink-0 bg-emerald-500 text-white text-center py-3 text-lg font-bold shadow">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div id="flash-msg" class="flex-shrink-0 bg-red-500 text-white text-center py-3 text-lg font-bold shadow">
                {{ session('error') }}
            </div>
        @endif

        <!-- ===== FORMULARIO (parte superior) ===== -->
        <div class="flex-shrink-0 bg-gray-50 border-b border-gray-200">
            <form method="POST" action="{{ route('turno.store') }}" id="turno-form" class="px-8 py-4">
                @csrf

                <!-- Fila 1: Documento + Nombre -->
                <div class="flex gap-4 mb-4">
                    <div style="width: 180px;" class="flex-shrink-0">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Tipo Doc.</label>
                        <select name="tipo_documento" id="tipo_documento" required
                            class="w-full px-3 py-3 border-2 border-gray-200 rounded-xl bg-white text-gray-800 font-medium @error('tipo_documento') border-red-400 @enderror">
                            <option value="">Seleccione</option>
                            <option value="CC" {{ old('tipo_documento') == 'CC' ? 'selected' : '' }}>C.C.</option>
                            <option value="TI" {{ old('tipo_documento') == 'TI' ? 'selected' : '' }}>T.I.</option>
                            <option value="CE" {{ old('tipo_documento') == 'CE' ? 'selected' : '' }}>C.E.</option>
                            <option value="PAS" {{ old('tipo_documento') == 'PAS' ? 'selected' : '' }}>Pasaporte</option>
                        </select>
                    </div>

                    <div class="flex-1">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">N&uacute;mero de Documento</label>
                        <input type="text" id="numero_documento" name="numero_documento"
                            value="{{ old('numero_documento') }}" required readonly
                            placeholder="Toque para ingresar su documento"
                            data-keyboard="numeric"
                            class="kb-input w-full px-4 py-3 border-2 border-gray-200 rounded-xl bg-white text-gray-800 font-medium cursor-pointer placeholder-gray-400 @error('numero_documento') border-red-400 @enderror">
                    </div>

                    <div class="flex-1">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Nombre Completo</label>
                        <input type="text" id="nombre_completo" name="nombre_completo"
                            value="{{ old('nombre_completo') }}" required readonly
                            placeholder="Toque para ingresar su nombre"
                            data-keyboard="alpha"
                            class="kb-input w-full px-4 py-3 border-2 border-gray-200 rounded-xl bg-white text-gray-800 font-medium cursor-pointer placeholder-gray-400 @error('nombre_completo') border-red-400 @enderror">
                    </div>
                </div>

                <!-- Fila 2: Prioridad + Trámite + Botón -->
                <div class="flex gap-4 items-end">
                    <div class="flex-shrink-0">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Atenci&oacute;n</label>
                        <div class="flex gap-2" id="prioridad-options">
                            <label class="prio-card" data-value="normal">
                                <input type="radio" name="prioridad" value="normal" class="sr-only" {{ old('prioridad', 'normal') == 'normal' ? 'checked' : '' }}>
                                <span class="text-2xl leading-none">🙎</span>
                                <span class="text-[11px] font-bold text-gray-700 mt-0.5">Normal</span>
                            </label>
                            <label class="prio-card" data-value="embarazada">
                                <input type="radio" name="prioridad" value="embarazada" class="sr-only" {{ old('prioridad') == 'embarazada' ? 'checked' : '' }}>
                                <span class="text-2xl leading-none">🤰</span>
                                <span class="text-[11px] font-bold text-gray-700 mt-0.5">Embarazada</span>
                            </label>
                            <label class="prio-card" data-value="tercera_edad">
                                <input type="radio" name="prioridad" value="tercera_edad" class="sr-only" {{ old('prioridad') == 'tercera_edad' ? 'checked' : '' }}>
                                <span class="text-2xl leading-none">🧓</span>
                                <span class="text-[11px] font-bold text-gray-700 mt-0.5">Adulto Mayor</span>
                            </label>
                            <label class="prio-card" data-value="discapacidad">
                                <input type="radio" name="prioridad" value="discapacidad" class="sr-only" {{ old('prioridad') == 'discapacidad' ? 'checked' : '' }}>
                                <span class="text-2xl leading-none">♿</span>
                                <span class="text-[11px] font-bold text-gray-700 mt-0.5">Discapacidad</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex-1">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Tipo de Tr&aacute;mite</label>
                        <select name="tipo_tramite_id" id="tipo_tramite_id" required
                            class="w-full px-3 py-3 border-2 border-gray-200 rounded-xl bg-white text-gray-800 font-medium @error('tipo_tramite_id') border-red-400 @enderror">
                            <option value="">Seleccione el trámite...</option>
                            @foreach($tiposTramite as $tipo)
                                <option value="{{ $tipo->id }}" {{ old('tipo_tramite_id') == $tipo->id ? 'selected' : '' }}>{{ $tipo->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex-shrink-0">
                        <button type="submit"
                            style="background-color: #111827; color: #ffffff;"
                            class="font-bold py-3 px-12 rounded-xl text-lg shadow-lg whitespace-nowrap">
                            GENERAR TURNO
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- ===== BARRA DE EDICION ===== -->
        <div class="flex-shrink-0 bg-white border-b border-gray-100 px-8 py-2 flex items-center gap-4" id="edit-bar">
            <span class="text-sm font-bold text-gray-400" id="kb-field-label">Seleccione un campo para escribir</span>
            <span class="text-xl font-bold text-indigo-600 tracking-wide truncate" id="kb-field-value"></span>
        </div>

        <!-- ===== TECLADO VIRTUAL (parte inferior) ===== -->
        <div class="flex-1 bg-gradient-to-b from-gray-700 to-gray-800 p-3 flex flex-col min-h-0" id="keyboard-area">

            <!-- Teclado numérico -->
            <div id="kb-numeric" class="flex-1 flex flex-col gap-2 min-h-0" style="display:none;">
                <!-- Centrar el teclado numérico para que no se estire a lo ancho -->
                <div class="flex-1 flex justify-center">
                    <div class="flex flex-col gap-2 h-full" style="width: 480px; max-width: 50%;">
                        <div class="flex-1 grid grid-cols-3 gap-2">
                            <button type="button" class="kb-key text-3xl" onclick="kbType('1')">1</button>
                            <button type="button" class="kb-key text-3xl" onclick="kbType('2')">2</button>
                            <button type="button" class="kb-key text-3xl" onclick="kbType('3')">3</button>
                        </div>
                        <div class="flex-1 grid grid-cols-3 gap-2">
                            <button type="button" class="kb-key text-3xl" onclick="kbType('4')">4</button>
                            <button type="button" class="kb-key text-3xl" onclick="kbType('5')">5</button>
                            <button type="button" class="kb-key text-3xl" onclick="kbType('6')">6</button>
                        </div>
                        <div class="flex-1 grid grid-cols-3 gap-2">
                            <button type="button" class="kb-key text-3xl" onclick="kbType('7')">7</button>
                            <button type="button" class="kb-key text-3xl" onclick="kbType('8')">8</button>
                            <button type="button" class="kb-key text-3xl" onclick="kbType('9')">9</button>
                        </div>
                        <div class="flex-1 grid grid-cols-3 gap-2">
                            <button type="button" class="kb-key kb-key-dark text-sm font-bold" onclick="kbClear()">LIMPIAR</button>
                            <button type="button" class="kb-key text-3xl" onclick="kbType('0')">0</button>
                            <button type="button" class="kb-key kb-key-dark text-xl" onclick="kbBackspace()">⌫</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Teclado QWERTY -->
            <div id="kb-alpha" class="flex-1 flex flex-col gap-1.5 min-h-0" style="display:none;">
                <!-- Fila Q-P -->
                <div class="flex-1 flex gap-1.5 justify-center">
                    <button type="button" class="kb-key text-xl flex-1" onclick="kbType('Q')">Q</button>
                    <button type="button" class="kb-key text-xl flex-1" onclick="kbType('W')">W</button>
                    <button type="button" class="kb-key text-xl flex-1" onclick="kbType('E')">E</button>
                    <button type="button" class="kb-key text-xl flex-1" onclick="kbType('R')">R</button>
                    <button type="button" class="kb-key text-xl flex-1" onclick="kbType('T')">T</button>
                    <button type="button" class="kb-key text-xl flex-1" onclick="kbType('Y')">Y</button>
                    <button type="button" class="kb-key text-xl flex-1" onclick="kbType('U')">U</button>
                    <button type="button" class="kb-key text-xl flex-1" onclick="kbType('I')">I</button>
                    <button type="button" class="kb-key text-xl flex-1" onclick="kbType('O')">O</button>
                    <button type="button" class="kb-key text-xl flex-1" onclick="kbType('P')">P</button>
                </div>
                <!-- Fila A-Ñ -->
                <div class="flex-1 flex gap-1.5 justify-center px-[3%]">
                    <button type="button" class="kb-key text-xl flex-1" onclick="kbType('A')">A</button>
                    <button type="button" class="kb-key text-xl flex-1" onclick="kbType('S')">S</button>
                    <button type="button" class="kb-key text-xl flex-1" onclick="kbType('D')">D</button>
                    <button type="button" class="kb-key text-xl flex-1" onclick="kbType('F')">F</button>
                    <button type="button" class="kb-key text-xl flex-1" onclick="kbType('G')">G</button>
                    <button type="button" class="kb-key text-xl flex-1" onclick="kbType('H')">H</button>
                    <button type="button" class="kb-key text-xl flex-1" onclick="kbType('J')">J</button>
                    <button type="button" class="kb-key text-xl flex-1" onclick="kbType('K')">K</button>
                    <button type="button" class="kb-key text-xl flex-1" onclick="kbType('L')">L</button>
                    <button type="button" class="kb-key text-xl flex-1" onclick="kbType('Ñ')">Ñ</button>
                </div>
                <!-- Fila Z-M + Borrar -->
                <div class="flex-1 flex gap-1.5 justify-center">
                    <button type="button" class="kb-key kb-key-dark text-sm" style="flex:1.5" onclick="kbClear()">LIMPIAR</button>
                    <button type="button" class="kb-key text-xl flex-1" onclick="kbType('Z')">Z</button>
                    <button type="button" class="kb-key text-xl flex-1" onclick="kbType('X')">X</button>
                    <button type="button" class="kb-key text-xl flex-1" onclick="kbType('C')">C</button>
                    <button type="button" class="kb-key text-xl flex-1" onclick="kbType('V')">V</button>
                    <button type="button" class="kb-key text-xl flex-1" onclick="kbType('B')">B</button>
                    <button type="button" class="kb-key text-xl flex-1" onclick="kbType('N')">N</button>
                    <button type="button" class="kb-key text-xl flex-1" onclick="kbType('M')">M</button>
                    <button type="button" class="kb-key kb-key-dark text-lg" style="flex:1.5" onclick="kbBackspace()">⌫ Borrar</button>
                </div>
                <!-- Fila Espacio -->
                <div class="flex-1 flex gap-1.5 justify-center px-[15%]">
                    <button type="button" class="kb-key text-base flex-1 tracking-widest" onclick="kbType(' ')">ESPACIO</button>
                </div>
            </div>

            <!-- Placeholder -->
            <div id="kb-placeholder" class="flex-1 flex items-center justify-center">
                <div class="text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-500 mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    <p class="text-gray-400 text-xl">Toque un campo del formulario para escribir</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Reloj
        function updateReloj() {
            const now = new Date();
            document.getElementById('reloj').textContent =
                now.toLocaleTimeString('es-CO', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        }
        updateReloj();
        setInterval(updateReloj, 1000);

        // Prioridad
        function updatePrioridadUI() {
            document.querySelectorAll('.prio-card').forEach(card => {
                const radio = card.querySelector('input[type="radio"]');
                const val = card.dataset.value;
                // Quitar todas las clases activas
                card.classList.remove('active-normal', 'active-embarazada', 'active-tercera_edad', 'active-discapacidad');
                if (radio.checked) {
                    card.classList.add('active-' + val);
                }
            });
        }
        document.querySelectorAll('.prio-card input').forEach(r => r.addEventListener('change', updatePrioridadUI));
        updatePrioridadUI();

        // Teclado virtual
        let campoActivo = null;
        const fieldLabels = {
            'numero_documento': 'Documento',
            'nombre_completo': 'Nombre'
        };

        document.querySelectorAll('.kb-input').forEach(input => {
            input.addEventListener('click', function(e) {
                e.preventDefault();
                activarCampo(this);
            });
            input.addEventListener('focus', function(e) {
                e.preventDefault();
                this.blur();
            });
        });

        function activarCampo(input) {
            if (campoActivo) campoActivo.classList.remove('field-active');
            campoActivo = input;
            campoActivo.classList.add('field-active');

            const tipo = input.dataset.keyboard;
            document.getElementById('kb-field-label').textContent = fieldLabels[input.id] + ':';
            actualizarVistaPrevia();

            document.getElementById('kb-numeric').style.display = tipo === 'numeric' ? 'flex' : 'none';
            document.getElementById('kb-alpha').style.display = tipo === 'alpha' ? 'flex' : 'none';
            document.getElementById('kb-placeholder').style.display = 'none';
        }

        function actualizarVistaPrevia() {
            const el = document.getElementById('kb-field-value');
            if (campoActivo) {
                el.textContent = campoActivo.value || '';
            }
        }

        function kbType(char) {
            if (!campoActivo) return;
            campoActivo.value += char;
            actualizarVistaPrevia();
        }

        function kbBackspace() {
            if (!campoActivo || !campoActivo.value) return;
            campoActivo.value = campoActivo.value.slice(0, -1);
            actualizarVistaPrevia();
        }

        function kbClear() {
            if (!campoActivo) return;
            campoActivo.value = '';
            actualizarVistaPrevia();
        }

        // Ocultar flash
        const flashEl = document.getElementById('flash-msg');
        if (flashEl) setTimeout(() => flashEl.style.display = 'none', 5000);
    </script>
</body>
</html>
