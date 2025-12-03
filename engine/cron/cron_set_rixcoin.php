<?php
require __DIR__ . '/../../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(dirname(dirname(__DIR__)));
$dotenv->load();

/* ---------- 1. Конфигурация ------------------------------------------------ */
$APP_TYPE = $_ENV['APP_TYPE'] ?? 'prod';       // prod | dev
$DB_HOST  = $_ENV['DB_HOST'];
$DB_NAME  = $_ENV['DB_NAME'];
$DB_USER  = $_ENV['DB_USER'];
$DB_PASS  = $_ENV['DB_PASS'];

const MIN_RIXCOIN_BALANCE = 300;

/* ---------- 2. Подключение к БД ------------------------------------------- */

try {
    $pdo = new PDO(
        "mysql:host=" . $DB_HOST . ";dbname=" . $DB_NAME . ";charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die("DB connection failed: " . $e->getMessage());
}

/* ---------- 3. Синхронизация RIXCOIN -------------------------------------- */

try {
    $stmt = $pdo->prepare(
        'SELECT u.uid, ua.id AS altcoin_id'
        . '     , COALESCE(ua.RIXCOIN, 0) AS balance'
        . '  FROM users u'
        . '  LEFT JOIN user_altcoins ua ON ua.user_id = u.uid'
        . ' WHERE COALESCE(ua.RIXCOIN, 0) < :minBalance'
    );
    $stmt->execute(['minBalance' => MIN_RIXCOIN_BALANCE]);

    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($users)) {
        echo "All users already have at least " . MIN_RIXCOIN_BALANCE . " RIXCOIN." . PHP_EOL;
        return;
    }

    $pdo->beginTransaction();

    $update = $pdo->prepare('UPDATE user_altcoins SET RIXCOIN = :minBalance WHERE user_id = :uid');
    $insert = $pdo->prepare('INSERT INTO user_altcoins (user_id, RIXCOIN) VALUES (:uid, :minBalance)');

    foreach ($users as $user) {
        $params = ['uid' => (int) $user['uid'], 'minBalance' => MIN_RIXCOIN_BALANCE];

        if (!empty($user['altcoin_id'])) {
            $update->execute($params);
        } else {
            $insert->execute($params);
        }
    }

    $pdo->commit();

    echo 'Updated ' . count($users) . ' user(s) to minimum RIXCOIN balance.' . PHP_EOL;
} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    throw $e;
}