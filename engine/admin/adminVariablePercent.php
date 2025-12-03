<?php
    include('adminHeader.php');

    $variablePercent = getVariablePercent($connect)['percent_per_day'];

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
                          <h5>Настройка процентов</h5>
                      </div>

                      <div class="col-sm-6">

                      </div>

                      <div class="card-body">
                          <form class="row" enctype="multipart/form-data" method="post" action="/admin/save-percent">
                              <div class="col-sm-3">
                                  <div class="mb-3">
                                      <label>Укажите значение для сделки с плавающим процентом</label>
                                      <input class="form-control" name="var_percent" type="number" step="0.01" value="<?= $variablePercent ?>">
                                  </div>

                                  <div class="mb-3">
                                      <label>Курс JIO</label>
                                      <input class="form-control" name="jiocoin_rate" type="number" step="0.000001" value="<?= getCoinRate($connect, 'JioCoin_real')['rate']; ?>">
                                  </div>
                              </div>

                              <div class="col-sm-12">
                                    <input class="btn btn-primary" type="submit" name="accept" id="accept" value="Сохранить">
                              </div>
                          </form>
                      </div>


                  </div>
              </div>
            </div>
          </div>
          <!-- Container-fluid Ends-->
        </div>
<?php
include('adminFooter.php');