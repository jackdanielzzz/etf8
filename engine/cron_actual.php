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
require __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

/* ---------- 1. Конфигурация ------------------------------------------------ */
$APP_TYPE = $_ENV['APP_TYPE'] ?? 'prod';       // prod | dev
$DB_HOST  = $_ENV['DB_HOST'];
$DB_NAME  = $_ENV['DB_NAME'];
$DB_USER  = $_ENV['DB_USER'];
$DB_PASS  = $_ENV['DB_PASS'];

const DEV_DATE_FILE = __DIR__ . '/dev_date.txt';  // где храним «виртуальную дату»

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

/* ---------- 3. Утилиты даты ------------------------------------------------ */
// Функция для отправки сообщения в Telegram.
function sendTgMessage($message) {
    // Получаем токен бота
    $botToken = $_ENV['TG_BOTTOKEN'];

    // Получаем идентификаторы чатов
    $chatIds = [$_ENV['TG_CHATID1'], $_ENV['TG_CHATID2']];

    // URL для отправки сообщений
    $apiUrl = "https://api.telegram.org/bot{$botToken}/sendMessage";

    // Общие данные для отправки
    $data = [
        'text' => $message,
        'parse_mode' => 'html'
    ];

    $results = [];

    foreach ($chatIds as $chatId) {
        $data['chat_id'] = $chatId;

        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        curl_close($ch);

        $results[] = $result;
    }

    return $results;
}

/**
 * Определяет «сегодня» в зависимости от режима.
 * В dev-режиме хранит значение в файле и сдвигает его каждый запуск.
 */
function getToday(string $env): string
{
    if ($env !== 'dev') {
        return (new DateTime('now', new DateTimeZone('UTC')))->format('Y-m-d');
    }

    // -------- DEV-режим --------
    if (!file_exists(DEV_DATE_FILE)) {
        // первый запуск: стартуем с вчерашнего дня – так первый run начислит «сегодня»
        $start = (new DateTime('now', new DateTimeZone('UTC')))
            ->modify('-1 day')->format('Y-m-d');
        file_put_contents(DEV_DATE_FILE, $start);
    }

    $current = trim(file_get_contents(DEV_DATE_FILE));
    $today   = new DateTime($current, new DateTimeZone('UTC'));
    $today->modify('+1 day');                       // прыгаем вперёд на 1 день
    file_put_contents(DEV_DATE_FILE, $today->format('Y-m-d'));

    return $today->format('Y-m-d');
}

/**
 * Преобразует строку YYYY-MM-DD в DateTime (UTC, без часового пояса).
 */
function dt(string $iso): DateTime
{
    return DateTime::createFromFormat('Y-m-d', $iso, new DateTimeZone('UTC'));
}

/* ---------- 4. Алгоритм генерации плавающих ставок ------------------------ */

/**
 * Генерирует массив из $days ставок, сумма которых = $targetRate
 * и каждая ∈ [$rateMin ; $rateMax] с точностью $scale знаков.
 *
 * @return float[]
 */
