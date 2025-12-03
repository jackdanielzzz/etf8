<?php
    include('adminHeader.php');

    $tokenizationRequest = getAllTokenizationsByTokenId($_GET['id']);
//print_arr($tokenizationRequest);
    $currentUser = getUserById($tokenizationRequest['user_id']);
//print_arr($currentUser);
//die();
?>

    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-6">
                        <h3>Заявка на токенизацию пользователя <a href="/admin/edit-user?id=<?= $currentUser['uid'] ?>"><i><?=$currentUser['user_name'] . ' ' . $currentUser['sur_name']?>:</i></a></h3>
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
                    <form enctype="multipart/form-data" class="card" method="post" action="/admin/save-token-request">

                        <div class="card-body">
                            <div class="form theme-form">
                                <input type="hidden" name="id" value="<?= $_GET['id'] ?>">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label>Статус заявки на токенизацию</label>

                                            <select class="form-select" name="status">
                                                <option value="0" <?= $tokenizationRequest['status'] == "0" ? "selected": ""  ?> >Пользователь подал заявку</option>
                                                <option value="1" <?= $tokenizationRequest['status'] == "1" ? "selected": ""  ?> >Заявка одобрена</option>
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
                                            <a class="btn btn-info" href="/admin/edit-user?id=<?= $tokenizationRequest['user_id'] ?>">Назад</a> </div>
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