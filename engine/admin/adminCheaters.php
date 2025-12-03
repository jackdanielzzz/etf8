<?php
    include('adminHeader.php');

// Вычисляем дату на неделю раньше от текущей даты
$startDate = $_GET['date'];
$formattedDate = $startDate;

// Разделите значение на день, месяц и год, используя точку в качестве разделителя
//$parts = explode('.', $startDate);

//// Проверьте, что разделение дало три части
//if (count($parts) === 3) {
//    // Создайте новую дату в формате ГГГГ-ММ-ДД
//    $formattedDate = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
//
//    // Теперь $formattedDate содержит дату в формате "2023-10-02"
//    // Вы можете использовать $formattedDate для дальнейших операций
//} else {
//    // Обработка ошибки, если формат даты неправильный
//    echo 'Неверный формат даты';
//}

// Вызываем функцию и передаем вычисленную дату
$allCheaters = getAllGroupedCheatersFromDate($formattedDate);

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
                          <h5>Нарушители с одинаковым IP и совпадающими идентификаторами устройства <?= count($allCheaters) > 0 ? "(" . count($allCheaters).")":""?></h5>
                      </div>

<!--                      <div class="col-sm-6">-->
<!--                          <form class="form-group row" id="dateForm" method="get" action="/admin/cheaters">-->
<!--                              <label class="col-sm-6 col-form-label text-end">Искать читеров начиная с этой даты:</label>-->
<!--                              <div class="col-xl-5 col-sm-9">-->
<!--                                  <div class="input-group">-->
<!--                                      <input class="datepicker-here form-control digits" type="text" data-language="ru" id="startDateInput" name="date" value="--><?//= $formattedDate ?><!--">-->
<!--                                      <button class="btn btn-primary-light" type="submit">Искать</button>-->
<!--                                  </div>-->
<!--                              </div>-->
<!--                          </form>-->
<!--                      </div>-->

                      <div class="card-body">
                          <div class="table-responsive">
                              <table class="display" id="basic-1">
                                  <thead>
                                  <tr>
                                      <th>IP, который совпал</th>
                                      <th>Нарушитель "А"</th>
                                      <th>Нарушитель "Б"</th>
                                      <th>Идентификатор устройства</th>
                                  </tr>
                                  </thead>
                                  <tbody>

                                  <?php
                                    if ($allCheaters):
                                      foreach ($allCheaters as $user):
                                      ?>
                                          <tr>
                                              <td>
                                                  <a href="/admin/edit-cheater?ip=<?= $user -> user_ip ?>"><?= $user -> user_ip ?></a>
                                              </td>

                                              <td>
                                                  <?php
                                                  $emailArr = explode(",", $user->email1);
                                                  $uidArr = explode(",", $user->uid1);

                                                  // Проверяем, что количество элементов в массивах одинаково
                                                  if (count($emailArr) === count($uidArr)) {
                                                      // Перебираем значения в цикле
                                                      for ($i = 0; $i < count($emailArr); $i++) {
                                                          $uid = $uidArr[$i];
                                                          $userById = getUserById($uid);
                                                          $email = $userById['email'];
                                                          // Выводим значения
                                                          echo "<a href='/admin/edit-user?id=$uid'>$email</a><br>";
                                                      }
                                                  }
                                                  ?>
                                              </td>

                                              <td>
                                                  <?php
                                                  $emailArr = explode(",", $user->email2);
                                                  $uidArr = explode(",", $user->uid2);

                                                  // Проверяем, что количество элементов в массивах одинаково
                                                  if (count($emailArr) === count($uidArr)) {
                                                      // Перебираем значения в цикле
                                                      for ($i = 0; $i < count($emailArr); $i++) {
                                                          $uid = $uidArr[$i];
                                                          $userById = getUserById($uid);
                                                          $email = $userById['email'];

                                                          // Выводим значения
                                                          echo "<a href='/admin/edit-user?id=$uid'>$email</a><br>";
                                                      }
                                                  }
                                                  ?>
                                              </td>

                                              <td>
                                                  <?= $user -> user_agent ?>
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
    <script>
        // Получите ссылку на форму по ее ID
        var dateForm = document.getElementById("dateForm");

        // Получите ссылку на поле ввода начальной даты по его ID
        var startDateInput = document.getElementById("startDateInput");

        // Добавьте обработчик события input к полю ввода
        startDateInput.addEventListener("input", function () {
            // Вызовите метод submit() для формы при изменении значения в поле ввода
            dateForm.submit();
        });
    </script>
<?php
include('adminFooter.php');