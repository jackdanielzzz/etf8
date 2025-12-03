<?php
    include('adminHeader.php');

    $underConstructionStatus = getUnderConstructionStatus($connect);
    $debugDateStatus = getDebugDateStatus($connect);

?>

        <div class="page-body">
          <div class="container-fluid">
            <div class="page-header">
              <div class="row">
                <div class="col-sm-6">

                </div>
              </div>
            </div>
          </div>
          <!-- Container-fluid starts-->
          <div class="container-fluid">
            <div class="row">
              <!-- Zero Configuration  Starts-->
              <div class="col-sm-12">
                  <div class="card">
                      <div class="card-header">
                          <h5>Настройки</h5>
                      </div>

                      <div class="card-body">
                          <form class="row" enctype="multipart/form-data" method="post" action="/admin/save-ticker-text">
                              <div class="col-sm-12">
                                  <div class="mb-3">
                                      <label>Бегущая строка РУССКАЯ<small> (макс. 255 символов)</small></label><br>
                                      <label><small>Если строка пустая - то она вообще выводится НЕ БУДЕТ</small></label>
                                      <input class="form-control" name="ticker_text_ru" type="text" value="<?= getTickerTextRU($connect) ?>">
                                        <br>
                                      <label>Бегущая строка ENGLISH<small> (max. 255 symbols)</small></label><br>
                                      <input class="form-control" name="ticker_text_en" type="text" value="<?= getTickerTextEN($connect) ?>">
                                  </div>
                              </div>
                              <div class="col-sm-12">
                                  <input class="btn btn-primary" type="submit" name="accept" id="accept" value="Сохранить">
                              </div>
                          </form>
                      </div>

                      <div class="card-body">
                          <h5>Статус режима тех. работ: <?php
                              $disabledRed = "";
                              $disabledGreen = "";
                              if ($underConstructionStatus['status'] == 1) {
                                  $disabledRed = "disabled";
                                  $disabledGreen = "";
                                  echo "<span style='color: red'>вкл. (вход пользователям заблокирован)</span>";
                              } elseif ($underConstructionStatus['status'] == 0) {
                                  $disabledRed = "";
                                  $disabledGreen = "disabled";
                                  echo "<span style='color: green'>откл. (пользователи могут входить в кабинет)</span>";
                              }
                              ?></h5>

                          <br><br>

                          <a href="/admin/action?action=disableUnderConstruction">
                              <button class="btn btn-primary btn-lg" type="button" <?php echo $disabledGreen ?>>
                                  Пустить инвесторов
                              </button>
                          </a>

                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                          <a href="/admin/action?action=enableUnderConstruction">
                              <button class="btn btn-danger btn-lg" type="button" <?php echo $disabledRed ?>>
                                  Послать нахуй
                              </button>
                          </a>
                      </div>

                      <div class="col-sm-6">

                      </div>

                      <?php if ($underConstructionStatus['status'] == 1):?>
                        <div class="card-body">
                            <?php if ($debugDateStatus['status'] == 0):?>
                                <a href="/admin/action?action=enableDebugDateStatus">
                                    <button class="btn btn-outline-warning-2x" type="button" <?php echo $disabledGreen ?>>
                                        Вкл режим редактирования времени
                                    </button>
                                </a>
                            <?php else: ?>
                                <a href="/admin/action?action=disableDebugDateStatus">
                                    <button class="btn btn-warning active" type="button" <?php echo $disabledGreen ?>>
                                        Выкл режим редактирования времени
                                    </button>
                                </a>
                            <?php endif;?>
                        </div>


                        <?php if ($debugDateStatus['status'] == 1):?>
                            <div class="card-body col-sm-4">
                            <form enctype="multipart/form-data" class="card" method="post" action="/admin/save-debug-date">
                              <label>Время системы(в таком формате: 2023-12-30 12:00:00)</label>
                              <input class="form-control" name="debug_date" type="text" value="<?= $debugDateStatus['value'] ?>"><br>

                              <input class="btn btn-primary btn-lg" type="submit" name="accept" id="accept" value="Сохранить">
                            </form>
                            </div>
                        <?php endif;?>
                      <?php endif;?>
                  </div>
              </div>
            </div>
          </div>
          <!-- Container-fluid Ends-->
        </div>
<?php
include('adminFooter.php');