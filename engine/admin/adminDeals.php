<?php
include('adminHeader.php');

$allDeals = getAllDeals();
?>

    <div class="page-body">
        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row">
                <!-- Zero Configuration  Starts-->
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5>Сделки</h5>

                            <a class="btn btn-primary" href="/admin/edit-deal">Добавить сделку</a>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="display" id="universal-save-sort">
                                    <thead>
                                    <tr>
                                        <th>ID сделки</th>
                                        <th>Название сделки</th>
                                        <th>Команда</th>
                                        <th>Регион</th>
                                        <th>Размер(тип)</th>
                                        <th>Min вход</th>
                                        <th>Max вход</th>
                                        <th>Количество дней</th>
                                        <th>Процент</th>
                                        <th>Действие</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    <?php

                                    foreach($allDeals as $deal){
                                        $team = getTeamById($deal['team_id']);
                                        $region = getRegionById($deal['region_id']);
                                        ?>

                                        <tr>
                                            <td><?=$deal['deal_id']?></td>
                                            <td><b><?=$deal['product']?></b></td>
                                            <td><?=$team['team_name']?></td>
                                            <td><?=$region['region_name']?></td>
                                            <td><?=$deal['deal_size']?></td>
                                            <td><?=$deal['amount_min']?></td>
                                            <td><?=$deal['amount_max']?></td>
                                            <td><?=$deal['term_days']?></td>
                                            <td><?=$deal['rate_without_RIX']?></td>
                                            <td>
                                                <a class="btn btn-outline-primary btn-sm" href="/admin/edit-deal?id=<?=$deal['deal_id']?>">Редактировать</a>
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
        let payInputButtons = document.querySelectorAll('.pay-input-button');
        // Проходимся по каждой кнопке ОПЛАТИТЬ и назначаем обработчик события "клик"
        payInputButtons.forEach(function(payButton) {
            payButton.addEventListener('click', function(event) {
                // Получаем ссылку на текущую кнопку
                let payButton = event.target;
                // Получаем ID из атрибута кнопки (например, data-id)
                let id = payButton.getAttribute('data-id');

                // Отправляем AJAX-запрос с использованием ID
                let xhr = new XMLHttpRequest();
                xhr.open('GET', '/admin/action?action=acceptInput&id=' + id, true);
                xhr.setRequestHeader('Content-Type', 'application/json');

                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            // Запрос успешно выполнен
                            console.log(this.response);

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
        let cancelInputButtons = document.querySelectorAll('.cancel-input-button');
        // Проходимся по каждой кнопке ОТМЕНИТЬ и назначаем обработчик события "клик"
        cancelInputButtons.forEach(function(cancelButton) {
            cancelButton.addEventListener('click', function(event) {
                // Получаем ссылку на текущую кнопку
                let cancelButton = event.target;
                // Получаем ID из атрибута кнопки (например, data-id)
                let id = cancelButton.getAttribute('data-id');

                // Отправляем AJAX-запрос с использованием ID
                let xhr = new XMLHttpRequest();
                xhr.open('GET', '/admin/action?action=denyInput&id=' + id, true);
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
        let blockInputButtons = document.querySelectorAll('.block-input-button');
        // Проходимся по каждой кнопке ЗАБЛОКИРОВАТЬ и назначаем обработчик события "клик"
        blockInputButtons.forEach(function(blockButton) {
            blockButton.addEventListener('click', function(event) {
                // Получаем ссылку на текущую кнопку
                let blockButton = event.target;
                // Получаем ID из атрибута кнопки (например, data-id)
                let id = blockButton.getAttribute('data-id');

                // Отправляем AJAX-запрос с использованием ID
                let xhr = new XMLHttpRequest();
                xhr.open('GET', '/admin/action?action=blockInput&id=' + id, true);
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
        let unBlockInputButtons = document.querySelectorAll('.unblock-input-button');
        // Проходимся по каждой кнопке РАЗБЛОКИРОВАТЬ и назначаем обработчик события "клик"
        unBlockInputButtons.forEach(function(unblockButton) {
            unblockButton.addEventListener('click', function(event) {
                // Получаем ссылку на текущую кнопку
                let unblockButton = event.target;
                // Получаем ID из атрибута кнопки (например, data-id)
                let id = unblockButton.getAttribute('data-id');

                // Отправляем AJAX-запрос с использованием ID
                let xhr = new XMLHttpRequest();
                xhr.open('GET', '/admin/action?action=unblockInput&id=' + id, true);
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
                                newBlockButton.className = 'btn-info block-input-button';
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