<?php
include('adminHeader.php');

$dealRequest = getDealRequestById((int)$_GET['id']);
if (!$dealRequest) {
    echo '<div class="page-body"><div class="container-fluid"><div class="alert alert-danger">Заявка не найдена</div></div></div>';
    include('adminFooter.php');
    exit;
}

$currentUser = getUserById($dealRequest['user_id']);
$dealTitle = $dealRequest['product'] ?? '';
?>

    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-6">
                        <h3>Заявка RIX mentor build пользователя <a href="/admin/edit-user?id=<?= $currentUser['uid'] ?>"><i><?=$currentUser['user_name'] . ' ' . $currentUser['sur_name']?>:</i></a></h3>
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
                    <form enctype="multipart/form-data" class="card" method="post" action="/admin/save-deal-request">

                        <div class="card-body">
                            <div class="form theme-form">
                                <input type="hidden" name="id" value="<?= (int)$_GET['id'] ?>">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label>Сделка</label>
                                            <input class="form-control" type="text" value="<?= $dealTitle !== '' ? $dealTitle : '—' ?>" disabled>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label>Создана</label>
                                            <input class="form-control" type="text" value="<?= $dealRequest['created_at'] ?? '' ?>" disabled>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label>Статус заявки</label>

                                            <select class="form-select" name="status">
                                                <option value="0" <?= $dealRequest['status'] == "0" ? "selected": ""  ?> >Новая заявка</option>
                                                <option value="1" <?= $dealRequest['status'] == "1" ? "selected": ""  ?> >Подтверждено</option>
                                                <option value="2" <?= $dealRequest['status'] == "2" ? "selected": ""  ?> >Отменено</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

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
                                            <a class="btn btn-info" href="/admin/mentor-requests">Назад</a> </div>
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