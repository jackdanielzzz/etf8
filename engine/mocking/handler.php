<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $code = $_POST["code"];

    if ($code == $_ENV['DEV_MOCK_PASS']) {
        setcookie("dev", "mock", time()+60*60*24*7); // Устанавливаем куку на 7 дней
        header('Location: /');
    } else
        echo "code error";
}