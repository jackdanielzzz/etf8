<?php

require_once 'blocks/modalsCab.php';
?>

<div class="page-content">

    <div class="content">

        <div class="tabs">

            <div class="tabs-content">
                <div class="d-flex align-items-start tabs-mobile">

                    <?php require_once 'blocks/leftTabCab.php'; ?>

                    <div class="profile">
                        <div class="profile-content">

                            <div class="profile-width">
                                <div class="profile-title">
                                    <h5>حسابي</h5>
                                </div>

                                <div class="profile-account">

                                    <div class="profile-account_img">

                                        <div class="profile-download">
                                            <input name="file" type="file" id="File" class="accountBar-file" multiple="">
                                            <label for="File" class="accountBar-fileButton2"></label>
                                        </div>

                                        <img src="/img/avatar_2.png" alt="">
                                    </div>

                                    <div class="profile-account_name">
                                        <span>اسم المستخدم</span>
                                        <p><?= $currentUser['user_name'] . " " . $currentUser['sur_name'] ?></p>
                                    </div>

                                </div>

                                <div class="accountBar-form">
                                    <form id="profileForm" autocomplete="off">
                                        <div class="form-group">
                                            <label class="profile-label">الاسم</label>
                                            <input type="text"
                                                   class="form-control"
                                                   name="user_name"
                                                   value="<?= htmlspecialchars($currentUser['user_name']) ?>">
                                        </div>

                                        <div class="form-group">
                                            <label class="profile-label">اسم العائلة</label>
                                            <input type="text"
                                                   class="form-control"
                                                   name="sur_name"
                                                   value="<?= htmlspecialchars($currentUser['sur_name']) ?>">
                                        </div>

                                        <div class="form-group">
                                            <label class="profile-label">بريدك الإلكتروني</label>
                                            <input type="email"
                                                   class="form-control"
                                                   name="email"
                                                   value="<?= htmlspecialchars($currentUser['email']) ?>"
                                                   pattern="^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$"
                                                   autocomplete="email"
                                            >
                                        </div>

                                        <div class="form-group">
                                            <label class="profile-label">هاتفك</label>
                                            <input type="tel"
                                                   class="form-control"
                                                   name="phone"
                                                   value="<?= htmlspecialchars($currentUser['phone']) ?>">
                                        </div>

                                        <div class="form-group">
                                            <label class="profile-label">كلمة المرور <br><small>الخاصة بك (اتركها فارغة - ستبقى نفس الكلمة نفسها)</small></label>
                                            <div class="input-container">
                                                <input type="password"
                                                       class="form-control"
                                                       id="password-profile"
                                                       name="password">
                                            </div>
                                        </div>

                                        <button type="submit" class="accountBar_button profile-button">
                                            أنقذ
                                        </button>
                                    </form>
                                </div>
                            </div>



                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>


</div>