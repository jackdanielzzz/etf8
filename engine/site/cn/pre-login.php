<?php
/* /engine/site/cn/login.php */
?>
<div class="page-content">
    <div class="figure-3"></div>
    <div class="figure-4"></div>

    <div class="content">
        <div class="formLogin-flex">
            <div class="formLogin">


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
                                <div class="alert alert-danger">电子邮件/密码对无效</div>
                            <?php endif; ?>

                            <?php if (!empty($_GET['forgot_sent'])): ?>
                                <div class="alert alert-success">如果系统中有此类电子邮件，则信件已发送。</div>
                            <?php endif; ?>

                            <?php if (!empty($_GET['forgot_error'])): ?>
                                <div class="alert alert-danger">无效的电子邮件</div>
                            <?php endif; ?>

                            <div class="formLogin-button">
                                <button id="submitBtn" type="submit" class="btn-sign btn btn-primary w-100">登录</button>
                                <a href="#" id="forgotLink" class="btn-pass btn btn-link">忘记密码？</a>
                            </div>

                            <div class="formLogin-sign">
                                <a href="#pills-registration" class="nav-link" data-toggle="pill">创建一个帐户</a>
                            </div>
                        </form>
                    </div>


                    <div class="tab-pane fade" id="pills-registration" role="tabpanel" aria-labelledby="pills-registration-tab">

                        <form id="regForm" action="/register-new-user" method="POST" autocomplete="off">
                            <!-- 1. Name / Last name -->
                            <div class="form-group">
                                <div class="input-container">
                                    <label for="user_name" class="placeholder">您的全名</label>
                                    <input id="user_name" name="user_name" class="input" type="text"
                                    maxlength="128" placeholder=" " />
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="input-container">
                                    <label for="sur_name" class="placeholder">您的姓氏</label>
                                    <input id="sur_name" name="sur_name" class="input" type="text"
                                    maxlength="128" placeholder=" " />
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="input-container">
                                    <label for="telegram" class="placeholder">电报昵称 *</label>
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
                                    <label for="passwordReg" class="placeholder">创建密码 *</label>
                                    <input id="passwordReg" name="password" class="input"
                                    type="password" minlength="8" required placeholder=" " />
                                    <button type="button" class="pass2 password-visible2"></button>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="input-container">
                                    <label for="passwordConfirm" class="placeholder">确认密码 *</label>
                                    <input id="passwordConfirm" class="input" type="password"
                                    minlength="8" required placeholder=" " />
                                    <button type="button" class="pass3 password-visible3"></button>
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="input-container">
                                    <label for="phone" class="placeholder">您的电话号码</label>
                                    <input id="phone" name="phone" class="input" type="tel"
                                           maxlength="64" placeholder=" " />
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="input-container">
                                    <label for="ref" class="placeholder">您有推荐代码吗？</label>
                                    <input id="ref" name="ref_code" class="input" type="text"
                                           value="<?= htmlspecialchars($_GET['code'] ?? '', ENT_QUOTES) ?>"
                                           maxlength="64" placeholder=" " />
                                </div>
                            </div>

                            <!-- 6. Submit -->
                            <div class="formLogin-button">
                                <button type="submit" class="btn-sign btn btn-primary w-100">创建一个帐户</button>
                                <a href="#pills-login" class="btn-pass btn btn-link" data-toggle="pill">已经有帐户？</a>
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
        submitBtn.textContent = '发送密码';


        document.getElementById('email').focus();
        document.getElementById('forgotLink').remove();
    });

    document.addEventListener('DOMContentLoaded', () => {

        const form   = document.getElementById('regForm');
        const p1     = document.getElementById('passwordReg');
        const p2     = document.getElementById('passwordConfirm');

        const check = () => {
            if (p1.value !== p2.value) {
                p2.setCustomValidity('密码不匹配');
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
                console.log(js);
                if (js.ok) {
                    $('#loginIcon').removeClass('d-none');
                    $('#loginMsg').html('检查您的电子邮件（包括“垃圾邮件”）并点击激活链接。');
                    $('#loginButton')
                        .text('Войти')
                        .removeAttr('data-dismiss')
                        .attr('href', '/login');
                    $('#text_2').modal('show');
                } else {
                    $('#loginMsg').html('注册错误： ' + js.error || '帐户创建失败。重试或写信给支持');
                    $('#loginButton')
                        .text('OK')
                        .attr('data-dismiss', 'modal')
                        .removeAttr('href');
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
        $('#loginTitle').text('电子邮件未确认');
        $('#loginMsg').html(
            '检查您的电子邮件（包括“垃圾邮件”）并&nbsp;点击链接激活&nbsp;您的帐户。<br>' +
            '然后尝试重新登录.'
        );
        $('#text_2').modal('show');
    });
    <?php endif; ?>

    <?php if (!empty($_GET['onreview'])): ?>
    document.addEventListener('DOMContentLoaded', () => {
        $('#loginTitle').text('审查中的文件');
        $('#loginMsg').html(
            '您的文件已成功上传并&nbsp;提交审核&nbsp;.<br>' +
            '请期待 - 最多&nbsp;需要 24&nbsp;小时.'
        );
        $('#text_2').modal('show');
    });
    <?php endif; ?>

    <?php if (!empty($_GET['gotoverify'])): ?>
    document.addEventListener('DOMContentLoaded', () => {
        $('#loginTitle').text('З上传所有文件');
        $('#loginMsg').html(
            '要开始检查，您需要上传<b>四个&nbsp;文件</b>（正面/背面、自拍、地址）。<br>' +
            'П完全加载后，我们将能够开始验证程序.'
        );

        $('#loginButton').text('Перейти').removeAttr('data-dismiss').on('click', function() {
            window.location.href = '/verification?code=' + <?php echo json_encode($_GET['gotoverify']); ?>;
        });


        $('#text_2').modal('show');
    });
    <?php endif; ?>


</script>