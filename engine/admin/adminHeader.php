<?php

    require_once __DIR__ . '/../app/functions.php';

    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 30 * 60)) {
        // last request was more than 30 minutes ago
        session_unset();     // unset $_SESSION variable for the run-time
        session_destroy();   // destroy session data in storage
    }
    $_SESSION['LAST_ACTIVITY'] = time();
    $userId = (int)($_SESSION['user_id'] ?? 0);

    if( !isset($_SESSION['admin']) or ($_SESSION['admin'] === 'user') ){
        header("Location: /");
        exit;
    }

    $allAdminDeals    = getUserDeals($userId);
    $allAdminInputs   = getCurrentUserInputsNotOrderedByDate($userId);
    $allAdminOutputs  = getCurrentUserOutputsNotOrderedByDate($userId);

    /* ------------------------------------------------------------------
     * Подразумеваем, что заранее получены:
     *   $allAdminInputs   – массив id его inputs
     *   $allAdminOutputs  – массив id его outputs
     *   $allAdminDeals    – массив id его user_actives (если нужно)
     * ----------------------------------------------------------------- */
    if ($_SESSION['admin'] === 'manager') {

        // ↑ текущий путь без query-строки
        $requestedUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // id из «?id=...» (0, если нет)
        $requestedId  = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        $ownInputIds   = array_column($allAdminInputs,  'id');   // [3, 7, …]
        $ownOutputIds  = array_column($allAdminOutputs, 'id');   // [5, 12, …]
        $ownDealsIds   = array_column($allAdminDeals,   'user_deal_id');   // [35, …]

        $isOwnInput   = in_array($requestedId, $ownInputIds,  true);
        $isOwnOutput  = in_array($requestedId, $ownOutputIds, true);
        $isOwnActive  = in_array($requestedId, $ownDealsIds, true);

        $allow = false;

        switch ($requestedUri) {

            case '/admin/edit-user':
                $allow = ($requestedId === $userId);
                break;

            case '/admin/edit-input':
                $allow = $isOwnInput;
                break;

            case '/admin/edit-output':
                $allow = $isOwnOutput;
                break;

            case '/admin/edit-active':
                $allow = $isOwnActive;
                break;
        }

        if (!$allow) {
            header("Location: /admin/edit-user?id={$userId}", true, 302);
            exit;
        }
    }


    // Вычисляем дату на неделю раньше от текущей даты
    $oneWeekAgo = date('Y-m-d', strtotime('-1 week'));

    $tokenizationRequests = getNewTokenizationsByAllUsers();
    $mentorBuildRequests  = getNewDealRequests();

//    if ($allNewIpos) {
//        $iposCount = $allNewIpos->num_rows;
//    } else {
//        $iposCount = 0;
//    }
//
    $unverifiedCount = countUnverifiedUsersWithFiles();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/assets/images/favicon.png" type="image/x-icon">
    <link rel="shortcut icon" href="/assets/images/favicon.png" type="image/x-icon">
    <title>Админка Etfrix</title>
    <!-- Google font-->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap" rel="stylesheet">
    <!-- Font Awesome-->
    <link rel="stylesheet" type="text/css" href="/assets/css/fontawesome.css">
    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="/assets/css/icofont.css">
    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="/assets/css/themify.css">
    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="/assets/css/flag-icon.css">
    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="/assets/css/feather-icon.css">
    <!-- Plugins css start-->
    <link rel="stylesheet" type="text/css" href="/assets/css/datatables.css">
<!--    <link rel="stylesheet" type="text/css" href="/assets/css/datatable-extension.css">-->
    <link rel="stylesheet" type="text/css" href="/assets/css/dropzone.css">
    <!-- Plugins css Ends-->
    <link rel="stylesheet" type="text/css" href="../assets/css/date-picker.css">
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.css">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="/assets/css/style.css">
    <link id="color" rel="stylesheet" href="/assets/css/color-1.css" media="screen">
<!--    <link rel="stylesheet" type="text/css" href="/assets/css/slick.css">-->
<!--    <link rel="stylesheet" type="text/css" href="/assets/css/slick-theme.css">-->
<!--    <link rel="stylesheet" type="text/css" href="/assets/css/rating.css">-->
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="/assets/css/responsive.css">
</head>
<body>
<!-- Loader starts-->
<div class="loader-wrapper">
    <div class="theme-loader">
        <div class="loader-p"></div>
    </div>