function generateVariableRates(
    float $targetRate,
    float $rateMin,
    float $rateMax,
    int   $days,
    int   $scale = 6
): array {

    if ($days < 1) {
        throw new InvalidArgumentException('Days must be positive');
    }

    $factor      = 10 ** $scale;
    $minPossible = $rateMin * $days;
    $maxPossible = $rateMax * $days;
    if ($targetRate < $minPossible || $targetRate > $maxPossible) {
        throw new RuntimeException('Target out of range');
    }

    /* ------------------------------------------------------------------ */
    /* 1. базовая выдача (минимум)                                         */
    $rates = array_fill(0, $days, $rateMin);
    $remain = $targetRate - $minPossible;          // что нужно распределить

    /* 2. случайные веса (u_i) ------------------------------------------- */
    $u = [];
    $uSum = 0.0;
    for ($i = 0; $i < $days; $i++) {
        $u[$i] = random_int(1, 1 << 20) / (1 << 20);  // float (0;1]
        $uSum += $u[$i];
    }

    /* 3. первое распределение ------------------------------------------- */
    for ($i = 0; $i < $days; $i++) {
        $rates[$i] += $remain * $u[$i] / $uSum;
    }

    /* 4. срезаем превышения и перераспределяем “лишнее” ----------------- */
    $overflow = 0.0;
    $flexIdx  = [];                                  // ещё могут расти
    for ($i = 0; $i < $days; $i++) {
        if ($rates[$i] > $rateMax) {
            $overflow += $rates[$i] - $rateMax;
            $rates[$i] = $rateMax;
        } else {
            $flexIdx[] = $i;
        }
    }
    if ($overflow > 0 && $flexIdx) {
        foreach ($flexIdx as $i) {                   // второго прохода хватит
            $add = $overflow / count($flexIdx);
            $room = $rateMax - $rates[$i];
            $delta = min($add, $room);
            $rates[$i] += $delta;
            $overflow  -= $delta;
        }
    }

    /* 5. косметика: ровно попасть в цель -------------------------------- */
    $currentSum = array_sum($rates);
    $diff       = $targetRate - $currentSum;          // ещё без округления

    if ($diff != 0.0) {
        // попробуем подвинуть ставку на величину diff
        foreach ($rates as $i => $r) {
            $new = $r + $diff;
            if ($new >= $rateMin && $new <= $rateMax) {
                $rates[$i] = $new;
                break;
            }
        }
    }

    /* 6. финальный рендер и тасовка ------------------------------------- */
    $step = 1 / $factor;
    foreach ($rates as &$r) {
        $r = round($r, $scale);
    }
    unset($r);

    /* 7. контроль: если ушли ниже – добавляем один шаг ------------------- */
    $finalSum = array_sum($rates);
    if ($finalSum < $targetRate - 1e-12) {           // подчёркнуто «меньше», допуск ≈ 0
        $need = ceil(($targetRate - $finalSum) * $factor) / $factor; // >= 1 step
        foreach ($rates as &$r) {
            if ($r + $need <= $rateMax) {
                $r = round($r + $need, $scale);
                break;
            }
        }
        unset($r);
    }

    shuffle($rates);
    return $rates;
}


/* ---------- 5. Создание расписания для новой user_deal -------------------- */

function ensureSchedule(PDO $pdo, array $deal): void
{
    $stmt = $pdo->prepare(
        "SELECT COUNT(*) FROM user_deal_accruals WHERE user_deal_id = ?"
    );
    $stmt->execute([$deal['id']]);
    if ($stmt->fetchColumn() > 0) return;                // уже создано

    // кол-во дней между (start_date+1) и end_date включительно
    $termDays = dt($deal['end_date'])->diff(dt($deal['start_date']))->days;
    if ($termDays < 1) {
        return; // нечего начислять
    }

    $rates = generateVariableRates(
        $deal['daily_target'],   // целевая суммарная ставка за весь срок
        $deal['daily_min'],      // минимальная дневная
        $deal['daily_max'],      // максимальная дневная
        $termDays                // количество дней начислений
    );

    $ins = $pdo->prepare("\n        INSERT INTO user_deal_accruals\n              (user_deal_id, accrual_date, day_index, daily_rate, amount)\n        VALUES (?, ?, ?, ?, ?)\n    ");

    // начисления начинаются со следующего дня после start_date
    $start = dt($deal['start_date'])->modify('+1 day');
    for ($d = 0; $d < $termDays; $d++) {
        $date   = (clone $start)->modify("+{$d} day");
        $rate   = $rates[$d];
        $amount = round($deal['principal'] * $rate, 2);

        $ins->execute([
            $deal['id'],
            $date->format('Y-m-d'),
            $d + 1,
            $rate,
            $amount
        ]);
    }
}

