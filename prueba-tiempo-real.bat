@echo off
title Sistema de Turnos - Prueba en Tiempo Real
color 0B
cls

echo.
echo ========================================
echo   PRUEBA DE TIEMPO REAL
echo ========================================
echo.
echo Este script te ayudara a probar el
echo sistema de actualizacion en tiempo real
echo.
echo PASOS A SEGUIR:
echo.
echo 1. Abre la Pantalla Publica en tu navegador:
echo    http://localhost:8000/pantalla-publica
echo.
echo 2. Abre el Panel de Cajero en otra pestana:
echo    http://localhost:8000/login
echo    Email: maria.gonzalez@cucuta.gov.co
echo    Password: cajero123
echo.
echo 3. Este script generara turnos automaticamente
echo.
echo ========================================
echo.
pause

:MENU
cls
echo.
echo ========================================
echo   MENU DE PRUEBAS
echo ========================================
echo.
echo 1. Generar 1 turno
echo 2. Generar 3 turnos
echo 3. Generar 5 turnos
echo 4. Generar 10 turnos
echo 5. Salir
echo.
echo ========================================
echo.

set /p opcion="Selecciona una opcion (1-5): "

if "%opcion%"=="1" (
    echo.
    echo Generando 1 turno...
    php artisan turno:generar 1
    echo.
    echo Turno generado! Ve al panel de cajero y llamalo.
    pause
    goto MENU
)

if "%opcion%"=="2" (
    echo.
    echo Generando 3 turnos...
    php artisan turno:generar 3
    echo.
    echo Turnos generados! Ve al panel de cajero y llamalos.
    pause
    goto MENU
)

if "%opcion%"=="3" (
    echo.
    echo Generando 5 turnos...
    php artisan turno:generar 5
    echo.
    echo Turnos generados! Ve al panel de cajero y llamalos.
    pause
    goto MENU
)

if "%opcion%"=="4" (
    echo.
    echo Generando 10 turnos...
    php artisan turno:generar 10
    echo.
    echo Turnos generados! Ve al panel de cajero y llamalos.
    pause
    goto MENU
)

if "%opcion%"=="5" (
    echo.
    echo Saliendo...
    exit
)

echo Opcion invalida
pause
goto MENU
