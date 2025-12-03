<?php

require_once '_auth.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    echo json_encode(['ok' => false, 'error' => 'method_not_allowed']);
    exit;
}

$uid = (int)$_SESSION['user_id'];

// Чтение входа (JSON + form-data)
$input = $_POST;
if (!$input && isset($_SERVER['CONTENT_TYPE']) && stripos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
    $input = json_decode((string)file_get_contents('php://input'), true) ?: [];
}

$libraryId = isset($input['library_id']) ? (int)$input['library_id'] : 0;
$quantity  = isset($input['quantity']) ? (int)$input['quantity'] : 0;

if ($libraryId <= 0 || $quantity <= 0) {
    http_response_code(422);
    echo json_encode(['ok' => false, 'error' => 'bad_input']);
    exit;
}

// Продажа доступна от 10 юнитов
if ($quantity < 10) {
    http_response_code(422);
    echo json_encode(['ok' => false, 'error' => 'quantity_too_small']);
    exit;
}

// Получаем цену NFT
$stmtPrice = $pdo->prepare('SELECT price FROM nft_library WHERE id = :id LIMIT 1');
$stmtPrice->execute([':id' => $libraryId]);
$price = $stmtPrice->fetchColumn();

if ($price === false) {
    http_response_code(404);
    echo json_encode(['ok' => false, 'error' => 'nft_not_found']);
    exit;
}

// Проверяем количество имеющихся юнитов
$stmtCount = $pdo->prepare('SELECT COUNT(*) FROM user_nfts WHERE user_id = :uid AND nft_library_id = :lib');
$stmtCount->execute([
    ':uid' => $uid,
    ':lib' => $libraryId,
]);
$ownedCount = (int)$stmtCount->fetchColumn();

if ($ownedCount < 10) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'error' => 'not_enough_units']);
    exit;
}

if ($quantity > $ownedCount) {
    http_response_code(422);
    echo json_encode(['ok' => false, 'error' => 'quantity_exceeds_available']);
    exit;
}

$totalValue = round((float)$price * $quantity, 2);

try {
    $pdo->beginTransaction();

    // Удаляем нужное количество NFT юнитов
    $deleteStmt = $pdo->prepare(
        'DELETE FROM user_nfts
         WHERE user_id = :uid AND nft_library_id = :lib
         ORDER BY id ASC
         LIMIT :qty'
    );
    $deleteStmt->bindValue(':uid', $uid, PDO::PARAM_INT);
    $deleteStmt->bindValue(':lib', $libraryId, PDO::PARAM_INT);
    $deleteStmt->bindValue(':qty', $quantity, PDO::PARAM_INT);
    $deleteStmt->execute();

    if ($deleteStmt->rowCount() !== $quantity) {
        throw new RuntimeException('delete_mismatch');
    }

    // Начисляем средства пользователю
    $added = setUserMoneyById($uid, $totalValue);

    if ($added === false) {
        throw new RuntimeException('balance_update_failed');
    }

    // Получаем актуальный баланс
    $user = getUserById($uid);

    $pdo->commit();

    echo json_encode([
        'ok'             => true,
        'sold'           => $quantity,
        'total_value'    => $totalValue,
        'owned_left'     => $ownedCount - $quantity,
        'balance'        => $user['balance'] ?? null,
        'message'        => 'sold',
    ]);
} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    http_response_code(500);
    echo json_encode([
        'ok'      => false,
        'error'   => 'db_error',
        'message' => $e->getMessage(),
    ]);
}