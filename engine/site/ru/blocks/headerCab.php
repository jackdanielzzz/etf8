<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/../engine/app/functions.php';

checkSession();

/* --- ДАННЫЕ ТЕКУЩЕГО ПОЛЬЗОВАТЕЛЯ --- */
$currentUser = getUserById($_SESSION['user_id']);
if (!$currentUser) {
    echo 'Ошибка с получением данных пользователя';
    exit;
}


$uid           = (int)$currentUser['uid'];
$teamId        = (int)$currentUser['team_id'];
$balance       = $currentUser['balance'];
$balanceTeam   = $currentUser['balance_team'];
$balancePromo  = $currentUser['balance_promo'];
$totalPromoAccrued  = $currentUser['total_promo_accrued'];
$totalTeamAccrued  = $currentUser['total_team_accrued'];
$regions       = getAllRegions();
$allDeals      = getUserDeals($uid);
$dealsByRegion = getDealsByRegion($teamId);
$flashDeals    = filterFlashDeals($dealsByRegion);
$dealRequests  = getUserDealRequestStatuses($uid);

define("DEV_DATE_FILE", dirname(__DIR__, 4) . '/engine/dev_date.txt');

if (($_ENV['APP_TYPE'] == 'dev') and (file_exists(DEV_DATE_FILE))) {
    $today = trim(file_get_contents(DEV_DATE_FILE));
} else {
    $today = date('Y-m-d');
}

$dailyIncomeByDeal = getUserDailyIncomeByDeal($uid, $today);
$dailyIncomeTotal  = getUserDailyIncomeTotal($uid, $today);
$quarterIncome     = getUserQuarterIncomeTotal($uid, $today);
$assetsToDailyIncomePercent = getAssetsToDailyIncomePercent($allDeals, $dailyIncomeByDeal);

$chartData = getUserDataForPercentChart($uid, $today);
$chartDataTeam = getTeamIncomeChartData($uid);
$assetQuotes = getAssetQuotes();
$userAltcoins = getUserAltcoins($uid);
$stats = getWalletStats($uid);

$dealsPie = $balance;
$teamPie   = $balanceTeam;
$promoPie  = $balancePromo;

$recursiveReferrals3LevelsForUser = getRecursiveUserReferrals($currentUser['uid']);
$recursiveReferralsCount          = array_sum(array_map('count', $recursiveReferrals3LevelsForUser));

$activeReferralsCount = getActiveReferralsCount($recursiveReferrals3LevelsForUser);

// Получаем флаг виртуального пользователя из БД (строка '0' или '1')
$isVirtual = ($currentUser['v_virtual'] === '1');

//var_dump($isVirtual);

?>

<html lang="ru">
<meta charset="UTF-8">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="shortcut icon" type="image/png" href="/img/favicon.png"/>
<link href="/css/bootstrap.min.css" type="text/css" rel="stylesheet">
<link href="/css/select2.min.css" type="text/css" rel="stylesheet">
<link href="/css/slick.css" type="text/css" rel="stylesheet">
<link href="/css/slick-theme.css" type="text/css" rel="stylesheet">
<link href="/css/lightbox.css" type="text/css" rel="stylesheet">
<link href="/css/main.css<?= "?v" . $_ENV['APP_VERSION']?>" type="text/css" rel="stylesheet">

<script type="text/javascript" src="/js/jquery-3.5.1.min.js"></script>

<script type="text/javascript" src="/js/slick.min.js"></script>
<script type="text/javascript" src="/js/lightbox.js"></script>
<script type="text/javascript" src="/js/typed.umd.js"></script>
<script type="text/javascript" src="/js/highcharts.js"></script>
<!--<script src="https://unpkg.com/typed.js@2.1.0/dist/typed.umd.js"></script>-->
<!--<script src = "https://code.highcharts.com/highcharts.js" ></script>-->
<script type="text/javascript" src="/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/js/select2.min.js"></script>
<script type="text/javascript" src="/js/main.js<?= "?v" . $_ENV['APP_VERSION']?>"></script>
<title>ETFRIX</title>
<body>

