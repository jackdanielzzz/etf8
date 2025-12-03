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
                                    <h5>我的办公室</h5>
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
                                        <span>姓名 用户</span>
                                        <p><?= $currentUser['user_name'] . " " . $currentUser['名称为'] ?></p>
                                    </div>

                                </div>

                                <div class="accountBar-form">
                                    <form id="profileForm" autocomplete="off">
                                        <div class="form-group">
                                            <label class="profile-label">你的名字</label>
                                            <input type="text"
                                                   class="form-control"
                                                   name="user_name"
                                                   value="<?= htmlspecialchars($currentUser['用户名']) ?>">
                                        </div>

                                        <div class="form-group">
                                            <label class="profile-label">你的姓</label>
                                            <input type="text"
                                                   class="form-control"
                                                   name="sur_name"
                                                   value="<?= htmlspecialchars($currentUser['名称为']) ?>">
                                        </div>

                                        <div class="form-group">
                                            <label class="profile-label">您的电子邮件</label>
                                            <input type="email"
                                                   class="form-control"
                                                   name="email"
                                                   value="<?= htmlspecialchars($currentUser['电子邮件']) ?>"
                                                   pattern="^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$"
                                                   autocomplete="email"
                                            >
                                        </div>

                                        <div class="form-group">
                                            <label class="profile-label">你的电话</label>
                                            <input type="tel"
                                                   class="form-control"
                                                   name="phone"
                                                   value="<?= htmlspecialchars($currentUser['电话']) ?>">
                                        </div>

                                        <div class="form-group">
                                            <label class="profile-label">您的密码<br><small>（留空 - 将保持不变）</small></label>
                                            <div class="input-container">
                                                <input type="password"
                                                       class="form-control"
                                                       id="password-profile"
                                                       name="password">
                                            </div>
                                        </div>

                                        <button type="submit" class="accountBar_button profile-button">
                                            节省
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