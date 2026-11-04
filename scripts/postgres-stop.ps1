# Stops the local portable PostgreSQL 17 from %LOCALAPPDATA%\pgsql.
# Override the location with the PG_ROOT environment variable.
$ErrorActionPreference = 'Stop'

$root = if ($env:PG_ROOT) { $env:PG_ROOT } else { Join-Path $env:LOCALAPPDATA 'pgsql' }
$bin = Join-Path $root 'pgsql\bin'
$data = Join-Path $root 'data'
$pgCtl = Join-Path $bin 'pg_ctl.exe'

if (-not (Test-Path $pgCtl)) {
    throw "pg_ctl.exe not found: $pgCtl. Install PostgreSQL or set PG_ROOT."
}

& $pgCtl -D $data stop -m fast
