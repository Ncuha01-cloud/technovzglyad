# Starts the local portable PostgreSQL 17 from %LOCALAPPDATA%\pgsql.
# Override the location with the PG_ROOT environment variable.
$ErrorActionPreference = 'Stop'

$root = if ($env:PG_ROOT) { $env:PG_ROOT } else { Join-Path $env:LOCALAPPDATA 'pgsql' }
$bin = Join-Path $root 'pgsql\bin'
$data = Join-Path $root 'data'
$log = Join-Path $root 'postgres.log'
$pgCtl = Join-Path $bin 'pg_ctl.exe'

if (-not (Test-Path $pgCtl)) {
    throw "pg_ctl.exe not found: $pgCtl. Install PostgreSQL or set PG_ROOT."
}

& $pgCtl status -D $data *> $null
if ($LASTEXITCODE -eq 0) {
    Write-Host 'PostgreSQL is already running on port 5432.'
    exit 0
}

& $pgCtl -D $data -l $log -o '"-p 5432"' start
if ($LASTEXITCODE -ne 0) {
    throw "Could not start PostgreSQL. See $log"
}

& (Join-Path $bin 'pg_isready.exe') -h 127.0.0.1 -p 5432
