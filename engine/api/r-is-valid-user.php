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
    // Если когда-нибудь понадобятся куки/сессии через CORS:
    // header('Access-Control-Allow-Credentials: true');
}
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
    // preflight
    http_response_code(204);
    exit;
}
/* === /CORS === */

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('X-Content-Type-Options: nosniff');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST, OPTIONS');
    echo json_encode(['ok' => false, 'error' => 'method_not_allowed']);
    exit;
}

/* Читаем token из разных источников: form, query, JSON */
$token = $_POST['token'] ?? ($_GET['token'] ?? null);
if (!$token && isset($_SERVER['CONTENT_TYPE']) && stripos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
    $json = json_decode(file_get_contents('php://input'), true);
    $token = $json['token'] ?? null;
}

if (!$token) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'auth_user_missing']);
    exit;
}

$user = getUserByHash($token);

if (!$user) {
    echo json_encode(['ok' => true, 'is_valid' => false]);
    exit;
}

$actualUserRixCoins = 0;
$actualFreeSpins = 0;

$freshAltcoins = getUserAltcoins($user['uid']);
if ($freshAltcoins) {
    $actualUserRixCoins = (int)($freshAltcoins['RIXCOIN'] ?? 0);
    $actualFreeSpins    = (int)($freshAltcoins['RIX_freespin'] ?? 0);
}

echo json_encode([
    'ok' => true,
    'is_valid' => true,
    'roulette_coin' => $actualUserRixCoins,
    'roulette_freespin' => $actualFreeSpins
]);
exit;
