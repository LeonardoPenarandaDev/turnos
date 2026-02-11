# 📡 Guía de Prueba en Tiempo Real

## 🎯 Objetivo
Probar que la pantalla pública se actualiza automáticamente cuando el cajero llama un turno.

---

## 📋 Preparación (Solo una vez)

### 1. Iniciar el Servidor
```bash
# Opción A: Doble click en
iniciar-servidor.bat

# Opción B: Desde terminal
php artisan serve
```

### 2. Abrir 3 Pestañas del Navegador

**Pestaña 1: Pantalla Pública**
```
http://localhost:8000/pantalla-publica
```
→ Deja esta pestaña visible en la mitad izquierda de tu pantalla

**Pestaña 2: Panel de Cajero**
```
http://localhost:8000/login
Email: maria.gonzalez@cucuta.gov.co
Password: cajero123
```
→ Coloca esta pestaña en la mitad derecha de tu pantalla

**Pestaña 3: Solicitar Turno (Opcional)**
```
http://localhost:8000
```

---

## 🧪 Método 1: Prueba Manual (Recomendado)

### Paso 1: Solicitar un Turno
1. Ve a la pestaña 3: http://localhost:8000
2. Completa el formulario:
   - Tipo: **CC**
   - Número: **12345678**
   - Nombre: **Juan Pérez**
   - Trámite: **Cualquiera**
3. Click **"Generar Turno"**
4. Verás el comprobante con código: **A001**

### Paso 2: Llamar el Turno (Como Cajero)
1. Ve a la pestaña 2 (Panel de Cajero)
2. Click en el botón grande: **"Llamar Siguiente Turno"**
3. El turno **A001** aparecerá en el panel del cajero

### Paso 3: Ver Actualización en Pantalla Pública
1. Ve a la pestaña 1 (Pantalla Pública)
2. **En menos de 3 segundos** verás:
   - El turno **A001** en grande en el centro
   - El número de caja (**Caja 1**)
   - El tipo de trámite
   - El turno también aparecerá en "Últimos Turnos Llamados"

### Paso 4: Probar más turnos
1. Repite los pasos 1-3 con diferentes documentos:
   - **23456789** → Generará **A002**
   - **34567890** → Generará **A003**
   - etc.

2. Cada vez que llames un turno, la pantalla pública se actualizará automáticamente

---

## ⚡ Método 2: Prueba Automática (Rápida)

### Usando el Script de Prueba

1. **Doble click** en: `prueba-tiempo-real.bat`

2. El script te mostrará un menú:
   ```
   1. Generar 1 turno
   2. Generar 3 turnos
   3. Generar 5 turnos
   4. Generar 10 turnos
   5. Salir
   ```

3. Selecciona opción **2** (generar 3 turnos)

4. Ve al **Panel de Cajero** y llama los turnos uno por uno

5. **Observa** cómo la pantalla pública se actualiza automáticamente

### O desde la Terminal

```bash
# Generar 1 turno
php artisan turno:generar 1

# Generar 5 turnos
php artisan turno:generar 5

# Generar 10 turnos
php artisan turno:generar 10
```

---

## 🔍 Verificación

### ✅ El sistema funciona correctamente si:

1. **Pantalla Pública se actualiza sola**
   - No necesitas refrescar (F5)
   - Máximo 3 segundos de espera
   - El turno nuevo aparece automáticamente

2. **Información completa se muestra**
   - Código del turno (ej: A001)
   - Número de caja
   - Tipo de trámite

3. **Últimos turnos se actualizan**
   - Los últimos 5 turnos llamados aparecen abajo
   - Se agregan automáticamente cuando llamas nuevos

4. **Consola del navegador sin errores**
   - Presiona F12 en la pantalla pública
   - Ve a "Console"
   - Deberías ver: "Datos recibidos: {turnoActual: {...}}"
   - **NO** debe haber errores en rojo

---

## 🐛 Solución de Problemas

### Problema: "La pantalla no se actualiza"

**Solución 1: Verificar la consola**
```
1. Presiona F12 en la pantalla pública
2. Ve a la pestaña "Console"
3. Busca el mensaje: "Datos recibidos:"
4. Si aparece cada 3 segundos = está funcionando
5. Si hay errores rojos = cópiamelos
```

**Solución 2: Verificar el endpoint**
```
Abre en el navegador:
http://localhost:8000/api/turnos-actualizados

Debes ver algo como:
{"turnoActual":{"codigo":"A001",...},"ultimosTurnos":[...]}
```

**Solución 3: Limpiar caché**
```bash
php artisan optimize:clear
# Luego refresca la página con Ctrl + Shift + R
```

### Problema: "No hay turnos pendientes"

**Solución:**
```bash
# Genera turnos de prueba
php artisan turno:generar 5

# O usa el formulario público
http://localhost:8000
```

### Problema: "El turno no aparece en la pantalla"

**Verifica:**
1. ¿El turno está en estado "llamado"? (debe cambiar de "pendiente")
2. ¿La consola muestra errores?
3. ¿El servidor sigue corriendo?

---

## 📊 Flujo Completo Visual

```
┌─────────────────┐      ┌──────────────────┐      ┌─────────────────┐
│   CIUDADANO     │      │     CAJERO       │      │ PANTALLA PÚBLICA│
│                 │      │                  │      │                 │
│ 1. Solicita     │──────▶                  │      │                 │
│    Turno (A001) │      │                  │      │                 │
│                 │      │ 2. Llama A001    │──────▶ 3. Actualiza   │
│                 │      │    (click botón) │      │    en 3 seg     │
│                 │      │                  │      │                 │
│ 4. Ve su turno  │◀─────────────────────────────▶│ 5. Muestra A001 │
│    en pantalla  │      │                  │      │    Caja 1       │
└─────────────────┘      └──────────────────┘      └─────────────────┘
```

---

## 🎮 Ejercicio Práctico

### Simula un día de atención:

1. **Genera 10 turnos:**
   ```bash
   php artisan turno:generar 10
   ```

2. **Coloca la pantalla pública en un monitor/TV:**
   ```
   http://localhost:8000/pantalla-publica
   Presiona F11 para pantalla completa
   ```

3. **Como cajero, atiende los turnos:**
   - Llama cada turno
   - Espera 10-20 segundos (simula atención)
   - Finaliza el turno
   - Llama el siguiente

4. **Observa:**
   - Cómo la pantalla pública se actualiza sola
   - Los últimos 5 turnos siempre visibles
   - La hora se actualiza en tiempo real

---

## 💡 Tips

- **Usa 2 monitores** si tienes: pantalla pública en uno, panel cajero en otro
- **Modo pantalla completa** (F11) en la pantalla pública para simular TV
- **Varios cajeros**: Abre múltiples ventanas de incógnito con diferentes cajeros
- **Genera muchos turnos** de una vez para pruebas de carga

---

## ✅ Checklist de Verificación

- [ ] El servidor está corriendo (`php artisan serve`)
- [ ] Pantalla pública abierta: http://localhost:8000/pantalla-publica
- [ ] Panel de cajero con sesión activa
- [ ] Al llamar un turno, aparece en < 3 segundos
- [ ] Los últimos turnos se actualizan automáticamente
- [ ] La hora se actualiza cada segundo
- [ ] No hay errores en la consola (F12)

---

**Si todo está ✅ = ¡El sistema de tiempo real funciona perfectamente!** 🎉
