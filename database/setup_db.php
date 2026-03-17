<?php
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }
        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value, ' "\'');
        if (!getenv($key)) {
            putenv("$key=$value");
        }
    }
}

require_once __DIR__ . '/../config/database.php';

function runMigration(PDO $pdo, string $file): void {
    $sql = file_get_contents($file);
    try {
        $pdo->exec($sql);
        echo "Executed: " . basename($file) . "\n";
    } catch (PDOException $e) {
        if ($e->getCode() == '42S01') {
            echo "Skipped (already exists): " . basename($file) . "\n";
        } else {
            echo "Error executing " . basename($file) . ": " . $e->getMessage() . "\n";
        }
    }
}

$pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
]);

$migrations = glob(__DIR__ . '/migrations/*.sql');
sort($migrations);

foreach ($migrations as $migration) {
    runMigration($pdo, $migration);
}

$seeders = glob(__DIR__ . '/seeders/*.sql');
sort($seeders);

foreach ($seeders as $seeder) {
    runMigration($pdo, $seeder);
}

echo "Database setup complete.\n";
