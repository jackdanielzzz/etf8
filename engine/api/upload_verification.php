<?php
// upload_verification.php
//session_start();

require_once __DIR__ . '/../app/functions.php';   // ← так же, как в других API

// 1) User must be authenticated через вашу логику
if (empty($_SESSION['verified_uid'])) {
    http_response_code(403);
    exit(json_encode(['error' => 'Forbidden']));
}

$slot = (int)($_POST['slot'] ?? 0);          // 1-4
if ($slot < 1 || $slot > 4) {
    echo json_encode(['error'=>'Bad slot']); exit;
}

$uid = (int) $_SESSION['verified_uid'];

// 2) Проверяем, что файл прилетел
if (empty($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    exit(json_encode(['error' => 'No file or upload error']));
}

// 3) Валидация расширения и размера (макс 5MB)
$allowed = ['jpg','jpeg','png'];
$ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
if (!in_array($ext, $allowed, true) || $_FILES['file']['size'] > 5 * 1024 * 1024) {
    http_response_code(422);
    exit(json_encode(['error' => 'Invalid file']));
}

// 4) Подготовка имени и директории
$origName = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', basename($_FILES['file']['name']));
$newName  = $uid . '_' . $origName;
$uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/userfiles/verification/';
$destination = $uploadDir . $newName;

//echo $user = trim(shell_exec('whoami'));


// 5) Перемещение
if (!move_uploaded_file($_FILES['file']['tmp_name'], $destination)) {
    http_response_code(500);
    exit(json_encode(['error' => 'Move failed']));
}

/* ---------- пишем в БД ---------- */
global $pdo;
$column = 'file'.$slot;               // file1, file2, file3, file4

// upsert (если запись уже есть — обновляем, иначе вставляем)
$sql = "
INSERT INTO user_verification (user_id, {$column})
VALUES (:uid, :fname)
ON DUPLICATE KEY UPDATE {$column} = VALUES({$column})
";
$stm = $pdo->prepare($sql);
$stm->execute([
    ':uid'   => $uid,
    ':fname' => $newName
]);

// 6) Успешный ответ
header('Content-Type: application/json');
echo json_encode(['success' => true, 'filename' => $newName]);
exit;