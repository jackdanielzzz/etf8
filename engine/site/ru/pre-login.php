<?php
/* /engine/site/ru/login.php */
?>
<div class="page-content">
    <div class="figure-3"></div>
    <div class="figure-4"></div>

    <div class="content">
        <div class="formLogin-flex">
            <div class="formLogin">

                <!-- Табы -->
                <ul class="nav nav-tabs" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pills-login-tab" data-toggle="pill"
                                data-target="#pills-login" type="button" role="tab"
                                aria-controls="pills-login" aria-selected="true">login</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-registration-tab" data-toggle="pill"
                                data-target="#pills-registration" type="button" role="tab"
                                aria-controls="pills-registration" aria-selected="false">registration</button>
                    </li>
                </ul>

                <div class="tab-content" id="pills-tabContent">

                    <!-- ==== LOGIN ==== -->
                    <div class="tab-pane fade show active" id="pills-login" role="tabpanel"
                         aria-labelledby="pills-login-tab">
                        <!-- ПЕРЕВОДИМ НА method="POST" -->
                        <form id="loginForm" action="/login" method="POST" autocomplete="off">

                            <div class="form-group">
                                <div class="input-container">
                                    <label for="email" class="placeholder">E-mail</label>
                                    <input id="email" name="email" class="input" type="email" required placeholder=" ">
                                </div>
                            </div>

                            <div class="form-group" id="passwordGroup">
                                <div class="input-container">
                                    <label for="form_password" class="placeholder">Ваш пароль</label>
                                    <input id="form_password" name="password" class="input" type="password" required placeholder=" ">
                                    <button type="button" class="pass password-visible"></button>
                                </div>
                            </div>

                            <input type="hidden" name="mode" id="mode" value="login">

                            <?php if (!empty($_GET['error'])): ?>
                                <div class="alert alert-danger">Неверная пара email/пароль</div>
                            <?php endif; ?>

                            <?php if (!empty($_GET['forgot_sent'])): ?>
                                <div class="alert alert-success">Если такой e-mail есть в системе — письмо отправлено.</div>
                            <?php endif; ?>

                            <?php if (!empty($_GET['forgot_error'])): ?>
                                <div class="alert alert-danger">Некорректный e-mail</div>
                            <?php endif; ?>

                            <div class="formLogin-button">
                                <button id="submitBtn" type="submit" class="btn-sign btn btn-primary w-100">Войти</button>
                                <a href="#" id="forgotLink" class="btn-pass btn btn-link">Забыли пароль?</a>
                            </div>

                            <div class="formLogin-sign">
                                <a href="#pills-registration" class="nav-link" data-toggle="pill">Создать аккаунт</a>
                            </div>
                        </form>
                    </div>

                    <!-- ==== REGISTRATION (оставил прежним, авторизация нам важнее) ==== -->
                    <div class="tab-pane fade" id="pills-registration" role="tabpanel" aria-labelledby="pills-registration-tab">
                        <!-- все ИЗМЕНЕНИЯ помечены ★  -->
                        <form id="regForm" action="/register-new-user" method="POST" autocomplete="off">
                            <div style="position:absolute;left:-9999px;opacity:0" aria-hidden="true">
                                <label>Ваш сайт</label>
                                <input type="text" name="website" tabindex="-1" autocomplete="off">
                                <input type="hidden" name="form_started_at" value="<?= time() ?>">
                            </div>
                            <!-- 1. Name / Last name -->
                            <div class="form-group">
                                <div class="input-container">
                                    <label for="user_name" class="placeholder">Ваше полное имя</label>
                                    <input id="user_name" name="user_name" class="input" type="text"
                                    maxlength="128" placeholder=" " />
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="input-container">
                                    <label for="sur_name" class="placeholder">Ваша фамилия</label>
                                    <input id="sur_name" name="sur_name" class="input" type="text"
                                    maxlength="128" placeholder=" " />
                                </div>
                            </div>

                            <!-- 2. Telegram (обязательное поле БД) -->
                            <div class="form-group">
                                <div class="input-container">
                                    <label for="telegram" class="placeholder">Telegram никнейм *</label>
                                    <input id="telegram" name="telegram" class="input" type="text"
                                    required placeholder="@username" />
                                </div>
                            </div>

                            <!-- 3. E-mail -->
                            <div class="form-group">
                                <div class="input-container">
                                    <label for="emailReg" class="placeholder">E-mail *</label>
                                    <input id="emailReg" name="email" class="input" type="email"
                                    required placeholder=" " />
                                </div>
                            </div>

                            <!-- 4. Password + Confirm -->
                            <div class="form-group">
                                <div class="input-container">
                                    <label for="passwordReg" class="placeholder">Придумайте пароль *</label>
                                    <input id="passwordReg" name="password" class="input"
                                    type="password" minlength="8" required placeholder=" " />
                                    <button type="button" class="pass2 password-visible2"></button>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="input-container">
                                    <label for="passwordConfirm" class="placeholder">Подтвердите пароль *</label>
                                    <input id="passwordConfirm" class="input" type="password"
                                    minlength="8" required placeholder=" " />
                                    <button type="button" class="pass3 password-visible3"></button>
                                </div>
                            </div>

                            <!-- 5. Phone (необязательно) -->
                            <div class="form-group">
                                <div class="input-container">
                                    <label for="phone" class="placeholder">Ваш номер телефона</label>
                                    <input id="phone" name="phone" class="input" type="tel"
                                           maxlength="64" placeholder=" " />
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="input-container">
                                    <label for="ref" class="placeholder">У вас есть реферальный код?</label>
                                    <input id="ref" name="ref_code" class="input" type="text"
                                           value="<?= htmlspecialchars($_GET['code'] ?? '', ENT_QUOTES) ?>"
                                           maxlength="64" placeholder=" " />
                                </div>
                            </div>

                            <?php
                            $prizes = getPrizeListPreserveDuplicates();
                            ?>

                            <?php if (!empty($prizes)): ?>
                                <?php $prizesStr = htmlspecialchars(implode(', ', $prizes), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>
                                <div class="form-group">
                                    <div class="input-container">
                                        <label for="roulette" class="placeholder">Призы рулетки</label>
                                        <input class="input" readonly value="<?= count($prizes) ?> приза" />
                                        <input id="roulette" name="roulette_prize" class="input" type="hidden" readonly value="<?= $prizesStr ?>" />
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- 6. Submit -->
                            <div class="formLogin-button">
                                <button type="submit" class="btn-sign btn btn-primary w-100">Создать аккаунт</button>
                                <a href="#pills-login" class="btn-pass btn btn-link" data-toggle="pill">Уже есть аккаунт?</a>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<div class="customModal_2">
    <div class="modal fade activity_modal" id="text_2" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">

            <div class="modal-content">

                <div class="modal-close">

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="verification-success_icon">
                        <i class="icon-successBig d-none" id="loginIcon" style="margin: 0 auto;"></i>
                    </div>

                    <div class="verification-success_text">
                        <p id="loginMsg"></p>
                    </div>

                    <div class="verification-success_button">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="loginButton"></button>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('forgotLink').addEventListener('click', function (e) {
        e.preventDefault();
        const pwdGroup  = document.getElementById('passwordGroup');
        const pwdInput  = document.getElementById('form_password');
        const submitBtn = document.getElementById('submitBtn');
        const mode      = document.getElementById('mode');

        pwdGroup.style.display = 'none';
        pwdInput.removeAttribute('required');

        mode.value = 'forgot';
        submitBtn.textContent = 'Отправить пароль';


        document.getElementById('email').focus();
        document.getElementById('forgotLink').remove();
    });

    document.addEventListener('DOMContentLoaded', () => {

        const form   = document.getElementById('regForm');
        const p1     = document.getElementById('passwordReg');
        const p2     = document.getElementById('passwordConfirm');

        const check = () => {
            if (p1.value !== p2.value) {
                p2.setCustomValidity('Пароли не совпадают');
            } else {
                p2.setCustomValidity('');
            }
        };
        p1.addEventListener('input', check);
        p2.addEventListener('input', check);


        const params = new URLSearchParams(window.location.search);
        if (params.has('code')) {
            const regTabBtn = document.getElementById('pills-registration-tab');
            if (regTabBtn) regTabBtn.click();
        }

        const loginLink = document.querySelector('a[href="#pills-login"][data-toggle="pill"]');
        if (loginLink) {
            loginLink.addEventListener('click', e => {
                e.preventDefault();
                const loginTabBtn = document.getElementById('pills-login-tab');
                if (loginTabBtn) loginTabBtn.click();
            });
        }

        form.addEventListener('submit', async e => {
            e.preventDefault();
            if (!form.checkValidity()) { form.reportValidity(); return; }

            const btn = form.querySelector('button[type="submit"]');
            btn.disabled = true;

            try {
                const fd = new FormData(form);
                const r = await fetch(form.action, {method:'POST', body: fd});
                // if (!r.ok) throw new Error('Server returned ' + r.status);
                const js = await r.json();
                // console.log(js);
                if (js.ok) {
                    $('#loginIcon').removeClass('d-none');
                    $('#loginMsg').text('Проверьте почту (включая «Спам») и перейдите по ссылке для активации.');

                    const $btn = $('#loginButton');
                    $btn
                        .text('Войти')
                        .removeAttr('data-dismiss')   // чтобы не закрывать модал по клику
                        .removeAttr('href')           // на случай, если раньше проставляли
                        .off('click')                 // снимаем старые обработчики
                        .on('click', function (e) {
                            e.preventDefault();
                            window.location.assign('/login'); // вот так и переходим
                        });

                    $('#text_2').modal('show');
                } else {
                    const fallback = 'Не удалось создать аккаунт. Попробуйте ещё раз или напишите в поддержку';
                    const msg = js && js.error ? ('Ошибка регистрации: ' + js.error) : fallback;
                    $('#loginMsg').text(msg);

                    const $btn = $('#loginButton');
                    $btn
                        .text('OK')
                        .attr('data-dismiss', 'modal') // закрыть модал
                        .removeAttr('href')            // гарантированно без href
                        .off('click');                 // и без редиректа

                    $('#text_2').modal('show');
                }


            } catch(err) {
                console.error(err);
                alert(err);
            } finally {
                btn.disabled = false;
            }
        });
    });

    <?php if (!empty($_GET['unverified'])): ?>
    document.addEventListener('DOMContentLoaded', function () {
        $('#loginTitle').text('E-mail не подтверждён');
        $('#loginMsg').html(
            'Проверьте почту (включая «Спам») и&nbsp;перейдите по&nbsp;ссылке для активации аккаунта.<br>' +
            'Затем попробуйте войти ещё раз.'
        );
        $('#text_2').modal('show');
    });
    <?php endif; ?>

    <?php if (!empty($_GET['onreview'])): ?>
    document.addEventListener('DOMContentLoaded', () => {
        $('#loginTitle').text('Документы на проверке');
        $('#loginMsg').html(
            'Ваши документы успешно загружены и&nbsp;переданы на&nbsp;проверку.<br>' +
            'Пожалуйста, ожидайте — это занимает до&nbsp;24&nbsp;часов.'
        );
        $('#text_2').modal('show');
    });
    <?php endif; ?>

    <?php if (!empty($_GET['gotoverify'])): ?>
    document.addEventListener('DOMContentLoaded', () => {
        $('#loginTitle').text('Загрузите все документы');
        $('#loginMsg').html(
            'Для начала проверки необходимо загрузить <b>четыре&nbsp;файла</b> (фронт/оборот, селфи, адрес).<br>' +
            'После полной загрузки мы сможем запустить процедуру верификации.'
        );

        $('#loginButton').text('Перейти').removeAttr('data-dismiss').on('click', function() {
            window.location.href = '/verification?code=' + <?php echo json_encode($_GET['gotoverify']); ?>;
        });

        // Показываем модалку
        $('#text_2').modal('show');
    });
    <?php endif; ?>


</script>