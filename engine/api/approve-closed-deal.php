<?php
require_once '_auth.php';

//global $pdo;

$uid  = (int)$_SESSION['user_id'];
$udid = (int)($_POST['user_deal_id'] ?? 0);

$sql = "UPDATE user_deals
           SET is_closed = 0
         WHERE id = :id AND user_id = :uid";
$ok  = $pdo->prepare($sql)->execute(['id'=>$udid,'uid'=>$uid]);

echo json_encode(['ok' => (int)$ok]);