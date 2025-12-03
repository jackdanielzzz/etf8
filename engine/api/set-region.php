<?php
// engine/api/set-region.php
session_start();
header('Content-Type: application/json');

$regionId = filter_input(INPUT_POST, 'region_id', FILTER_VALIDATE_INT);

if (!$regionId) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Bad region_id']);
    exit;
}

echo json_encode(['status' => 'ok']);