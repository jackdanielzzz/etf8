<?php
    include('adminHeader.php');

    $input = getInputById($_GET['id']);
?>

    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-6">
                        <h3>Редактирование ввода пользователя</h3>
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
                    <form enctype="multipart/form-data" class="card" method="post" action="/admin/save-input">
                        <div class="card-body">
                            <div class="form theme-form">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label>Сумма ввода в $</label>
                                            <input name="input_id" type="hidden" value="<?= $input['id'] ?>">
                                            <input class="form-control" name="amount_usd" type="text" value="<?= $input['amount_usd'] ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label>Сумма в крипте (необязательно)</label>
                                            <input class="form-control" name="amount_crypto" type="text" value="<?= $input['amount_crypto'] ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label>Метод ввода</label>
                                            <select class="form-select" name="method">
                                                <option value="Bitcoin (BTC)" <?= $input['method'] == "Bitcoin (BTC)" ? "selected": ""  ?> >Bitcoin (BTC)</option>
                                                <option value="Tether (TRC-20)" <?= $input['method'] == "Tether (TRC-20)" ? "selected": ""  ?> >Tether (TRC-20)</option>
                                                <option value="Ethereum" <?= $input['method'] == "Ethereum" ? "selected": ""  ?> >Ethereum</option>
                                                <option value="USDT conversion to JioCoin (TRC-20)" <?= $input['method'] == "USDT conversion to JioCoin (TRC-20)" ? "selected": ""  ?> >USDT conversion to JioCoin (TRC-20)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label>Дата</label>
                                            <input class="form-control" name="date" type="text" value="<?= $input['date'] ?>">
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
                                            <a class="btn btn-info" href="/admin/edit-user?id=<?= $input['user_id'] ?>">Назад</a> </div>
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