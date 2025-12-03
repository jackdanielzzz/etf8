<?php

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/sendInfoToEmail.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['mode'] ?? '') === 'forgot') {
    $email = trim($_POST['email'] ?? '');

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: /login?forgot_error=1'); exit;
    }

    global $pdo;

    // найдём пользователя
    $stmt = $pdo->prepare('SELECT user_name, email, password FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // не раскрываем наличие почты — всегда редиректим с успехом
    if ($user) {
        $name     = $user['user_name'] ?: 'Пользователь';
        $password = (string)$user['password']; // отправим как хранится в БД

        // подгружаем ваш шаблон (если есть) и подставим данные
        $path = $_SERVER['DOCUMENT_ROOT'];
        $tplPath = $path . '/../engine/template/forgot-password.html';
        $tpl = is_file($tplPath) ? file_get_contents($tplPath) : '<p>Ваш пароль: <b>{{PASSWORD}}</b></p>';

        $html = str_replace(
            ['{{NAME}}','{{PASSWORD}}'],
            [
                htmlspecialchars($name, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
                htmlspecialchars($password, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')
            ],
            $tpl
        );

        // отправка письма (используем ваш отправщик если есть)
        if (function_exists('sendInfoToEmail')) {
            sendInfoToEmail($user['email'], 'Ваш пароль для входа', $html);
        } else {
            $headers  = "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            $headers .= "From: ETFRIX <no-reply@etfrix.com>\r\n";
            @mail($user['email'], '=?UTF-8?B?'.base64_encode('Ваш пароль для входа').'?=', $html, $headers);
        }
    }

    header('Location: /login?forgot_sent=1'); exit;
}

// Разрешаем только POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /login'); exit;
}

$email    = trim($_POST['email']    ?? '');
$password = trim($_POST['password'] ?? '');

if ($email === '' || $password === '') {
    header('Location: /login?error=1'); exit;
}

$stmt = $pdo->prepare(
    'SELECT uid, password, admin, active,
            email_status, verified, activation
       FROM users
      WHERE email = :email
      LIMIT 1'
);
$stmt->execute(['email' => $email]);
$user = $stmt->fetch();

if (!$user || $user['active'] !== '1' || ($password !== $user['password'])) {
    // Логируем попытку и редиректим
    header('Location: /login?error=1'); exit;
}

if ($user['email_status'] === '0') {
    // Перебрасываем пользователя обратно на форму логина
    //    ?unverified=1 — всего лишь «флажок» для pre-login.php
    header('Location: /login?unverified=1&email=' . urlencode($email));  // email пригодится для повтора письма, если нужно
    exit;
}

/* 3. Email подтверждён, но аккаунт ещё не верифицирован? */
if ($user['email_status'] === '1' && $user['verified'] === '0') {

    /* — Проверяем, что пользователь уже загрузил ВСЕ 4 файла — */
    $v = getUserVerificationByUserId((int)$user['uid']);
    $allOk = false;
    if ($v) {
        // Собираем все четыре поля в массив
        $files = [
            $v['file1'],
            $v['file2'],
            $v['file3'],
            $v['file4'],
        ];

        // Оставляем только «ненулевые» и «непустые» строки
        $filled = array_filter($files, function($f) {
            // приводим к строке и обрезаем пробелы
            $s = trim((string)$f);
            // Если строка получилась непустой — считается «положительной»
            return $s !== '';
        });

        // Все четыре должны быть заполнены
        $allOk = count($filled) === 4;
    }

    if ($allOk) {                              // файлы загружены → ждём проверку
        header('Location: /login?onreview=1');
    } else {                                   // ничего нет или не всё загружено → страница загрузки
        header('Location: /login?gotoverify=' . urlencode($user['activation']));
    }
    exit;
}

/* ==== авторизация прошла ==== */
$_SESSION['user_id'] = (int)$user['uid'];
$_SESSION['admin']   = $user['admin'];

//session_regenerate_id(true);                // защита от фиксации сессии

// нормализуем данные для логирования
$uid      = (int)$user['uid'];
$ip       = getClientIp();
$agent    = mb_substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 512);
$isMobile = isMobile(); // bool

$pdo->beginTransaction();
try {
    // обновляем last_login в users
    $update = $pdo->prepare(
        'UPDATE users
            SET last_login = NOW(),
                last_login_ip = :ip,
                last_login_agent = :ua,
                last_login_ismobile = :ismobile
          WHERE uid = :uid'
    );
    $update->execute([
        'ip'       => $ip,
        'ua'       => $agent,
        'ismobile' => $isMobile ? 1 : 0,
        'uid'      => $uid,
    ]);

    // логируем вход в user_login
    if (!setNewLoginData($uid, $ip, $isMobile, $agent)) {
        throw new RuntimeException('Failed to insert into user_login');

    }

    $pdo->commit();
} catch (Throwable $e) {
    $pdo->rollBack();
    // здесь можно залогировать ошибку в ваш лог
    // logger()->error('auth_log_error', ['e' => $e]);
}

$lang = strtolower($_COOKIE['lang'] ?? 'ru');
header('Location: /'. $lang.'/account/deals');
//error_log('[LOGIN] BEFORE REDIRECT: SID=' . session_id() . ' DATA=' . json_encode($_SESSION, JSON_UNESCAPED_UNICODE));
exit;