</div>
<!-- Loader ends-->
<!-- page-wrapper Start-->
<div class="page-wrapper" id="pageWrapper">
    <!-- Page Header Start-->
    <div class="page-main-header">
        <div class="main-header-right row m-0">
            <div class="main-header-left">
                <div class="logo-wrapper"><a href="/ru/account/deals"><img class="img-fluid" src="/assets/images/logo/dark-logo.png" alt=""></a></div>
                <div class="dark-logo-wrapper"><a href="/ru/account/deals"><img class="img-fluid" src="/assets/images/logo/dark-logo.png" alt=""></a></div>
                <div class="toggle-sidebar"><i class="status_toggle middle" data-feather="align-center" id="sidebar-toggle"></i></div>
            </div>
            <div class="nav-right col pull-right right-menu p-0">
                <ul class="nav-menus">
                    <li><a class="text-dark" href="#!" onclick="javascript:toggleFullScreen()"><i data-feather="maximize"></i></a></li>
                    <li class="onhover-dropdown p-0">
                        <button class="btn btn-primary-light" type="button"><a href="/ru/account/deals"><i data-feather="log-out"></i>В кабинет</a></button>
                    </li>
                </ul>
            </div>
            <div class="d-lg-none mobile-toggle pull-right w-auto"><i data-feather="more-horizontal"></i></div>
        </div>
    </div>
    <!-- Page Header Ends-->
    <!-- Page Body Start-->
    <div class="page-body-wrapper horizontal-menu">
        <!-- Page Sidebar Start-->
        <header class="main-nav">
            <div class="sidebar-user text-center">
                <img class="img-90 rounded-circle" src="/assets/images/dashboard/1.png" alt="">
                <div class="badge-bottom"><span class="badge badge-primary">New</span></div>
                <a href="">
                    <h6 class="mt-3 f-14 f-w-600"><?= $_SESSION['admin'] === 'admin' ? 'Администратор' : 'Менеджер' ?></h6></a>
                <p class="mb-0 font-roboto"><?= $_ENV['CLEAR_URL'] ?></p>

            </div>
            <nav>
                <div class="main-navbar">
                    <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
                    <div id="mainnav">
                        <ul class="nav-menu custom-scrollbar">
                            <li class="back-btn">
                                <div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
                            </li>
                            <?php if ($_SESSION['admin'] === 'admin'): ?>
                                <li class="sidebar-main-title">
                                    <div> <h6>Разделы</h6> </div>
                                </li>
                                <li class="dropdown">
                                    <a class="nav-link menu-title link-nav" href="/admin/accounts"><i data-feather="yml"></i><span>Аккаунты</span></a>
                                </li>
                                <li class="dropdown">
                                    <a class="nav-link menu-title link-nav" href="/admin/inputs"><i data-feather="yml"></i><span>Ввод</span></a>
                                </li>
                                <li class="dropdown">
                                    <a class="nav-link menu-title link-nav" href="/admin/outputs"><i data-feather="yml"></i><span>Вывод</span></a>
                                </li>
                                <li class="dropdown">
                                    <a class="nav-link menu-title link-nav" href="/admin/deals"><i data-feather="yml"></i><span>Сделки</span></a>
                                </li>
                                <li class="dropdown">
                                    <a class="nav-link menu-title link-nav" href="/admin/verification"><i data-feather="yml"></i><span>Верификация<?= " (" . $unverifiedCount . ")" ?></span></a>
                                </li>

                                <li class="dropdown">
                                    <a class="nav-link menu-title link-nav" href="/admin/tokenization"><i data-feather="yml"></i><span>Заявки на токенизацию<?= " (" . count($tokenizationRequests) . ")" ?></span></a>
                                </li>

                                <li class="dropdown">
                                    <a class="nav-link menu-title link-nav" href="/admin/mentor-requests"><i data-feather="yml"></i><span>RIX mentor build<?= " (" . count($mentorBuildRequests) . ")" ?></span></a>
                                </li>

                                <li class="sidebar-main-title">
                                    <div> <h6>Доп функционал</h6> </div>
                                </li>
                                <li class="dropdown">
                                    <a class="nav-link menu-title link-nav" href="/admin/promo"><i data-feather="yml"></i><span>Редактирование ПРОМО</span></a>
                                </li>

                                <li class="dropdown">
                                    <a class="nav-link menu-title link-nav" href="/admin/roulette"><i data-feather="yml"></i><span>Призы рулетки</span></a>
                                </li>

                                <li class="dropdown">
                                    <a class="nav-link menu-title link-nav" href="/admin/cheaters?date=<?= $oneWeekAgo?>"><i data-feather="yml"></i><span>Поиск читеров</span></a>
                                </li>
                            <?php endif; ?>


<!--                            <li class="dropdown">-->
<!--                                <a class="nav-link menu-title link-nav" href="/admin/refs"><i data-feather="yml"></i><span>Реф. начисления</span></a>-->
<!--                            </li>-->

<!--                            <li class="dropdown">-->
<!--                                <a class="nav-link menu-title link-nav" href="/admin/logins"><i data-feather="yml"></i><span>Логины</span></a>-->
<!--                            </li>-->
<!--                            -->
<!---->
<!--                            <li class="dropdown">-->
<!--                                <a class="nav-link menu-title link-nav" href="/admin/settings"><i data-feather="yml"></i><span>Настройки</span></a>-->
<!--                            </li>-->



                        </ul>
                    </div>
                    <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
                </div>
            </nav>
        </header>