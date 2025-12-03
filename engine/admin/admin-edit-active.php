<?php
    include('adminHeader.php');

    $userDeal = getUserDealsByDealId($_GET['id']);

    $currentUser = getUserById($userDeal['user_id']);

    $dealName = getCurrentDealById($userDeal['deal_id']);

    $allDeals = getDealsByRegionSimple($currentUser['team_id']);
//    print_arr($allDeals);
//    die();
?>

    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-6">
                        <h3>Редактирование сделки пользователя</h3>
                    </div>
                    <div class="col-sm-6">

                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid starts-->

        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <form enctype="multipart/form-data" class="card" method="post" action="/admin/save-active">
                        <input name="id" type="hidden" value="<?= $userDeal['id'] ?>">
                        <div class="card-header">
                            <h5><?= $dealName['product'] ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="form theme-form">

                                <?php $allDeals = getDealsByRegionSimple($currentUser['team_id']); ?>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label>Тип сделки(для текущего региона пользователя)</label>
                                            <select class="form-select" name="deal_id">
                                                <?php foreach ($allDeals as $deal):
                                                    $name = $deal['product']; ?>
                                                    <option value="<?= $deal['deal_id'] ?>" <?= $name === $dealName['product'] ? "selected" : "" ?>>
                                                        <?= $name ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label>Сумма покупки актива</label>

                                            <input class="form-control" name="principal" type="text" value="<?= $userDeal['principal'] ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label>Дата начала актива</label>
                                            <input class="form-control" name="start_date" type="text" value="<?= $userDeal['start_date'] ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label>Дата конца актива</label>
                                            <input class="form-control" name="end_date" type="text" value="<?= $userDeal['end_date'] ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label>Доходность на текущий момент(сколько выплачено)</label>
                                            <input class="form-control" name="accrued_amount" type="text" value="<?=round($userDeal['accrued_amount'],2) ?>">
                                        </div>
                                    </div>
                                </div>

                                <?php $status = $userDeal['status']; ?>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label>Статус</label>
                                            <select class="form-select" name="status">
                                                <option value="active" <?= $status === "active" ? "selected" : "" ?>>Active</option>
                                                <option value="completed" <?= $status === "completed" ? "selected" : "" ?>>Completed</option>
                                                <option value="cancelled" <?= $status === "cancelled" ? "selected" : "" ?>>Canceled</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>



<!--                                <div class="row">-->
<!--                                    <div class="col-sm-6">-->
<!--                                        <div class="mb-3">-->
<!--                                            <label>Инструкция как сделать, чтобы актив "отработал":</label><br/>-->
<!--                                            • Меняем "Дата начала актива"(только месяц) например, месяц назад, то есть месяцу сделать -1 вручную<br/>-->
<!--                                            • Меняем "Дата конца актива"(только месяц) например, месяц назад, то есть месяцу сделать -1 вручную<br/>-->
<!--                                            • Нажимаем зеленую кнопку "сохранить" ниже<br/>-->
<!--                                            • Эта страница обновится и новые данные считаются из базы, проверяем все что вносили применилось!<br/>-->
<!--                                            • Открываем другой браузер или в странице инкогнито:<br/>-->
<!--                                            <a href="https://--><?//= $_ENV['CLEAR_URL'] ?><!--/">--><?//= $_ENV['CLEAR_URL'] ?><!--</a><br/>-->
<!--                                            • Логинимся в кабинет пользователя по данным ниже:<br/>-->
<!---->
<!--                                            Логин:  --><?//=$currentUser['email'] ?><!--<br/>-->
<!--                                            Пароль:  --><?//=$currentUser['password'] ?><!--<br/>-->
<!---->
<!--                                            • Просто попадаем в кабинет и видим что актив отработал и нажимаем "ок" на отработавшем активе в кабинете пользователя, при этом бабки автоматически зайдут на счет<br/>-->
<!--                                            • Это все)<br/>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->

                                <div class="row">
                                    <div class="col">
                                        <div class="mb-3">
                                            &nbsp;
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="text-end">
                                            <input class="btn btn-primary m-r-10" type="submit" name="accept" id="accept" value="Сохранить">
                                            <a class="btn btn-info" href="/admin/edit-user?id=<?= $currentUser['uid'] ?>">Назад</a> </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </form>
                </div>

            </div>
        </div>

        <!-- Container-fluid Ends-->
    </div>
<?php
include('adminFooter.php');