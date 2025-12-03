<?php
declare(strict_types=1);

require_once '_auth.php';

$uid    = (int)$_SESSION['user_id'];
$dealId = (int)($_POST['deal_id'] ?? 0);

if ($dealId <= 0) {
    http_response_code(422);
    echo json_encode(['error' => 'bad_request']);
    exit;
}

$sql = "SELECT deal_id, need_confirm
          FROM deals
         WHERE deal_id = :did
           AND team_id = (SELECT team_id FROM users WHERE uid = :uid LIMIT 1)
         LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->execute(['did' => $dealId, 'uid' => $uid]);
$dealTpl = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$dealTpl) {
    http_response_code(404);
    echo json_encode(['error' => 'deal_not_found']);
    exit;
}

if ((int)($dealTpl['need_confirm'] ?? 0) !== 1) {
    http_response_code(409);
    echo json_encode(['error' => 'not_required']);
    exit;
}

$sql = "SELECT id, status
          FROM user_deal_requests
         WHERE user_id = :uid AND deal_id = :did
         ORDER BY id DESC
         LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->execute(['uid' => $uid, 'did' => $dealId]);
$existing = $stmt->fetch(PDO::FETCH_ASSOC);

if ($existing && (int)$existing['status'] !== 2) {
    http_response_code(409);
    echo json_encode([
        'error'      => 'exists',
        'request_id' => (int)$existing['id'],
        'status'     => (int)$existing['status'],
    ]);
    exit;
}

$ins = $pdo->prepare('INSERT INTO user_deal_requests (user_id, deal_id) VALUES (:uid, :deal_id)');
$ins->execute(['uid' => $uid, 'deal_id' => $dealId]);

http_response_code(201);

echo json_encode([
    'ok'         => 1,
    'request_id' => (int)$pdo->lastInsertId(),
]);