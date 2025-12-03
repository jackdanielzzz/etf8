<div class="nav flex-column nav-pills me-3 tabs-left" id="v-pills-tab" role="tablist" aria-orientation="vertical">

    <a href="/ar/account/deals" class="nav-link <?= isActiveTab('/ar/account/deals') ? "active" : "" ?>">


        <span class="tabsLeft">
            <span class="tabsLeft-top">
                <span class="tabsLeft-top_left">
                    <span class="tabsLeft-top_icon">
                        <i class="icon-bar"></i>
                    </span>

                    <span class="tabsLeft-top-title">
                        <p>أصول</p>
                    </span>
                </span>

                <span class="tabsRight-top_right">
                    <?php /** @var $assetsToDailyIncomePercent */
                    if ($assetsToDailyIncomePercent > 0 ): ?>
                        <span class="tabsRight-top_icon">
                            <i class="icon-arrowTop"></i>
                        </span>
                    <?php endif; ?>

                    <span class="tabsRight-top_sum">
                        <p><?= $assetsToDailyIncomePercent . "%" ?></p>
                    </span>
                </span>
            </span>

            <span class="tabsLeft-bottom">

                <span class="tabsLeft-bottom_left">
                    <span class="tabsLeft-bottom_icon">
                        <i class="icon-teph"></i>
                    </span>

                    <span class="tabsLeft-bottom_text">
                        <p><?= moneyFormat($balance) ?></p>
                        <span>Usdt</span>
                    </span>
                </span>

                <span class="tabsRight-bottom_right">
                    <span class="tabsRight-bottom_graphics">

                        <div class="tab-graphic">

                          <figure class="highcharts-block">
                            <div id="line"></div>
                          </figure>

                        </div>

                    </span>
                </span>

            </span>
        </span>


    </a>

    <a href="/ar/account/team" class="nav-link <?= isActiveTab('/ar/account/team') ? "active" : "" ?>">
        <span class="tabsLeft">
            <span class="tabsLeft-top">
                <span class="tabsLeft-top_left">
                    <span class="tabsLeft-top_icon">
                        <i class="icon-team"></i>
                    </span>

                    <span class="tabsLeft-top-title">
                        <p>فريق</p>
                    </span>
                </span>
            </span>

            <span class="tabsLeft-bottom">

                <span class="tabsLeft-bottom_left">
                    <span class="tabsLeft-bottom_icon">
                        <i class="icon-teph"></i>
                    </span>

                    <span class="tabsLeft-bottom_text">
                        <p><?= moneyFormat($balanceTeam) ?></p>
                        <span>Usdt</span>
                    </span>
                </span>

                <span class="tabsRight-bottom_right">
                    <span class="tabsRight-bottom_graphics">
                        <div class="tab-graphic">

                          <figure class="highcharts-block">
                            <div id="lineRed"></div>
                          </figure>

                        </div>
                    </span>
                </span>

            </span>
        </span>
    </a>

    <a href="/ar/account/promo" class="nav-link <?= isActiveTab('/ar/account/promo') ? "active" : "" ?>">
        <span class="tabsLeft">
            <span class="tabsLeft-top">
                <span class="tabsLeft-top_left">
                    <span class="tabsLeft-top_icon">
                        <i class="icon-promo"></i>
                    </span>

                    <span class="tabsLeft-top-title">
                        <p>الترويجي</p>
                    </span>
                </span>
            </span>

            <span class="tabsLeft-bottom">

                <span class="tabsLeft-bottom_left">
                    <span class="tabsLeft-bottom_icon">
                        <i class="icon-teph"></i>
                    </span>

                    <span class="tabsLeft-bottom_text">
                        <p><?= moneyFormat($balancePromo) ?></p>
                        <span>Usdt</span>
                    </span>
                </span>

                <span class="tabsRight-bottom_right">
                    <span class="tabsRight-bottom_graphics">
                         <div class="tab-graphic">

                          <figure class="highcharts-block">
                            <div id="lineGreen"></div>
                          </figure>

                        </div>
                    </span>
                </span>

            </span>
        </span>
    </a>

    <a href="/ar/account/rix" class="nav-link nav-link_height <?= isActiveTab('/ar/account/rix') ? "active" : "" ?>">
        <span class="tabsLeft">
            <span class="tabsLeft-top">
                <span class="tabsLeft-top_left">
                    <span class="tabsLeft-top_icon">
                        <i class="icon-collection"></i>
                    </span>

                    <span class="tabsLeft-top-title">
                        <p>مجموعة RIX</p>
                    </span>
                </span>
            </span>
        </span>
    </a>

    <div class="tab-state">
        <div class="accountBar-state mt-0">

            <div class="accountBar-state_top">
                <div class="accountBar-state_icon">
                    <i class="icon-skills"></i>
                </div>

                <div class="accountBar-state_title">
                    <h4>حالة الحساب</h4>
                </div>
            </div>


            <div class="accountBar-state_bottom">

                <div class="accountBar-state_item">
                    <div class="accountBar-item_icon">
                        <i class="icon-state2"></i>
                    </div>

                    <div class="accountBar-item_text">
                        <p>حالة العميل</p>
                        <span><?= $currentUser['rating'] ?></span>
                    </div>
                </div>

                <div class="accountBar-state_item">
                    <div class="accountBar-item_icon">
                        <i class="icon-state3"></i>
                    </div>

                    <div class="accountBar-item_text">
                        <p>التحقق من العميل</p>
                        <span><?= $currentUser['verified'] == 1 ? 'التحقق' : 'لم يتم التحقق منه'?></span>
                    </div>
                </div>

                <div class="accountBar-state_item">
                    <div class="accountBar-item_icon">
                        <i class="icon-state4"></i>
                    </div>

                    <div class="accountBar-item_text">
                        <p>تاريخ التسجيل</p>
                        <span><?= $currentUser['create_date'] ?></span>
                    </div>
                </div>

                <div class="accountBar-state_item">
                    <div class="accountBar-item_icon">
                        <i class="icon-state5"></i>
                    </div>

                    <div class="accountBar-item_text">
                        <p>دعم 24/7</p>
                        <a href="https://t.me/<?= $_ENV['TG_SUPPORT'] ?>"><?= $_ENV['TG_SUPPORT'] ?></a>
                    </div>
                </div>

                <div class="accountBar-state_item">
                    <div class="accountBar-item_icon">
                        <i class="icon-state6"></i>
                    </div>

                    <div class="accountBar-item_text">
                        <p>رابط الإحالة</p>
                        <a
                            href="<?='https://' . $_ENV['CLEAR_URL'] . '/referral?code=' . $currentUser['activation']?>"
                            class="js-copy-referral"
                            data-url="<?='https://' . $_ENV['CLEAR_URL'] . '/referral?code=' . $currentUser['activation']?>"
                        >
                            <?='https://' . $_ENV['CLEAR_URL'] . '/referral?code..'?>
                        </a>
                    </div>
                </div>

                <div class="accountBar-state_item">
                    <div class="accountBar-item_icon">
                        <i class="icon-exitBar"></i>
                    </div>

                    <div class="accountBar-item_text">
                        <a href="/logout" class="state_a">مخرج</a>
                    </div>
                </div>
            </div>


        </div>
    </div>

</div>