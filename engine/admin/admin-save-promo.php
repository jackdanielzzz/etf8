<?php
include $_SERVER['DOCUMENT_ROOT'] . '/../engine/app/functions.php';

checkSession();

if (empty($_SESSION['admin']) || $_SESSION['admin'] === 'user') {
    header('Location: /logout', true, 302);
    exit();
}

$idRaw = $_POST['id'] ?? 'new';
$isNew = $idRaw === 'new';
$promoId = $isNew ? null : (int)$idRaw;

$titleRu = trim($_POST['news_title_ru'] ?? '');
$titleEn = trim($_POST['news_title_en'] ?? '');
$rawRu   = trim($_POST['text_source_ru'] ?? '');
$rawEn   = trim($_POST['text_source_en'] ?? '');
$markupRu = trim($_POST['markup_ru'] ?? '');
$markupEn = trim($_POST['markup_en'] ?? '');
$newsDate = trim($_POST['news_date'] ?? '');

if ($markupRu === '' && $rawRu !== '') {
    $markupRu = generatePromoMarkup('ru', $rawRu) ?? '';
}

if ($markupEn === '' && $rawEn !== '') {
    $markupEn = generatePromoMarkup('en', $rawEn) ?? '';
}

$data = [
    'news_title_ru' => $titleRu,
    'news_title_en' => $titleEn,
    'raw_text_ru'   => $rawRu,
    'raw_text_en'   => $rawEn,
    'markup_ru'     => $markupRu,
    'markup_en'     => $markupEn,
    'news_date'     => $newsDate !== '' ? $newsDate : null,
    'image_path'    => '',
];

if ($isNew) {
    $promoId = createPromo($data);
} else {
    $existing = getOnePromoById($promoId) ?: [];
    $data['image_path'] = $existing['image_path'] ?? '';
}

if (isset($_FILES['promo_image']) && $_FILES['promo_image']['error'] !== UPLOAD_ERR_NO_FILE) {
    $savedPath = savePromoImage($_FILES['promo_image'], (int)$promoId);
    if ($savedPath !== null) {
        $data['image_path'] = $savedPath;
    }
}

if ($isNew) {
    updatePromo((int)$promoId, $data);
} else {
    updatePromo((int)$promoId, $data);
}

header('Location: /admin/promo', true, 302);
exit();