<?php
    include($_SERVER['DOCUMENT_ROOT'] . '/../engine/app/functions.php');

if (!empty($_POST['debug_date'])) {
    $realDateNow = new DateTime();
    $dateNow = new DateTime($_POST['debug_date']);

    if ($realDateNow->getTimestamp() > $dateNow->getTimestamp()) {
        echo "Нельзя ставить дату в прошлом, нажми назад и укажи правильную дату! <br><br>";
    } elseif ($realDateNow->getTimestamp() < $dateNow->getTimestamp()) {
        if (setDebugDate($connect, $_POST['debug_date']))
            header('Location: ' . $_SERVER['HTTP_REFERER']);
    } else {
        echo "Даты равны.";
    }

}
