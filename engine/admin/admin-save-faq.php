<?php
    include($_SERVER['DOCUMENT_ROOT'] . '/../engine/app/functions.php');

//    print_arr($_POST);

    setPageTextByName($connect, $_POST['faq_text'], 'faq-main');

    header('Location: ' . $_SERVER['HTTP_REFERER']);
