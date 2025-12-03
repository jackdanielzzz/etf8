<?php
include($_SERVER['DOCUMENT_ROOT'] . '/../engine/app/functions.php');
$result = "start";
$amount = 0;
$user = null;

if(isset($_POST['type']) && !empty($_POST['type'])){
    if(isset($_POST['id']) && !empty($_POST['id'])){
        $id = $_POST['id'];
        $type = $_POST['type'];

        if ($type == 'input') {
            $transaction = getInputById($id);
        } else if ($type == 'output') {
            $transaction = getOutputById($id);
        }

        if (isset($transaction)) {
            $user = getUserById($transaction['user_id']);
            $amount = $transaction['amount_usd'];
            if ($transaction['status'] == 0 and $transaction['blocked'] == 0) {
                $result = 'success';
            } else {
                $result = 'already_done';
            }

        }

    }
}

// Создаем массив с нужными полями
$data = array(
    "result" => $result,
    "amount" => $amount,
    "usermail" => $user['email']
);

// Преобразуем массив в JSON и выводим результат
echo json_encode($data);