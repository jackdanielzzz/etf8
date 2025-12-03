<?php
    include('adminHeader.php');

    $allActives = getAllActives($connect);

?>

        <div class="page-body">

          <!-- Container-fluid starts-->
          <div class="container-fluid">
            <div class="row">
              <!-- Zero Configuration  Starts-->
              <div class="col-sm-12">
                  <div class="card">
                      <div class="card-header">
                          <h5>Активы</h5>
                      </div>

                      <div class="col-sm-6">

                      </div>

                      <div class="card-body">
                          <div class="table-responsive">
                              <table class="display" id="basic-1">
                                  <thead>
                                  <tr>
                                      <th>Пользователь</th>
                                      <th>Название</th>
                                      <th>Цена start</th>
                                      <th>Цена end</th>
                                      <th>Время старта</th>
                                      <th>Время конца</th>
                                      <th>Оплачено дней</th>
                                      <th>Оплачено $</th>
                                      <th>Статус</th>
                                  </tr>
                                  </thead>
                                  <tbody>

                                  <?php
                                  foreach($allActives as $active) {
                                      $user = getUserById($connect, $active['user_id']);
                                      $activeName = getCurrentActiveById($connect, $active['active_id']);
                                      ?>
                                      <tr>
                                          <td>
                                              <?=$user['user_name'] . ' ' . $user['sur_name'] . "<br>(" . $user['email'] . ")"?>
                                          </td>

                                          <td>
                                              <?=$activeName['active_name']?>
                                          </td>

                                          <td>
                                              <?= round($active['start_amount'],2)  ?>
                                          </td>

                                          <td>
                                              <?= round($active['end_amount'],2) ?>
                                          </td>

                                          <td>
                                              <?=$active['start_date']?>
                                          </td>

                                          <td>
                                              <?=$active['end_date']?>
                                          </td>

                                          <td>
                                              <?=$active['days_cashed']?>
                                          </td>

                                          <td>
                                              <?=$active['usd_cashed']?>
                                          </td>

                                          <td>
                                              <?php if( $active['status'] == '0' ) echo "Актив куплен, но человек еще не нажал ok";
                                                 if( $active['status'] == '1' ) echo "Актив куплен, человек нажал ok";
                                                 if( $active['status'] == '2' ) echo "Актив закончился автоматически, но человек еще не нажал ok";
                                                 if( $active['status'] == '3' ) echo "Актив закончился, человек нажал ok и деньги вернулись";
                                              ?>
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