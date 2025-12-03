<?php
include('adminHeader.php');

$allDealRequests = getAllDealRequests();
?>

    <div class="page-body">

        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row">
                <!-- Zero Configuration  Starts-->
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Заявки на RIX mentor build</h5>
                        </div>

                        <div class="col-sm-6">

                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="display" id="basic-1">
                                    <thead>
                                    <tr>
                                        <th>Пользователь</th>
                                        <th>Сделка</th>
                                        <th>Дата</th>
                                        <th>Статус</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    <?php
                                    foreach($allDealRequests as $oneRequest) {
                                        $user = getUserById($oneRequest['user_id']);
                                        $dealTitle = $oneRequest['product'] ?? '';
                                        ?>
                                        <tr style="font-weight: <?= $oneRequest['status'] == 0 ? "bold" : "normal" ?>;">
                                            <td>
                                                <a href="/admin/edit-user?id=<?= $user['uid'] ?>">
                                                    <?=$user['user_name'] . ' ' . $user['sur_name'] . "<br>(" . $user['email'] . ")"?>
                                                </a>
                                            </td>

                                            <td>
                                                <?= $dealTitle !== '' ? $dealTitle : '—' ?>
                                            </td>

                                            <td>
                                                <?=$oneRequest['created_at'] ?? ''?>
                                            </td>

                                            <td>
                                                <a href="/admin/edit-deal-request?id=<?= $oneRequest['id'] ?>">
                                                    <?php
                                                    if( $oneRequest['status'] == '0' ) echo "Новая заявка";
                                                    if( $oneRequest['status'] == '1' ) echo "Подтверждено";
                                                    if( $oneRequest['status'] == '2' ) echo "Отменено";
                                                    ?>
                                                </a>
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
<?php
include('adminFooter.php');