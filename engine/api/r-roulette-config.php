<?php
require_once __DIR__ . '/../app/functions.php';

// простой "секрет" для обфускации
const ROULETTE_SIMPLE_SECRET = 'etfrix-roulette-2025';

/* === CORS === */
// Обратите внимание на двоеточия внутри кавычек!
header('Access-Control-Allow-Origin: *');
header('Vary: Origin');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
    // Для OPTIONS запроса возвращаем 204 No Content и завершаем работу
    http_response_code(204);
    exit;
}
/* === /CORS === */

// дальше всё как у тебя
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('X-Content-Type-Options: nosniff');

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    header('Allow: POST, OPTIONS');
    echo json_encode(['ok' => false, 'error' => 'method_not_allowed']);
    exit;
}

/**
 * Проверка простого base64-токена
 * Формат: base64("<ts>:<secret>")
 */
function verifySimpleToken(?string $token, int $ttlSec = 300): bool
{

    return true;

    if (!$token) {
        return false;
    }

    $decoded = base64_decode($token, true);
    if ($decoded === false) {
        return false;
    }

    $parts = explode(':', $decoded, 2);
    if (count($parts) !== 2) {
        return false;
    }

    [$ts, $secret] = $parts;

    if ($secret !== ROULETTE_SIMPLE_SECRET) {
        return false;
    }

    if (!ctype_digit($ts)) {
        return false;
    }

    $ts  = (int)$ts;
    $now = time();

    if (abs($now - $ts) > $ttlSec) {
        return false; // протух
    }

    return true;
}

// читаем тело (ожидаем JSON: { "token": "..." })
$rawBody = file_get_contents('php://input');
$data    = json_decode($rawBody, true);

if (!is_array($data)) {
    $data = [];
}

$token = isset($data['token']) && is_string($data['token']) ? $data['token'] : null;
$authUser = isset($data['auth-user']) && is_string($data['auth-user']) ? $data['auth-user'] : null;

if (!verifySimpleToken($token)) {
    http_response_code(401);
    echo json_encode([
        'ok'    => false,
        'error' => 'invalid_token',
    ]);
    exit;
}

try {
    $config = getRouletteConfig($authUser);
    echo json_encode($config, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'ok'      => false,
        'error'   => 'db_error',
        'message' => $e->getMessage(),
    ]);
}