<div class="page">

    <div class="page-header">
        <header class="headerAccount">
            <div class="content">
                <div class="headerAccount-content">

                    <div class="headerAccount-left">

                        <div class="header-settings">
                            <a href="#" class="js-sideBarCtrl2">
                                <i class="icon-settings"></i>
                            </a>

                            <div class="sideBar2">

                                <div class="content">
                                    <div class="sideBar-content2">

                                        <div class="setting-link">

                                            <a href="#" class="setting-link_item" data-toggle="modal" data-target="#arbitration">
                                                <div class="setting-link_icon">
                                                    <i class="icon-smart"></i>
                                                </div>

                                                <div class="setting-link_text">
                                                    <p>Заключить ETF контракт</p>
                                                </div>
                                            </a>

                                            <a href="#" class="setting-link_item">
                                                <div class="setting-link_icon">
                                                    <i class="icon-stake"></i>
                                                </div>

                                                <div class="setting-link_text">
                                                    <p class="disabled_link">Стейкинг</p>
                                                </div>
                                            </a>

                                            <a href="#" class="setting-link_item" data-toggle="modal" data-target="#activity">
                                                <div class="setting-link_icon">
                                                    <i class="icon-wallet"></i>
                                                </div>

                                                <div class="setting-link_text">
                                                    <p>RIXWallet</p>
                                                </div>
                                            </a>

                                        </div>

                                        <div class="setting">

                                            <a href="/ru/account/deals" class="setting-item">

                                                <div class="setting-top">
                                                    <div class="setting-icon">
                                                        <i class="icon-bar"></i>
                                                    </div>

                                                    <div class="setting-title">
                                                        <h5>Активы</h5>
                                                    </div>
                                                </div>

                                                <div class="setting-bottom">
                                                    <div class="setting-icon">
                                                        <i class="icon-teph"></i>
                                                    </div>

                                                    <div class="setting-sum">
                                                        <p><?= moneyFormat($balance) ?></p>
                                                        <span>Usdt</span>
                                                    </div>
                                                </div>

                                            </a>

                                            <a href="/ru/account/team" class="setting-item">

                                                <div class="setting-top">
                                                    <div class="setting-icon">
                                                        <i class="icon-team"></i>
                                                    </div>

                                                    <div class="setting-title">
                                                        <h5>Команда</h5>
                                                    </div>
                                                </div>

                                                <div class="setting-bottom">
                                                    <div class="setting-icon">
                                                        <i class="icon-teph"></i>
                                                    </div>

                                                    <div class="setting-sum">
                                                        <p><?= moneyFormat($balanceTeam) ?></p>
                                                        <span>Usdt</span>
                                                    </div>
                                                </div>

                                            </a>

                                            <a href="/ru/account/promo" class="setting-item">

                                                <div class="setting-top">
                                                    <div class="setting-icon">
                                                        <i class="icon-promo"></i>
                                                    </div>

                                                    <div class="setting-title">
                                                        <h5>Промо</h5>
                                                    </div>
                                                </div>

                                                <div class="setting-bottom">
                                                    <div class="setting-icon">
                                                        <i class="icon-teph"></i>
                                                    </div>

                                                    <div class="setting-sum">
                                                        <p><?= moneyFormat($balancePromo) ?></p>
                                                        <span>Usdt</span>
                                                    </div>
                                                </div>

                                            </a>

                                            <a href="/ru/account/rix" class="setting-item">

                                                <div class="setting-top">
                                                    <div class="setting-icon">
                                                        <i class="icon-collection"></i>
                                                    </div>

                                                    <div class="setting-title">
                                                        <h5>RIX Collection</h5>
                                                    </div>
                                                </div>

                                            </a>

                                            <a href="/userfiles/etfrix.apk" class="setting-item">
                                                <img src="/img/etfrix-apk-small.png">
                                            </a>

                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="header-logo">
                            <a href="/ru" class="logo"></a>
                        </div>
                    </div>

                    <div class="headerAccount-right">
                        <div class="headerAccount-menu d-lg-flex d-none">
                            <div class="headerAccount-menu_item">
                                <div class="headerAccount-menu_icon">
                                    <i class="icon-smart"></i>
                                </div>

                                <div class="headerAccount-menu_link">
                                    <a href="#" data-toggle="modal" data-target="#arbitration">Заключить ETF контракт</a>
                                </div>
                            </div>

                            <div class="headerAccount-menu_item">
                                <div class="headerAccount-menu_icon">
                                    <i class="icon-stake"></i>
                                </div>

                                <div class="headerAccount-menu_link">
                                    <a href="#" class="disabled_link">Стейкинг</a>
                                </div>
                            </div>

                            <div class="headerAccount-menu_item">
                                <div class="headerAccount-menu_icon">
                                    <i class="icon-wallet"></i>
                                </div>

                                <div class="headerAccount-menu_link">
                                    <a href="#" data-toggle="modal" data-target="#activity">RIXWallet</a>
                                </div>
                            </div>

                            <?php if ($currentUser['admin'] === 'admin'): ?>
                                <div class="headerAccount-menu_item">
                                    <div class="headerAccount-menu_icon">
                                        <i class="icon-wallet"></i>
                                    </div>

                                    <div class="headerAccount-menu_link">
                                        <a href="/admin/inputs">AdminKa</a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <a href="/ru/account/profile" class="headerAccount-avatar d-lg-flex d-none">
                            <div class="headerAccount-avatar_name">
                                <p><?= $currentUser['user_name'] . " " . $currentUser['sur_name'] ?></p>
                            </div>

                            <div class="headerAccount-avatar_img">
                                <img src="/img/avatar.png" alt="">
                            </div>
                        </a>
                    </div>

                    <div class="modal-lang-switch language-accordion d-block d-lg-none">
                        <select id="currency-1" class="form-control select-single-2">
                            <option value="en">EN</option>
                            <option value="ru">RU</option>
                            <option value="cn">CH</option>
                            <option value="ar">AR</option>
                        </select>
                    </div>

                    <div class="mobileMenu headerAccount_mobile d-lg-none d-block">
                        <div class="mobileMenu-content">

                            <div class="mobileMenu-burger">
                                <button class="js-sideBarCtrl menu_burger">
                                    <span class="menu-global menu-top"></span>
                                    <span class="menu-global menu-middle"></span>
                                    <span class="menu-global menu-bottom"></span>
                                </button>

                                <div class="sideBar sideBar-right">

                                    <div class="content">
                                        <div class="sideBar-content sideBar-content_right">


                                            <div class="accountBar">
                                                <div class="accountBar-title">
                                                    <h5>Мой кабинет</h5>
                                                </div>

                                                <div class="accountBar-profile">

                                                    <div class="accountBar-profile_img">

                                                        <div class="accountBar-download">
                                                            <input name="file" type="file" id="File" class="accountBar-file" multiple="">
                                                            <label for="File" class="accountBar-fileButton"></label>
                                                        </div>

                                                        <img src="/img/avatar_2.png" alt="">
                                                    </div>

                                                    <div class="accountBar-profile_name">
                                                        <span>Имя пользователя</span>
                                                        <p><?= $currentUser['user_name'] . " " . $currentUser['sur_name'] ?></p>
                                                    </div>

                                                </div>

                                                <div class="accountBar-form">
                                                    <form id="profileForm" autocomplete="off">
                                                        <div class="form-group">
                                                            <label class="profile-label">Ваше имя</label>
                                                            <input type="text"
                                                                   class="form-control"
                                                                   name="user_name"
                                                                   value="<?= htmlspecialchars($currentUser['user_name']) ?>">
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="profile-label">Ваша фамилия</label>
                                                            <input type="text"
                                                                   class="form-control"
                                                                   name="sur_name"
                                                                   value="<?= htmlspecialchars($currentUser['sur_name']) ?>">
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="profile-label">Ваш email</label>
                                                            <input type="email"
                                                                   class="form-control"
                                                                   name="email"
                                                                   value="<?= htmlspecialchars($currentUser['email']) ?>"
                                                                   pattern="^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$"
                                                                   autocomplete="email"
                                                            >
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="profile-label">Ваш телефон</label>
                                                            <input type="tel"
                                                                   class="form-control"
                                                                   name="phone"
                                                                   value="<?= htmlspecialchars($currentUser['phone']) ?>">
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="profile-label">Ваш пароль<br><small>(оставьте пустым — останется прежний)</small></label>
                                                            <div class="input-container">
                                                                <input type="password"
                                                                       class="form-control"
                                                                       id="password-profile"
                                                                       name="password">
                                                            </div>
                                                        </div>

                                                        <button type="submit" class="accountBar_button profile-button">
                                                            Сохранить
                                                        </button>
                                                    </form>
                                                </div>

                                                <div class="accountBar-state">

                                                    <div class="accountBar-state_top">
                                                        <div class="accountBar-state_icon">
                                                            <i class="icon-skills"></i>
                                                        </div>

                                                        <div class="accountBar-state_title">
                                                            <h4>Состояние аккаунта</h4>
                                                        </div>
                                                    </div>


                                                    <div class="accountBar-state_bottom">

                                                        <div class="accountBar-state_item">
                                                            <div class="accountBar-item_icon">
                                                                <i class="icon-state2"></i>
                                                            </div>

                                                            <div class="accountBar-item_text">
                                                                <p>Статус Клиента</p>
                                                                <span><?= $currentUser['rating'] ?></span>
                                                            </div>
                                                        </div>

                                                        <div class="accountBar-state_item">
                                                            <div class="accountBar-item_icon">
                                                                <i class="icon-state3"></i>
                                                            </div>

                                                            <div class="accountBar-item_text">
                                                                <p>Верификация клиента</p>
                                                                <span><?= $currentUser['verified'] == 1 ? 'Верифицирован' : 'Не верифицирован'?></span>
                                                            </div>
                                                        </div>

                                                        <div class="accountBar-state_item">
                                                            <div class="accountBar-item_icon">
                                                                <i class="icon-state4"></i>
                                                            </div>

                                                            <div class="accountBar-item_text">
                                                                <p>Дата регистрации</p>
                                                                <span><?= $currentUser['create_date'] ?></span>
                                                            </div>
                                                        </div>

                                                        <div class="accountBar-state_item">
                                                            <div class="accountBar-item_icon">
                                                                <i class="icon-state5"></i>
                                                            </div>

                                                            <div class="accountBar-item_text">
                                                                <p>Поддержка 24/7</p>
                                                                <a href="https://t.me/<?= $_ENV['TG_SUPPORT'] ?>"><?= $_ENV['TG_SUPPORT'] ?></a>
                                                            </div>
                                                        </div>

                                                        <div class="accountBar-state_item">
                                                            <div class="accountBar-item_icon">
                                                                <i class="icon-state6"></i>
                                                            </div>

                                                            <div class="accountBar-item_text">
                                                                <p>Партнерская ссылка</p>
                                                                <a
                                                                        href="<?='https://' . $_ENV['CLEAR_URL'] . '/referral?code=' . $currentUser['activation']?>"
                                                                        class="js-copy-referral"
                                                                        data-url="<?='https://' . $_ENV['CLEAR_URL'] . '/referral?code=' . $currentUser['activation']?>"
                                                                >
                                                                    <?='https://' . $_ENV['CLEAR_URL'] . '/referral?code..'?>
                                                                </a>
                                                            </div>
                                                        </div>

                                                        <div class="accountBar-state_item">
                                                            <div class="accountBar-item_icon">
                                                                <i class="icon-exitBar"></i>
                                                            </div>

                                                            <div class="accountBar-item_text">
                                                                <a href="/logout" class="state_a">Выход</a>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>


                                            </div>


                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>

                    </div>

                </div>

            </div>
        </header>