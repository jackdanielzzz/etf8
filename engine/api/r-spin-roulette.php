<?php
require_once __DIR__ . '/../app/functions.php';

/* === CORS === */
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
$allow = [
    'https://roulette.etfrix.com',
    'https://etfrix.com',
    'http://localhost:3000',
];

if ($origin && in_array($origin, $allow, true)) {
    header('Access-Control-Allow-Origin: ' . $origin);
    header('Vary: Origin');
    header('Access-Control-Allow-Methods: POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
}
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') { http_response_code(204); exit; }
/* === /CORS === */

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('X-Content-Type-Options: nosniff');

/* Метод */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST, OPTIONS');
    echo json_encode(['ok' => false, 'error' => 'method_not_allowed']); exit;
}

/* Чтение входа (x-www-form-urlencoded / JSON / query) */
$in = $_POST;
if (!$in && isset($_SERVER['CONTENT_TYPE']) && stripos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
    $in = json_decode(file_get_contents('php://input'), true) ?: [];
}

$uidToken = $in['token'] ?? ($_GET['token'] ?? null);
$spent = $in['spent'] ?? null;
$prize = $in['prize'] ?? null;
// Проверяем флаг фриспина (true/false или "true"/"false")
$isFreeSpin = !empty($in['free']) && ($in['free'] === true || $in['free'] === 'true');

/* Валидация */
if (!$uidToken) { http_response_code(400); echo json_encode(['ok'=>false,'error'=>'auth_user_missing']); exit; }

// Если это не фриспин, то spent обязателен и должен быть числом
if (!$isFreeSpin && ($spent === null || !is_numeric($spent) || $spent < 0)) {
    http_response_code(400); echo json_encode(['ok'=>false,'error'=>'bad_spent']); exit;
}

$spent = (int)$spent;
$prize = trim((string)$prize);

if ($prize === '') { http_response_code(400); echo json_encode(['ok'=>false,'error'=>'bad_prize']); exit; }

/* Пользователь */
$user = getUserByHash($uidToken);
if (!$user) { echo json_encode(['ok'=>true,'is_valid'=>false]); exit; }

/* Запись результата */
try {
    $pdo->beginTransaction(); // Начинаем транзакцию

    // 0. БЛОКИРУЕМ строку и получаем текущие балансы (RIXCOIN и RIX_freespin)
    $stmtCheck = $pdo->prepare("SELECT RIXCOIN, RIX_freespin FROM user_altcoins WHERE user_id = :uid FOR UPDATE");
    $stmtCheck->execute([':uid' => $user['uid']]);
    $balanceRow = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    // Если записи нет вообще (странная ситуация, но обработаем)
    if (!$balanceRow) {
        $balanceRow = ['RIXCOIN' => 0, 'RIX_freespin' => 0];
    }

    $currentBalanceCoin = (int)$balanceRow['RIXCOIN'];
    $currentBalanceFree = (int)$balanceRow['RIX_freespin'];

    // --- ЛОГИКА ПРОВЕРКИ СРЕДСТВ ---
    if ($isFreeSpin) {
        // Если фриспин: проверяем, есть ли хотя бы 1
        if ($currentBalanceFree < 1) {
            $pdo->rollBack();
            http_response_code(200);
            echo json_encode([
                'ok'                => true,
                'saved'             => false,
                'error'             => 'insufficient_spins',
                'message'           => 'No free spins available',
                'roulette_coin'     => $currentBalanceCoin,
                'roulette_freespin' => $currentBalanceFree
            ]);
            exit;
        }
    } else {
        // Если платный спин: проверяем хватает ли монет
        if ($currentBalanceCoin < $spent) {
            $pdo->rollBack();
            http_response_code(200);
            echo json_encode([
                'ok'                => true,
                'saved'             => false,
                'error'             => 'insufficient_funds',
                'message'           => 'Not enough RIXCOIN',
                'roulette_coin'     => $currentBalanceCoin,
                'roulette_freespin' => $currentBalanceFree
            ]);
            exit;
        }
    }

    // 1. Списываем стоимость
    $actualUserRixCoins = 0;
    $actualFreeSpins = 0;

    if ($isFreeSpin) {
        // Списываем 1 фриспин
        $actualFreeSpins = setUserFreeSpinsById($user['uid'], -1);
        // Монеты не меняются, берем из базы
        $actualUserRixCoins = $currentBalanceCoin;
    } else {
        // Списываем монеты
        $actualUserRixCoins = setUserRouletteCoinById($user['uid'], -$spent);
        // Фриспины не меняются
        $actualFreeSpins = $currentBalanceFree;
    }

    $table = getRouletteItemsTableForUser($uidToken);

    // 2. Начисляем приз и фиксируем в истории
    $spentToRecord = $isFreeSpin ? 0 : $spent;

    try {
        $prizeResult = awardRoulettePrizeToUser($user['uid'], $prize, $table, $spentToRecord);
    } catch (RuntimeException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        http_response_code(418);
        echo json_encode(['ok' => true, 'error' => 'token_error', 'message' => $e->getMessage()]);
        exit();
    }

    $nftData = $prizeResult['nft'] ?? null;

    // 4. Финальная синхронизация балансов перед коммитом
    // На случай если приз изменил балансы, делаем финальный SELECT (опционально, но надежно)
    // Либо полагаемся на возвращаемые значения функций.
    // Для надежности можно дернуть getUserAltcoins, так как он делает простой SELECT.
    $freshAltcoins = getUserAltcoins($user['uid']);
    if ($freshAltcoins) {
        $actualUserRixCoins = (float)($freshAltcoins['RIXCOIN'] ?? 0);
        $actualFreeSpins    = (int)($freshAltcoins['RIX_freespin'] ?? 0);
    }

    $pdo->commit(); // Применяем изменения

    // 5. Формируем ответ
    $response = [
        'ok'                => true,
        'is_valid'          => true,
        'saved'             => true,
        'roulette_coin'     => $actualUserRixCoins,
        'roulette_freespin' => $actualFreeSpins,
    ];

    if ($nftData) {
        $response['won_nft'] = [
            'name'  => $nftData['name'],
            'image' => "https://etfrix.com" . $nftData['image_path']
        ];
    }

    echo json_encode($response);

} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(500);
    echo json_encode(['ok'=>false,'error'=>'db_error','message'=>$e->getMessage()]);
}