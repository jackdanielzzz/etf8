<?php

require_once  '_auth.php';

$userId = (int)$_SESSION['user_id'];

/* ---------- 2) читаем и валидируем ---------- */
$name    = trim($_POST['user_name'] ?? '');
$surname = trim($_POST['sur_name']  ?? '');
$email   = trim($_POST['email']     ?? '');
$phone   = trim($_POST['phone']     ?? '');
$pass    =       $_POST['password'] ?? '';

$errors = [];
if ($name === '')                       $errors['user_name'] = 'Имя обязательно';
if ($surname === '')                    $errors['sur_name']  = 'Фамилия обязательна';
if (!filter_var($email, FILTER_VALIDATE_EMAIL))
    $errors['email']     = 'Некорректный email';
// телефоны бывают разными — простая проверка длины
if ($phone !== '' && strlen($phone) < 7) $errors['phone']    = 'Телефон слишком короткий';

if ($errors) {
    echo json_encode(['error' => 'validation', 'fields' => $errors]);
    exit;
}

/* ---------- 3) строим запрос ---------- */
$set = 'user_name = :n, sur_name = :s, email = :e, phone = :p';
$params = [
    ':n' => $name,
    ':s' => $surname,
    ':e' => $email,
    ':p' => $phone,
];

if ($pass !== '') {               // пароль менять не обязательно
    $set     .= ', password = :pw';
    $params[':pw'] = $pass;
}

$params[':id'] = $userId;

$sql = "UPDATE users SET $set WHERE uid = :id LIMIT 1";
$ok  = $pdo->prepare($sql)->execute($params);

if (!$ok) {
    http_response_code(500);
    echo json_encode(['error' => 'db']);
    exit;
}

echo json_encode([
    'ok'        => true,
    'user_name' => $name,     // вернём, если нужно обновить UI
]);