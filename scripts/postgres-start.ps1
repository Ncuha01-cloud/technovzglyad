# Запуск локального портативного PostgreSQL 17 из %LOCALAPPDATA%\pgsql.
# Путь можно переопределить переменной окружения PG_ROOT.
# Этот скрипт нужен, потому что сервер установлен без прав администратора.

# Останавливаем выполнение при любой ошибке.
$ErrorActionPreference = 'Stop'

# Определяем корневую папку PostgreSQL: PG_ROOT или %LOCALAPPDATA%\pgsql.
$root = if ($env:PG_ROOT) { $env:PG_ROOT } else { Join-Path $env:LOCALAPPDATA 'pgsql' }

# Папка с исполняемыми файлами PostgreSQL.
$bin = Join-Path $root 'pgsql\bin'

# Папка с данными базы.
$data = Join-Path $root 'data'

# Файл журнала сервера.
$log = Join-Path $root 'postgres.log'

# Полный путь к утилите управления pg_ctl.
$pgCtl = Join-Path $bin 'pg_ctl.exe'

# Проверяем, что PostgreSQL действительно установлен.
if (-not (Test-Path $pgCtl)) {
    # Сообщаем понятную ошибку, если утилита не найдена.
    throw "pg_ctl.exe not found: $pgCtl. Install PostgreSQL or set PG_ROOT."
}

# Проверяем текущий статус сервера, скрывая лишний вывод.
& $pgCtl status -D $data *> $null

# Если сервер уже запущен, повторно его не стартуем.
if ($LASTEXITCODE -eq 0) {
    # Сообщаем, что всё уже работает.
    Write-Host 'PostgreSQL is already running on port 5432.'
    # Завершаем скрипт с успешным кодом.
    exit 0
}

# Запускаем сервер на порту 5432 с записью вывода в лог.
& $pgCtl -D $data -l $log -o '"-p 5432"' start

# Если запуск не удался, показываем ошибку.
if ($LASTEXITCODE -ne 0) {
    # Подсказываем, где смотреть причину.
    throw "Could not start PostgreSQL. See $log"
}

# Проверяем готовность сервера принимать подключения.
& (Join-Path $bin 'pg_isready.exe') -h 127.0.0.1 -p 5432
