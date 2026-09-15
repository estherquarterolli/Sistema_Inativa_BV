@echo off
setlocal
cd /d "%~dp0"

where php >nul 2>&1
if errorlevel 1 (
    echo PHP nao foi encontrado. Feche esta janela e abra o arquivo novamente.
    pause
    exit /b 1
)

echo Sistema Inativa BV disponivel em http://localhost:8000
echo Para encerrar, pressione Ctrl+C nesta janela.
start "" "http://localhost:8000"
php -S localhost:8000 -t public public/index.php

endlocal
