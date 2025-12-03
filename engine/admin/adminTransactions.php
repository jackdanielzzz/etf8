<?php
    include('adminHeader.php');

//    $allTransactions = getAllTransactions($connect);

?>

        <div class="page-body">

          <!-- Container-fluid starts-->
          <div class="container-fluid">
            <div class="row">
              <!-- Zero Configuration  Starts-->
              <div class="col-sm-12">
                  <div class="card">
                      <div class="card-header">
                          <h5>Все транзакции</h5>
                      </div>

                      <div class="col-sm-6">

                      </div>

                      <div class="card-body">
                          <div class="table-responsive">
                              <table class="display" id="basic-1">
                                  <thead>
                                  <tr>
                                      <th>Пользователь</th>
                                      <th>Кому зачисление</th>
                                      <th>Сумма в $</th>
                                      <th>Процент %</th>
                                      <th>Сумма к зачислению</th>
                                      <th>Дата</th>
                                      <th>Тип</th>
                                  </tr>
                                  </thead>
                                  <tbody>
                                  <?php
//                                  foreach($allTransactions as $transaction) {
//                                      $currentUser = getUserById($connect, $transaction['user_id']);
//                                      $referralUser = getUserById($connect, $transaction['ref_id']);
//
//                                      if( (int)$transaction['type'] == 0 ) $style = "color:green; font-weight: bold;";
//                                      if( (int)$transaction['type'] == 1 ) $style = "color:green;";
//                                      if( (int)$transaction['type'] == 2 ) $style = "color:grey;";
//                                      if( (int)$transaction['type'] == 3 ) $style = "color:red;";
//                                      if( (int)$transaction['type'] == 4 ) $style = "color:red;";
//                                      if( (int)$transaction['type'] == 5 ) $style = "color:red;";
//                                      if( (int)$transaction['type'] == 6 ) $style = "color:blue;";
//                                      if( (int)$transaction['type'] == 7 ) $style = "color:#5565bfa1;";
//                                      if( (int)$transaction['type'] == 8 ) $style = "color:#5565bfa1;";
//                                      ?>
<!--                                      <tr style="--><?//= $style ?><!--">-->
<!--                                          <td>-->
<!--                                              --><?// if ($currentUser) {?>
<!--                                                  <b>--><?//=$currentUser['user_name'] . ' ' . $currentUser['sur_name']?><!--</b><br>-->
<!--                                                  --><?//= '( id: '.$currentUser['uid'] . ' | email:<br>' . $currentUser['email'] . ')'?>
<!--                                              --><?//} else { ?>
<!--                                                  <p style="color: red">транзакция ссылается на несуществующего пользователя</p>-->
<!--                                              --><?// }?>
<!--                                          </td>-->
<!--                                          <td>-->
<!--                                              --><?// if ($referralUser) {?>
<!--                                                  <b>--><?//=$referralUser['user_name'] . ' ' . $referralUser['sur_name']?><!--</b><br>-->
<!--                                                  --><?//= '( id: '.$referralUser['uid'] . ' | email: <br>' . $referralUser['email'] . ')'?>
<!--                                              --><?//} else { ?>
<!--                                                  <p style="color: red">транзакция ссылается на несуществующего пользователя</p>-->
<!--                                              --><?// }?>
<!--                                          </td>-->
<!--                                          <td>-->
<!--                                              --><?//=$transaction['amount_usd'] . ' $'?>
<!--                                          </td>-->
<!--                                          <td>-->
<!--                                              --><?//=$transaction['percent'] . ' %'?>
<!--                                          </td>-->
<!--                                          <td>-->
<!--                                              --><?//=$transaction['total_usd'] . ' $'?>
<!--                                          </td>-->
<!--                                          <td>-->
<!--                                              --><?//=$transaction['date']?>
<!--                                          </td>-->
<!---->
<!--                                          <td>-->
<!--                                              --><?php
//                                              if( (int)$transaction['type'] == 0 ) echo "Ввод средств подтвержден <br> администратором";
//                                              if( (int)$transaction['type'] == 1 ) echo "Вывод средств подтвержден <br> администратором";
//                                              if( (int)$transaction['type'] == 2 ) echo "Автоматические реферральные <br> начисления";
//                                              if( (int)$transaction['type'] == 3 ) echo "Ввод средств отменен <br> администратором";
//                                              if( (int)$transaction['type'] == 4 ) echo "Админ отменил вывод средств и средства вернулись на счет пользователю";
//                                              if( (int)$transaction['type'] == 5 ) echo "Сбой в операции <br> обратитесь к разработчику";
//                                              if( (int)$transaction['type'] == 6 ) echo "Покупка актива <br> пользователем";
//                                              if( (int)$transaction['type'] == 7 ) echo "Автоматические начисления <br> по активу";
//                                              if( (int)$transaction['type'] == 8 ) echo "Автоматические закрытие <br> актива по времени";
//                                              if( (int)$transaction['type'] == 9 ) echo "Тело актива <br> вернулось пользователю";
//                                              if( (int)$transaction['type'] == 10 ) echo "Пользователь создал событие ВЫВОДА СРЕДСТВ и деньги снялись со счета";
//                                              if( (int)$transaction['type'] == 11 ) echo "Админ вручную изменил баланс";
//                                              ?>
<!--                                          </td>-->
<!---->
<!---->
<!--                                      </tr>-->
<!--                                      --><?php
//                                  }
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