<?php
    include('adminHeader.php');

    $itemId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $rouletteItem = $itemId > 0 ? getRouletteItemById($itemId) : null;

    if ($itemId > 0 && $rouletteItem === null) {
        header('Location: /admin/roulette');
        exit();
    }
?>

    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-6">
                        <!--                  <h3>Админка товаров</h3>-->
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
                    <form enctype="multipart/form-data" class="card" method="post" action="/admin/roulette/save">
                        <div class="card-header">
                            <h5>Редактирование приза рулетки</h5>
                        </div>
                        <div class="card-body">
                            <div class="form theme-form">
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-3">
                                            <label style="color: #46b43f">ID</label>
                                            <input type="hidden" name="id" value="<?= $itemId ?>">
                                            <input class="form-control" type="text" value="<?= $itemId ?>" disabled>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="mb-3">
                                            <label style="color: #46b43f">Токен</label>
                                            <input class="form-control" name="token" type="text" value="<?= htmlspecialchars($rouletteItem['token'] ?? '') ?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="mb-3">
                                            <label style="color: #3a4fb8">Название</label>
                                            <input class="form-control" name="item_name" type="text" value="<?= htmlspecialchars($rouletteItem['item_name'] ?? '') ?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="mb-3">
                                            <label style="color: #3a4fb8">Описание</label>
                                            <textarea class="form-control" name="description" rows="3"><?= htmlspecialchars($rouletteItem['description'] ?? '') ?></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="mb-3">
                                            <label style="color: #b4674f">Шанс (пользователь)</label>
                                            <input class="form-control" name="drop_chance" type="number" min="0" value="<?= htmlspecialchars((string)($rouletteItem['drop_chance'] ?? 0)) ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="mb-3">
                                            <label style="color: #b4674f">Шанс (гость)</label>
                                            <input class="form-control" name="drop_chance_guest" type="number" min="0" value="<?= htmlspecialchars((string)($rouletteItem['drop_chance_guest'] ?? 0)) ?>">
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col">
                                        <div class="mb-3">
                                            <label>Порядок сортировки</label>
                                            <input class="form-control" name="sort" type="number" value="<?= htmlspecialchars((string)($rouletteItem['sort'] ?? 0)) ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-10">
                                        <div class="mb-3">
                                            <label>Активен</label>
                                            <div class="m-checkbox-inline custom-radio-ml">
                                                <label class="d-block" for="is_active">
                                                    <input class="checkbox_animated" id="is_active" name="is_active" type="checkbox" <?= !empty($rouletteItem['is_active']) ? 'checked' : '' ?>> Включить
                                                </label>
                                            </div>
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
                                            <a class="btn btn-danger" href="/admin/action?action=deleteNewsById&id=<?=  $oneNews['id'] ?>" style="margin-right: 30px;">Удалить</a>
                                            <input class="btn btn-primary m-r-10" type="submit" name="accept" id="accept" value="Сохранить">
                                            <a class="btn btn-info" href="/admin/roulette">Назад</a></div>
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