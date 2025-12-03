<?php
    include('adminHeader.php');

    $allOutputs = getAllOutputs();

?>

        <div class="page-body">

          <!-- Container-fluid starts-->
          <div class="container-fluid">
            <div class="row">
              <!-- Zero Configuration  Starts-->
              <div class="col-sm-12">
                  <div class="card">
                      <div class="card-header">
                          <h5>Вывод средств</h5>
                      </div>

                      <div class="col-sm-6">

                      </div>

                      <div class="card-body">
                          <div class="table-responsive">
                              <table class="display" id="universal-save-sort">
                                  <thead>
                                  <tr>
                                      <th>Инвестор</th>
                                      <th>Метод</th>
                                      <th>Кошелек</th>
                                      <th>Дата</th>
                                      <th>Сумма</th>
                                      <th>Статус</th>
                                      <th>Действие</th>
                                      <th>Действие</th>
                                      <th>Действие</th>
                                  </tr>
                                  </thead>
                                  <tbody>

                                  <?php

                                  foreach($allOutputs as $currentOutput ){
                                      $currentUser = getUserById($currentOutput['user_id']);
                                      ?>

                                      <tr>
                                          <td>
                                              <?php if ($currentUser) {?>
                                                  <a href="/admin/edit-user?id=<?= $currentUser['uid'] ?>">
                                                      <?='<b>'. $currentUser['user_name'] . ' ' . $currentUser['sur_name'] . '</b><br>(' .$currentUser['email'] .')'?>
                                                  </a>
                                              <?php } else { ?>
                                                  <p style="color: red">транзакция ссылается на несуществующего пользователя</p>
                                              <?php }?>
                                          </td>
                                          <td><?=$currentOutput['method']?></td>
                                          <td><?=$currentOutput['wallet']?></td>
                                          <td><?=$currentOutput['date']?></td>
                                          <td><?=$currentOutput['amount_usd']?></td>

                                          <?php if ($currentOutput['blocked']==0) { ?>
                                              <td><?=($currentOutput['status']==0)?"Ожидание...":(($currentOutput['status']==1)?"ОК":"ОТМЕНА")?></td>
                                          <?php } else { ?>
                                              <td><?=($currentOutput['blocked']==1)?"БЛОК":""?></td>
                                            <?php } ?>
                                          <td>
                                              <?php if ($currentUser)
                                                    if( $currentOutput['blocked']==0 ) {?>
                                                   <?php if( $currentOutput['status']==0 ){ ?>
                                                            <button class="btn-primary pay-output-button" data-id="<?= $currentOutput['id'] ?>">Оплатить</button>
                                                   <?php }} ?>
                                          </td>

                                          <td>
                                              <?php if ($currentUser)
                                                    if( $currentOutput['blocked']==0 ){?>
                                                        <?php if( $currentOutput['status']==0 ){ ?>
                                                            <button class="btn-info cancel-output-button" data-id="<?= $currentOutput['id'] ?>">Отменить</button>
                                                    <?php }} ?>
                                          </td>

                                          <td>
                                              <?php if ($currentUser) {?>
                                                  <?php if( $currentOutput['blocked'] != 1 ){ ?>
                                                      <button class="btn-danger block-output-button" data-id="<?= $currentOutput['id'] ?>">Заблокировать</button>
                                                  <?php } else if( $currentOutput['blocked'] == 1 ) { ?>
                                                      <button class="btn btn-warning btn-xs unblock-output-button" data-id="<?= $currentOutput['id'] ?>">Разблокировать</button>
                                              <?php }} ?>
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

    <script>
        // Получаем ссылки на все кнопки ОПЛАТИТЬ в таблице
        let payOutputButtons = document.querySelectorAll('.pay-output-button');
        // Проходимся по каждой кнопке ОПЛАТИТЬ и назначаем обработчик события "клик"
        payOutputButtons.forEach(function(payButton) {
            payButton.addEventListener('click', function(event) {
                // Получаем ссылку на текущую кнопку
                let payButton = event.target;
                // Получаем ID из атрибута кнопки (например, data-id)
                let id = payButton.getAttribute('data-id');

                // Отправляем AJAX-запрос с использованием ID
                let xhr = new XMLHttpRequest();
                xhr.open('GET', '/admin/action?action=acceptOutput&id=' + id, true);
                xhr.setRequestHeader('Content-Type', 'application/json');

                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            // Запрос успешно выполнен
                            console.log('AJAX-запрос выполнен успешно.');

                            let json = JSON.parse(this.response);
                            if (json.result === "success") {
                                console.log('success');
                                // Получаем ссылку на родительскую ячейку текущей кнопки
                                let curPos = payButton.parentNode;
                                // Получаем ссылку на ячейку слева от текущей
                                let leftPos = curPos.previousElementSibling;
                                leftPos.innerHTML = 'OK';
                                let rightPos = curPos.nextElementSibling;
                                rightPos.innerHTML = '';
                                // Создаем новую кнопку
                                let newButton = document.createElement('button');
                                newButton.className = 'btn-info';
                                // Устанавливаем значение атрибута data-id
                                newButton.setAttribute('data-id', id);
                                newButton.textContent = 'Готово';
                                // Заменяем содержимое текущей ячейки на новую кнопку
                                curPos.innerHTML = '';
                                curPos.appendChild(newButton);
                            } else {
                                console.log(json);
                            }

                        } else {
                            // Обработка ошибок
                            alert('Произошла ошибка при выполнении AJAX-запроса.');
                        }
                    }
                };

                xhr.send();
            });
        });

        // Получаем ссылки на все кнопки ОТМЕНИТЬ в таблице
        let cancelOutputButtons = document.querySelectorAll('.cancel-output-button');
        // Проходимся по каждой кнопке ОТМЕНИТЬ и назначаем обработчик события "клик"
        cancelOutputButtons.forEach(function(cancelButton) {
            cancelButton.addEventListener('click', function(event) {
                // Получаем ссылку на текущую кнопку
                let cancelButton = event.target;
                // Получаем ID из атрибута кнопки (например, data-id)
                let id = cancelButton.getAttribute('data-id');

                // Отправляем AJAX-запрос с использованием ID
                let xhr = new XMLHttpRequest();
                xhr.open('GET', '/admin/action?action=denyOutput&id=' + id, true);
                xhr.setRequestHeader('Content-Type', 'application/json');

                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            // Запрос успешно выполнен
                            console.log('AJAX-запрос выполнен успешно.');

                            let json = JSON.parse(this.response);
                            if (json.result === "success") {
                                console.log('success');
                                // Получаем ссылку на родительскую ячейку текущей кнопки
                                let curPos = cancelButton.parentNode;
                                // Получаем ссылку на ячейку слева от текущей
                                let leftPos = curPos.previousElementSibling;
                                leftPos.innerHTML = '';
                                leftPos = leftPos.previousElementSibling;
                                leftPos.innerHTML = 'ОТМЕНА';
                                // Создаем новую кнопку
                                let newButton = document.createElement('button');
                                newButton.className = 'btn-info';
                                // Устанавливаем значение атрибута data-id
                                newButton.setAttribute('data-id', id);
                                newButton.textContent = 'Готово';
                                // Заменяем содержимое текущей ячейки на новую кнопку
                                curPos.innerHTML = '';
                                curPos.appendChild(newButton);
                            } else {
                                console.log(json);
                            }

                        } else {
                            // Обработка ошибок
                            alert('Произошла ошибка при выполнении AJAX-запроса.');
                        }
                    }
                };

                xhr.send();
            });
        });

        // Получаем ссылки на все кнопки ЗАБЛОКИРОВАТЬ в таблице
        let blockOutputButtons = document.querySelectorAll('.block-output-button');
        // Проходимся по каждой кнопке ЗАБЛОКИРОВАТЬ и назначаем обработчик события "клик"
        blockOutputButtons.forEach(function(blockButton) {
            blockButton.addEventListener('click', function(event) {
                // Получаем ссылку на текущую кнопку
                let blockButton = event.target;
                // Получаем ID из атрибута кнопки (например, data-id)
                let id = blockButton.getAttribute('data-id');

                // Отправляем AJAX-запрос с использованием ID
                let xhr = new XMLHttpRequest();
                xhr.open('GET', '/admin/action?action=blockOutput&id=' + id, true);
                xhr.setRequestHeader('Content-Type', 'application/json');

                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            // Запрос успешно выполнен
                            console.log('AJAX-запрос выполнен успешно.');

                            let json = JSON.parse(this.response);
                            if (json.result === "success") {
                                console.log('success');
                                // Получаем ссылку на родительскую ячейку текущей кнопки
                                let curPos = blockButton.parentNode;
                                // Получаем ссылку на ячейку слева от текущей
                                let leftPos = curPos.previousElementSibling;
                                leftPos.innerHTML = '';
                                leftPos = leftPos.previousElementSibling;
                                leftPos.innerHTML = '';
                                leftPos = leftPos.previousElementSibling;
                                // Создаем новую кнопку
                                let newButton = document.createElement('button');
                                newButton.className = 'btn-info';
                                // Устанавливаем значение атрибута data-id
                                newButton.setAttribute('data-id', id);
                                newButton.textContent = 'Готово';
                                // Заменяем содержимое текущей ячейки на новую кнопку
                                curPos.innerHTML = '';
                                curPos.appendChild(newButton);
                                leftPos.innerHTML = 'БЛОК';
                            } else {
                                console.log(json);
                            }

                        } else {
                            // Обработка ошибок
                            alert('Произошла ошибка при выполнении AJAX-запроса.');
                        }
                    }
                };

                xhr.send();
            });
        });

        // Получаем ссылки на все кнопки РАЗБЛОКИРОВАТЬ в таблице
        let unBlockOutputButtons = document.querySelectorAll('.unblock-output-button');
        // Проходимся по каждой кнопке РАЗБЛОКИРОВАТЬ и назначаем обработчик события "клик"
        unBlockOutputButtons.forEach(function(unblockButton) {
            unblockButton.addEventListener('click', function(event) {
                // Получаем ссылку на текущую кнопку
                let unblockButton = event.target;
                // Получаем ID из атрибута кнопки (например, data-id)
                let id = unblockButton.getAttribute('data-id');

                // Отправляем AJAX-запрос с использованием ID
                let xhr = new XMLHttpRequest();
                xhr.open('GET', '/admin/action?action=unblockOutput&id=' + id, true);
                xhr.setRequestHeader('Content-Type', 'application/json');

                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            // Запрос успешно выполнен
                            console.log('AJAX-запрос выполнен успешно.');

                            let json = JSON.parse(this.response);
                            if (json.result === "success") {
                                console.log('success');
                                // Получаем ссылку на родительскую ячейку текущей кнопки
                                let curPos = unblockButton.parentNode;
                                // Получаем ссылку на ячейку слева от текущей
                                let leftPos = curPos.previousElementSibling;
                                leftPos = leftPos.previousElementSibling;
                                leftPos = leftPos.previousElementSibling;
                                // Создаем новую кнопку
                                let newBlockButton = document.createElement('button');
                                newBlockButton.className = 'btn-info';
                                // Устанавливаем значение атрибута data-id
                                newBlockButton.setAttribute('data-id', id);
                                newBlockButton.textContent = 'Готово';
                                // Заменяем содержимое текущей ячейки на новую кнопку
                                curPos.innerHTML = '';
                                curPos.appendChild(newBlockButton);
                                leftPos.innerHTML = '<p style="color:red;">Для подгрузки актуального статуса необходимо обновить страницу</p>';
                            } else {
                                console.log(json);
                            }

                        } else {
                            // Обработка ошибок
                            alert('Произошла ошибка при выполнении AJAX-запроса.');
                        }
                    }
                };

                xhr.send();
            });
        });
    </script>

<?php
include('adminFooter.php');