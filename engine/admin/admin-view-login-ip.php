<?php
if (!empty($_GET['ip'])) {
    $details = json_decode(file_get_contents("https://ipinfo.io/{$_GET['ip']}/json"));
    if (isset($details->city)) {
        $city = $details->city;
    } else {
        $city = "не удалось определить город";
    }
} else {
    $city = "IP-адрес не указан";
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Город по IP-адресу</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-weight: bold;
            font-size: 36px;
            text-align: center;
        }
    </style>
</head>
<body>
<div><?php echo $city; ?></div>
</body>
</html>