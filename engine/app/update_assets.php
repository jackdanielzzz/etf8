<?php
/**************************************************************************
 * cron_accrual.php
 *
 * ▸  PROD-режим (APP_ENV=prod, значение по-умолчанию)  – берём «системную» дату.
 * ▸  DEV-режим  ($_ENV['APP_TYPE'])                         – храним «текущий» день
 *    в файле dev_date.txt и при каждом запуске сдвигаем его на +1 сутки,
 *    тем самым эмулируя ежедневный cron.
 *
 *   ▸  Запуск в проде:  APP_ENV=prod php cron_accrual.php
 *   ▸  Запуск в деве :  APP_ENV=dev  php cron_accrual.php
 **************************************************************************/
require __DIR__ . '/../../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(dirname(dirname(__DIR__)));
$dotenv->load();

/* ---------- 1. Конфигурация ------------------------------------------------ */
$APP_TYPE = $_ENV['APP_TYPE'] ?? 'prod';       // prod | dev
$DB_HOST  = $_ENV['DB_HOST'];
$DB_NAME  = $_ENV['DB_NAME'];
$DB_USER  = $_ENV['DB_USER'];
$DB_PASS  = $_ENV['DB_PASS'];

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


/* ---------- 8.  Котировки активов  --------------------------------------- */
error_reporting(E_ALL & ~E_DEPRECATED);
$conf = Finnhub\Configuration::getDefaultConfiguration()->setApiKey('token', 'd0q4k0pr01qmj4ni1l5gd0q4k0pr01qmj4ni1l60');
$finn = new Finnhub\Api\DefaultApi(
    new GuzzleHttp\Client(),
    $conf
);

// 8.2  Список тикеров (можно вынести в .env или отдельный файл)
$tickers = [
    'VOO','SPY','IVV','QQQ','VTI','ARKK','ARKF','BITO','IBIT','GBTC','BLOK','VUG','SMH',
    'BINANCE:BTCUSDT','BINANCE:ETHUSDT','BINANCE:SOLUSDT','BINANCE:XRPUSDT',
    'BINANCE:LTCUSDT','BINANCE:USDCUSDT','BINANCE:TONUSDT'
];

// 8.3  Prepared-statement
$ins = $pdo->prepare(
    "INSERT INTO asset_quotes (symbol, price, percent_change, quote_time)
     VALUES (:s, :p, :dp, :qt)
     ON DUPLICATE KEY UPDATE price = VALUES(price),
                             percent_change = VALUES(percent_change),
                             quote_time = VALUES(quote_time)"
);
echo "<br><br> START4";
foreach ($tickers as $t) {
    try {
        $q = $finn->quote($t);

        if ($q->getC() === null) {
            echo "⚠️  $t — нет данных\n";
            continue;
        }

        // безопасно пытаемся взять timestamp из SDK,
        // иначе используем «сейчас» (UTC)
        $qt = (method_exists($q, 'getT') && $q->getT())
            ? (new DateTime('@'.$q->getT()))
                ->setTimezone(new DateTimeZone('UTC'))
                ->format('Y-m-d H:i:s')
            : (new DateTime('now', new DateTimeZone('UTC')))
                ->format('Y-m-d H:i:s');

        $ins->execute([
            's'  => $t,
            'p'  => $q->getC(),
            'dp' => $q->getDp(),
            'qt' => $qt,
        ]);

        echo "✓ $t = {$q->getC()} ({$q->getDp()} %)\n";

    } catch (Throwable $e) {
        echo "⨯ $t — {$e->getMessage()}\n";
    }
}

echo "<br><br> END";