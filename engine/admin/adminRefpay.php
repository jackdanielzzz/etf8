<?php
    include('adminHeader.php');

    $allRefTransactions = getAllRefTransactions($connect);

?>

        <div class="page-body">

          <!-- Container-fluid starts-->
          <div class="container-fluid">
            <div class="row">
              <!-- Zero Configuration  Starts-->
              <div class="col-sm-12">
                  <div class="card">
                      <div class="card-header">
                          <h5>Реферальные начисления</h5>
                      </div>

                      <div class="col-sm-6">

                      </div>

                      <div class="card-body">
                          <div class="table-responsive">
                              <table class="display" id="basic-0-col-sort-desk">
                                  <thead>
                                  <tr>
                                      <th>Дата</th>
                                      <th>От кого</th>
                                      <th>Кому</th>
                                      <th>Общая сумма</th>
                                      <th>%</th>
                                      <th>Начислено</th>
                                  </tr>
                                  </thead>
                                  <tbody>

                                  <?php
                                  foreach($allRefTransactions as $refTransaction) {
                                      $user = getUserById($connect, $refTransaction['user_id']);
                                      $ref = getUserById($connect, $refTransaction['ref_id']);
                                      ?>
                                      <tr>
                                          <td>
                                              <?= $refTransaction['date'] ?>
                                          </td>
                                          <td>
                                              <a href="/admin/edit-user?id=<?= $user['uid'] ?>">
                                                  <?=$user['user_name'] . ' ' . $user['sur_name'] . "<br>(" . $user['email'] . ")"?>
                                              </a>
                                          </td>

                                          <td>
                                              <a href="/admin/edit-user?id=<?= $ref['uid'] ?>">
                                                  <?=$ref['user_name'] . ' ' . $ref['sur_name'] . "<br>(" . $ref['email'] . ")"?>
                                              </a>
                                          </td>
                                          <td>
                                              <?= $refTransaction['amount_usd'] ?>
                                          </td>
                                          <td>
                                              <?= $refTransaction['percent'] ?>
                                          </td>
                                          <td style="font-weight: bold">
                                              <?= $refTransaction['total_usd'] ?>
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