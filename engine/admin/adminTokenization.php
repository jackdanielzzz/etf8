<?php
    include('adminHeader.php');

    $allTokenizationRequests = getAllTokenizations();
//print_arr($allTokenizationRequests);

?>

        <div class="page-body">

          <!-- Container-fluid starts-->
          <div class="container-fluid">
            <div class="row">
              <!-- Zero Configuration  Starts-->
              <div class="col-sm-12">
                  <div class="card">
                      <div class="card-header">
                          <h5>Заявки на токенизацию</h5>
                      </div>

                      <div class="col-sm-6">

                      </div>

                      <div class="card-body">
                          <div class="table-responsive">
                              <table class="display" id="basic-1">
                                  <thead>
                                  <tr>
                                      <th>Пользователь</th>
                                      <th>Дата</th>
                                      <th>Статус</th>
                                  </tr>
                                  </thead>
                                  <tbody>

                                  <?php
                                  foreach($allTokenizationRequests as $oneRequest) {
                                      $user = getUserById($oneRequest['user_id']);
                                      ?>
                                      <tr style="font-weight: <?= $oneRequest['status'] == 0 ? "bold" : "normal" ?>;">
                                          <td>
                                              <a href="/admin/edit-user?id=<?= $user['uid'] ?>">
                                                  <?=$user['user_name'] . ' ' . $user['sur_name'] . "<br>(" . $user['email'] . ")"?>
                                              </a>
                                          </td>

                                          <td>
                                              <?=$oneRequest['date']?>
                                          </td>

                                          <td>
                                              <a href="/admin/edit-token-request?id=<?= $oneRequest['id'] ?>">
                                                  <?php if( $oneRequest['status'] == '0' ) echo "Пользователь подал заявку";
                                                        if( $oneRequest['status'] == '1' ) echo "Заявка одобрена.";
                                                  ?>
                                              </a>
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