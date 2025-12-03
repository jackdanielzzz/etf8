<?php
    include($_SERVER['DOCUMENT_ROOT'] . '/../engine/app/functions.php');

    if ($_POST['id'] !== '' && $_POST['status'] !== '') {

        if (setStatusTokenRequestById($_POST['id'], $_POST['status'])) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            echo "Ошибка сохранения! <br> <br><br>Информация для отладки:<br>";
            print_arr($_POST);
        }

    } else {
        echo "Ошибка! Вы пытаетесь сохранить пустое поле!<br><br> А ну быстро вернись назад и заполни нормально)<br><br>";

        echo "Если ошибка повторится - фоткай то что ниже и отправь в отдел разработки:<br>";
        print_arr($_POST);
    }

