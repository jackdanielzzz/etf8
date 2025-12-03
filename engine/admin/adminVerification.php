<?php
    include('adminHeader.php');

    $unverifiedUsersWithFiles = getUnverifiedUsersWithFiles();

//    print_arr($unverifiedUsersWithFiles);

?>

        <div class="page-body">

          <!-- Container-fluid starts-->
          <div class="container-fluid">
            <div class="row">
              <!-- Zero Configuration  Starts-->
              <div class="col-sm-12">
                  <div class="card">
                      <div class="card-header">
                          <h5>Верификация пользователей</h5>
                      </div>

                      <div class="col-sm-6">

                      </div>

                      <div class="card-body">
                          <div class="table-responsive">
                              <table class="display" id="basic-1">
                                  <thead>
                                  <tr>
                                      <th>Пользователь</th>
                                      <th>Фото1</th>
                                      <th>Фото2</th>
                                      <th>Фото3</th>
                                      <th>Фото4</th>
                                  </tr>
                                  </thead>
                                  <tbody>

                                  <?php
                                  foreach($unverifiedUsersWithFiles as $unverifiedUser) {
                                      $user = getUserById($unverifiedUser['user_id']);
                                      ?>
                                      <tr style="font-weight: <?= true ? "bold" : "normal" ?>;">
                                          <td>
                                              <a href="/admin/edit-user?id=<?= $user['uid'] ?>">
                                                  <?=$user['user_name'] . ' ' . $user['sur_name'] . "<br>(" . $user['email'] . ")"?>
                                              </a>
                                          </td>

                                          <td>
                                              <a href="/userfiles/verification/<?= $unverifiedUser['file1']?>">
                                                  <img src="/userfiles/verification/<?= $unverifiedUser['file1']  ?>" style="width: 150px">
                                              </a>
                                          </td>
                                          <td>
                                              <a href="/userfiles/verification/<?= $unverifiedUser['file2']?>" target="_blank">
                                                  <img src="/userfiles/verification/<?= $unverifiedUser['file2']  ?>" style="width: 150px">
                                              </a>
                                          </td>
                                          <td>
                                              <a href="/userfiles/verification/<?= $unverifiedUser['file3']?>" target="_blank">
                                                  <img src="/userfiles/verification/<?= $unverifiedUser['file3']  ?>" style="width: 150px">
                                              </a>
                                          </td>
                                          <td>
                                              <a href="/userfiles/verification/<?= $unverifiedUser['file4']?>" target="_blank">
                                                  <img src="/userfiles/verification/<?= $unverifiedUser['file4']  ?>" style="width: 150px">
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