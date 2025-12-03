<?php
declare(strict_types=1);

// Нормализация пути
$currentPathRaw = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$currentPath    = '/' . trim($currentPathRaw, '/'); // /foo/ -> /foo

// ⚠️ Публичный JSON — отдать до защиты
if ($currentPath === '/phrases' || $currentPath === '/phrases.json') {
    header('Content-Type: application/json; charset=utf-8');
    $file = __DIR__ . '/phrases.json';
    if (is_file($file)) {
        readfile($file);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'phrases.json not found']);
    }
    exit;
}

// 301-редирект с etfrix.org на etfrix.com
//if (isset($_SERVER['HTTP_HOST'])
//    && in_array($_SERVER['HTTP_HOST'], ['etfrix.org', 'www.etfrix.org'], true)
//) {
//    $uri = $_SERVER['REQUEST_URI'] ?? '/';
//    header('Location: https://etfrix.com' . $uri, true, 301);
//    exit;
//}

// Жёстко задаём политику куки (до session_start)
ini_set('session.cookie_secure', '1');      // только https
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Lax');



if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

//error_log('[CAB] REQUEST COOKIE=' . ($_SERVER['HTTP_COOKIE'] ?? '—') . ' SID=' . session_id() . ' DATA=' . json_encode($_SESSION, JSON_UNESCAPED_UNICODE));

require_once __DIR__ . '/../vendor/autoload.php';   // Composer autoload

use Dotenv\Dotenv;

/* ---------- Загрузка переменных окружения ---------- */
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

/* ---------- Роутер ---------- */
$router = new AltoRouter();

/* ---------- Авторизация ---------- */
function isAuthenticated(): bool
{
    return isset($_SESSION['user_id']) && is_int($_SESSION['user_id']);
}

/* ---------- Глобальная защита всех страниц ---------- */
$currentPath = rtrim(strtok($_SERVER['REQUEST_URI'], '?'), '/');  // /foo/ → /foo

// 🔸 1. «Белый список» публичных URL whitelist
$publicPaths = [
    '/login',            // страница входа
    '/ru',
    '/ru/about',
    '/ru/tokenization',
    '/ru/affiliate',
    '/en',
    '/en/about',
    '/en/tokenization',
    '/en/affiliate',
    '/register-new-user',
    '/referral',
    '/roulette',
    '/verification',
    '/api/is-valid-user',
    '/api/spin-roulette',
    '/api/roulette-config',
    '/api/sell-nft',
    '/api/deal-request',
    '/api/upload_verification',
    '/api/transfer_team_balance',
    '/api/transfer_promo_balance',
    '/admin/tg-confirm',
    '/admin/tg-confirm-action',
    '/admin/tg-get-info',
    '/phrases',
    '/phrases.json'

];

// true, если запрос пришёл ajax-ом (fetch / XHR)
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH'])
    && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

if (!isAuthenticated() && !in_array($currentPath, $publicPaths, true)) {

    // 🔸 2. Отдаём 401 для ajax, иначе — редирект
    if ($isAjax) {
        http_response_code(401);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['ok' => false, 'error' => 'auth_required']);
    } else {
        header('Location: /login');
    }
    exit;
}


/* ---------- Автопереключение языка ---------- */
$router->map('GET', '/', function () {
    if (isset($_COOKIE['lang'])) {
        if      ($_COOKIE['lang'] === 'ru') header('Location: /ru');
        elseif  ($_COOKIE['lang'] === 'en') header('Location: /en');
        elseif  ($_COOKIE['lang'] === 'cn') header('Location: /cn');
        elseif  ($_COOKIE['lang'] === 'ar') header('Location: /ar');
    } else {
        header('Location: /en');
    }
});

$router->map('GET', '/_session', function () {
    header('Content-Type: text/plain; charset=utf-8');
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    echo "SID: " . session_id() . PHP_EOL;
    var_dump($_SESSION);
});

