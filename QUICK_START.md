# 🚀 Inicio Rápido - Sistema de Turnos

## Ejecuta estos comandos en orden:

### 1. Instalar todo
```bash
composer install && npm install
```

### 2. Configurar base de datos
```bash
php artisan migrate
php artisan db:seed
```

### 3. Compilar assets
```bash
npm run build
```

### 4. Iniciar servidor
```bash
php artisan serve
```

## 🎯 Accede a:
- **Solicitar Turno:** http://localhost:8000
- **Panel Cajero:** http://localhost:8000/cajero (maria.gonzalez@cucuta.gov.co / cajero123)
- **Panel Admin:** http://localhost:8000/admin (admin@cucuta.gov.co / admin123)

## ✅ Todo está listo!
- ✅ Backend 100% funcional
- ✅ 2 vistas de ejemplo creadas (solicitar turno y comprobante)
- ✅ Validaciones de seguridad
- ✅ Rate limiting
- ✅ Eventos para tiempo real
- ✅ Datos de prueba

## 📝 Próximo paso:
Completa las demás vistas en `resources/views/`

Lee [SETUP_GUIDE.md](SETUP_GUIDE.md) para detalles completos.
