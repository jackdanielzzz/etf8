<?php
    include($_SERVER['DOCUMENT_ROOT'] . '/../engine/app/functions.php');

    // Сессию вторично не запускаем
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

//    header('Content-Type: application/json');

    /* ---------- 1. Авторизация ---------- */
    if (empty($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['status'=>'error','message'=>'Unauthorized']);  // единый формат
        exit;
    }

    $currentUser = getUserById($_POST['id']);
    $adminUser = getUserById($_SESSION['user_id']);

    if ($adminUser['admin'] === 'user') {
        http_response_code(401);
        echo json_encode(['status'=>'error','message'=>'Unauthorized']);  // единый формат
        exit;
    }

//    print_arr($currentUser);
//    die();

if (!empty($_POST['id'])) {

    if ($currentUser['user_name'] !== $_POST['user_name'] || $currentUser['sur_name'] !== $_POST['sur_name']) {
        if (setUserNames($currentUser['uid'], $_POST['user_name'], $_POST['sur_name'])) {
            logTransaction($adminUser['uid'], $_POST['id'], 0, 0, 0, 23); //Админ изменил имя и фамилию пользователя
        }
    }

    if ($currentUser['email_status'] !== $_POST['email_status']) {
        if (setUserEmailStatus($currentUser['uid'], $_POST['email_status'])) {
            logTransaction($adminUser['uid'] , $_POST['id'], 0, 0, 0, 24); //Админ изменил email статус пользователя
        }
    }

    if ( $currentUser['status'] !== $_POST['status'] ||
        $currentUser['active'] !== $_POST['active'] ||
        $currentUser['rating'] !== $_POST['rating'] ||
        $currentUser['create_date'] !== $_POST['create_date'] ||
        $currentUser['verified'] !== $_POST['verified']) {
        if (setUserCabinetStatus($currentUser['uid'], $_POST['status'], $_POST['active'], $_POST['rating'], $_POST['verified'], $_POST['create_date'])) {
            logTransaction($adminUser['uid'] , $_POST['id'], 0, 0, 0, 25); //Админ изменил статус кабиинета пользователя
        }
    }


    if ($currentUser['email'] !== $_POST['email']) {
        $newEmail = preg_replace('/\s+/', '', $_POST['email']);
        if (setUserEmail($currentUser['uid'], $newEmail)) {
            logTransaction($adminUser['uid'] , $_POST['id'], 0, 0, 0, 24); //Админ изменил email статус пользователя
        }
    }

    if ($currentUser['referral'] !== $_POST['referral']) {
        if (setUserReferral($currentUser['uid'], $_POST['referral'])) {
            logTransaction($adminUser['uid'] , $_POST['id'], 0, 0, 0, 22); //Админ изменил реферала пользователю
        }
    }

    if ((int)$currentUser['team_id'] !== (int)$_POST['team_id']) {
        if (setUserTeamId($currentUser['uid'], (int)$_POST['team_id'])) {
            logTransaction($adminUser['uid'], $_POST['id'], 0, 0, 0, 27); //Админ изменил команду пользователя
        }
    }

    if ($currentUser['password'] !== $_POST['password']) {
       if (setUserPassword($currentUser['uid'], $_POST['password'])) {
           logTransaction($adminUser['uid'] , $_POST['id'], 0, 0, 0, 26); //Админ изменил пароль пользователя
        }
    }

    if ($currentUser['balance'] !== $_POST['balance']) {
        if (setBalanceById($_POST['id'], $_POST['balance'])) {
            logTransaction($adminUser['uid'] , $_POST['id'], $currentUser['balance'], 0, $_POST['balance'], 20); //Админ изменил баланс пользователя
        }
    }

    if ($currentUser['balance_team'] !== $_POST['balance_team']) {
        if (setBalanceTeamById($_POST['id'], $_POST['balance_team'])) {
            logTransaction($adminUser['uid'], $_POST['id'], $currentUser['balance_team'], 0, $_POST['balance_team'], 20);
        }
    }

    if ($currentUser['balance_promo'] !== $_POST['balance_promo']) {
        if (setBalancePromoById($_POST['id'], $_POST['balance_promo'])) {
            logTransaction($adminUser['uid'], $_POST['id'], $currentUser['balance_promo'], 0, $_POST['balance_promo'], 20);
        }
    }

    if ($currentUser['total_accrued'] !== $_POST['total_accrued']) {
        if (setTotalAccruedById($_POST['id'], $_POST['total_accrued'])) {
            logTransaction($adminUser['uid'], $_POST['id'], $currentUser['total_accrued'], 0, $_POST['total_accrued'], 20);
        }
    }

    if ($currentUser['total_team_accrued'] !== $_POST['total_team_accrued']) {
        if (setTotalTeamAccruedById($_POST['id'], $_POST['total_team_accrued'])) {
            logTransaction($adminUser['uid'], $_POST['id'], $currentUser['total_team_accrued'], 0, $_POST['total_team_accrued'], 20);
        }
    }

    if ($currentUser['total_promo_accrued'] !== $_POST['total_promo_accrued']) {
        if (setTotalPromoAccruedById($_POST['id'], $_POST['total_promo_accrued'])) {
            logTransaction($adminUser['uid'], $_POST['id'], $currentUser['total_promo_accrued'], 0, $_POST['total_promo_accrued'], 20);
        }
    }

    if ($currentUser['roulette_coin'] !== $_POST['roulette_coin']) {
        if (setRixCoinById($_POST['id'], $_POST['roulette_coin'])) {
            logTransaction($adminUser['uid'], $_POST['id'], $currentUser['roulette_coin'], 0, $_POST['roulette_coin'], 20);
        }
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
}