/* -------------------- <ru> -------------------- */
$router->map('GET', '/ru', function () {
    include __DIR__ . '/../engine/site/ru/blocks/header.php';
    require __DIR__ . '/../engine/site/ru/main-page.php';
    include __DIR__ . '/../engine/site/ru/blocks/footer.php';
});

$router->map('GET', '/ru/about', function () {
    include __DIR__ . '/../engine/site/ru/blocks/header.php';
    require __DIR__ . '/../engine/site/ru/main-about.php';
    include __DIR__ . '/../engine/site/ru/blocks/footer.php';
});

$router->map('GET', '/ru/affiliate', function () {
    include __DIR__ . '/../engine/site/ru/blocks/header.php';
    require __DIR__ . '/../engine/site/ru/main-affiliate.php';
    include __DIR__ . '/../engine/site/ru/blocks/footer.php';
});

$router->map('GET', '/ru/faq', function () {
    include __DIR__ . '/../engine/site/ru/blocks/header.php';
    require __DIR__ . '/../engine/site/ru/main-faq.php';
    include __DIR__ . '/../engine/site/ru/blocks/footer.php';
});

$router->map('GET', '/ru/tokenization', function () {
    include __DIR__ . '/../engine/site/ru/blocks/header.php';
    require __DIR__ . '/../engine/site/ru/main-tokenization.php';
    include __DIR__ . '/../engine/site/ru/blocks/footer.php';
});

/* ---- Кабинет ---- */
$router->map('GET', '/ru/account/deals', function () {
    include __DIR__ . '/../engine/site/ru/blocks/headerCab.php';
    require __DIR__ . '/../engine/site/ru/account-deals.php';
    include __DIR__ . '/../engine/site/ru/blocks/footerCab.php';
});

$router->map('GET', '/ru/account/team', function () {
    include __DIR__ . '/../engine/site/ru/blocks/headerCab.php';
    require __DIR__ . '/../engine/site/ru/account-team.php';
    include __DIR__ . '/../engine/site/ru/blocks/footerCab.php';
});

$router->map('GET', '/ru/account/promo', function () {
    include __DIR__ . '/../engine/site/ru/blocks/headerCab.php';
    require __DIR__ . '/../engine/site/ru/account-promo.php';
    include __DIR__ . '/../engine/site/ru/blocks/footerCab.php';
});

$router->map('GET', '/ru/account/rix', function () {
    include __DIR__ . '/../engine/site/ru/blocks/headerCab.php';
    require __DIR__ . '/../engine/site/ru/account-rix.php';
    include __DIR__ . '/../engine/site/ru/blocks/footerCab.php';
});

$router->map('GET', '/ru/account/profile', function () {
    include __DIR__ . '/../engine/site/ru/blocks/headerCab.php';
    require __DIR__ . '/../engine/site/ru/account-profile.php';
    include __DIR__ . '/../engine/site/ru/blocks/footerCab.php';
});

/* -------------------- </ru> ------------------- */

/* -------------------- <en> -------------------- */
$router->map('GET', '/en', function () {
    include __DIR__ . '/../engine/site/en/blocks/header.php';
    require __DIR__ . '/../engine/site/en/main-page.php';
    include __DIR__ . '/../engine/site/en/blocks/footer.php';
});

$router->map('GET', '/en/about', function () {
    include __DIR__ . '/../engine/site/en/blocks/header.php';
    require __DIR__ . '/../engine/site/en/main-about.php';
    include __DIR__ . '/../engine/site/en/blocks/footer.php';
});

$router->map('GET', '/en/affiliate', function () {
    include __DIR__ . '/../engine/site/en/blocks/header.php';
    require __DIR__ . '/../engine/site/en/main-affiliate.php';
    include __DIR__ . '/../engine/site/en/blocks/footer.php';
});

$router->map('GET', '/en/faq', function () {
    include __DIR__ . '/../engine/site/en/blocks/header.php';
    require __DIR__ . '/../engine/site/en/main-faq.php';
    include __DIR__ . '/../engine/site/en/blocks/footer.php';
});

