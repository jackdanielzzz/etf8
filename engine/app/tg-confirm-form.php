<!DOCTYPE html>
<html>
<head>
    <title>Успешно</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-size: 24px;
        }

        .btn {
            margin: 20px;
            padding: 20px 40px;
            font-size: 24px;
            border: 2px solid #000;
            background-color: #f0f0f0;
            cursor: pointer;
        }

        .success-message {
            display: none;
            margin: 20px;
            font-size: 24px;
            color: green;
        }

        .close-message {
            display: none;
            margin: 20px;
            font-size: 24px;
            color: #0827c2;
        }
    </style>
</head>
<body>
<h1 id="head1">Подтверждение операции</h1>
<p id="main-text">Вы подтверждаете <span id="operation-type" style="font-weight: bold"></span> <span id="amount"></span> пользователю <span id="email"></span>?</p>
<div id="buttons-container">
    <button class="btn" onclick="onOkClick()">ДА</button>
    <button class="btn" onclick="onCancelClick()">НЕТ</button>
</div>

<div id="success-message" class="success-message">Успешно</div>
<div id="close-message" class="close-message">Просто закройте страницу</div>

<script>
    // Функция для получения параметров из строки запроса (GET параметры)
    function getQueryParams() {
        const params = {};
        const search = window.location.search.substring(1); // Удалить знак вопроса из строки запроса
        const pairs = search.split('&'); // Разбить на пары параметров
        pairs.forEach(pair => {
            const parts = pair.split('='); // Разделить на имя и значение параметра
            if (parts.length === 2) {
                const key = decodeURIComponent(parts[0]); // Декодировать имя параметра
                const value = decodeURIComponent(parts[1]); // Декодировать значение параметра
                params[key] = value; // Добавить параметр в объект params
            }
        });
        return params; // Вернуть объект с параметрами
    }

    function onOkClick() {
        const buttonsContainer = document.getElementById('buttons-container');
        const successMessage = document.getElementById('success-message');

        // Извлекаем значение параметра 'id' из текущего URL страницы
        const params = getQueryParams(); // Получить параметры из строки запроса
        const id = params['id']; // Получить значение параметра 'id'
        const action = params['action']; // Получить значение параметра 'action'

        // Создаем новый объект XMLHttpRequest
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '/admin/tg-confirm-action', true);
        // Устанавливаем заголовок для отправки данных в формате 'application/x-www-form-urlencoded'
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        // Определяем функцию, которая будет вызываться при изменении состояния запроса
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // Запрос успешно выполнен
                    console.log(this.response);
                    let json = JSON.parse(this.response);
                    if (json.result === "success") {
                        buttonsContainer.style.display = 'none';
                        successMessage.style.display = 'block';
                    }
                    if (json.result === "already_done") {
                        alert("Сервер говорит, что операция уже была выполнена ранее!");
                    }
                }
            }
        };
        // Формируем данные для отправки в виде строки 'id=значение_id&type=значение_type'
        const data = `id=${encodeURIComponent(id)}&action=${encodeURIComponent(action)}`;
        // Отправляем POST-запрос с данными
        xhr.send(data);

    }

    function onCancelClick() {
        const buttonsContainer = document.getElementById('buttons-container');
        const closeMessage = document.getElementById('close-message');

        buttonsContainer.style.display = 'none';
        closeMessage.style.display = 'block';
    }

    function checkInputOrOutput(text) {
        if (text.includes("input")) {
            return "input";
        } else if (text.includes("output")) {
            return "output";
        } else {
            return "no any matches";
        }
    }

    function checkActionType(text) {
        if (text === 'tg-accept-input') {
            return "ввод";
        } else if (text === 'tg-deny-input') {
            return "отмену ввода";
        } else if (text === 'tg-block-input') {
            return "БЛОКировку ввода";
        } else if (text === 'tg-accept-output') {
            return "вывод";
        } else if (text === 'tg-deny-output') {
            return "отмену ввода";
        } else if (text === 'tg-block-output') {
            return "БЛОКировку вывода";
        }
    }

    // Функция для отправки AJAX-запроса
    function sendOnLoadAjaxRequest() {
        // Извлекаем значение параметра 'id' из текущего URL страницы
        const params = getQueryParams(); // Получить параметры из строки запроса
        const id = params['id']; // Получить значение параметра 'id'
        const action = params['action']; // Получить значение параметра 'action'
        const type = checkInputOrOutput(action);
        const actionType = checkActionType(action);

        // Создаем новый объект XMLHttpRequest
        const xhr = new XMLHttpRequest();
        // Открываем POST-запрос на URL '/admin/tg-get-info'
        xhr.open('POST', '/admin/tg-get-info', true);
        // Устанавливаем заголовок для отправки данных в формате 'application/x-www-form-urlencoded'
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        // Определяем функцию, которая будет вызываться при изменении состояния запроса
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // Запрос успешно выполнен
                    console.log(this.response);
                    let json = JSON.parse(this.response);
                    if (json.result === "success") {
                        // Находим элементы <span> по их id
                        const operationTypeSpan = document.getElementById("operation-type");
                        const amountSpan = document.getElementById("amount");
                        const emailSpan = document.getElementById("email");

                        // Устанавливаем значения текстового содержимого <span> из данных JSON
                        operationTypeSpan.textContent = actionType; // Может быть, у вас уже есть значение из JSON, вместо жесткой строки
                        amountSpan.textContent = json.amount + "$";
                        emailSpan.textContent = json.usermail;
                    }
                    if (json.result === "already_done") {
                        const mainText = document.getElementById('head1');
                        mainText.style.display = 'none';


                        const buttonsDiv = document.getElementById('buttons-container');
                        buttonsDiv.style.display = 'none';

                        const head1Element = document.getElementById('main-text');
                        head1Element.textContent = 'Операция «' + actionType + '» ' + json.amount + "$ пользователю: " + json.usermail + ' уже была выполнена ранее. Закройте страницу!';
                    }
                }
            }
        };
        // Формируем данные для отправки в виде строки 'id=значение_id&type=значение_type'
        const data = `id=${encodeURIComponent(id)}&type=${encodeURIComponent(type)}`;
        // Отправляем POST-запрос с данными
        xhr.send(data);
    }

    // Вызываем функцию отправки AJAX-запроса при загрузке страницы
    sendOnLoadAjaxRequest();
</script>
</body>
</html>
