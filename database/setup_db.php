<?php
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
