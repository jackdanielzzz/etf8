<?php

require_once '_auth.php';

$uid = (int)$_SESSION['user_id'];

/* ---------- 2. Входные данные ---------- */
$amountCrypto = (float)($_POST['amount_crypto'] ?? 0);
$amountUsd = (float)($_POST['amount_usd'] ?? 0);
$method       = $_POST['method'];
$txHash       = trim($_POST['tx_hash'] ?? '');

if ($amountCrypto <= 0 || strlen($txHash) < 20) {
    http_response_code(422);
    echo json_encode(['status'=>'error','message'=>'bad_input']);
    exit;
}


/* ---------- 4. Запись в БД ---------- */
$sql = "INSERT INTO inputs
        (user_id, amount_usd, amount_crypto, method, tx_hash)
        VALUES (:uid,:usd,:cry,:m,:h)";
$pdo->prepare($sql)->execute([
    'uid'=>$uid,'usd'=>$amountUsd,'cry'=>$amountCrypto,
    'm'=>$method,'h'=>$txHash
]);

$inputId = (int)$pdo->lastInsertId();
if ($inputId > 0) {
    $currentUser = getUserById($uid);
    $name = $currentUser['user_name'] . ' ' . $currentUser['sur_name'] . ' (' . $currentUser['email'] . ')';
    $refUser = getUserById($currentUser['referral']);
    $message = topUpTgMessage($name, $amountUsd, $method, $amountCrypto, $inputId, $refUser);

    if (sendTgMessage($message)) {
        echo json_encode([
            'status'   => 'success',
            'input_id' => $inputId
        ]);
    } else {
        echo json_encode([
            'status'   => 'error1'
        ]);
    }

} else{
    echo json_encode([
        'status'   => 'error2'
    ]);
}

