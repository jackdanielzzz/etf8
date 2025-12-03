<?php

require_once '_auth.php';

$uid = (int)$_SESSION['user_id'];

try {
    $pdo->beginTransaction();

    // Блокируем строку пользователя
    $stmt = $pdo->prepare(
        "SELECT balance, balance_team 
         FROM users 
         WHERE uid = :uid FOR UPDATE"
    );
    $stmt->execute([':uid' => $uid]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        throw new Exception('User not found');
    }

    $teamSum = (float) $user['balance_team'];
    if ($teamSum <= 0) {
        // Нечего переводить
        $pdo->rollBack();
        echo json_encode(['error' => 'nothing_to_transfer']);
        exit;
    }

    // Обновляем балансы
    $stmt = $pdo->prepare(
        "UPDATE users 
         SET balance = balance + :sum,
             total_team_accrued = total_team_accrued + :sum,
             balance_team = 0
         WHERE uid = :uid"
    );
    $stmt->execute([
        ':sum' => $teamSum,
        ':uid' => $uid
    ]);

    /* ---------- логируем перевод ---------- */
    $log = $pdo->prepare(
        "INSERT INTO transactions
        (user_id, ref_id, amount_usd, percent, total_usd, type)
     VALUES (:uid, :uid, :sum, 0, :sum, 28)"
    );
    $log->execute([
        ':uid' => $uid,
        ':sum' => $teamSum
    ]);

    $pdo->commit();

    echo json_encode(['success' => true, 'amount' => $teamSum]);
} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['error' => $e]);
}