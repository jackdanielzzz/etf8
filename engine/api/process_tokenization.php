<?php

require_once '_auth.php';

$uid = (int)$_SESSION['user_id'];

global $pdo;
/* ---------- 4. Запись в БД ---------- */
$sql = "INSERT INTO tokenization
        (user_id)
        VALUES (:user_id)";

$stmt = $pdo->prepare($sql);
if ($stmt->execute(['user_id' => $uid])) {
    echo json_encode([
        'status'      => 'success',
        'request_id'  => $pdo->lastInsertId()
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'status'  => 'error',
        'message' => 'db_error'
    ]);
}