<?php
/* /engine/app/db.php */

$dsn = 'mysql:host=' . $_ENV['DB_HOST'] .
    ';dbname='    . $_ENV['DB_NAME'] .
    ';charset=utf8mb4';

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS'], $options);

    /* -- ЭТА СТРОКА ДЕЛАЕТ $pdo ДОСТУПНЫМ ПО ВСЕМУ СКРИПТУ -- */
    $GLOBALS['pdo'] = $pdo;

} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}
