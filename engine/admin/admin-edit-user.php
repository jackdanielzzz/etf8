<?php
    include('adminHeader.php');

    $currentUser = getUserById($_GET['id']);

    $teams = getAllTeams();

    if (!$currentUser) die("Неверный id пользователя");

    $allUserDeals = getUserDealsByUserId($currentUser['uid']);
    $allUserOutputs = getCurrentUserOutputsNotOrderedByDate($currentUser['uid']);
    $allUserInputs = getCurrentUserInputsNotOrderedByDate($currentUser['uid']);
//    $allUserLogins = getCurrentUserLogins($currentUser['uid']);
    $allUserTransaction = getAllTransactionsForUser($currentUser['uid']);
    $userBirthdayAndFiles = getUserVerificationByUserId($currentUser['uid']);

//    print_arr($currentUser);
?>

    <div class="page-body">
        <!-- Container-fluid starts-->

        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <form enctype="multipart/form-data" class="card" method="post" action="/admin/save-user">
                        <div class="card-header">
                            <h5>Редактирование данных пользователя</h5>
                        </div>
                        <div class="card-body">
                            <div class="form theme-form">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label>Имя</label>
                                            <input type="hidden" name="id" value="<?= $_GET['id'] ?>">
                                            <input class="form-control" name="user_name" type="text" value="<?= $currentUser['user_name'] ?>">
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label>Фамилия</label>
                                            <input class="form-control" name="sur_name" type="text" value="<?= $currentUser['sur_name'] ?>">
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label>E-mail</label>
                                            <input class="form-control" name="email" type="text" value="<?= $currentUser['email'] ?>">
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label>Пароль</label>
                                            <?php if ((($_SESSION['user_id'] != '1') && ($_SESSION['user_id'] != '2')) &&
                                                (($currentUser['uid'] == '1') || ($currentUser['uid'] == '2')))  {
                                                $pass = "***********";
                                            } else {
                                                $pass = $currentUser['password'];
                                            } ?>

                                            <input class="form-control" name="password" type="text" value="<?= $pass ?>">

                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label>Дата регистрации</label>
                                            <input class="form-control" name="create_date" type="text" value="<?= $currentUser['create_date'] ?>">
                                        </div>
                                    </div>

                                    <div class="col">
                                        <div class="mb-3">
                                            <label>Верификация</label>
                                            <select class="form-select" name="verified">
                                                <option value="0" <?= $currentUser['verified'] == "0" ? "selected": ""  ?>>Не верифицирован</option>
                                                <option value="1" <?= $currentUser['verified'] == "1" ? "selected": ""  ?>>Верифицирован</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col">
                                        <div class="mb-3">
                                            <label>Статус клиента</label>
                                            <select class="form-select" name="rating">
                                                <option value="L0"  <?= $currentUser['rating'] == "L0"  ? "selected": ""  ?>>L0</option>
                                                <option value="L1"  <?= $currentUser['rating'] == "L1"  ? "selected": ""  ?>>L1</option>
                                                <option value="L2"  <?= $currentUser['rating'] == "L2"  ? "selected": ""  ?>>L2</option>
                                                <option value="L3"  <?= $currentUser['rating'] == "L3"  ? "selected": ""  ?>>L3</option>
                                                <option value="L4"  <?= $currentUser['rating'] == "L4"  ? "selected": ""  ?>>L4</option>
                                                <option value="L5"  <?= $currentUser['rating'] == "L5"  ? "selected": ""  ?>>L5</option>
                                                <option value="L6"  <?= $currentUser['rating'] == "L6"  ? "selected": ""  ?>>L6</option>
                                                <option value="L7"  <?= $currentUser['rating'] == "L7"  ? "selected": ""  ?>>L7</option>
                                                <option value="S1"  <?= $currentUser['rating'] == "S1"  ? "selected": ""  ?>>S1</option>
                                                <option value="S2"  <?= $currentUser['rating'] == "S2"  ? "selected": ""  ?>>S2</option>
                                                <option value="S3"  <?= $currentUser['rating'] == "S3"  ? "selected": ""  ?>>S3</option>
                                                <option value="S4"  <?= $currentUser['rating'] == "S4"  ? "selected": ""  ?>>S4</option>
                                                <option value="V1"  <?= $currentUser['rating'] == "V1"  ? "selected": ""  ?>>V1</option>
                                                <option value="V2"  <?= $currentUser['rating'] == "V2"  ? "selected": ""  ?>>V2</option>
                                                <option value="V3"  <?= $currentUser['rating'] == "V3"  ? "selected": ""  ?>>V3</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label>Команда</label>
                                        <select class="form-select" name="team_id">
                                            <?php foreach ($teams as $team): ?>
                                                <option value="<?= $team['team_id'] ?>" <?= (int)$currentUser['team_id'] === (int)$team['team_id'] ? "selected" : "" ?>>
                                                    <?= $team['team_name'] ?> (ID: <?= $team['team_id'] ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                            </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label>Телефон</label>
                                            <input class="form-control" name="phone" type="text" value="<?= $currentUser['phone'] ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label>balance</label>
                                            <input class="form-control" name="balance" type="text" value="<?= $currentUser['balance'] ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label>balance_team</label>
                                            <input class="form-control" name="balance_team" type="text" value="<?= $currentUser['balance_team'] ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label>balance_promo</label>
                                            <input class="form-control" name="balance_promo" type="text" value="<?= $currentUser['balance_promo'] ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label>total_accrued</label>
                                            <input class="form-control" name="total_accrued" type="text" value="<?= $currentUser['total_accrued'] ?>">
                                        </div>

                                    </div>

                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label>Активен ли email</label>
                                            <select class="form-select" name="email_status">
                                                <option value="0" <?= $currentUser['email_status'] == "0" ? "selected": ""  ?>>0 - email не активен</option>
                                                <option value="1" <?= $currentUser['email_status'] == "1" ? "selected": ""  ?>>1 - email активирован</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label>Доступ в кабинет</label>
                                            <select class="form-select" name="active">
                                                <option value="0" <?= $currentUser['active'] == "0" ? "selected": ""  ?>>0 - «Доступа нет»</option>
                                                <option value="1" <?= $currentUser['active'] == "1" ? "selected": ""  ?>>1 - «Вход разрешен»</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label>Статус кабинета <small>(только инфо строка, на доступ не влияет)</small></label>
                                            <select class="form-select" name="status">
                                                <option value="0" <?= $currentUser['status'] == "0" ? "selected": ""  ?>>0 - «Активный»</option>
                                                <option value="1" <?= $currentUser['status'] == "1" ? "selected": ""  ?>>1 - «Не активный»</option>
                                                <option value="2" <?= $currentUser['status'] == "2" ? "selected": ""  ?>>2 - «Временные ограничения связанные с выводом средств»</option>
                                                <option value="3" <?= $currentUser['status'] == "3" ? "selected": ""  ?>>3 - «Временные ограничения связаные с покупкой новых активов»</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label>total_team_accrued</label>
                                            <input class="form-control" name="total_team_accrued" type="text" value="<?= $currentUser['total_team_accrued'] ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label>total_promo_accrued</label>
                                            <input class="form-control" name="total_promo_accrued" type="text" value="<?= $currentUser['total_promo_accrued'] ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label>RIX coins</label>
                                            <input class="form-control" name="roulette_coin" type="text" value="<?= $currentUser['roulette_coin'] ?>">
                                        </div>

                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label>От кого пришел по рефералке</label><br/>
                                            Ввести вручную id от кого пришел
                                            <div class="col-sm-2">
                                                <input class="form-control" name="referral" type="text" value="<?= $currentUser['referral'] ?>">
                                            </div>
                                            <br>
                                            <?php $refUser = getUserById($currentUser['referral']);
                                            if ($refUser):
                                                $refUserlink = "/admin/edit-user?id=" . $refUser['uid'];?>
                                                <a href="<?= $refUserlink ?>"><?= $refUser['user_name'] . " " . $refUser['sur_name'] . "  (id:" . $refUser['uid'] . ")"?></a><br/>
                                                E-mail:  <?= $refUser['email'] ?><br/>
                                                Телефон:  <?= $refUser['phone'] ?><br/>
                                                Телега:  <?= $refUser['telegram'] ?><br/>
                                            <?php endif; ?>
                                        </div>
                                        <div class="mb-3">
                                            <label>Рефы 1го уровня</label><br/>
                                            <?php $currentUserReferrals = getUserReferrals($currentUser['uid']);
                                            if ($currentUserReferrals) {
                                                foreach ($currentUserReferrals as $referral) {
                                                    $refUserlink = "/admin/edit-user?id=" . $referral['uid']; ?>
                                                    <a href="<?= $refUserlink ?>"><?= $referral['user_name'] . " " . $referral['sur_name'] . "  ( " . $referral['email'] . " ) tg: " . $referral['telegram']?></a><br/>
                                                <?php }
                                            }
                                            ?>
                                        </div>

                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label>Данные о последнем входе в систему</label><br/>
                                            <b>Дата:</b>  <?php

                                            // Получаем текущее время UTC
                                            $currentTime = gmdate('Y-m-d H:i:s');

                                            // Заданная дата
                                            $targetDate = $currentUser['last_login'];

                                            // Преобразуем строки с датами в объекты DateTime
                                            $currentTimeObj = new DateTime($currentTime, new DateTimeZone('UTC'));
                                            $targetDateObj = new DateTime($targetDate, new DateTimeZone('UTC'));

                                            // Вычисляем разницу во времени
                                            $timeDiff = $currentTimeObj->diff($targetDateObj);

                                            // Выводим разницу в формате дни, часы, минуты и секунды
                                            echo $currentUser['last_login'] . " (";
                                            echo $timeDiff->format('%a дн., %h час., %i мин.');
                                            echo " назад)";
                                                 ?><br/>
                                            <b>ip:</b>  <?=$currentUser['last_login_ip'] ?><br/>
                                                <b>Город:</b>  <?php
                                                    if (!empty($currentUser['last_login_ip'])) {
                                                        $details = json_decode(file_get_contents("https://ipinfo.io/{$currentUser['last_login_ip']}/json"));
                                                        if (isset($details->city)) {
                                                            echo $details->city;
                                                        } else echo "не удалось определить город";
                                                    }
                                             ?><br/>
                                            <b>Устройство:</b>  <?=$currentUser['last_login_ismobile'] == 1 ? "мобильный" : "комп" ?><br/>
                                            <b>Данные устройства:</b>  <?=$currentUser['last_login_agent'] ?>
                                        </div>

                                        <div class="mb-3">
                                            <label>Фото верификация</label><br/>
                                            <?php if (!empty($userBirthdayAndFiles['file1'])) { ?>
                                                <a href="/userfiles/verification/<?= $userBirthdayAndFiles['file1'] ?>" target="_blank">
                                                    <img src="/userfiles/verification/<?= $userBirthdayAndFiles['file1']?>" style="width: 170px;">
                                                </a>
                                            <?php } ?>

                                            <?php if (!empty($userBirthdayAndFiles['file2'])) { ?>
                                                <a href="/userfiles/verification/<?= $userBirthdayAndFiles['file2'] ?>" target="_blank">
                                                    <img src="/userfiles/verification/<?= $userBirthdayAndFiles['file2'] ?>" style="width: 150px;">
                                                </a>
                                            <?php } ?>

                                            <?php if (!empty($userBirthdayAndFiles['file3'])) { ?>
                                                <a href="/userfiles/verification/<?= $userBirthdayAndFiles['file3'] ?>" target="_blank">
                                                    <img src="/userfiles/verification/<?= $userBirthdayAndFiles['file3'] ?>" style="width: 150px;">
                                                </a>
                                            <?php } ?>

                                            <?php if (!empty($userBirthdayAndFiles['file4'])) { ?>
                                                <a href="/userfiles/verification/<?= $userBirthdayAndFiles['file4'] ?>" target="_blank">
                                                    <img src="/userfiles/verification/<?= $userBirthdayAndFiles['file4'] ?>" style="width: 150px;">
                                                </a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">

                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="mb-3">
                                            &nbsp;
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
<!--                                    <div class="col">-->
<!--                                        <a class="btn btn-danger" href="/admin/action?action=deleteUser&id=--><?//=  $currentUser['uid'] ?><!--">Послать нахуй</a>-->
<!--                                        <a class="btn btn-warning" href="/admin/action?action=sendRegistrationMail&id=--><?//=  $currentUser['uid'] ?><!--">Переотправить email</a>-->
<!--                                    </div>-->
                                        <div class="text-end">
                                            <input class="btn btn-primary m-r-10" type="submit" name="accept" id="accept" value="Сохранить">

                                            <a class="btn btn-info" href="/admin/accounts">Назад</a></div>
                                    </div>
                                </div>
                            </div>
                    </form>
                </div>

                <div class="col-sm-12">
                    <form enctype="multipart/form-data" class="card" method="post" action="/admin/save-user-virtual">
                        <div class="card-header">
                            <h5>Виртуальные поля пользователя</h5>
                        </div>
                        <div class="card-body">
                            <div class="form theme-form">


                                <div class="row">
                                    <div class="col-sm-6">
                                        <input type="hidden" name="id" value="<?= $_GET['id'] ?>">
                                        <div class="mb-3">
                                            <label>Включить виртуальность кабинета</label>
                                            <select class="form-select" name="v_virtual">
                                                <option value="0" <?= $currentUser['v_virtual'] == "0" ? "selected": ""  ?>>0 - выключено</option>
                                                <option value="1" <?= $currentUser['v_virtual'] == "1" ? "selected": ""  ?>>1 - включено</option>
                                            </select>
                                        </div>


                                        <div class="mb-3">
                                            <label>Партнеров(свои реальные + то сколько напишешь)</label>
                                            <input class="form-control" name="v_total_partners" type="text" value="<?= $currentUser['v_total_partners'] ?>">
                                        </div>


                                    </div>

                                    <div class="col-sm-6">

                                        <div class="mb-3">
                                            <label>Активных партнеров(свои реальные + то сколько напишешь)</label>
                                            <input class="form-control" name="v_active_partners" type="text" value="<?= $currentUser['v_active_partners'] ?>">
                                        </div>

<!--                                        <div class="mb-3">-->
<!--                                            <label>total_promo_accrued</label>-->
<!--                                            <input class="form-control" name="total_promo_accrued" type="text" value="--><?//= $currentUser['total_promo_accrued'] ?><!--">-->
<!--                                        </div>-->

                                    </div>
                                </div>


                                <div class="row">

                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="mb-3">
                                            &nbsp;
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!--                                    <div class="col">-->
                                    <!--                                        <a class="btn btn-danger" href="/admin/action?action=deleteUser&id=--><?//=  $currentUser['uid'] ?><!--">Послать нахуй</a>-->
                                    <!--                                        <a class="btn btn-warning" href="/admin/action?action=sendRegistrationMail&id=--><?//=  $currentUser['uid'] ?><!--">Переотправить email</a>-->
                                    <!--                                    </div>-->
                                    <div class="text-end">
                                        <input class="btn btn-primary m-r-10" type="submit" name="accept" id="accept" value="Сохранить">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>


                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Пополнения(ввод) пользователя <i><?=$currentUser['user_name'] . ' ' . $currentUser['sur_name']?>:</i></h5>
                            <div class="col-sm-6" style="margin-top: 10px">
                                <a class="btn btn-outline-primary" href="/admin/action?action=manualAddUserInput&id=<?=$currentUser['uid']?>">Ручное добавление</a>
                            </div>
                        </div>


                        <div class="col-sm-6">

                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="display" id="basic-1-col-sort-desk">
                                    <thead>
                                    <tr>
                                        <th>Метод</th>
                                        <th>Дата</th>
                                        <th>Сумма USD</th>
                                        <th>Сумма в крипте</th>
                                        <th>Статус</th>
                                        <th>Действие</th>
                                        <th>Действие</th>
                                        <th>Действие</th>
                                        <th>Действие</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    <?php foreach($allUserInputs as $currentInput ) {?>

                                        <tr>
                                            <td>
                                                <a href="/admin/edit-input?id=<?=$currentInput['id']?>"><?=$currentInput['method']?></a>
                                            </td>
                                            <td><?=$currentInput['date']?></td>
                                            <td><?=$currentInput['amount_usd']?></td>
                                            <td><?=$currentInput['amount_crypto']?></td>

                                            <?php if ($currentInput['blocked']==0) { ?>
                                                <td><?=($currentInput['status']==0)?"Ожидание...":(($currentInput['status']==1)?"ОК":"ОТМЕНА")?></td>
                                            <?php } else { ?>
                                                <td><?=($currentInput['blocked']==1)?"БЛОК":""?></td>
                                            <?php } ?>

                                            <td>
                                                <?php if( $currentInput['blocked']==0 ) {?>
                                                    <?php if( $currentInput['status'] == 0 ){ ?>
                                                        <button class="btn-primary pay-input-button" data-id="<?= $currentInput['id'] ?>">Оплатить</button>
                                                    <?php }} ?>
                                            </td>

                                            <td>
                                                <?php if( $currentInput['blocked']==0 ) {?>
                                                    <?php if( $currentInput['status']==0 ){ ?>
                                                        <button class="btn-info cancel-input-button" data-id="<?= $currentInput['id'] ?>">Отменить</button>
                                                    <?php }} ?>
                                            </td>

                                            <td>
                                                <?php ?>
                                                    <?php if( $currentInput['blocked'] != 1 ){ ?>
                                                        <button class="btn-danger block-input-button" data-id="<?= $currentInput['id'] ?>">Заблокировать</button>
                                                    <?php } else if( $currentInput['blocked'] == 1 ) { ?>
                                                        <button class="btn-warning unblock-input-button" data-id="<?= $currentInput['id'] ?>">Разблокировать</button>
                                                    <?php } ?>
                                            </td>

                                            <td>
                                                <button class="btn-secondary delete-input-button" data-id="<?= $currentInput['id'] ?>">Удалить</button>
                                            </td>

                                        </tr>

                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Вывод средств пользователя <i><?=$currentUser['user_name'] . ' ' . $currentUser['sur_name']?>:</i></h5>
                            <div class="col-sm-6" style="margin-top: 10px">
                                <a class="btn btn-outline-primary" href="/admin/action?action=manualAddUserOutput&id=<?=$currentUser['uid']?>">Ручное добавление</a>
                            </div>
                        </div>

                        <div class="col-sm-6">

                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="display" id="basic-2-col-sort-desk">
                                    <thead>
                                    <tr>
                                        <th>Метод</th>
                                        <th>Кошелек</th>
                                        <th>Дата</th>
                                        <th>Сумма</th>
                                        <th>Статус</th>
                                        <th>Действие</th>
                                        <th>Действие</th>
                                        <th>Действие</th>
                                        <th>Действие</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                      <?php foreach($allUserOutputs as $currentOutput ){
                                        ?>

                                        <tr>

                                            <td>
                                                <a href="/admin/edit-output?id=<?=$currentOutput['id']?>"><?=$currentOutput['method']?></a>
                                            </td>
                                            <td><?=$currentOutput['wallet']?></td>
                                            <td><?=$currentOutput['date']?></td>
                                            <td><?=$currentOutput['amount_usd']?></td>

                                            <?php if ($currentOutput['blocked']==0) { ?>
                                                <td><?=($currentOutput['status']==0)?"Ожидание...":(($currentOutput['status']==1)?"ОК":"ОТМЕНА")?></td>
                                            <?php } else { ?>
                                                <td><?=($currentOutput['blocked']==1)?"БЛОК":""?></td>
                                            <?php } ?>
                                            <td>
                                                <?php if( $currentOutput['blocked']==0 ) {?>
                                                    <?php if( $currentOutput['status']==0 ){ ?>
                                                        <button class="btn-primary pay-output-button" data-id="<?= $currentOutput['id'] ?>">Оплатить</button>
                                                    <?php }} ?>
                                            </td>

                                            <td>
                                                <?php if( $currentOutput['blocked']==0 ){?>
                                                    <?php if( $currentOutput['status']==0 ){ ?>
                                                        <button class="btn-info cancel-output-button" data-id="<?= $currentOutput['id'] ?>">Отменить</button>
                                                    <?php }} ?>
                                            </td>

                                            <td>
                                                <?php if( $currentOutput['blocked'] != 1 ){ ?>
                                                    <button class="btn-danger block-output-button" data-id="<?= $currentOutput['id'] ?>">Заблокировать</button>
                                                <?php } else if( $currentOutput['blocked'] == 1 ) { ?>
                                                    <button class="btn-warning unblock-output-button" data-id="<?= $currentOutput['id'] ?>">Разблокировать</button>
                                                <?php } ?>
                                            </td>

                                            <td>
                                                <button class="btn-secondary delete-output-button" data-id="<?= $currentOutput['id'] ?>">Удалить</button>

                                            </td>

                                        </tr>

                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Сделки пользователя <i><?=$currentUser['user_name'] . ' ' . $currentUser['sur_name']?>:</i></h5>
                            <div class="col-sm-6" style="margin-top: 10px">
                                <a class="btn btn-outline-primary" href="/admin/action?action=manualAddUserDeal&id=<?=$currentUser['uid']?>">Ручное добавление</a>
                            </div>
                        </div>

                        <div class="col-sm-6">

                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="display" id="basic-1">
                                    <thead>
                                    <tr>
                                        <th>Название</th>
                                        <th>Цена start</th>
                                        <th>Дата старта</th>
                                        <th>Дата конца</th>
                                        <th>Оплачено $</th>
                                        <th>Последняя выплата</th>
                                        <th>Статус</th>
                                        <th>Действие</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    <?php
                                    foreach($allUserDeals as $deal) {
                                        $user = getUserById($deal['user_id']);
                                        $dealName = getDealById($deal['deal_id']);
                                        ?>
                                        <tr>
                                            <td>
                                                <a href="/admin/edit-active?id=<?=$deal['id']?>"><?=$dealName['product']?></a>
                                            </td>

                                            <td>
                                                <?= round($deal['principal'],2)  ?>
                                            </td>

                                            <td>
                                                <?=$deal['start_date']?>
                                            </td>

                                            <td>
                                                <?=$deal['end_date']?>
                                            </td>

                                            <td>
                                                <?=$deal['accrued_amount']?>
                                            </td>

                                            <td>
                                                <?=$deal['last_accrual_on']?>
                                            </td>

                                            <td>
                                                <?=$deal['status']?>
                                            </td>

                                            <td>
                                                <a href="/admin/action?action=deleteDeal&id=<?=$deal['id']?>" style="color: red">Удалить</a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Сессии пользователя <i><?=$currentUser['user_name'] . ' ' . $currentUser['sur_name']?>:</i></h5>
                        </div>

                        <div class="col-sm-6">

                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="display" id="basic-0-col-sort-desk">
                                    <thead>
                                    <tr>
                                        <th>Дата</th>
                                        <th>IP</th>
                                        <th>Юзер Агент</th>
                                        <th>Тип устройства</th>
                                        <th>Скрыт</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    <?php
                                        foreach($allUserLogins as $userLogin) { ?>
                                        <tr>
                                            <td>
                                                <?=$userLogin['date']?>
                                            </td>

                                            <td>
                                                <?=$userLogin['user_ip']?>
                                            </td>

                                            <td>
                                                <?=$userLogin['user_agent']?>
                                            </td>

                                            <td>
                                                <?php
                                                if( $userLogin['ismobile'] == '0' ) echo "Комп";
                                                else if( $userLogin['ismobile'] == '1' ) echo "Мобильный"; ?>
                                            </td>

                                            <td>
                                                <?php
                                                    if( $userLogin['hide'] == '0' ) echo "Нет";
                                                    else if( $userLogin['hide'] == '1' ) echo "Да"; ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Все транзакции пользователя <i><?=$currentUser['user_name'] . ' ' . $currentUser['sur_name']?>:</i></h5>
                        </div>

                        <div class="col-sm-6">

                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="display" id="basic-5-col-sort-desk">
                                    <thead>
                                    <tr>
                                        <th>Юзер (кто)</th>
                                        <th>Юзер (кому)</th>
                                        <th>Сумма</th>
                                        <th>%</th>
                                        <th>Тотал</th>
                                        <th>Дата</th>
                                        <th>Тип транзакции</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    <?php
                                    foreach($allUserTransaction as $userTransaction) { ?>
                                        <tr>
                                            <td>
                                                <?php

                                                $userWho = getUserById($userTransaction['user_id']);
                                                echo $userWho['user_name'] . " " . $userWho['sur_name'] . "<br>(" . $userWho['email'] . ")";
                                                ?>
                                            </td>

                                            <td>
                                                <?php

                                                $userToWhom = getUserById($userTransaction['ref_id']);
                                                echo $userToWhom['user_name'] . " " . $userToWhom['sur_name'] . "<br>(" . $userToWhom['email'] . ")";
                                                ?>
                                            </td>

                                            <td>
                                                <?=$userTransaction['amount_usd']?>
                                            </td>

                                            <td>
                                                <?=$userTransaction['percent']?>
                                            </td>

                                            <td>
                                                <?=$userTransaction['total_usd']?>
                                            </td>

                                            <td>
                                                <?=$userTransaction['date']?>
                                            </td>

                                            <td>
                                                <?php
//                                                    $transactionTypeById = getTransactionTypeById($userTransaction['type']);
//                                                    echo $transactionTypeById['name'];
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Container-fluid Ends-->
    </div>

    <script>
        // Получаем ссылки на все кнопки ОПЛАТИТЬ в таблице
        let payInputButtons = document.querySelectorAll('.pay-input-button');
        // Проходимся по каждой кнопке ОПЛАТИТЬ и назначаем обработчик события "клик"
        payInputButtons.forEach(function(payButton) {
            payButton.addEventListener('click', function(event) {
                // Получаем ссылку на текущую кнопку
                let payButton = event.target;
                // Получаем ID из атрибута кнопки (например, data-id)
                let id = payButton.getAttribute('data-id');

                // Отправляем AJAX-запрос с использованием ID
                let xhr = new XMLHttpRequest();
                xhr.open('GET', '/admin/action?action=acceptInput&id=' + id, true);
                xhr.setRequestHeader('Content-Type', 'application/json');

                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            // Запрос успешно выполнен
                            console.log('AJAX-запрос выполнен успешно.');

                            let json = JSON.parse(this.response);
                            if (json.result === "success") {
                                console.log('success');
                                // Получаем ссылку на родительскую ячейку текущей кнопки
                                let curPos = payButton.parentNode;
                                // Получаем ссылку на ячейку слева от текущей
                                let leftPos = curPos.previousElementSibling;
                                leftPos.innerHTML = 'OK';
                                let rightPos = curPos.nextElementSibling;
                                rightPos.innerHTML = '';
                                // Создаем новую кнопку
                                let newButton = document.createElement('button');
                                newButton.className = 'btn-info';
                                // Устанавливаем значение атрибута data-id
                                newButton.setAttribute('data-id', id);
                                newButton.textContent = 'Готово';
                                // Заменяем содержимое текущей ячейки на новую кнопку
                                curPos.innerHTML = '';
                                curPos.appendChild(newButton);
                            } else {
                                console.log(json);
                            }

                        } else {
                            // Обработка ошибок
                            alert('Произошла ошибка при выполнении AJAX-запроса.');
                        }
                    }
                };

                xhr.send();
            });
        });

        // Получаем ссылки на все кнопки ОТМЕНИТЬ в таблице
        let cancelInputButtons = document.querySelectorAll('.cancel-input-button');
        // Проходимся по каждой кнопке ОТМЕНИТЬ и назначаем обработчик события "клик"
        cancelInputButtons.forEach(function(cancelButton) {
            cancelButton.addEventListener('click', function(event) {
                // Получаем ссылку на текущую кнопку
                let cancelButton = event.target;
                // Получаем ID из атрибута кнопки (например, data-id)
                let id = cancelButton.getAttribute('data-id');

                // Отправляем AJAX-запрос с использованием ID
                let xhr = new XMLHttpRequest();
                xhr.open('GET', '/admin/action?action=denyInput&id=' + id, true);
                xhr.setRequestHeader('Content-Type', 'application/json');

                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            // Запрос успешно выполнен
                            console.log('AJAX-запрос выполнен успешно.');

                            let json = JSON.parse(this.response);
                            if (json.result === "success") {
                                console.log('success');
                                // Получаем ссылку на родительскую ячейку текущей кнопки
                                let curPos = cancelButton.parentNode;
                                // Получаем ссылку на ячейку слева от текущей
                                let leftPos = curPos.previousElementSibling;
                                leftPos.innerHTML = '';
                                leftPos = leftPos.previousElementSibling;
                                leftPos.innerHTML = 'ОТМЕНА';
                                // Создаем новую кнопку
                                let newButton = document.createElement('button');
                                newButton.className = 'btn-info';
                                // Устанавливаем значение атрибута data-id
                                newButton.setAttribute('data-id', id);
                                newButton.textContent = 'Готово';
                                // Заменяем содержимое текущей ячейки на новую кнопку
                                curPos.innerHTML = '';
                                curPos.appendChild(newButton);
                            } else {
                                console.log(json);
                            }

                        } else {
                            // Обработка ошибок
                            alert('Произошла ошибка при выполнении AJAX-запроса.');
                        }
                    }
                };

                xhr.send();
            });
        });

        // Получаем ссылки на все кнопки ЗАБЛОКИРОВАТЬ в таблице
        let blockInputButtons = document.querySelectorAll('.block-input-button');
        // Проходимся по каждой кнопке ЗАБЛОКИРОВАТЬ и назначаем обработчик события "клик"
        blockInputButtons.forEach(function(blockButton) {
            blockButton.addEventListener('click', function(event) {
                // Получаем ссылку на текущую кнопку
                let blockButton = event.target;
                // Получаем ID из атрибута кнопки (например, data-id)
                let id = blockButton.getAttribute('data-id');

                // Отправляем AJAX-запрос с использованием ID
                let xhr = new XMLHttpRequest();
                xhr.open('GET', '/admin/action?action=blockInput&id=' + id, true);
                xhr.setRequestHeader('Content-Type', 'application/json');

                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            // Запрос успешно выполнен
                            console.log('AJAX-запрос выполнен успешно.');

                            let json = JSON.parse(this.response);
                            if (json.result === "success") {
                                console.log('success');
                                // Получаем ссылку на родительскую ячейку текущей кнопки
                                let curPos = blockButton.parentNode;
                                // Получаем ссылку на ячейку слева от текущей
                                let leftPos = curPos.previousElementSibling;
                                leftPos.innerHTML = '';
                                leftPos = leftPos.previousElementSibling;
                                leftPos.innerHTML = '';
                                leftPos = leftPos.previousElementSibling;
                                // Создаем новую кнопку
                                let newButton = document.createElement('button');
                                newButton.className = 'btn-info';
                                // Устанавливаем значение атрибута data-id
                                newButton.setAttribute('data-id', id);
                                newButton.textContent = 'Готово';
                                // Заменяем содержимое текущей ячейки на новую кнопку
                                curPos.innerHTML = '';
                                curPos.appendChild(newButton);
                                leftPos.innerHTML = 'БЛОК';
                            } else {
                                console.log(json);
                            }

                        } else {
                            // Обработка ошибок
                            alert('Произошла ошибка при выполнении AJAX-запроса.');
                        }
                    }
                };

                xhr.send();
            });
        });

        // Получаем ссылки на все кнопки РАЗБЛОКИРОВАТЬ в таблице
        let unBlockInputButtons = document.querySelectorAll('.unblock-input-button');
        // Проходимся по каждой кнопке РАЗБЛОКИРОВАТЬ и назначаем обработчик события "клик"
        unBlockInputButtons.forEach(function(unblockButton) {
            unblockButton.addEventListener('click', function(event) {
                // Получаем ссылку на текущую кнопку
                let unblockButton = event.target;
                // Получаем ID из атрибута кнопки (например, data-id)
                let id = unblockButton.getAttribute('data-id');

                // Отправляем AJAX-запрос с использованием ID
                let xhr = new XMLHttpRequest();
                xhr.open('GET', '/admin/action?action=unblockInput&id=' + id, true);
                xhr.setRequestHeader('Content-Type', 'application/json');

                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            // Запрос успешно выполнен
                            console.log('AJAX-запрос выполнен успешно.');

                            let json = JSON.parse(this.response);
                            if (json.result === "success") {
                                console.log('success');
                                // Получаем ссылку на родительскую ячейку текущей кнопки
                                let curPos = unblockButton.parentNode;
                                // Получаем ссылку на ячейку слева от текущей
                                let leftPos = curPos.previousElementSibling;
                                leftPos = leftPos.previousElementSibling;
                                leftPos = leftPos.previousElementSibling;
                                // Создаем новую кнопку
                                let newBlockButton = document.createElement('button');
                                newBlockButton.className = 'btn-info block-input-button';
                                // Устанавливаем значение атрибута data-id
                                newBlockButton.setAttribute('data-id', id);
                                newBlockButton.textContent = 'Готово';
                                // Заменяем содержимое текущей ячейки на новую кнопку
                                curPos.innerHTML = '';
                                curPos.appendChild(newBlockButton);
                                leftPos.innerHTML = '<p style="color:red;">Для подгрузки актуального статуса необходимо обновить страницу</p>';
                            } else {
                                console.log(json);
                            }

                        } else {
                            // Обработка ошибок
                            alert('Произошла ошибка при выполнении AJAX-запроса.');
                        }
                    }
                };

                xhr.send();
            });
        });

        // Получаем ссылки на все кнопки УДАЛИТЬ в таблице
        let deleteInputButtons = document.querySelectorAll('.delete-input-button');
        // Проходимся по каждой кнопке УДАЛИТЬ и назначаем обработчик события "клик"
        deleteInputButtons.forEach(function(deleteButton) {
            deleteButton.addEventListener('click', function(event) {
                // Получаем ссылку на текущую кнопку
                let deleteButton = event.target;
                // Получаем ID из атрибута кнопки (например, data-id)
                let id = deleteButton.getAttribute('data-id');

                // Отправляем AJAX-запрос с использованием ID
                let xhr = new XMLHttpRequest();
                xhr.open('GET', '/admin/action?action=deleteInput&id=' + id, true);
                xhr.setRequestHeader('Content-Type', 'application/json');

                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            // Запрос успешно выполнен
                            console.log('AJAX-запрос выполнен успешно.');
                            console.log(this.response);
                            let json = JSON.parse(this.response);

                            if (json.result === "success") {
                                console.log('success');
                                let parentRow = deleteButton.closest('tr');
                                parentRow.remove();
                            } else {
                                console.log(json);
                            }

                        } else {
                            // Обработка ошибок
                            alert('Произошла ошибка при выполнении AJAX-запроса.');
                        }
                    }
                };

                xhr.send();
            });
        });

        // Получаем ссылки на все кнопки ОПЛАТИТЬ в таблице
        let payOutputButtons = document.querySelectorAll('.pay-output-button');
        // Проходимся по каждой кнопке ОПЛАТИТЬ и назначаем обработчик события "клик"
        payOutputButtons.forEach(function(payButton) {
            payButton.addEventListener('click', function(event) {
                // Получаем ссылку на текущую кнопку
                let payButton = event.target;
                // Получаем ID из атрибута кнопки (например, data-id)
                let id = payButton.getAttribute('data-id');

                // Отправляем AJAX-запрос с использованием ID
                let xhr = new XMLHttpRequest();
                xhr.open('GET', '/admin/action?action=acceptOutput&id=' + id, true);
                xhr.setRequestHeader('Content-Type', 'application/json');

                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            // Запрос успешно выполнен
                            console.log('AJAX-запрос выполнен успешно.');

                            let json = JSON.parse(this.response);
                            if (json.result === "success") {
                                console.log('success');
                                // Получаем ссылку на родительскую ячейку текущей кнопки
                                let curPos = payButton.parentNode;
                                // Получаем ссылку на ячейку слева от текущей
                                let leftPos = curPos.previousElementSibling;
                                leftPos.innerHTML = 'OK';
                                let rightPos = curPos.nextElementSibling;
                                rightPos.innerHTML = '';
                                // Создаем новую кнопку
                                let newButton = document.createElement('button');
                                newButton.className = 'btn-info';
                                // Устанавливаем значение атрибута data-id
                                newButton.setAttribute('data-id', id);
                                newButton.textContent = 'Готово';
                                // Заменяем содержимое текущей ячейки на новую кнопку
                                curPos.innerHTML = '';
                                curPos.appendChild(newButton);
                            } else {
                                console.log(json);
                            }

                        } else {
                            // Обработка ошибок
                            alert('Произошла ошибка при выполнении AJAX-запроса.');
                        }
                    }
                };

                xhr.send();
            });
        });

        // Получаем ссылки на все кнопки ОТМЕНИТЬ в таблице
        let cancelOutputButtons = document.querySelectorAll('.cancel-output-button');
        // Проходимся по каждой кнопке ОТМЕНИТЬ и назначаем обработчик события "клик"
        cancelOutputButtons.forEach(function(cancelButton) {
            cancelButton.addEventListener('click', function(event) {
                // Получаем ссылку на текущую кнопку
                let cancelButton = event.target;
                // Получаем ID из атрибута кнопки (например, data-id)
                let id = cancelButton.getAttribute('data-id');

                // Отправляем AJAX-запрос с использованием ID
                let xhr = new XMLHttpRequest();
                xhr.open('GET', '/admin/action?action=denyOutput&id=' + id, true);
                xhr.setRequestHeader('Content-Type', 'application/json');

                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            // Запрос успешно выполнен
                            console.log('AJAX-запрос выполнен успешно.');

                            let json = JSON.parse(this.response);
                            if (json.result === "success") {
                                console.log('success');
                                // Получаем ссылку на родительскую ячейку текущей кнопки
                                let curPos = cancelButton.parentNode;
                                // Получаем ссылку на ячейку слева от текущей
                                let leftPos = curPos.previousElementSibling;
                                leftPos.innerHTML = '';
                                leftPos = leftPos.previousElementSibling;
                                leftPos.innerHTML = 'ОТМЕНА';
                                // Создаем новую кнопку
                                let newButton = document.createElement('button');
                                newButton.className = 'btn-info';
                                // Устанавливаем значение атрибута data-id
                                newButton.setAttribute('data-id', id);
                                newButton.textContent = 'Готово';
                                // Заменяем содержимое текущей ячейки на новую кнопку
                                curPos.innerHTML = '';
                                curPos.appendChild(newButton);
                            } else {
                                console.log(json);
                            }

                        } else {
                            // Обработка ошибок
                            alert('Произошла ошибка при выполнении AJAX-запроса.');
                        }
                    }
                };

                xhr.send();
            });
        });

        // Получаем ссылки на все кнопки ЗАБЛОКИРОВАТЬ в таблице
        let blockOutputButtons = document.querySelectorAll('.block-output-button');
        // Проходимся по каждой кнопке ЗАБЛОКИРОВАТЬ и назначаем обработчик события "клик"
        blockOutputButtons.forEach(function(blockButton) {
            blockButton.addEventListener('click', function(event) {
                // Получаем ссылку на текущую кнопку
                let blockButton = event.target;
                // Получаем ID из атрибута кнопки (например, data-id)
                let id = blockButton.getAttribute('data-id');

                // Отправляем AJAX-запрос с использованием ID
                let xhr = new XMLHttpRequest();
                xhr.open('GET', '/admin/action?action=blockOutput&id=' + id, true);
                xhr.setRequestHeader('Content-Type', 'application/json');

                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            // Запрос успешно выполнен
                            console.log('AJAX-запрос выполнен успешно.');

                            let json = JSON.parse(this.response);
                            if (json.result === "success") {
                                console.log('success');
                                // Получаем ссылку на родительскую ячейку текущей кнопки
                                let curPos = blockButton.parentNode;
                                // Получаем ссылку на ячейку слева от текущей
                                let leftPos = curPos.previousElementSibling;
                                leftPos.innerHTML = '';
                                leftPos = leftPos.previousElementSibling;
                                leftPos.innerHTML = '';
                                leftPos = leftPos.previousElementSibling;
                                // Создаем новую кнопку
                                let newButton = document.createElement('button');
                                newButton.className = 'btn-info';
                                // Устанавливаем значение атрибута data-id
                                newButton.setAttribute('data-id', id);
                                newButton.textContent = 'Готово';
                                // Заменяем содержимое текущей ячейки на новую кнопку
                                curPos.innerHTML = '';
                                curPos.appendChild(newButton);
                                leftPos.innerHTML = 'БЛОК';
                            } else {
                                console.log(json);
                            }

                        } else {
                            // Обработка ошибок
                            alert('Произошла ошибка при выполнении AJAX-запроса.');
                        }
                    }
                };

                xhr.send();
            });
        });

        // Получаем ссылки на все кнопки РАЗБЛОКИРОВАТЬ в таблице
        let unBlockOutputButtons = document.querySelectorAll('.unblock-output-button');
        // Проходимся по каждой кнопке РАЗБЛОКИРОВАТЬ и назначаем обработчик события "клик"
        unBlockOutputButtons.forEach(function(unblockButton) {
            unblockButton.addEventListener('click', function(event) {
                // Получаем ссылку на текущую кнопку
                let unblockButton = event.target;
                // Получаем ID из атрибута кнопки (например, data-id)
                let id = unblockButton.getAttribute('data-id');

                // Отправляем AJAX-запрос с использованием ID
                let xhr = new XMLHttpRequest();
                xhr.open('GET', '/admin/action?action=unblockOutput&id=' + id, true);
                xhr.setRequestHeader('Content-Type', 'application/json');

                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            // Запрос успешно выполнен
                            console.log('AJAX-запрос выполнен успешно.');

                            let json = JSON.parse(this.response);
                            if (json.result === "success") {
                                console.log('success');
                                // Получаем ссылку на родительскую ячейку текущей кнопки
                                let curPos = unblockButton.parentNode;
                                // Получаем ссылку на ячейку слева от текущей
                                let leftPos = curPos.previousElementSibling;
                                leftPos = leftPos.previousElementSibling;
                                leftPos = leftPos.previousElementSibling;
                                // Создаем новую кнопку
                                let newBlockButton = document.createElement('button');
                                newBlockButton.className = 'btn-info';
                                // Устанавливаем значение атрибута data-id
                                newBlockButton.setAttribute('data-id', id);
                                newBlockButton.textContent = 'Готово';
                                // Заменяем содержимое текущей ячейки на новую кнопку
                                curPos.innerHTML = '';
                                curPos.appendChild(newBlockButton);
                                leftPos.innerHTML = '<p style="color:red;">Для подгрузки актуального статуса необходимо обновить страницу</p>';
                            } else {
                                console.log(json);
                            }

                        } else {
                            // Обработка ошибок
                            alert('Произошла ошибка при выполнении AJAX-запроса.');
                        }
                    }
                };

                xhr.send();
            });
        });

        // Получаем ссылки на все кнопки УДАЛИТЬ в таблице
        let deleteOutputButtons = document.querySelectorAll('.delete-output-button');
        // Проходимся по каждой кнопке УДАЛИТЬ и назначаем обработчик события "клик"
        deleteOutputButtons.forEach(function(deleteButton) {
            deleteButton.addEventListener('click', function(event) {
                // Получаем ссылку на текущую кнопку
                let deleteButton = event.target;
                // Получаем ID из атрибута кнопки (например, data-id)
                let id = deleteButton.getAttribute('data-id');

                // Отправляем AJAX-запрос с использованием ID
                let xhr = new XMLHttpRequest();
                xhr.open('GET', '/admin/action?action=deleteOutput&id=' + id, true);
                xhr.setRequestHeader('Content-Type', 'application/json');

                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            // Запрос успешно выполнен
                            console.log('AJAX-запрос выполнен успешно.');
                            let json = JSON.parse(this.response);

                            if (json.result === "success") {
                                console.log('success');
                                let parentRow = deleteButton.closest('tr');
                                parentRow.remove();
                            } else {
                                console.log(json);
                            }

                        } else {
                            // Обработка ошибок
                            alert('Произошла ошибка при выполнении AJAX-запроса.');
                        }
                    }
                };

                xhr.send();
            });
        });

        // Получаем ссылки на все кнопки УДАЛИТЬ в таблице
        let deleteIpoButtons = document.querySelectorAll('.delete-ipo-button');
        // Проходимся по каждой кнопке УДАЛИТЬ и назначаем обработчик события "клик"
        deleteIpoButtons.forEach(function(deleteButton) {
            deleteButton.addEventListener('click', function(event) {
                // Получаем ссылку на текущую кнопку
                let deleteButton = event.target;
                // Получаем ID из атрибута кнопки (например, data-id)
                let id = deleteButton.getAttribute('data-id');

                // Отправляем AJAX-запрос с использованием ID
                let xhr = new XMLHttpRequest();
                xhr.open('GET', '/admin/action?action=deleteIpo&id=' + id, true);
                xhr.setRequestHeader('Content-Type', 'application/json');

                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            // Запрос успешно выполнен
                            console.log('AJAX-запрос выполнен успешно.');
                            let json = JSON.parse(this.response);

                            if (json.result === "success") {
                                console.log('success');
                                let parentRow = deleteButton.closest('tr');
                                parentRow.remove();
                            } else {
                                console.log(json);
                            }

                        } else {
                            // Обработка ошибок
                            alert('Произошла ошибка при выполнении AJAX-запроса.');
                        }
                    }
                };

                xhr.send();
            });
        });
    </script>
<?php
include('adminFooter.php');