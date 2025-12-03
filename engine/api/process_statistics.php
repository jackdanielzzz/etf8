<?php
declare(strict_types=1);

require_once '_auth.php';

header('Content-Type: application/json; charset=utf-8');

$uid = (int)($_SESSION['user_id'] ?? 0);
if ($uid <= 0) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'unauth']);
    exit;
}

/* ---------- Входные данные ---------- */
$amountUsd = isset($_POST['amount_usd']) ? (float)$_POST['amount_usd'] : 0.0;
$method    = isset($_POST['method']) ? trim((string)$_POST['method']) : 'unknown';

$currentUser = getUserById($uid);
$name = ($currentUser['user_name'] ?? '') . ' ' . ($currentUser['sur_name'] ?? '') . ' (' . ($currentUser['email'] ?? '') . ')';

/* ---------- Отправка в Telegram ---------- */
$message = viewTgMessage($name, $amountUsd, $method); // «Просмотр кошелька»
sendTgMessage($message);

/* ---------- Ответ ---------- */
echo json_encode(['status' => 'ok']);