$router->map('GET', '/en/tokenization', function () {
    include __DIR__ . '/../engine/site/en/blocks/header.php';
    require __DIR__ . '/../engine/site/en/main-tokenization.php';
    include __DIR__ . '/../engine/site/en/blocks/footer.php';
});

/* ---- Кабинет ---- */
$router->map('GET', '/en/account/deals', function () {
    include __DIR__ . '/../engine/site/en/blocks/headerCab.php';
    require __DIR__ . '/../engine/site/en/account-deals.php';
    include __DIR__ . '/../engine/site/en/blocks/footerCab.php';
});

$router->map('GET', '/en/account/team', function () {
    include __DIR__ . '/../engine/site/en/blocks/headerCab.php';
    require __DIR__ . '/../engine/site/en/account-team.php';
    include __DIR__ . '/../engine/site/en/blocks/footerCab.php';
});

$router->map('GET', '/en/account/promo', function () {
    include __DIR__ . '/../engine/site/en/blocks/headerCab.php';
    require __DIR__ . '/../engine/site/en/account-promo.php';
    include __DIR__ . '/../engine/site/en/blocks/footerCab.php';
});

$router->map('GET', '/en/account/rix', function () {
    include __DIR__ . '/../engine/site/en/blocks/headerCab.php';
    require __DIR__ . '/../engine/site/en/account-rix.php';
    include __DIR__ . '/../engine/site/en/blocks/footerCab.php';
});

$router->map('GET', '/en/account/profile', function () {
    include __DIR__ . '/../engine/site/en/blocks/headerCab.php';
    require __DIR__ . '/../engine/site/en/account-profile.php';
    include __DIR__ . '/../engine/site/en/blocks/footerCab.php';
});

/* -------------------- </en> ------------------- */

/* -------------------- <cn> -------------------- */
$router->map('GET', '/cn', function () {
    include __DIR__ . '/../engine/site/cn/blocks/header.php';
    require __DIR__ . '/../engine/site/cn/main-page.php';
    include __DIR__ . '/../engine/site/cn/blocks/footer.php';
});

$router->map('GET', '/cn/about', function () {
    include __DIR__ . '/../engine/site/cn/blocks/header.php';
    require __DIR__ . '/../engine/site/cn/main-about.php';
    include __DIR__ . '/../engine/site/cn/blocks/footer.php';
});

$router->map('GET', '/cn/affiliate', function () {
    include __DIR__ . '/../engine/site/cn/blocks/header.php';
    require __DIR__ . '/../engine/site/cn/main-affiliate.php';
    include __DIR__ . '/../engine/site/cn/blocks/footer.php';
});

$router->map('GET', '/cn/faq', function () {
    include __DIR__ . '/../engine/site/cn/blocks/header.php';
    require __DIR__ . '/../engine/site/cn/main-faq.php';
    include __DIR__ . '/../engine/site/cn/blocks/footer.php';
});

$router->map('GET', '/cn/tokenization', function () {
    include __DIR__ . '/../engine/site/cn/blocks/header.php';
    require __DIR__ . '/../engine/site/cn/main-tokenization.php';
    include __DIR__ . '/../engine/site/cn/blocks/footer.php';
});

/* ---- Кабинет ---- */
$router->map('GET', '/cn/account/deals', function () {
    include __DIR__ . '/../engine/site/cn/blocks/headerCab.php';
    require __DIR__ . '/../engine/site/cn/account-deals.php';
    include __DIR__ . '/../engine/site/cn/blocks/footerCab.php';
});

$router->map('GET', '/cn/account/team', function () {
    include __DIR__ . '/../engine/site/cn/blocks/headerCab.php';
    require __DIR__ . '/../engine/site/cn/account-team.php';
    include __DIR__ . '/../engine/site/cn/blocks/footerCab.php';
});

$router->map('GET', '/cn/account/promo', function () {
    include __DIR__ . '/../engine/site/cn/blocks/headerCab.php';
    require __DIR__ . '/../engine/site/cn/account-promo.php';
    include __DIR__ . '/../engine/site/cn/blocks/footerCab.php';
});

