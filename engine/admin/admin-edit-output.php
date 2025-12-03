<?php
    include('adminHeader.php');

    $output = getOutputById($_GET['id']);
?>

    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-6">
                        <h3>Редактирование вывода пользователя</h3>
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
                    <form enctype="multipart/form-data" class="card" method="post" action="/admin/save-output">
                        <div class="card-body">
                            <div class="form theme-form">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label>Сумма вывода в $</label>
                                            <input name="output_id" type="hidden" value="<?= $output['id'] ?>">
                                            <input class="form-control" name="amount_usd" type="text" value="<?= $output['amount_usd'] ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label>Кошелек (необязательно)</label>
                                            <input class="form-control" name="wallet" type="text" value="<?= $output['wallet'] ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label>Метод вывода</label>
                                            <select class="form-select" name="method">
                                                <option value="Bitcoin (BTC)" <?= $output['method'] == "Bitcoin (BTC)" ? "selected": ""  ?> >Bitcoin (BTC)</option>
                                                <option value="Tether (TRC-20)" <?= $output['method'] == "Tether (TRC-20)" ? "selected": ""  ?> >Tether (TRC-20)</option>
                                                <option value="Ethereum" <?= $output['method'] == "Ethereum" ? "selected": ""  ?> >Ethereum</option>
                                                <option value="JioCoin(JIO)" <?= $output['method'] == "JioCoin(JIO)" ? "selected": ""  ?> >JioCoin(JIO)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label>Дата</label>
                                            <input class="form-control" name="date" type="text" value="<?= $output['date'] ?>">
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
                                            <a class="btn btn-info" href="/admin/edit-user?id=<?= $output['user_id'] ?>">Назад</a> </div>
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