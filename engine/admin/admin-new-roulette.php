<?php
    include('adminHeader.php');
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
                            <h5>Добавление приза рулетки</h5>
                        </div>
                        <div class="card-body">
                            <div class="form theme-form">
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-3">
                                            <label style="color: #46b43f">Токен</label>
                                            <input type="hidden" name="id" value="new">
                                            <input class="form-control" name="token" type="text" value="" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="mb-3">
                                            <label style="color: #3a4fb8">Название</label>
                                            <input class="form-control" name="item_name" type="text" value="" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="mb-3">
                                            <label style="color: #3a4fb8">Описание</label>
                                            <textarea class="form-control" name="description" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="mb-3">
                                            <label style="color: #b4674f">Шанс (пользователь)</label>
                                            <input class="form-control" name="drop_chance" type="number" min="0" value="0">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="mb-3">
                                            <label style="color: #b4674f">Шанс (гость)</label>
                                            <input class="form-control" name="drop_chance_guest" type="number" min="0" value="0">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="mb-3">
                                            <label>Имя файла изображения</label>
                                            <input class="form-control" name="image_name" type="text" value="">
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col">
                                        <div class="mb-3">
                                            <label>Порядок сортировки</label>
                                            <input class="form-control" name="sort" type="number" value="0">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-10">
                                        <div class="mb-3">
                                            <label>Активен</label>
                                            <div class="m-checkbox-inline custom-radio-ml">
                                                <label class="d-block" for="is_active">
                                                    <input class="checkbox_animated" id="is_active" name="is_active" type="checkbox" checked> Включить
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
                                            <input class="btn btn-primary m-r-10" type="submit" name="accept" id="accept" value="Сохранить">
                                            <a class="btn btn-info" href="/admin/roulette">Назад</a>
                                        </div>
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