$router->map('GET', '/cn/account/rix', function () {
    include __DIR__ . '/../engine/site/cn/blocks/headerCab.php';
    require __DIR__ . '/../engine/site/cn/account-rix.php';
    include __DIR__ . '/../engine/site/cn/blocks/footerCab.php';
});

$router->map('GET', '/cn/account/profile', function () {
    include __DIR__ . '/../engine/site/cn/blocks/headerCab.php';
    require __DIR__ . '/../engine/site/cn/account-profile.php';
    include __DIR__ . '/../engine/site/cn/blocks/footerCab.php';
});

/* -------------------- </cn> ------------------- */

/* -------------------- <ar> -------------------- */
$router->map('GET', '/ar', function () {
    include __DIR__ . '/../engine/site/ar/blocks/header.php';
    require __DIR__ . '/../engine/site/ar/main-page.php';
    include __DIR__ . '/../engine/site/ar/blocks/footer.php';
});

$router->map('GET', '/ar/about', function () {
    include __DIR__ . '/../engine/site/ar/blocks/header.php';
    require __DIR__ . '/../engine/site/ar/main-about.php';
    include __DIR__ . '/../engine/site/ar/blocks/footer.php';
});

$router->map('GET', '/ar/affiliate', function () {
    include __DIR__ . '/../engine/site/ar/blocks/header.php';
    require __DIR__ . '/../engine/site/ar/main-affiliate.php';
    include __DIR__ . '/../engine/site/ar/blocks/footer.php';
});

$router->map('GET', '/ar/faq', function () {
    include __DIR__ . '/../engine/site/ar/blocks/header.php';
    require __DIR__ . '/../engine/site/ar/main-faq.php';
    include __DIR__ . '/../engine/site/ar/blocks/footer.php';
});

$router->map('GET', '/ar/tokenization', function () {
    include __DIR__ . '/../engine/site/ar/blocks/header.php';
    require __DIR__ . '/../engine/site/ar/main-tokenization.php';
    include __DIR__ . '/../engine/site/ar/blocks/footer.php';
});

/* ---- Кабинет ---- */
$router->map('GET', '/ar/account/deals', function () {
    include __DIR__ . '/../engine/site/ar/blocks/headerCab.php';
    require __DIR__ . '/../engine/site/ar/account-deals.php';
    include __DIR__ . '/../engine/site/ar/blocks/footerCab.php';
});

$router->map('GET', '/ar/account/team', function () {
    include __DIR__ . '/../engine/site/ar/blocks/headerCab.php';
    require __DIR__ . '/../engine/site/ar/account-team.php';
    include __DIR__ . '/../engine/site/ar/blocks/footerCab.php';
});

$router->map('GET', '/ar/account/promo', function () {
    include __DIR__ . '/../engine/site/ar/blocks/headerCab.php';
    require __DIR__ . '/../engine/site/ar/account-promo.php';
    include __DIR__ . '/../engine/site/ar/blocks/footerCab.php';
});

$router->map('GET', '/ar/account/rix', function () {
    include __DIR__ . '/../engine/site/ar/blocks/headerCab.php';
    require __DIR__ . '/../engine/site/ar/account-rix.php';
    include __DIR__ . '/../engine/site/ar/blocks/footerCab.php';
});

$router->map('GET', '/ar/account/profile', function () {
    include __DIR__ . '/../engine/site/ar/blocks/headerCab.php';
    require __DIR__ . '/../engine/site/ar/account-profile.php';
    include __DIR__ . '/../engine/site/ar/blocks/footerCab.php';
});

/* -------------------- </ar> ------------------- */

/* ---------- Страница логина (доступн всем) ---------- */
$router->map('GET',  '/login', function () {
    include __DIR__ . '/../engine/site/ru/blocks/headerLogin.php';
    require __DIR__ . '/../engine/site/ru/pre-login.php';
    include __DIR__ . '/../engine/site/ru/blocks/footerLogin.php';
});

