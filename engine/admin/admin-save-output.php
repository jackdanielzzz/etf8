<?php
    include($_SERVER['DOCUMENT_ROOT'] . '/../engine/app/functions.php');

if (!empty($_POST['output_id']) and !empty($_POST['amount_usd']) and !empty($_POST['date']) ) {

    if (setOutputData($_POST['output_id'], $_POST['amount_usd'], $_POST['method'], $_POST['wallet'], $_POST['date'])) {
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

