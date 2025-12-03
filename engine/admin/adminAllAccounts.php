<?php
    include('adminHeader.php');

    $allUsers = getAllUsers();

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
                          <h5>Аккаунты</h5>
                      </div>

                      <div class="col-sm-6">

                      </div>

                      <div class="card-body">
                          <div class="table-responsive">
                              <table class="display" id="universal-save-sort">
                                  <thead>
                                  <tr>
                                      <th>Имя фамилия</th>
                                      <th>Контакты</th>
                                      <th>Дата регистрации</th>
                                      <th>Почта подтверждена</th>
                                      <th>Баланс</th>
                                      <th>Активы</th>
                                      <th>Активный аккаунт</th>
                                  </tr>
                                  </thead>
                                  <tbody>

                                  <?php
                                    if ($allUsers):
                                      foreach ($allUsers as $user):
                                      ?>
                                          <tr>
                                              <td>
                                                  <a href="/admin/edit-user?id=<?= $user['uid'] ?>">
                                                    <?=$user['user_name'] . ' ' . $user['sur_name']?>
                                                  </a>
                                              </td>

                                              <td>
                                                  <b>id: </b><?=$user['uid']?><br>
                                                  <b>Тел: </b><?=$user['phone']?><br>
                                                  <b>Телега: </b><?=$user['telegram']?><br>
                                                  <b>Почта: </b><?=$user['email']?><br>
                                              </td>

                                              <td>
                                                  <?=$user['create_date']?>
                                              </td>

                                              <td>
                                                  <?php if( $user['email_status'] == '1' ) echo "Да";
                                                  else echo "Нет";
                                                  ?>
                                              </td>

                                              <td>
                                                  <?=round($user['balance'],2)?>
                                              </td>

                                              <td>
                                                  <?=$user['balance']?>
                                              </td>

                                              <td>
                                                  <?php
                                                  if( $user['active'] == '1' ) echo "Да";
                                                  else echo "Нет";
                                                  ?>
                                                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                  <?php if ($user) {
                                                      if ($user['active'] == 1){ ?>
                                                          <a href="/admin/action?action=disableUser&id=<?=$user['uid']?>" style="color: red;  background-color: #ced4da; padding-left: 5px; border-left-width: 6px;  padding-top: 5px; padding-bottom: 5px; padding-right: 5px;">выкл</a>
                                                      <?php }
                                                      else if ($user['active'] == 0){ ?>
                                                          <a href="/admin/action?action=enableUser&id=<?=$user['uid']?>" style="color: green; background-color: #ced4da; padding-left: 5px; border-left-width: 6px;  padding-top: 5px; padding-bottom: 5px; padding-right: 5px;">вкл</a>
                                                      <?php }
                                                  }?>
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