$router->map('GET',  '/referral', function () {
    include __DIR__ . '/../engine/site/ru/blocks/headerLogin.php';
    require __DIR__ . '/../engine/site/ru/pre-login.php';
    include __DIR__ . '/../engine/site/ru/blocks/footerLogin.php';
});

$router->map('GET',  '/roulette', function () {
    include __DIR__ . '/../engine/site/ru/blocks/headerLogin.php';
    require __DIR__ . '/../engine/site/ru/pre-login.php';
    include __DIR__ . '/../engine/site/ru/blocks/footerLogin.php';
});

$router->map('GET',  '/verification', function () {
    include __DIR__ . '/../engine/site/ru/blocks/headerLogin.php';
    require __DIR__ . '/../engine/site/ru/pre-verification.php';
    include __DIR__ . '/../engine/site/ru/blocks/footerLogin.php';
});

$router->map('POST', '/login', function () {
    require __DIR__ . '/../engine/app/login.php';     // см. пункт 5 выше
});

$router->map('GET', '/logout', function () {
    require __DIR__ . '/../engine/app/logout.php';     // см. пункт 5 выше
});

/* ---------- Прочие сервисные роуты (при необходимости) ---------- */
$router->map('POST', '/code-handler', function () {
    require __DIR__ . '/../engine/mocking/handler.php';
});

$router->map('POST', '/api/invest', function () {
    require __DIR__ . '/../engine/api/invest.php';
});

$router->map('POST', '/api/deal-request', function () {
    require __DIR__ . '/../engine/api/request-deal.php';
});

$router->map('POST', '/api/set-region', function () {
    require __DIR__ . '/../engine/api/set-region.php';
});

$router->map('POST', '/api/approve-new-deal', function () {
    require __DIR__ . '/../engine/api/approve-new-deal.php';
});

$router->map('POST', '/api/approve-closed-deal', function () {
    require __DIR__ . '/../engine/api/approve-closed-deal.php';
});

//$router->map('GET', '/api/cron', function () {
//    require __DIR__ . '/../engine/cron_actual.php';
//});
//
//$router->map('GET', '/api/reset', function () {
//    require __DIR__ . '/../engine/reset.php';
//});

$router->map('POST', '/api/is-valid-user', function () {
    require __DIR__ . '/../engine/api/r-is-valid-user.php';
});

$router->map('POST', '/api/spin-roulette', function () {
    require __DIR__ . '/../engine/api/r-spin-roulette.php';
});

$router->map('POST|OPTIONS', '/api/roulette-config', function () {
    require __DIR__ . '/../engine/api/r-roulette-config.php';
});

$router->map('POST', '/api/sell-nft', function () {
    require __DIR__ . '/../engine/api/sell-nft.php';
});

//$router->map('GET', '/api/assets', function () {
//    require __DIR__ . '/../engine/app/update_assets.php';
//});

$router->map('POST', '/api/process_statistics', function () {
    require __DIR__ . '/../engine/api/process_statistics.php';
});

$router->map('POST', '/api/process_tokenization', function () {
    require __DIR__ . '/../engine/api/process_tokenization.php';
});

$router->map('POST', '/api/process_input', function () {
    require __DIR__ . '/../engine/api/process_input.php';
});

$router->map('POST', '/api/process_output', function () {
    require __DIR__ . '/../engine/api/process_output.php';
});

$router->map('POST', '/api/update-profile', function () {
    require __DIR__ . '/../engine/api/update_profile.php';
});

$router->map('POST', '/register-new-user', function () {
    require __DIR__ . '/../engine/app/register-new-user.php';
});

$router->map('POST', '/api/upload_verification', function () {
    require __DIR__ . '/../engine/api/upload_verification.php';
});

$router->map('POST', '/api/transfer_team_balance', function () {
    require __DIR__ . '/../engine/api/transfer_team_balance.php';
});

