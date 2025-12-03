<?php
    include('adminHeader.php');

    $cheatersLoginsList = getCheatersByIP($_GET['ip']);

?>

        <div class="page-body">
          <div class="container-fluid">
            <div class="page-header">
              <div class="row">
<!--                <div class="col-sm-6">-->
<!--                    <form action="admin-new.php">-->
<!--                        <input type="hidden" name="category_id" value="0">-->
<!--                        <input class="btn btn-outline-primary" type="submit" value="Добавить товар">-->
<!--                    </form>-->
<!--                </div>-->
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
                          <h5>Нарушители с совпадающими идентификаторами устройства <br> <b>IP:</b> <?=  $_GET['ip'] ?><br><b>Город:</b>
                              <?php
                              if (!empty($_GET['ip'])) {
                                  $details = json_decode(file_get_contents("https://ipinfo.io/{$_GET['ip']}/json"));
                                  if (isset($details->city)) {
                                      echo $details->city;
                                  } else echo "не удалось определить город";
                              }
                              ?>
                          </h5>
                      </div>

                      <div class="col-sm-6">

                      </div>

                      <div class="card-body">
                          <div class="table-responsive">
                              <table class="display" id="basic-1">
                                  <thead>
                                  <tr>
                                      <th>Нарушитель "А"</th>
                                      <th>Нарушитель "Б"</th>
                                      <th>IP, который совпал</th>
                                      <th>Дата входа "А"</th>
                                      <th>Дата входа "Б"</th>
                                      <th>Идентификатор устройства, который тоже совпал у нарушителей</th>
                                      <th>Действие</th>
                                  </tr>
                                  </thead>
                                  <tbody>

                                  <?php
                                    if ($cheatersLoginsList):
                                      foreach ($cheatersLoginsList as $user):
                                      ?>
                                          <tr>

                                              <td>
                                                  <a href="/admin/edit-user?id=<?= $user -> uid1 ?>"><?= $user -> email1 ?></a>
                                              </td>

                                              <td>
                                                  <a href="/admin/edit-user?id=<?= $user -> uid2 ?>"><?= $user -> email2 ?></a>
                                              </td>

                                              <td>
                                                  <?= $user -> user_ip ?>
                                              </td>

                                              <td>
                                                  <?= $user -> date1 ?>
                                              </td>

                                              <td>
                                                  <?= $user -> date2 ?>
                                              </td>

                                              <td>
                                                  <?= $user -> user_agent ?>
                                              </td>

                                              <td>
                                                  <a href="/admin/action?action=removeFromCheatersList&id1=<?=$user -> id1?>&id2=<?=$user -> id2?>" style="color: red">убрать из списка</a>
                                              </td>

                                          </tr>
                                      <?php endforeach;
                                    endif;?>

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