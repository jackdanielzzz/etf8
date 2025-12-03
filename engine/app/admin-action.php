<?php

require_once __DIR__ . '/../app/functions.php';
//include($_SERVER['DOCUMENT_ROOT'] . '/../engine/app/sendInfoToEmail.php');


//session_start();
checkSession();

if (empty($_SESSION["admin"]) || ($_SESSION['admin'] === 'user')) {
    header("Location: /logout", true, 302);

    exit();
}

if (isset($_GET['action']) && !empty($_GET['action'])) {

    if ($_GET['action'] == 'acceptInput') {
        $result = "start_to_accept_input";
        $currentInput = getInputById($_GET['id']);
//        print_arr($currentInput);
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
                $result = "error: if (acceptInputById)";
            }

        // Отображение результата
        echo json_encode(["result" => $result]);
    }

    if ($_GET['action'] == 'denyInput') {
        $result = "start_to_deny_input";
        $currentInput = getInputById($_GET['id']);
        $currentUser = getUserById($currentInput['user_id']);

        $status = 5; // [5] - system error
        if (denyInputById($currentInput['id'])) {
            $status = 3; // [3] - deny input by admin
            $result = "success";
        }
        logTransaction($currentUser['uid'], $currentUser['uid'], $currentInput['amount_usd'], 100, $currentInput['amount_usd'], $status);

        // Отображение результата
        echo json_encode(["result" => $result]);
    }

    if ($_GET['action'] == 'blockInput') {
        $result = "start_to_block_input";
        $currentInput = getInputById($_GET['id']);
        $currentUser = getUserById($currentInput['user_id']);

        if (blockInputById($currentInput['id'])) {
            logTransaction(1, $currentUser['uid'], $currentInput['amount_usd'], 100, $currentInput['amount_usd'], 14); // [14]- admin block user input
            $result = "success";
        }

        // Отображение результата
        echo json_encode(["result" => $result]);
    }

    if ($_GET['action'] == 'acceptOutput') {
        $result = "start_to_accept_output";
        $currentOutput = getOutputById($_GET['id']);
        $currentUser = getUserById($currentOutput['user_id']);

        if (acceptOutputById($currentOutput['id'])) {
            logTransaction($currentUser['uid'], $currentUser['uid'], $currentOutput['amount_usd'], 100, $currentOutput['amount_usd'], 1); // [1] - accept output by admin
            $result = "success";
        }

        // Отображение результата
        echo json_encode(["result" => $result]);
    }

    if ($_GET['action'] == 'denyOutput') {
        $result = "start_to_deny_output";
        $currentOutput = getOutputById($_GET['id']);
        $currentUser = getUserById($currentOutput['user_id']);


        if (denyOutputById($currentOutput['id'])) {

            if (setUserMoneyById($currentUser['uid'], $currentOutput['amount_usd'])) {
                logTransaction( $currentUser['uid'], $currentUser['uid'], $currentOutput['amount_usd'], 100, $currentOutput['amount_usd'], 4); // [4] - deny output by admin
                $result = "success";
            }
        }

        // Отображение результата
        echo json_encode(["result" => $result]);
    }

    if ($_GET['action'] == 'blockOutput') {
        $result = "start_to_block_output";
        $currentOutput = getOutputById( $_GET['id']);
        $currentUser = getUserById( $currentOutput['user_id']);

        if (blockOutputById( $currentOutput['id'])) {
            logTransaction( 1, $currentUser['uid'], $currentOutput['amount_usd'], 100, $currentOutput['amount_usd'], 12); // [12]- admin block user output
            $result = "success";
        }

        // Отображение результата
        echo json_encode(["result" => $result]);
    }

    if ($_GET['action'] == 'unblockOutput') {
        $result = "start_to_unblock_output";
        $currentOutput = getOutputById($_GET['id']);
        $currentUser = getUserById($currentOutput['user_id']);

        if (unblockOutputById($currentOutput['id'])) {
            logTransaction(1, $currentUser['uid'], $currentOutput['amount_usd'], 100, $currentOutput['amount_usd'], 13); // [13]- admin unblock user output
            $result = "success";
        }

        // Отображение результата
        echo json_encode(["result" => $result]);
    }

    if ($_GET['action'] == 'unblockInput') {
        $result = "start_to_unblock_input";
        $currentInput = getInputById($_GET['id']);
        $currentUser = getUserById($currentInput['user_id']);

        if (unblockInputById($currentInput['id'])) {
            logTransaction(1, $currentUser['uid'], $currentInput['amount_usd'], 100, $currentInput['amount_usd'], 15); // [15]- admin unblock user input
            $result = "success";
        }

        // Отображение результата
        echo json_encode(["result" => $result]);
    }

    if ($_GET['action'] == 'disableUser') {
        $currentUser = getUserById($_GET['id']);
        if ($currentUser) {
            setUserDisabled($currentUser['uid']);
        }

        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

    if ($_GET['action'] == 'enableUser') {
        $currentUser = getUserById($_GET['id']);
        if ($currentUser) {
            setUserEnabled($currentUser['uid']);
        }

        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

    if ($_GET['action'] == 'enableUnderConstruction') {
        if (setUnderConstructionStatus(1))
            header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

    if ($_GET['action'] == 'disableUnderConstruction') {
        if (setUnderConstructionStatus( 0)) {
            setDebugDateStatus( 0);
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
    }

    if ($_GET['action'] == 'enableDebugDateStatus') {
        if (setDebugDateStatus( 1))
            header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
    if ($_GET['action'] == 'disableDebugDateStatus') {
        if (setDebugDateStatus( 0))
            header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

    if ($_GET['action'] == 'deleteDeal') {
        if (!empty($_GET['id'])) {
            if (deleteDealById($_GET['id'])) {
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            }
        }
    }

    if ($_GET['action'] == 'deleteInput') {
        if (!empty($_GET['id'])) {
            $result = "start_to_delete_input";
            if (deleteInputById($_GET['id'])) {
                $result = "success";
            }

            // Отображение результата
            echo json_encode(["result" => $result]);
        }
    }

    if ($_GET['action'] == 'deleteOutput') {
        if (!empty($_GET['id'])) {
            $result = "start_to_delete_output";
            if (deleteOutputById($_GET['id'])) {
                $result = "success";
            }

            // Отображение результата
            echo json_encode(["result" => $result]);
        }
    }

    if ($_GET['action'] == 'manualAddUserInput') {
        if (!empty($_GET['id'])) {
            if (createNewInput($_GET['id'], 1, 1, "Tether (TRC-20)")) {
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            }
        }
    }

    if ($_GET['action'] == 'manualAddUserOutput') {
        if (!empty($_GET['id'])) {
            if (createNewOutput($_GET['id'], 1, "вывод нарисован вручную", "Tether (TRC-20)")) {
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            }
        }
    }

    if ($_GET['action'] == 'manualAddUserDeal') {
        if (!empty($_GET['id'])) {
            if (createNewDeal($_GET['id'], 1, 1, 0)) {
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            }
        }
    }

    if ($_GET['action'] == 'removeFromCheatersList') {
        if (!empty($_GET['id1']) and !empty($_GET['id2'])) {
            if (setHideToUserLoginId($_GET['id1'])) {
                if (setHideToUserLoginId($_GET['id2']))
                    header('Location: ' . $_SERVER['HTTP_REFERER']);
            }
        }
    }
}