$router->map('POST', '/api/transfer_promo_balance', function () {
    require __DIR__ . '/../engine/api/transfer_promo_balance.php';
});

$router->map('GET', '/admin/tg-confirm', function () {
    require __DIR__ . '/../engine/app/tg-confirm-form.php';
});

$router->map('POST', '/admin/tg-confirm-action', function () {
    require __DIR__ . '/../engine/app/tg-confirm-action.php';
});

$router->map('POST', '/admin/tg-get-info', function () {
    require __DIR__ . '/../engine/app/tg-get-info.php';
});

$router->map('GET', '/phrases', function () {
    header('Content-Type: application/json; charset=utf-8');
    $file = __DIR__ . '/../http/phrases.json';
    if (is_file($file)) {
        readfile($file);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'phrases.json not found']);
    }
});

$router->map('GET', '/phrases.json', function () {   // ← добавили роут
    header('Content-Type: application/json; charset=utf-8');
    $file = __DIR__ . '/../http/phrases.json';
    if (is_file($file)) {
        readfile($file);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'phrases.json not found']);
    }
});

///////////////////////////////// admin ///////////////////////////////

$router->map('GET', '/admin/action', function () {
    require __DIR__ . '/../engine/app/admin-action.php';
});

$router->map('POST', '/admin/save-active', function () {
    require __DIR__ . '/../engine/admin/admin-save-active.php';
});

$router->map('POST', '/admin/save-input', function () {
    require __DIR__ . '/../engine/admin/admin-save-input.php';
});

$router->map('POST', '/admin/save-user', function () {
    require __DIR__ . '/../engine/admin/admin-save-user.php';
});

$router->map('POST', '/admin/save-user-virtual', function () {
    require __DIR__ . '/../engine/admin/admin-save-user-virt.php';
});

$router->map('POST', '/admin/save-output', function () {
    require __DIR__ . '/../engine/admin/admin-save-output.php';
});

$router->map('POST', '/admin/save-debug-date', function () {
    require __DIR__ . '/../engine/admin/admin-save-debug-date.php';
});

$router->map('GET', '/admin/edit-user', function () {
    require __DIR__ . '/../engine/admin/admin-edit-user.php';
});

$router->map('GET', '/admin/edit-cheater', function () {
    require __DIR__ . '/../engine/admin/admin-edit-cheater-ip.php';
});

$router->map('GET', '/admin/edit-input', function () {
    require __DIR__ . '/../engine/admin/admin-edit-input.php';
});

$router->map('GET', '/admin/faq', function () {
    require __DIR__ . '/../engine/admin/admin-edit-faq.php';
});

$router->map('GET', '/admin/logins', function () {
    require __DIR__ . '/../engine/admin/adminLogins.php';
});

$router->map('GET', '/admin/login-view-ip', function () {
    require __DIR__ . '/../engine/admin/admin-view-login-ip.php';
});

$router->map('POST', '/admin/faq/save', function () {
    require __DIR__ . '/../engine/admin/admin-save-faq.php';
});

$router->map('GET', '/admin/edit-output', function () {
    require __DIR__ . '/../engine/admin/admin-edit-output.php';
});

$router->map('GET', '/admin/edit-deal-request', function () {
    require __DIR__ . '/../engine/admin/admin-edit-deal-request.php';
});

$router->map('POST', '/admin/save-deal-request', function () {
    require __DIR__ . '/../engine/admin/admin-save-deal-request.php';
});

$router->map('GET', '/admin/edit-token-request', function () {
    require __DIR__ . '/../engine/admin/admin-edit-token-request.php';
});

$router->map('POST', '/admin/save-token-request', function () {
    require __DIR__ . '/../engine/admin/admin-save-token-request.php';
});

$router->map('GET', '/admin/edit-active', function () {
    require __DIR__ . '/../engine/admin/admin-edit-active.php';
});

$router->map('GET', '/admin/edit-deal', function () {
    require __DIR__ . '/../engine/admin/admin-edit-deal.php';
});

