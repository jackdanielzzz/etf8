<?php
    include($_SERVER['DOCUMENT_ROOT'] . '/../engine/app/functions.php');

//    print_arr($_POST);
//    die();
if (!empty($_POST['id']) and !empty($_POST['principal']) and !empty($_POST['start_date']) and !empty($_POST['end_date']) and ($_POST['accrued_amount']!='') and ($_POST['deal_id']!='') ) {

    if (updateUserDealById($_POST['id'], $_POST['deal_id'], $_POST['principal'], $_POST['start_date'], $_POST['end_date'], $_POST['accrued_amount'], $_POST['status'])) {

        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }


} else {
    echo "Ошибка! Вы пытаетесь сохранить пустое поле!<br><br> вернись назад и заполни нормально)<br><br>";

    echo "Если ошибка повторится - фоткай то что ниже и отправь в отдел разработки:<br>";
    print_arr($_POST);
}

