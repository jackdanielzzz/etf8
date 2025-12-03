<?php
require_once '_auth.php';

$uid  = (int)$_SESSION['user_id'];
$udid = (int)($_POST['user_deal_id'] ?? 0);

//global $pdo;

$sql = "UPDATE user_deals
           SET is_new = 0
         WHERE id = :id AND user_id = :uid";

$ok  = $pdo->prepare($sql)->execute(['id'=>$udid,'uid'=>$uid]);

echo json_encode(['ok' => (int)$ok]);