$router->map('POST', '/admin/save-deal', function () {
    require __DIR__ . '/../engine/admin/admin-save-deal.php';
});

$router->map('GET', '/admin/inputs', function () {
    require __DIR__ . '/../engine/admin/adminInputs.php';
});

$router->map('GET', '/admin/deals', function () {
    require __DIR__ . '/../engine/admin/adminDeals.php';
});

$router->map('GET', '/admin/outputs', function () {
    require __DIR__ . '/../engine/admin/adminOutputs.php';
});

$router->map('GET', '/admin/actives', function () {
    require __DIR__ . '/../engine/admin/adminActives.php';
});

$router->map('GET', '/admin/tokenization', function () {
    require __DIR__ . '/../engine/admin/adminTokenization.php';
});

$router->map('GET', '/admin/mentor-requests', function () {
    require __DIR__ . '/../engine/admin/adminDealRequests.php';
});

$router->map('GET', '/admin/verification', function () {
    require __DIR__ . '/../engine/admin/adminVerification.php';
});

$router->map('GET', '/admin/refs', function () {
    require __DIR__ . '/../engine/admin/adminRefpay.php';
});

$router->map('GET', '/admin/accounts', function () {
    require __DIR__ . '/../engine/admin/adminAllAccounts.php';
});

$router->map('GET', '/admin/promo', function () {
    require __DIR__ . '/../engine/admin/adminPromo.php';
});

$router->map('GET', '/admin/promo/new', function () {
    require __DIR__ . '/../engine/admin/admin-new-promo.php';
});

$router->map('GET', '/admin/promo/edit', function () {
    require __DIR__ . '/../engine/admin/admin-edit-promo.php';
});

$router->map('POST', '/admin/promo/save', function () {
    require __DIR__ . '/../engine/admin/admin-save-promo.php';
});

$router->map('GET', '/admin/promo/delete', function () {
    require __DIR__ . '/../engine/admin/admin-delete-promo.php';
});

$router->map('GET', '/admin/roulette', function () {
    require __DIR__ . '/../engine/admin/adminRouletteItems.php';
});

$router->map('GET', '/admin/roulette/new', function () {
    require __DIR__ . '/../engine/admin/admin-new-roulette.php';
});

$router->map('GET', '/admin/roulette/edit', function () {
    require __DIR__ . '/../engine/admin/admin-edit-roulette.php';
});

$router->map('GET', '/admin/settings', function () {
    require __DIR__ . '/../engine/admin/adminSettings.php';
});

//$router->map('GET', '/admin/wallet-settings', function () {
//    require __DIR__ . '/../engine/admin/adminWalletSettings.php';
//});

$router->map('GET', '/admin/variable-percent', function () {
    require __DIR__ . '/../engine/admin/adminVariablePercent.php';
});

$router->map('GET', '/admin/cheaters', function () {
    require __DIR__ . '/../engine/admin/adminCheaters.php';
});

$router->map('GET', '/admin', function () {
    session_start(); // обязательно, если не стартована

    if (!isset($_SESSION['admin'])) {
        header('Location: /logout', true, 302);
        exit();
    }

    if ($_SESSION['admin'] === 'admin') {
        header('Location: /admin/inputs', true, 302);
        exit();
    }

    if ($_SESSION['admin'] === 'manager') {
        $userId = (int)($_SESSION['user_id'] ?? 0);
        header("Location: /admin/edit-user?id={$userId}", true, 302);
        exit();
    }

    // если роль неизвестна — разлогиниваем
    header('Location: /logout', true, 302);
    exit();
});

//------------------------------end-admin--------------------------//

/* ---------- Диспетчер ---------- */
$match = $router->match();

if (is_array($match) && is_callable($match['target'])) {
    call_user_func_array($match['target'], $match['params']);
    exit;
}

/* ---------- 404 ---------- */
echo htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES | ENT_HTML5) . '<br><br>';
echo '<h1>404 – Page not found</h1>';
