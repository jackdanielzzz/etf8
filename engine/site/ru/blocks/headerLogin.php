<?php

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

require_once $_SERVER['DOCUMENT_ROOT'] . '/../engine/app/functions.php';

if ($path === '/verification') {
    $code = $_GET['code'] ?? null;
    if (!$code) {
        header('Location: /login');
        exit;
    }

//    require_once $_SERVER['DOCUMENT_ROOT'] . '/../engine/app/functions.php';

    $user = findUserByActivation($code);

    if (!$user) {
        header('Location: /login');
        exit;
    }

    $_SESSION['verified_uid'] = $user['uid'];
    activateUserEmailStatus($user['uid']);

    $verification = getUserVerificationByUserId((int)$user['uid']);

    $filledSlots = [];
    for ($i = 1; $i <= 4; $i++) {
        if (!empty($verification["file{$i}"])) {
            $filledSlots[] = (string)$i;
        }
    }
    echo "<script>";
    echo "window.preFilledVerificationSlots = " . json_encode($filledSlots) ;
    echo "</script>";
}

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
<script src="https://unpkg.com/typed.js@2.1.0/dist/typed.umd.js"></script>
<script src = "https://code.highcharts.com/highcharts.js" ></script>
<script type="text/javascript" src="/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/js/select2.min.js"></script>
<script type="text/javascript" src="/js/main.js<?= "?v" . $_ENV['APP_VERSION']?>"></script>
<title>ETFRIX</title>
<body>

<div class="page">

    <div class="page-header">
        <header class="headerForm">
            <div class="content">
                <div class="headerForm-content">

                    <div class="header-logo">
                        <a href="/ru<?= getRefCode() ?>" class="logo"></a>
                    </div>

                    <div class="headerForm-right d-lg-flex d-none">

<!--                        <div class="header-language language-accordion">-->
<!--                            <select name="currency-2" id="currency-2" class="form-control select-single-2">-->
<!--                                <option value="">EN</option>-->
<!--                                <option value="">RU</option>-->
<!--                                <option value="petersburg">CH</option>-->
<!--                                <option value="samara">AR</option>-->
<!--                            </select>-->
<!--                        </div>-->

                        <div class="headerForm-social">
                            <div class="menu-social_item">
                                <a href="#">
                                    <i class="icon-twitter2"></i>
                                </a>
                            </div>

                            <div class="menu-social_item">
                                <a href="#">
                                    <i class="icon-fb2"></i>
                                </a>
                            </div>

                            <div class="menu-social_item">
                                <a href="#">
                                    <i class="icon-ig2"></i>
                                </a>
                            </div>

                            <div class="menu-social_item">
                                <a href="#">
                                    <i class="icon-tube2"></i>
                                </a>
                            </div>

                            <div class="menu-social_item">
                                <a href="#">
                                    <i class="icon-in2"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="mobileMenu d-lg-none d-block">
                        <div class="mobileMenu-content">

                            <div class="mobileMenu-burger">
                                <button class="js-sideBarCtrl menu_burger">
                                    <span class="menu-global menu-top"></span>
                                    <span class="menu-global menu-middle"></span>
                                    <span class="menu-global menu-bottom"></span>
                                </button>

                                <div class="sideBar">
                                    <div class="sideBar-content">
                                        <div class="content">

                                             <div class="footerTop-left_language language-accordion">

                            <select name="currency-2" id="currency-5" class="form-control select-single-2">
                                <option value="en">EN</option>
                                <option value="ru">RU</option>
                                <option value="cn">CH</option>
                                <option value="ar">AR</option>
                            </select>

                        </div>

                                            <div class="menu-social">
                                                <div class="menu-social_item">
                                                    <a href="#">
                                                        <i class="icon-twitter"></i>
                                                    </a>
                                                </div>

                                                <div class="menu-social_item">
                                                    <a href="#">
                                                        <i class="icon-fb"></i>
                                                    </a>
                                                </div>

                                                <div class="menu-social_item">
                                                    <a href="#">
                                                        <i class="icon-ig"></i>
                                                    </a>
                                                </div>

                                                <div class="menu-social_item">
                                                    <a href="#">
                                                        <i class="icon-tube"></i>
                                                    </a>
                                                </div>

                                                <div class="menu-social_item">
                                                    <a href="#">
                                                        <i class="icon-in"></i>
                                                    </a>
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
    </div>