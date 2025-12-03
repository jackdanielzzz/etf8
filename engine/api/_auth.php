<?php

require_once __DIR__ . '/../app/functions.php';

// Сессию вторично не запускаем
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

/* ---------- 1. Авторизация ---------- */
if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status'=>'error','message'=>'Unauthorized']);  // единый формат
    exit;
}