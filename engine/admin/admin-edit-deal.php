<?php
include('adminHeader.php');

$dealId  = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$deal    = $dealId ? getDealById($dealId) : null;
$teams   = getAllTeams();
$regions = getAllRegions();
$sizes   = ['Small', 'Medium', 'Large', 'Flash'];

$deal = $deal ?: [
    'team_id'          => $teams[0]['team_id'] ?? 0,
    'region_id'        => $regions[0]['region_id'] ?? 0,
    'need_confirm'     => 0,
    'deal_size'        => 'Small',
    'payout_mode'      => 'end',
    'product'          => '',
    'amount_min'       => 0,
    'amount_max'       => 0,
    'term_days'        => 0,
    'rate_without_RIX' => 0,
    'rate_with_RIX'    => 0,
    'config_note'      => '',
    'config_note_en'   => '',
    'config_note_cn'   => '',
    'config_note_ar'   => '',
];

$withoutCorridor = calculateDealRateCorridor((float)$deal['rate_without_RIX']);
$withCorridor    = calculateDealRateCorridor((float)($deal['rate_with_RIX'] ?? 0));
?>

    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-6">
                        <h5><?= $dealId ? 'Редактирование сделки' : 'Новая сделка' ?></h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <form class="card" method="post" action="/admin/save-deal">
                        <div class="card-header">
                            <h5>Параметры сделки</h5>
                        </div>
                        <div class="card-body">
                            <div class="form theme-form">
                                <input type="hidden" name="deal_id" value="<?= $dealId ?>">

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label>Команда</label>
                                            <select name="team_id" class="form-select">
                                                <?php foreach ($teams as $team): ?>
                                                    <option value="<?= $team['team_id'] ?>" <?= ($deal['team_id'] == $team['team_id']) ? 'selected' : '' ?>>
                                                        <?= $team['team_name'] ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label>Регион</label>
                                            <select name="region_id" class="form-select">
                                                <?php foreach ($regions as $region): ?>
                                                    <option value="<?= $region['region_id'] ?>" <?= ($deal['region_id'] == $region['region_id']) ? 'selected' : '' ?>>
                                                        <?= $region['region_name'] ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label>Тип сделки</label>
                                            <select name="deal_size" class="form-select">
                                                <?php foreach ($sizes as $size): ?>
                                                    <option value="<?= $size ?>" <?= ($deal['deal_size'] === $size) ? 'selected' : '' ?>><?= $size ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label>Тип выплаты</label>
                                            <select name="payout_mode" class="form-select">
                                                <option value="end" <?= ($deal['payout_mode'] == 'end') ? 'selected' : '' ?>>В конце</option>
                                                <option value="daily" <?= ($deal['payout_mode'] == 'daily') ? 'selected' : '' ?>>Ежедневно</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Название сделки</label>
                                            <input class="form-control" name="product" type="text" value="<?= htmlspecialchars($deal['product']) ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label>Нужна ручная верификация(только для FLASH сделок!)</label>
                                            <select name="need_confirm" class="form-select">
                                                <option value="0" <?= ((int)$deal['need_confirm'] === 0) ? 'selected' : '' ?>>Нет</option>
                                                <option value="1" <?= ((int)$deal['need_confirm'] === 1) ? 'selected' : '' ?>>Да</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label>Min вход</label>
                                            <input class="form-control" name="amount_min" type="number" value="<?= $deal['amount_min'] ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label>Max вход</label>
                                            <input class="form-control" name="amount_max" type="number" value="<?= $deal['amount_max'] ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label>Количество дней</label>
                                            <input class="form-control" name="term_days" type="number" value="<?= $deal['term_days'] ?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label>% без RIX(в день)</label>
                                            <input class="form-control" name="rate_without_RIX" type="number" step="0.01" value="<?= $deal['rate_without_RIX'] ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label>Коридор (мин)</label>
                                            <input class="form-control" type="number" step="0.0001" value="<?= $withoutCorridor['min'] ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label>Коридор (макс)</label>
                                            <input class="form-control" type="number" step="0.0001" value="<?= $withoutCorridor['max'] ?>" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label>% c RIX(в день)</label>
                                            <input class="form-control" name="rate_with_RIX" type="number" step="0.01" value="<?= $deal['rate_with_RIX'] ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label>Коридор c RIX (мин)</label>
                                            <input class="form-control" type="number" step="0.0001" value="<?= $withCorridor['min'] ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label>Коридор c RIX (макс)</label>
                                            <input class="form-control" type="number" step="0.0001" value="<?= $withCorridor['max'] ?>" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label>Описание (RU)</label>
                                            <textarea class="form-control" name="config_note" rows="4"><?= htmlspecialchars($deal['config_note']) ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label>Описание (EN)</label>
                                            <textarea class="form-control" name="config_note_en" rows="4"><?= htmlspecialchars($deal['config_note_en']) ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label>Описание (CN)</label>
                                            <textarea class="form-control" name="config_note_cn" rows="4"><?= htmlspecialchars($deal['config_note_cn']) ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label>Описание (AR)</label>
                                            <textarea class="form-control" name="config_note_ar" rows="4"><?= htmlspecialchars($deal['config_note_ar']) ?></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="text-end">
                                            <input class="btn btn-primary m-r-10" type="submit" value="Сохранить">
                                            <a class="btn btn-info" href="/admin/deals">Назад</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php
include('adminFooter.php');