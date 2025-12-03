<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/../engine/app/functions.php';

checkSession();

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
        <header class="header">
            <div class="content">
                <div class="header-content">

                    <div class="header-logo">
                        <a href="/ar" class="logo"></a>
                    </div>

                    <div class="desktopMenu d-lg-block d-none">
                        <ul class="desktopMenu-ul">
                            <li class="desktopMenu-li <?= isActiveTab('/ar') ? "desktopMenu_active" : "" ?>">
                                <a href="/ar">وطن</a>
                            </li>
                            <li class="desktopMenu-li <?= isActiveTab('/ar/affiliate') ? "desktopMenu_active" : "" ?>">
                                <a href="/ar/affiliate">للشركاء</a>
                            </li>
                            <li class="desktopMenu-li <?= isActiveTab('/ar/about') ? "desktopMenu_active" : "" ?>">
                                <a href="/ar/about">من نحن</a>
                            </li>
                            <li class="desktopMenu-li <?= isActiveTab('/ar/faq') ? "desktopMenu_active" : "" ?>">
                                <a href="/ar/faq">FAQ</a>
                            </li>

                            <li class="desktopMenu-li <?= isActiveTab('/ar/tokenization') ? "desktopMenu_active" : "" ?>">
                                <a href="/ar/tokenization">ترميز الأصول</a>
                            </li>
                        </ul>
                    </div>

                    <div class="header-language language-accordion ">
                        <select name="currency-2" id="currency-2" class="form-control select-single-2">
                            <option value="">EN</option>
                            <option value="">RU</option>
                            <option value="petersburg">CH</option>
                            <option value="samara">AR</option>
                        </select>
                    </div>

                    <div class="header-cabinet">
                        <a href="/ar/account/deals">
                            <p>الحساب الشخصي</p>

                            <div class="cabinet-icon">
                                <i class="icon-user"></i>
                            </div>
                        </a>
                    </div>


                    <div class="mobileMenu d-lg-none d-block">
                        <div class="mobileMenu-content">

                            <div class="mobileMenu-header">
                                <div class="mobileMenu-cabinet">
                                    <a href="/ar/account/deals">
                                        <div class="cabinet-icon">
                                            <i class="icon-user"></i>
                                        </div>
                                    </a>
                                </div>

                                <div class="mobileMenu-burger">
                                    <button class="js-sideBarCtrl menu_burger">
                                        <span class="menu-global menu-top"></span>
                                        <span class="menu-global menu-middle"></span>
                                        <span class="menu-global menu-bottom"></span>
                                    </button>

                                    <div class="sideBar">
                                        <div class="sideBar-content">
                                            <div class="content">

                                                <div class="menu">
                                                    <ul class="menu-ul">
                                                        <li class="menu-li">
                                                            <a href="/ar" class="menu-a <?= isActiveTab('/ar') ? "menu_active" : "" ?>">وطن</a>
                                                        </li>
                                                        <li class="menu-li">
                                                            <a href="/ar/affiliate" class="menu-a <?= isActiveTab('/ar/affiliate') ? "menu_active" : "" ?>">للشركاء</a>
                                                        </li>
                                                        <li class="menu-li">
                                                            <a href="/ar/about" class="menu-a <?= isActiveTab('/ar/about') ? "menu_active" : "" ?>">من نحن</a>
                                                        </li>
                                                        <li class="menu-li">
                                                            <a href="/ar/faq" class="menu-a <?= isActiveTab('/ar/faq') ? "menu_active" : "" ?>">FAQ</a>
                                                        </li>

                                                        <li class="menu-li">
                                                            <a href="/ar/tokenization <?= isActiveTab('/ar/tokenization') ? "menu_active" : "" ?>" class="menu-a">ترميز الأصول</a>
                                                        </li>
                                                    </ul>
                                                </div>

                                                <div class="menu-language__new language-accordion">
                                                    <select name="currency-2" id="currency-4" class="form-control select-single-2">
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
            </div>
        </header>



    </div>