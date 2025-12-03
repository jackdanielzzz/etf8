<?php
include($_SERVER['DOCUMENT_ROOT'] . '/../engine/app/functions.php');

$dealId = isset($_POST['deal_id']) ? (int)$_POST['deal_id'] : 0;

$allowedSizes = ['Small', 'Medium', 'Large', 'Flash'];
$dealSize = in_array($_POST['deal_size'] ?? '', $allowedSizes, true)
    ? $_POST['deal_size']
    : 'Small';

$payoutMode = $_POST['payout_mode'] ?? 'end';
if (!in_array($payoutMode, ['end', 'daily'], true)) {
    $payoutMode = 'end';
}

$data = [
    'team_id'          => (int)($_POST['team_id'] ?? 0),
    'region_id'        => (int)($_POST['region_id'] ?? 0),
    'need_confirm'     => (int)($_POST['need_confirm'] ?? 0),
    'payout_mode'      => $payoutMode,
    'deal_size'        => $dealSize,
    'product'          => trim((string)($_POST['product'] ?? '')),
    'amount_min'       => (int)($_POST['amount_min'] ?? 0),
    'amount_max'       => (int)($_POST['amount_max'] ?? 0),
    'term_days'        => (int)($_POST['term_days'] ?? 0),
    'rate_without_RIX' => (float)($_POST['rate_without_RIX'] ?? 0),
    'rate_with_RIX'    => isset($_POST['rate_with_RIX'])
        ? (float)$_POST['rate_with_RIX']
        : (float)($_POST['rate_without_RIX'] ?? 0),
    'config_note'      => $_POST['config_note'] ?? '',
    'config_note_en'   => $_POST['config_note_en'] ?? '',
    'config_note_cn'   => $_POST['config_note_cn'] ?? '',
    'config_note_ar'   => $_POST['config_note_ar'] ?? '',
];

if ($dealId > 0) {
    updateDeal($dealId, $data);
} else {
    createDeal($data);
}

header('Location: /admin/deals');
exit;