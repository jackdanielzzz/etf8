<?php
/**
 * reset.php — helper script for dev‑environment.
 *
 * Removes dev_date.txt and wipes user_deal_accruals + user_deals tables
 * without relying on TRUNCATE (uses DELETE + AUTO_INCREMENT reset to avoid
 * FK‑related MySQL errors).
 */

declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    header('Content-Type: text/plain; charset=utf-8');
}

error_reporting(E_ALL);
ini_set('display_errors', '1');

require __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

// ---------- 1. Check env ----------
if (($_ENV['APP_TYPE'] ?? '') !== 'dev') {
    http_response_code(403);
    echo "403 Forbidden: reset allowed only in dev environment\n";
    exit;
}

// ---------- 2. Remove dev_date.txt ----------
$devDatePath = __DIR__ . '/dev_date.txt';
if (is_file($devDatePath)) {
    unlink($devDatePath);
    echo "✔ dev_date.txt removed\n";
} else {
    echo "ℹ dev_date.txt не найден (нечего удалять)\n";
}

// ---------- 3. Connect DB ----------
try {
    $pdo = new PDO(
        sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', $_ENV['DB_HOST'], $_ENV['DB_NAME']),
        $_ENV['DB_USER'],
        $_ENV['DB_PASS'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    http_response_code(500);
    echo "DB connection failed: " . $e->getMessage() . "\n";
    exit;
}

// ---------- 4. Wipe tables ----------
try {
    // 1) child table accruals
    $pdo->exec('DELETE FROM user_deal_accruals');
    $pdo->exec('ALTER TABLE user_deal_accruals AUTO_INCREMENT = 1');

    // 2) parent table deals (accruals depended on deals)
    $pdo->exec('DELETE FROM user_deals');
    $pdo->exec('ALTER TABLE user_deals AUTO_INCREMENT = 1');

    // 3) заявки на сделки
    $pdo->exec('DELETE FROM user_deal_requests');
    $pdo->exec('ALTER TABLE user_deal_requests AUTO_INCREMENT = 1');


    echo "✔ Сделки в базе сброшены\n";
} catch (PDOException $e) {
    http_response_code(500);
    echo "DB error: " . $e->getMessage() . "\n";
    exit;
}

echo "Готово.\n";