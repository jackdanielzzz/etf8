<?php
declare(strict_types=1);

require_once '_auth.php';   // даёт $pdo + сессию

$uid = (int)$_SESSION['user_id'];

// --- 2. Валидируем входные данные ---
$dealId = (int)($_POST['deal_id'] ?? 0);
$amount = (int)($_POST['amount']  ?? 0);
if ($dealId <= 0 || $amount <= 0) {
    http_response_code(422);
    echo json_encode(['error' => print_r($_POST)]); exit;
}

// --- 3. Берём шаблон сделки ---
$sql = "SELECT *
          FROM deals
         WHERE deal_id = :did
           AND team_id = (SELECT team_id FROM users WHERE uid = :uid LIMIT 1)
         LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->execute(['did' => $dealId, 'uid' => $uid]);
$dealTpl = $stmt->fetch();

/* --- Flash нельзя, если у этого же юзера в регионе уже есть 'Large','Medium','Small' ---
if ($dealTpl['deal_size'] === 'Flash') {
    $sql = "SELECT 1
              FROM user_deals ud
              JOIN deals d  ON d.deal_id = ud.deal_id
             WHERE ud.user_id  = :uid
               AND ud.status IN ('active','new','pending')
               AND d.deal_size IN ('Large','Medium','Small')
             LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'uid' => $uid,
    ]);

    if ($stmt->fetchColumn()) {
        echo json_encode(['error' => 'blocked_by_large']);
        exit;
    }
}
 --- временно отключили логику  --- */

if (!$dealTpl) {
    http_response_code(404);
    echo json_encode(['error' => 'deal_not_found']); exit;
}

if ((int)($dealTpl['need_confirm'] ?? 0) === 1) {
    $req = $pdo->prepare('SELECT status FROM user_deal_requests WHERE user_id = :uid AND deal_id = :deal ORDER BY id DESC LIMIT 1');
    $req->execute(['uid' => $uid, 'deal' => $dealId]);
    $status = $req->fetchColumn();

    if ((int)$status !== 1) {
        http_response_code(409);
        echo json_encode(['error' => 'need_confirmation']); exit;
    }
}

if ($amount < $dealTpl['amount_min'] || $amount > $dealTpl['amount_max']) {
    http_response_code(422);
    echo json_encode(['error' => 'amount_range']); exit;
}

// --- 4. Проверяем баланс пользователя ---
$pdo->beginTransaction();
$bal = $pdo->query("SELECT balance FROM users WHERE uid = {$uid} FOR UPDATE")
    ->fetchColumn();

if ($bal < $amount) {
    $pdo->rollBack();
    http_response_code(422);
    echo json_encode(['error' => 'not_enough_funds']); exit;
}

// --- 4.5. Рассчитываем % для Flash ---
$percentPerDay   = (float)$dealTpl['rate_without_RIX'];   // 0.35 %
$dailyTarget = ($percentPerDay / 100) * $dealTpl['term_days'];  // 0.2975

if ($dealTpl['deal_size'] === 'Flash') {
    $minRate = (float)$dealTpl['rate_without_RIX_min'] * 100; // 0.03
    $maxRate = (float)$dealTpl['rate_without_RIX_max'] * 100; // 0.06

    if ($minRate < $maxRate) {
        // Генерируем случайное число с плавающей точкой
        $randomRate = $minRate + mt_rand() / mt_getrandmax() * ($maxRate - $minRate);
    } elseif ($minRate > 0) {
        $randomRate = $minRate; // Если min=max, берем min
    }
    $dailyTarget = ($randomRate / 100) * $dealTpl['term_days'];  // 0.2975
}

// --- 5. Создаём user_deals ---
$today = date('Y-m-d');
$end   = (new DateTime($today))
    ->modify('+' . $dealTpl['term_days'] . ' day')
    ->format('Y-m-d');

$ins = $pdo->prepare("
    INSERT INTO user_deals
        (user_id, deal_id, principal, start_date, end_date,
         daily_target, daily_min, daily_max, payout_mode)
    VALUES
        (:uid, :deal, :pr, :s, :e,
         :tgt, :min, :max, :payout_mode)  
");
$ins->execute([
    'uid'  => $uid,
    'deal' => $dealId,
    'pr'   => $amount,
    's'    => $today,
    'e'    => $end,
    'tgt'  => $dailyTarget,
    'min'  => $dealTpl['rate_without_RIX_min'],
    'max'  => $dealTpl['rate_without_RIX_max'],
    'payout_mode' => $dealTpl['payout_mode'] ?? 'end',
]);

// --- 6. Списываем деньги ---
$pdo->prepare("UPDATE users SET balance = balance - :sum WHERE uid = :uid")
    ->execute(['sum' => $amount, 'uid' => $uid]);

$pdo->commit();

echo json_encode([
    'ok'        => 1,
    'user_deal' => $pdo->lastInsertId(),
    'new_bal'   => $bal - $amount,
]);
