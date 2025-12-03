<?php
include $_SERVER['DOCUMENT_ROOT'] . '/../engine/app/functions.php';

global $pdo;

$itemId            = $_POST['id'] ?? null;
$token             = trim($_POST['token'] ?? '');
$itemName          = trim($_POST['item_name'] ?? '');
$description       = trim($_POST['description'] ?? '');
$dropChance        = isset($_POST['drop_chance']) ? (int)$_POST['drop_chance'] : 0;
$dropChanceGuest   = isset($_POST['drop_chance_guest']) ? (int)$_POST['drop_chance_guest'] : 0;
$imageName         = trim($_POST['image_name'] ?? '');
$sort              = isset($_POST['sort']) ? (int)$_POST['sort'] : 0;
$isActive          = isset($_POST['is_active']) ? 1 : 0;

if ($token === '' || $itemName === '') {
    header('Location: /admin/roulette');
    exit();
}

if ($itemId === 'new' || $itemId === null || $itemId === '') {
    $sql = 'INSERT INTO roulette_items (token, item_name, drop_chance, drop_chance_guest, image_name, description, is_active, sort)
            VALUES (:token, :item_name, :drop_chance, :drop_chance_guest, :image_name, :description, :is_active, :sort)';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'token'             => $token,
        'item_name'         => $itemName,
        'drop_chance'       => $dropChance,
        'drop_chance_guest' => $dropChanceGuest,
        'image_name'        => $imageName,
        'description'       => $description,
        'is_active'         => $isActive,
        'sort'              => $sort,
    ]);
} else {
    $sql = 'UPDATE roulette_items
            SET token = :token,
                item_name = :item_name,
                drop_chance = :drop_chance,
                drop_chance_guest = :drop_chance_guest,
                image_name = :image_name,
                description = :description,
                is_active = :is_active,
                sort = :sort
            WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'id'                => (int)$itemId,
        'token'             => $token,
        'item_name'         => $itemName,
        'drop_chance'       => $dropChance,
        'drop_chance_guest' => $dropChanceGuest,
        'image_name'        => $imageName,
        'description'       => $description,
        'is_active'         => $isActive,
        'sort'              => $sort,
    ]);
}

header('Location: /admin/roulette');
exit();