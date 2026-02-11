@echo off
title Sistema de Turnos - Servidor Local
color 0A
echo.
echo ========================================
echo   SISTEMA DE TURNOS - ALCALDIA CUCUTA
echo ========================================
echo.
echo [*] Iniciando servidor Laravel...
echo.
cd /d "%~dp0"
php artisan serve --host=127.0.0.1 --port=8000
