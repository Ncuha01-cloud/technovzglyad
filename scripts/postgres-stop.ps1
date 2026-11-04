# Остановка локального портативного PostgreSQL 17 из %LOCALAPPDATA%\pgsql.
# Путь можно переопределить переменной окружения PG_ROOT.
# Скрипт аккуратно завершает сервер, чтобы не повредить данные.

# Останавливаем выполнение при любой ошибке.
$ErrorActionPreference = 'Stop'

# Определяем корневую папку PostgreSQL: PG_ROOT или %LOCALAPPDATA%\pgsql.
$root = if ($env:PG_ROOT) { $env:PG_ROOT } else { Join-Path $env:LOCALAPPDATA 'pgsql' }

# Папка с исполняемыми файлами PostgreSQL.
$bin = Join-Path $root 'pgsql\bin'

# Папка с данными базы.
$data = Join-Path $root 'data'

# Полный путь к утилите управления pg_ctl.
$pgCtl = Join-Path $bin 'pg_ctl.exe'

# Проверяем, что PostgreSQL действительно установлен.
if (-not (Test-Path $pgCtl)) {
    # Сообщаем понятную ошибку, если утилита не найдена.
    throw "pg_ctl.exe not found: $pgCtl. Install PostgreSQL or set PG_ROOT."
}

# Останавливаем сервер в быстром режиме (завершает активные подключения).
& $pgCtl -D $data stop -m fast
