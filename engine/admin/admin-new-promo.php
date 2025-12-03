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
                    <form enctype="multipart/form-data" class="card" method="post" action="/admin/promo/save">
                        <div class="card-header">
                            <h5>Добавление ПРОМО</h5>
                        </div>
                        <div class="card-body">
                            <div class="form theme-form">
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-3">
                                            <label style="color: #46b43f">Название ПРОМО (РУССКИЙ)</label>
                                            <input type="hidden" name="id" value="new">
                                            <input class="form-control" name="news_title_ru" type="text" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="mb-3">
                                            <label style="color: #46b43f">Название ПРОМО (ENGLISH)</label>
                                            <input class="form-control" name="news_title_en" type="text" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="mb-3">
                                            <label style="color: #3a4fb8">Исходный текст (РУССКИЙ)</label>
                                            <textarea class="form-control" name="text_source_ru" rows="6" placeholder="Вставляй простой текст сюда"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="mb-3">
                                            <label style="color: #3a4fb8">Исходный текст (ENGLISH)</label>
                                            <textarea class="form-control" name="text_source_en" rows="6" placeholder="Вставляй простой текст сюда"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="mb-3">
                                            <label style="color: #b4674f">Сгенерированная вёрстка (РУССКИЙ)</label>
                                            <textarea class="form-control" name="markup_ru" rows="12" placeholder="Сначала нужно вставить обычный текст выше и нажать сохранить. При следующем открытии новости тут будет верстка, которую потом можно поправить"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="mb-3">
                                            <label style="color: #b4674f">Сгенерированная вёрстка (ENGLISH)</label>
                                            <textarea class="form-control" name="markup_en" rows="12" placeholder="Сначала нужно вставить обычный текст выше и нажать сохранить. При следующем открытии новости тут будет верстка, которую потом можно поправить"></textarea>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col">
                                        <div class="mb-3">
                                            <label>Дата</label>
                                            <input class="form-control" name="news_date" type="text" value="<?= (new \DateTime())->format('Y-m-d H:i:s'); ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-10">
                                        <div class="mb-3">
                                            <label>Фото (автокроп 290x225)</label>
                                            <div class="element-download" style="display: flex; margin-bottom: 30px;">
                                                <input class="form-control" name="promo_image" type="file" accept="image/*">
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
                                            <a class="btn btn-info" href="/admin/promo">Назад</a>
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
?>