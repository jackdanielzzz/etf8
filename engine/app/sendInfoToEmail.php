<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendInfoToEmail($email, $title, $body): string
{

    if ($_ENV['DEV_FAKE_SEND_EMAIL'] == 'true'){
        return "success";
    }

// Настройки PHPMailer
    $mail = new PHPMailer();
    try {
//        $mail->SMTPDebug = 3; //Alternative to above constant
        $mail->isSMTP();
        $mail->CharSet = "UTF-8";
        $mail->SMTPAuth = true;

        // Настройки вашей почты
        $mail->Host       = $_ENV['MAIL_HOST']; // SMTP сервера вашей почты
        $mail->Username   = $_ENV['MAIL_USER']; // Логин на почте
        $mail->Password   = $_ENV['MAIL_PASS']; // Пароль на почте
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
        $mail->setFrom($_ENV['MAIL_USER'], $_ENV['MAIL_NAME']); // Адрес самой почты и имя отправителя

        // Получатель письма
        $mail->addAddress($email);

        // Отправка сообщения
        $mail->isHTML(true);
        $mail->Subject = $title;
        $mail->Body = $body;

        // Проверяем отравленность сообщения
        if ($mail->send()) {
            $result = "success";
        } else {
            $result = $mail->ErrorInfo;
        }

    } catch (Exception $e) {
        $result = $mail->ErrorInfo;
    }

    return $result;
}