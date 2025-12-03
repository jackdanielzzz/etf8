<?php
    include('adminHeader.php');
//include($_SERVER['DOCUMENT_ROOT'] . '/../engine/app/functions.php');
    $allLogins = getAllLogins($connect);
//    print_arr($allLogins);

?>

        <div class="page-body">
          <!-- Container-fluid starts-->
          <div class="container-fluid">
            <div class="row">
              <!-- Zero Configuration  Starts-->
              <div class="col-sm-12">
                  <div class="card">
                      <div class="card-header">
                          <h5>Входы пользователей за последние 3 дня</h5>
                      </div>

                      <div class="col-sm-6">

                      </div>

                      <div class="card-body">
                          <div class="table-responsive">
                              <table class="display" id="universal-save-sort">
                                  <thead>
                                  <tr>
                                      <th>Пользователь</th>
                                      <th>Дата и время(киев)</th>
                                      <th>IP</th>
                                      <th>Юзер агент</th>
                                      <th>Устройство</th>
                                  </tr>
                                  </thead>
                                  <tbody>

                                    <?php

                                      foreach($allLogins as $currentLogin ){
                                        $currentUser = getUserById($connect, $currentLogin['user_id']);
                                        ?>

                                      <tr>
                                          <td>
                                              <?php
                                                if ($currentUser) {?>
                                                    <a href="/admin/edit-user?id=<?= $currentUser['uid'] ?>">
                                                        <?='<b>'. $currentUser['user_name'] . ' ' . $currentUser['sur_name'] . '</b><br>(' .$currentUser['email'] .')'?>
                                                    </a>
                                                <?php }?>
                                          </td>
                                          <td><?php
                                              $date = DateTime::createFromFormat('Y-m-d H:i:s', $currentLogin['date']);
                                              $new_timestamp = strtotime('+3 hours', $date->getTimestamp());
                                              echo $new_date = date('Y-m-d H:i:s', $new_timestamp);
                                               ?></td>
                                          <td>
                                              <a href="/admin/login-view-ip?ip=<?= $currentLogin['user_ip'] ?>" target="_blank">
                                                  <?= $currentLogin['user_ip'] ?>
                                              </a>
                                          </td>

                                          <td><?=$currentLogin['user_agent']?></td>
                                          <td><?=$currentLogin['ismobile'] == 1 ? "мобильный" : "комп" ?></td>

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