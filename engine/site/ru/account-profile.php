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
                                    <h5>Мой кабинет</h5>
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
                                        <span>Имя пользователя</span>
                                        <p><?= $currentUser['user_name'] . " " . $currentUser['sur_name'] ?></p>
                                    </div>

                                </div>

                                <div class="accountBar-form">
                                    <form id="profileForm" autocomplete="off">
                                        <div class="form-group">
                                            <label class="profile-label">Ваше имя</label>
                                            <input type="text"
                                                   class="form-control"
                                                   name="user_name"
                                                   value="<?= htmlspecialchars($currentUser['user_name']) ?>">
                                        </div>

                                        <div class="form-group">
                                            <label class="profile-label">Ваша фамилия</label>
                                            <input type="text"
                                                   class="form-control"
                                                   name="sur_name"
                                                   value="<?= htmlspecialchars($currentUser['sur_name']) ?>">
                                        </div>

                                        <div class="form-group">
                                            <label class="profile-label">Ваш email</label>
                                            <input type="email"
                                                   class="form-control"
                                                   name="email"
                                                   value="<?= htmlspecialchars($currentUser['email']) ?>"
                                                   pattern="^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$"
                                                   autocomplete="email"
                                            >
                                        </div>

                                        <div class="form-group">
                                            <label class="profile-label">Ваш телефон</label>
                                            <input type="tel"
                                                   class="form-control"
                                                   name="phone"
                                                   value="<?= htmlspecialchars($currentUser['phone']) ?>">
                                        </div>

                                        <div class="form-group">
                                            <label class="profile-label">Ваш пароль<br><small>(оставьте пустым — останется прежний)</small></label>
                                            <div class="input-container">
                                                <input type="password"
                                                       class="form-control"
                                                       id="password-profile"
                                                       name="password">
                                            </div>
                                        </div>

                                        <button type="submit" class="accountBar_button profile-button">
                                            Сохранить
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