<?php
include $_SERVER['DOCUMENT_ROOT'] . '/../engine/app/functions.php';

checkSession();

if (empty($_SESSION['admin']) || $_SESSION['admin'] === 'user') {
    header('Location: /logout', true, 302);
    exit();
}

$promoId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($promoId > 0) {
    $existing = getOnePromoById($promoId);
    if ($existing && deletePromoById($promoId)) {
        $path = $existing['image_path'] ?? '';
        $root = rtrim($_SERVER['DOCUMENT_ROOT'] ?? dirname(__DIR__, 2), '/');
        if ($path !== '' && is_file($root . $path)) {
            unlink($root . $path);
        }
    }
}

header('Location: /admin/promo', true, 302);
exit();