/* ---------- 6. Основная дневная обработка --------------------------------- */

function processOneDay(PDO $pdo, string $today, PDOStatement $logStmt, int $runId, int &$dealsLogged): void
{

    // — активные сделки, у которых «сегодня» лежит внутри срока
    $sql = "SELECT ud.*, ud.id AS deal_id, d.term_days, u.user_name, u.sur_name, u.email
            FROM user_deals ud  
            JOIN deals  d ON d.deal_id = ud.deal_id      
            JOIN users  u ON u.uid     = ud.user_id      
            WHERE ud.status = 'active' AND ud.start_date <= :today   AND ud.end_date   >= :today";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['today' => $today]);
    $activeDeals = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $pdo->beginTransaction();

    foreach ($activeDeals as $deal) {
        ensureSchedule($pdo, $deal);

        echo $payoutMode  = $deal['payout_mode'];
        $payDailyNow = ($payoutMode === 'daily');

        // есть ли строка на сегодня в расписании?
        $row = $pdo->prepare("
        SELECT accrual_id, amount, day_index, daily_rate
        FROM   user_deal_accruals
        WHERE  user_deal_id = ? AND accrual_date = ?");
        $row->execute([$deal['id'], $today]);
        $row = $row->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            echo "<br>⨯  Нет строки accruals на $today for deal[id]={$deal['deal_id']}\n";
            continue;
        }

        // уже начислено?
        if ($deal['last_accrual_on'] !== null && $deal['last_accrual_on'] >= $today) {
            continue;
        }

        // баланс пользователя – ежедневный доход на balance (если daily) и на total_accrued
        $pdo->prepare("
                             UPDATE users 
                             SET total_accrued = total_accrued + :amt
                             WHERE uid = :uid
                             ")
            ->execute([
                'amt' => $row['amount'],
                'uid' => $deal['user_id'],
            ]);

        if ($payDailyNow) {
            $pdo->prepare("UPDATE users SET balance = balance + :sum WHERE uid = :uid")
                ->execute([
                    'sum' => $row['amount'],
                    'uid' => $deal['user_id'],
                ]);
        }

        // обновить user_deals
        $pdo->prepare("UPDATE user_deals  
                                SET accrued_amount = accrued_amount + ?, 
                                    last_accrual_on = ?    
                                WHERE id = ?
                             ")->execute([$row['amount'], $today, $deal['deal_id']]);

        // финиш сделки?
        if ($today === $deal['end_date']) {

            // тело + доходность (если не выплачивали ежедневно)
            $closeSum = $deal['principal'];

            if (!$payDailyNow) {
                $closeSum += $deal['accrued_amount']         // доход **до** сегодняшнего дня
                    + $row['amount'];                 // доход ЗА сегодняшний день
            }

            $pdo->prepare("UPDATE users SET balance = balance + :sum WHERE uid = :uid")
                ->execute([
                'sum' => $closeSum,
                'uid' => $deal['user_id'],
            ]);

            // пометить сделку завершённой
            $pdo->prepare("UPDATE user_deals SET status = 'completed', is_closed = 1 WHERE id = ?")
                ->execute([$deal['deal_id']]);
        }

        /* === B. лог начисления ================================================ */
        if ($logStmt === null) {
            die("Error: \$logStmt is null. Check the SQL query or database connection.");
        }

        $logStmt->execute([
            $runId,               // run_id
            $today,               // today_date
            $deal['id'],          // user_deal_id
            $row['accrual_id'],   // accrual_id
            $deal['user_id'],     // user_id
            $row['amount'],       // amount
            $row['daily_rate'],   // ставка
            $row['day_index'],    // день N
            $deal['principal'],   // тело вклада
            ($today === $deal['end_date']) ? 1 : 0 // was_closed
        ]);
        $dealsLogged++;


        echo "<br>✓ deal ID  {$deal['id']}: "
            . "{$deal['user_name']} {$deal['sur_name']} (user_id={$deal['user_id']}) ({$deal['email']})"
            . " начислено {$row['amount']}$"
            . " день {$row['day_index']}/{$deal['term_days']} \n";
    }

    $pdo->commit();
}

/* ---------- 7. Запуск ------------------------------------------------------ */

$today = getToday($APP_TYPE);
echo "==> РАБОЧАЯ ДАТА: $today   (APP_TYPE=$APP_TYPE) <==\n";


/*  after you have $today */
$yesterday = (new DateTimeImmutable($today, new DateTimeZone('UTC')))
    ->modify('-1 day')
    ->format('Y-m-d');

/* 1. Собираем реферальные начисления за вчера.
 *    В исходной базе они лежат в transactions с type = 2
 *    (см. transaction_types id=3 — «Автоматические реферальные начисления») :contentReference[oaicite:0]{index=0} */
$sql = "
    SELECT user_id, SUM(total_usd) AS team_sum
      FROM transactions
     WHERE type = 2
       AND DATE(date) = :y
     GROUP BY user_id
";
$stmt = $pdo->prepare($sql);
$stmt->execute(['y' => $yesterday]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* 2. Обновляем team_income_roll,
 *    сдвигая окно d13←d12←…←d0 и кладя свежую сумму в d0 */
$shift = "
    ON DUPLICATE KEY UPDATE
       d13 = d12, d12 = d11, d11 = d10, d10 = d9,
       d9  = d8,  d8  = d7,  d7  = d6,  d6  = d5,
       d5  = d4,  d4  = d3,  d3  = d2,  d2  = d1,
       d1  = d0,
       d0  = VALUES(d0),
       updated_at = NOW()
";
$ins = $pdo->prepare("
    INSERT INTO team_income_roll (user_id, d0)
    VALUES (:uid, :sum)
    $shift
");

foreach ($rows as $r) {
    $ins->execute([
        'uid' => $r['user_id'],
        'sum' => $r['team_sum'],
    ]);
}

/* 3. Для тех, у кого вчера ═ 0 USDT, всё-равно двигаем окно,
 *    чтобы нули тоже «старели». */
$pdo->query("
    UPDATE team_income_roll SET
       d13 = d12, d12 = d11, d11 = d10, d10 = d9,
       d9  = d8,  d8  = d7,  d7  = d6,  d6  = d5,
       d5  = d4,  d4  = d3,  d3  = d2,  d2  = d1,
       d1  = d0, d0 = 0,
       updated_at = NOW()
  WHERE updated_at < CURDATE()            -- сегодня ещё не трогали
");




/* === A. начало run ===================================================== */
$runStmt = $pdo->prepare("
    INSERT INTO cron_runs (started_at, app_type, today_date)
    VALUES (NOW(), ?, ?)
");
$runStmt->execute([$APP_TYPE, $today]);
$runId = (int)$pdo->lastInsertId();

/* шаблон для строки accrual_logs */
$logStmt = $pdo->prepare("
    INSERT INTO accrual_logs
           (run_id, run_at, today_date,
            user_deal_id, accrual_id, user_id,
            amount, daily_rate, day_index, principal, was_closed)
    VALUES (?, NOW(), ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

$dealsLogged  = 0;     // счётчик для сводки
$quotesLogged = 0;     // пригодится, если вернёте блок котировок



processOneDay($pdo, $today, $logStmt, $runId, $dealsLogged);


/* === C. закрываем run ================================================== */
$updRun = $pdo->prepare("
    UPDATE cron_runs
       SET finished_at      = NOW(),
           processed_deals  = ?,
           processed_quotes = ?,
           status           = 'ok'
     WHERE id = ?
");
$updRun->execute([$dealsLogged, $quotesLogged, $runId]);

if ($APP_TYPE == 'prod') {
    sendTgMessage("CRON №$runId: $today обработано $dealsLogged сделок");
}

