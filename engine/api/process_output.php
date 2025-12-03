<?php

require_once '_auth.php';   // ← так же, как в других API

$user_id = (int)$_SESSION['user_id'];
$wallet  = trim($_POST['wallet']  ?? '');
$amount  = floatval($_POST['amount'] ?? 0);
$method  = trim($_POST['method']  ?? '');

if ($wallet === '' || $amount <= 0) {
    echo json_encode(['status'=>'error','message'=>'Invalid input']);
    exit;
}

try {
    $currentUser = getUserById($user_id);

    if ($currentUser['balance'] >= $amount) {

        $outputId = createNewOutput($user_id, $amount, $wallet, $method);
        if ($outputId > 0) {
            $name = $currentUser['user_name'] . ' ' . $currentUser['sur_name'] . ' (' . $currentUser['email'] . ')';
            $refUser = getUserById($currentUser['referral']);
            $message = withdrawTgMessage($name, $currentUser['telegram'], $amount, $method, $wallet, $outputId, $refUser);

            if (setUserMoneyById($user_id, -$amount)) {
                logTransaction($user_id, $user_id, $amount, 100, $amount, 10);
                sendTgMessage($message);
                echo json_encode(['status' => 'success']);
            }
        }

    } else {
        echo json_encode(['status' => 'not_enough']);
    }


} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status'=>'error','message'=>'Database error']);
}
