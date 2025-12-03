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

//    print_arr($_POST);
//    die();

if (!empty($_POST['id'])) {

    if ( $currentUser['v_virtual'] !== $_POST['v_virtual'] ||
        $currentUser['v_active_partners'] !== $_POST['v_active_partners'] ||
        $currentUser['v_total_partners'] !== $_POST['v_total_partners']) {
        if (setUserCabinetVirtualData($currentUser['uid'], $_POST['v_virtual'], $_POST['v_active_partners'], $_POST['v_total_partners'])) {
            logTransaction($adminUser['uid'] , $_POST['id'], 0, 0, 0, 25); //Админ изменил статус кабиинета пользователя
        }
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
}
