<?php
/* /engine/site/en/login.php */
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

                        <form action="/login" method="POST" autocomplete="off">

                            <div class="form-group">
                                <div class="input-container">
                                    <label for="email" class="placeholder">E-mail</label>
                                    <input id="email" name="email" class="input" type="email" required placeholder=" ">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="input-container">
                                    <label for="form_password" class="placeholder">Your password</label>
                                    <input id="form_password" name="password" class="input" type="password"
                                           required placeholder=" ">
                                    <button type="button" class="pass password-visible"></button>
                                </div>
                            </div>

                            <?php if (!empty($_GET['error'])): ?>
                                <div class="alert alert-danger">Invalid email/password pair</div>
                            <?php endif; ?>

                            <div class="formLogin-button">
                                <button type="submit" class="btn-sign btn btn-primary w-100">Войти</button>
                                <a href="#" class="btn-pass btn btn-link">Forgot your password?</a>
                            </div>

                            <div class="formLogin-sign">
                                <a href="#pills-registration" class="nav-link" data-toggle="pill">Create an account</a>
                            </div>
                        </form>
                    </div>


                    <div class="tab-pane fade" id="pills-registration" role="tabpanel" aria-labelledby="pills-registration-tab">
                        <!-- все ИЗМЕНЕНИЯ помечены ★  -->
                        <form id="regForm" action="/register-new-user" method="POST" autocomplete="off">
                            <!-- 1. Name / Last name -->
                            <div class="form-group">
                                <div class="input-container">
                                    <label for="user_name" class="placeholder">Your full name</label>
                                    <input id="user_name" name="user_name" class="input" type="text"
                                    maxlength="128" placeholder=" " />
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="input-container">
                                    <label for="sur_name" class="placeholder">Your last name</label>
                                    <input id="sur_name" name="sur_name" class="input" type="text"
                                    maxlength="128" placeholder=" " />
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="input-container">
                                    <label for="telegram" class="placeholder">Telegram nickname *</label>
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
                                    <label for="passwordReg" class="placeholder">Create a password *</label>
                                    <input id="passwordReg" name="password" class="input"
                                    type="password" minlength="8" required placeholder=" " />
                                    <button type="button" class="pass2 password-visible2"></button>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="input-container">
                                    <label for="passwordConfirm" class="placeholder">Confirm your password *</label>
                                    <input id="passwordConfirm" class="input" type="password"
                                    minlength="8" required placeholder=" " />
                                    <button type="button" class="pass3 password-visible3"></button>
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="input-container">
                                    <label for="phone" class="placeholder">Your phone number</label>
                                    <input id="phone" name="phone" class="input" type="tel"
                                           maxlength="64" placeholder=" " />
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="input-container">
                                    <label for="ref" class="placeholder">Do you have a referral code?</label>
                                    <input id="ref" name="ref_code" class="input" type="text"
                                           value="<?= htmlspecialchars($_GET['code'] ?? '', ENT_QUOTES) ?>"
                                           maxlength="64" placeholder=" " />
                                </div>
                            </div>

                            <!-- 6. Submit -->
                            <div class="formLogin-button">
                                <button type="submit" class="btn-sign btn btn-primary w-100">Create an account</button>
                                <a href="#pills-login" class="btn-pass btn btn-link" data-toggle="pill">Already have an account?</a>
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
    document.addEventListener('DOMContentLoaded', () => {

        const form   = document.getElementById('regForm');
        const p1     = document.getElementById('passwordReg');
        const p2     = document.getElementById('passwordConfirm');

        const check = () => {
            if (p1.value !== p2.value) {
                p2.setCustomValidity('Passwords don't match');
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
                    $('#loginMsg').html('Check your mail (including "Spam") and follow the link to activate.');
                    $('#loginButton')
                        .text('Войти')
                        .removeAttr('data-dismiss')
                        .attr('href', '/login');
                    $('#text_2').modal('show');
                } else {
                    $('#loginMsg').html('Registration error: ' + js.error || 'Account creation failed. Try again or write to support');
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
        $('#loginTitle').text('E-mail not confirmed');
        $('#loginMsg').html(
            'Check your email (including "Spam") and&nbsp;follow the&nbsp;link to activate your account. <br>' +
            'Then try to log in again.'
        );
        $('#text_2').modal('show');
    });
    <?php endif; ?>

    <?php if (!empty($_GET['onreview'])): ?>
    document.addEventListener('DOMContentLoaded', () => {
        $('#loginTitle').text('Documents under review');
        $('#loginMsg').html(
            'Your documents have been successfully uploaded and&nbsp;submitted for&nbsp;review. <br>' +
            'Please wait – it takes up to&nbsp;24&nbsp;hours.'
        );
        $('#text_2').modal('show');
    });
    <?php endif; ?>

    <?php if (!empty($_GET['gotoverify'])): ?>
    document.addEventListener('DOMContentLoaded', () => {
        $('#loginTitle').text('Upload all documents');
        $('#loginMsg').html(
            'To start the check, you need to upload <b>four&nbsp;files</b> (front/back, selfie, address). <br>' +
            'Once fully loaded, we will be able to start the verification procedure.'
        );

        $('#loginButton').text('Перейти').removeAttr('data-dismiss').on('click', function() {
            window.location.href = '/verification?code=' + <?php echo json_encode($_GET['gotoverify']); ?>;
        });


        $('#text_2').modal('show');
    });
    <?php endif; ?>


</script>