<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

require_once 'functions.php';
require_once 'sendInfoToEmail.php';

/** Реальный IP за прокси/Cloudflare */
function clientIp(): string {
    $candidates = [
        'HTTP_CF_CONNECTING_IP',
        'HTTP_X_REAL_IP',
        'HTTP_X_FORWARDED_FOR', // может содержать список IP → берём первый
        'REMOTE_ADDR',
    ];
    foreach ($candidates as $k) {
        if (!empty($_SERVER[$k])) {
            $v = trim((string)$_SERVER[$k]);
            if ($k === 'HTTP_X_FORWARDED_FOR') {
                $v = trim(explode(',', $v)[0]);
            }
            // 128 в БД хватает; 45 достаточно для IPv6
            return mb_substr($v, 0, 128);
        }
    }
    return '0.0.0.0';
}

/** Примитивная детекция мобильного UA */
function isMobileUa(string $ua): bool {
    $ua = mb_strtolower($ua);
    return (bool)preg_match('/android|iphone|ipad|ipod|mobile|blackberry|opera mini|opera mobi|windows phone|miui|harmonyos/u', $ua);
}

/** Хонепот: скрытое поле должно быть пустым */
function honeypotOk(string $field = 'website'): bool {
    return empty($_POST[$field] ?? '');
}

/** Мини-таймер: форма не должна прилетать быстрее N секунд (защита от ботов) */
function formDelayOk(int $seconds = 3, string $field = 'form_started_at'): bool {
    $t = (int)($_POST[$field] ?? 0);
    return $t > 0 && (time() - $t) >= $seconds;
}

try {
    global $pdo;

    // --- базовые данные окружения ---
    $ip     = clientIp();
    $agent  = mb_substr((string)($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 512);
    $isMob  = isMobileUa($agent) ? 1 : 0;

    // --- простая антибот-проверка (дополняет капчу) ---
    if (!honeypotOk() || !formDelayOk(3)) {
        throw new RuntimeException('Suspicious activity detected: honeypot(' . honeypotOk() . ')' . ', formDelay(' . formDelayOk() . ')');
    }

    $refCode      = isset($_POST['ref_code']) ? mb_strtolower(trim($_POST['ref_code'])) : '';
    $teamId       = 1;     // дефолт
    $referralUid  = 0;     // колонка referral

    if ($refCode !== '') {
        $q = $pdo->prepare('SELECT uid, team_id FROM users WHERE activation = ? LIMIT 1');
        $q->execute([$refCode]);
        if ($row = $q->fetch(PDO::FETCH_ASSOC)) {
            $referralUid = (int)$row['uid'];
            $teamId      = (int)$row['team_id'];
        }
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new RuntimeException('Неверный метод запроса');
    }

    // ---------- входные данные ----------
    $email    = filter_var(trim($_POST['email']   ?? ''), FILTER_VALIDATE_EMAIL);
    $name     = trim($_POST['user_name']  ?? '');
    $surname  = trim($_POST['sur_name']   ?? '');
    $telegram = trim($_POST['telegram']   ?? '');
    $phone    = trim($_POST['phone']      ?? '');
    $password = (string)($_POST['password'] ?? '');

    if (!$email)               throw new RuntimeException('Некорректный E-mail');
    if (!$telegram)            throw new RuntimeException('Укажите Telegram');
    if (mb_strlen($password) < 8) throw new RuntimeException('Пароль короче 8 символов');

    // --- базовый rate-limit по IP за последний час (можно ужесточить) ---
    $rl = $pdo->prepare("SELECT COUNT(*) FROM users WHERE last_login_ip = ? AND create_date > NOW() - INTERVAL 1 HOUR");
    $rl->execute([$ip]);
    if ((int)$rl->fetchColumn() >= 10) { // например, не более 10 регистраций/час с IP
        throw new RuntimeException('Слишком много попыток. Попробуйте позже.');
    }

    // --- MX-проверка домена почты (режим soft-fail) ---
    $domain = substr(strrchr($email, "@"), 1);
    if ($domain && !checkdnsrr($domain, 'MX')) {
        throw new RuntimeException('Почтовый домен не принимает почту (нет MX-записи)');
    }

    // ---------- не занята ли почта ----------
    $stmt = $pdo->prepare('SELECT 1 FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    if ($stmt->fetchColumn()) {
        throw new RuntimeException('Пользователь с таким e-mail уже существует');
    }

    // ---------- вставка пользователя ----------
    $activation = unique_activation(5);

    $ins = $pdo->prepare(
        'INSERT INTO users
           (email, user_name, sur_name, telegram, phone,
            password, activation,
            team_id, referral,
            last_login_ip, last_login_ismobile, last_login_agent)
         VALUES
           (:email, :uname, :sname, :tg, :phone,
            :pwd,   :act,
            :team,  :referral,
            :last_ip, :is_mob, :agent)'
    );

    $ins->execute([
        ':email'    => $email,
        ':uname'    => $name    ?: null,
        ':sname'    => $surname ?: null,
        ':tg'       => $telegram,
        ':phone'    => $phone   ?: null,
        ':pwd'      => $password,
        ':act'      => $activation,
        ':team'     => $teamId,
        ':referral' => $referralUid,

        // <-- добавили запись IP/UA
        ':last_ip'  => $ip,
        ':is_mob'   => $isMob,
        ':agent'    => $agent,
    ]);

    $userId = (int)$pdo->lastInsertId();
    if ($userId <= 0) {
        throw new RuntimeException('Не удалось получить ID нового пользователя');
    }

    // ---------- бонус рефереру ----------
    if ($referralUid > 0) {
        addUserAltcoinAmount($referralUid, 'RIXCOIN', 300);
    }


    // ---------- добавляем призы рулетки, если были переданы ----------
    $roulettePrizes = getRoulettePrizesFromPost('roulette_prize');
    if (!empty($roulettePrizes)) {
        $ownTx = !$pdo->inTransaction();
        if ($ownTx) $pdo->beginTransaction();

        $prizeStmt = $pdo->prepare('INSERT INTO roulette_prize (user_id, prize_token) VALUES (:uid, :token)');
        foreach ($roulettePrizes as $token) {
            $prizeStmt->execute([':uid' => $userId, ':token' => $token]);
        }

        if ($ownTx) $pdo->commit();
    }

    // ---------- письмо активации ----------
    $activationUrl = 'https://' . $_ENV['CLEAR_URL'] . '/verification?code=' . $activation;
    $clearUrl = $_ENV['CLEAR_URL'];
    $body = registrationEmailBody($activationUrl, $clearUrl);
    $result = sendInfoToEmail($email, 'E-mail confirmation', $body);

    echo json_encode(['ok' => true]);

} catch (Throwable $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}
