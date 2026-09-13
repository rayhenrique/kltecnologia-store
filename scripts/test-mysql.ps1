$ErrorActionPreference = 'Stop'
$projectPath = Split-Path -Parent $PSScriptRoot
$testEnvironment = @{
    DB_CONNECTION = 'mysql'
    DB_HOST = '127.0.0.1'
    DB_PORT = '3306'
    DB_DATABASE = 'kltecnologia_test'
    DB_USERNAME = 'root'
    DB_PASSWORD = ''
    DB_URL = ''
}
$previousEnvironment = @{}

Push-Location $projectPath
try {
    foreach ($name in $testEnvironment.Keys) {
        $previousEnvironment[$name] = [Environment]::GetEnvironmentVariable($name, 'Process')
        [Environment]::SetEnvironmentVariable($name, $testEnvironment[$name], 'Process')
    }

    Write-Output 'Testes MySQL: Wamp 127.0.0.1:3306 / kltecnologia_test (banco exclusivo de testes).'
    php artisan config:clear --no-ansi
    if ($LASTEXITCODE -ne 0) {
        throw 'Falha ao limpar a configuração antes dos testes.'
    }

    php artisan test --no-ansi
    $testExitCode = $LASTEXITCODE
}
finally {
    foreach ($name in $previousEnvironment.Keys) {
        [Environment]::SetEnvironmentVariable($name, $previousEnvironment[$name], 'Process')
    }
    Pop-Location
}

exit $testExitCode
