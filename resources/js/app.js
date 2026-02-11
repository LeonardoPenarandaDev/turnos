import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Validación de duplicados en tiempo real
document.addEventListener('DOMContentLoaded', function() {
    const tipoDocumentoInput = document.getElementById('tipo_documento');
    const numeroDocumentoInput = document.getElementById('numero_documento');

    if (tipoDocumentoInput && numeroDocumentoInput) {
        let timeoutId;

        function validarDuplicado() {
            const tipoDocumento = tipoDocumentoInput.value;
            const numeroDocumento = numeroDocumentoInput.value;

            if (!tipoDocumento || !numeroDocumento || numeroDocumento.length < 6) {
                return;
            }

            clearTimeout(timeoutId);
            timeoutId = setTimeout(async () => {
                try {
                    const response = await fetch('/turnos/validar-duplicado', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            tipo_documento: tipoDocumento,
                            numero_documento: numeroDocumento
                        })
                    });

                    const data = await response.json();

                    if (data.existe) {
                        mostrarAlerta('Ya existe un turno activo con este documento hoy', 'warning');
                    }
                } catch (error) {
                    console.error('Error al validar duplicado:', error);
                }
            }, 500);
        }

        tipoDocumentoInput.addEventListener('change', validarDuplicado);
        numeroDocumentoInput.addEventListener('input', validarDuplicado);
    }
});

// Función para mostrar alertas
function mostrarAlerta(mensaje, tipo = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `mb-4 p-4 rounded-lg ${
        tipo === 'warning' ? 'bg-yellow-100 border border-yellow-400 text-yellow-700' :
        tipo === 'error' ? 'bg-red-100 border border-red-400 text-red-700' :
        'bg-blue-100 border border-blue-400 text-blue-700'
    }`;
    alertDiv.textContent = mensaje;

    const form = document.querySelector('form');
    if (form) {
        form.insertBefore(alertDiv, form.firstChild);
        setTimeout(() => alertDiv.remove(), 5000);
    }
}

// Auto-hide alerts
setTimeout(() => {
    const alerts = document.querySelectorAll('.alert, [role="alert"]');
    alerts.forEach(alert => {
        if (!alert.classList.contains('permanent')) {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }
    });
}, 5000);
