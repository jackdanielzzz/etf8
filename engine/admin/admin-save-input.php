<?php
    include($_SERVER['DOCUMENT_ROOT'] . '/../engine/app/functions.php');

//    print_arr($_POST);

if (!empty($_POST['input_id']) and !empty($_POST['amount_usd']) and !empty($_POST['date']) ) {

    if (setInputData($_POST['input_id'], $_POST['amount_usd'], $_POST['amount_crypto'], $_POST['method'], $_POST['date'])) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    } else {
        echo "Ошибка сохранения! <br> <br><br>Информация для отладки:";
        print_arr($_POST);
    }


} else {
    echo "Ошибка! Вы пытаетесь сохранить пустое поле!<br><br> А ну быстро вернись назад и заполни нормально)<br><br>";

    echo "Если ошибка повторится - фоткай то что ниже и отправь в отдел разработки:<br>";
    print_arr($_POST);
}

