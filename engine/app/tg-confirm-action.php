<?php

include($_SERVER['DOCUMENT_ROOT'] . '/../engine/app/functions.php');
if (checkTgActions($_POST['action'])) {
    $result = "start checkTgActions...";
    if ($_POST['action'] == 'tg-accept-input') {
        $result = "start_to_accept_input";
        $currentInput = getInputById($_POST['id']);
        $currentUser = getUserById($currentInput['user_id']);

        if ($currentInput['status'] == 0) {
            if (acceptInputById($currentInput['id'])) {
                if (setUserMoneyById($currentUser['uid'], $currentInput['amount_usd'])) {
                    logTransaction($currentUser['uid'], $currentUser['uid'], $currentInput['amount_usd'], 100, $currentInput['amount_usd'], 0);
                    $result = "success";

                    $referralsLvl1 = getUserById($currentUser['referral']);

                    if ($referralsLvl1) {
                        $level1Percent = 5;
                        $money1Percent = floatval($currentInput['amount_usd']) * ($level1Percent / 100);
                        if (setTeamMoneyById($referralsLvl1['uid'], $money1Percent)) {
                            logTransaction($currentUser['uid'], $referralsLvl1['uid'], $currentInput['amount_usd'], $level1Percent, $money1Percent, 2);
                        }


                        $referralsLvl2 = getUserById($referralsLvl1['referral']);
                        if ($referralsLvl2) {
                            $level2Percent = 3;
                            $money2Percent = floatval($currentInput['amount_usd']) * ($level2Percent / 100);
                            if (setTeamMoneyById($referralsLvl2['uid'], $money2Percent)) {
                                logTransaction($currentUser['uid'], $referralsLvl2['uid'], $currentInput['amount_usd'], $level2Percent, $money2Percent, 2);
                            }

                            $referralsLvl3 = getUserById($referralsLvl2['referral']);
                            if ($referralsLvl3) {
                                $level3Percent = 1;
                                $money3Percent = floatval($currentInput['amount_usd']) * ($level3Percent / 100);
                                if (setTeamMoneyById($referralsLvl3['uid'], $money3Percent)) {
                                    logTransaction($currentUser['uid'], $referralsLvl3['uid'], $currentInput['amount_usd'], $level3Percent, $money3Percent, 2);
                                }
                            }
                        }
                    }
                } else {
                    $result = "error: if (setUserMoneyById)";
                }
            }
        } else {
            $result = "already_done";
        }
    }

    if ($_POST['action'] == 'tg-deny-input') {
        $result = "start_to_deny_input";
        $currentInput = getInputById($_POST['id']);
        $currentUser = getUserById($currentInput['user_id']);

        $transactionStatus = 5; // [5] - system error

        if ($currentInput['status'] == 2) { //если статус уже = 2 (denyInput)
            $result = "already_done";
        } else {
            if (denyInputById($currentInput['id'])) {
                $transactionStatus = 3; // [3] - deny input by admin
                $result = "success";
            }
        }
        logTransaction($currentUser['uid'], $currentUser['uid'], $currentInput['amount_usd'], 100, $currentInput['amount_usd'], $transactionStatus);
    }

    if ($_POST['action'] == 'tg-block-input') {
        $result = "start_to_block_input";
        $currentInput = getInputById($_POST['id']);
        $currentUser = getUserById($currentInput['user_id']);
        if ($currentInput['blocked'] == 0) {
            if (blockInputById($currentInput['id'])) {
                logTransaction(1, $currentUser['uid'], $currentInput['amount_usd'], 100, $currentInput['amount_usd'], 14); // [14]- admin block user input
                $result = "success";
            }
        } else {
            $result = "already_done";
        }
    }

    if ($_POST['action'] == 'tg-accept-output') {
        $result = "start_to_accept_output";
        $currentOutput = getOutputById($_POST['id']);
        $currentUser = getUserById($currentOutput['user_id']);

        if ($currentOutput['status'] == 0) {
            if (acceptOutputById($currentOutput['id'])) {
                logTransaction($currentUser['uid'], $currentUser['uid'], $currentOutput['amount_usd'], 100, $currentOutput['amount_usd'], 17); // [17] - accept output by admin via tg bot
                $result = "success";
            }
        } else {
            $result = "already_done";
        }
    }

    if ($_POST['action'] == 'tg-deny-output') {
        $result = "start_to_deny_output";
        $currentOutput = getOutputById($_POST['id']);
        $currentUser = getUserById($currentOutput['user_id']);

        if ($currentOutput['status'] == 2) {
            $result = "already_done";
        } else {
            if (denyOutputById($currentOutput['id'])) {
                if (setUserMoneyById($currentUser['uid'], $currentOutput['amount_usd']))
                    logTransaction($currentUser['uid'], $currentUser['uid'], $currentOutput['amount_usd'], 100, $currentOutput['amount_usd'], 4); // [4] - deny output by admin
                $result = "success";
            }
        }
    }

    if ($_POST['action'] == 'tg-block-output') {
        $result = "start_to_block_output";
        $currentOutput = getOutputById($_POST['id']);
        $currentUser = getUserById($currentOutput['user_id']);

        if ($currentOutput['blocked'] == 0) {
            if (blockOutputById($currentOutput['id'])) {
                logTransaction(1, $currentUser['uid'], $currentOutput['amount_usd'], 100, $currentOutput['amount_usd'], 12); // [12]- admin block user output
                $result = "success";
            }
        } else {
            $result = "already_done";
        }
    }

    // Преобразуем массив в JSON и выводим результат
    echo json_encode(["result" => $result]);
}