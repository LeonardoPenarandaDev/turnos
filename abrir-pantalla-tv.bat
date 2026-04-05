@echo off
:: ====================================================
:: Abre la pantalla publica de turnos en Chrome
:: con autoplay de audio y voz habilitado
:: ====================================================

:: Ruta de Chrome (ajusta si tu instalacion es diferente)
set CHROME="C:\Program Files\Google\Chrome\Application\chrome.exe"
if not exist %CHROME% set CHROME="C:\Program Files (x86)\Google\Chrome\Application\chrome.exe"

:: URL de la pantalla publica
set URL=http://127.0.0.1:8000/pantalla

echo Abriendo pantalla de turnos con audio automatico...

start "" %CHROME% ^
  --autoplay-policy=no-user-gesture-required ^
  --disable-features=AutoplayIgnoreWebAudio ^
  --kiosk ^
  --no-first-run ^
  --disable-infobars ^
  --disable-session-crashed-bubble ^
  --noerrdialogs ^
  %URL%

echo Listo! La pantalla deberia abrirse en modo kiosco.
