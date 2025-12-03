<?php

require_once 'blocks/modalsCab.php';
?>

<div class="page-content">
        

    <div class="figure-15"></div>

    <div class="content">
        <div class="tabs">

            <div class="tabs-content">
                <div class="d-flex align-items-start tabs-mobile">

                    <?php require_once 'blocks/leftTabCab.php'; ?>

                    <div class="tab-content" id="v-pills-tabContent">

                        <div class="tab-pane fade show active" id="v-pills-assets" role="tabpanel" aria-labelledby="v-pills-assets-tab">


                            <div class="tabs-1">

                                <div class="total">

                                    <div class="total-left">

                                        <div class="total-leftTop">
                                            <div class="total-title">
                                                <h5>Общий доход</h5>
                                            </div>

                                            <div class="total-top">
                                                <?php if ($assetsToDailyIncomePercent > 0 ): ?>
                                                    <div class="total-icon">
                                                        <i class="icon-arrowTop"></i>
                                                    </div>
                                                <?php endif; ?>

                                                <div class="total-text">
                                                    <p><?= $assetsToDailyIncomePercent . "%" ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="total-sum">

                                            <div class="total-sum_icon">
                                                <i class="icon-tephBig"></i>
                                            </div>

                                            <div class="total-sum_text">
                                                <p><?= number_format($currentUser['total_accrued'], 1, '.', ' ') ?></p>
                                                <span>Usdt</span>
                                            </div>

                                        </div>

                                        <div class="total-button" id="rixaiBoost">
                                            <a href="#" data-toggle="modal" data-target="#boost1">
                                                <i class="icon-rein"></i>
                                                <span>RIX AI boost</span>
                                            </a>
                                        </div>

                                    </div>

                                    <div class="total-center"></div>

                                    <div class="total-right">

                                        <div class="total-graphic">
                                            <figure class="highcharts-block">
                                                <div id="charts"></div>
                                            </figure>


                                            <div class="total-circle"></div>
                                        </div>

                                        <div class="total-right_text">

                                            <div class="total-right_item">
                                                <div class="total-right_title">
                                                    <h5>Суточный доход</h5>
                                                </div>

                                                <div class="total-right_sum">

                                                    <div class="total-right_icon">
                                                        <i class="icon-teph"></i>
                                                    </div>

                                                    <div class="total-right__text">
                                                        <p><?= $dailyIncomeTotal ?></p>
                                                        <span>Usdt</span>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="total-right_item">
                                                <div class="total-right_title total-right_orange">
                                                    <h5>Квартальный доход</h5>
                                                </div>

                                                <div class="total-right_sum">

                                                    <div class="total-right_icon">
                                                        <i class="icon-teph"></i>
                                                    </div>

                                                    <div class="total-right__text">
                                                        <p><?= $quarterIncome ?></p>
                                                        <span>Usdt</span>
                                                    </div>

                                                </div>
                                            </div>


                                        </div>



                                    </div>

                                </div>


                                <?php
                                function renderDealCard(array $deal, array $dailyIncomeByDeal, bool $hidden = false): string
                                {
                                    $isClosed   = (bool)$deal['is_closed'];
                                    $isActive   = $deal['status'] === 'active';
                                    $isInactive = !$isActive && !$isClosed;
                                    $isNew      = (bool)$deal['is_new'];
                                    $dayIncome  = $dailyIncomeByDeal[$deal['user_deal_id']] ?? 0;

                                    $classes = ['totalDeal'];
                                    if ($isInactive)   $classes[] = 'closed';
                                    if ($hidden)       $classes[] = 'd-none';

                                    ob_start(); ?>
                                    <div class="<?= implode(' ', $classes) ?>"
                                         data-user-deal-id="<?= (int)$deal['user_deal_id'] ?>"
                                         data-is-new="<?= (int)$isNew ?>">

                                        <!-- ===== ОБЩАЯ ИНФОРМАЦИЯ ===== -->
                                        <div class="totalInfo">

                                            <!-- ==== ЛЕВО: суммы, даты ==== -->
                                            <div class="totalInfo-left">
                                                <?php
                                                // Чтобы не повторять одинаковые три блока, описываем их одной функцией
                                                $items = [
                                                    ['Сумма ETF-актива',    $deal['principal']],
                                                    ['Общая доходность',    $deal['accrued_amount']]
                                                ];
                                                foreach ($items as [$title, $val]): ?>
                                                    <div class="totalInfo-left_item">
                                                        <div class="totalInfo-left_title"><h5><?= $title ?></h5></div>
                                                        <div class="totalInfo-left_block">
                                                            <div class="totalInfo-left_icon"><i class="icon-teph"></i></div>
                                                            <div class="totalInfo-left_text">
                                                                <p><?= moneyFormat($val) ?></p><span> USDT</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>

                                                <div class="totalInfo-left_date">
                                                    <div class="totalInfo-date-Item"><p>Дата начала:</p>
                                                        <span><?= date('d.m.Y', strtotime($deal['start_date'])) ?></span></div>
                                                    <div class="totalInfo-date-Item"><p>Дата окончания:</p>
                                                        <span><?= date('d.m.Y', strtotime($deal['end_date'])) ?></span></div>
                                                </div>
                                            </div><!-- /.totalInfo-left -->

                                            <div class="totalInfo-center"></div>

                                            <!-- ==== ПРАВО: продукт и ваш доход ==== -->
                                            <div class="totalInfo-right">
                                                <div class="totalInfo-right_top">
                                                    <div class="totalInfo-right_block">
                                                        <div class="totalInfo-right_left">
                                                            <div class="totalInfo-right_title"><h5>Конфигурация ETF</h5></div>
                                                            <div class="totalInfo-right_caption">
                                                                <span><?= htmlspecialchars($deal['product']) ?></span>
                                                            </div>
                                                        </div>

                                                        <div class="totalInfo-right_right">
                                                            <div class="totalInfo-right_title"><h5>Суточный доход:</h5></div>
                                                            <div class="totalInfo-left_block">
                                                                <div class="totalInfo-left_icon"><i class="icon-teph"></i></div>
                                                                <div class="totalInfo-left_text">
                                                                    <p><?= $dayIncome ?></p><span> USDT</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Кнопка «Подробнее» -->
                                                <div class="totalInfo-right_button">
                                                    <a href="#" data-toggle="modal" data-target="#text"
                                                       data-title="<?= htmlspecialchars($deal['product']) ?>"
                                                       data-note="<?= htmlspecialchars($deal['config_note'] ?? '', ENT_QUOTES) ?>">
                                                        Подробнее
                                                    </a>
                                                </div>

                                                <div class="totalInfo-status-Item">
                                                    <p>Статус конфигурации: <?= $deal['status'] ?></p>
                                                </div>
                                            </div><!-- /.totalInfo-right -->

                                        </div><!-- /.totalInfo -->

                                        <!-- ===== БАННЕРЫ ===== -->
                                        <?php if ($isNew): ?>
                                            <div class="totalClose">
                                                <div class="totalClose-icon"><i class="icon-check2"></i></div>
                                                <div class="totalClose-text"><p>Поздравляем, у Вас октрыта новая конфигурация</p></div>
                                                <div class="totalClose-button"><button type="button" class="js-deal-ok">OK</button></div>
                                            </div>
                                        <?php elseif ($deal['status'] === 'completed' && $isClosed): ?>
                                            <div class="totalClose">
                                                <div class="totalClose-icon"><i class="icon-check2"></i></div>
                                                <div class="totalClose-text">
                                                    <p>Конфигурация закрыта – вы заработали <?= moneyFormat($deal['accrued_amount']) ?> USDT</p>
                                                </div>
                                                <div class="totalClose-button"><button type="button" class="js-deal-close-ok">OK</button></div>
                                            </div>
                                        <?php endif; ?>
                                    </div><!-- /.totalDeal -->
                                    <?php
                                    return ob_get_clean();
                                }

                                /* ---------- ОТРИСОВКА ВСЕХ СДЕЛОК ОДНИМ ЦИКЛОМ ---------- */
                                $hasInactive = false;

                                foreach ($allDeals as $deal) {
                                    $isInactive = ($deal['status'] !== 'active' && $deal['is_closed'] == 0);
                                    if ($isInactive) $hasInactive = true;
                                    echo renderDealCard($deal, $dailyIncomeByDeal, $isInactive); // d-none для «спящих»
                                }

                                /* ---------- КНОПКА «ПОКАЗАТЬ НЕАКТИВНЫЕ» ---------- */
                                if ($hasInactive): ?>
                                    <div class="totalInfo-right_button" id="switchInactiveDeals">
                                        <a href="#">Показать завершенные конфигурации</a>
                                    </div>
                                <?php endif; ?>


                            </div>
            </div>

        </div>

    </div>


    <div class="customModal_2">
    <div class="modal fade activity_modal" id="text" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">

            <div class="modal-content">

                <div class="modal-close">

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>


                <div class="modal-body">

                    <div class="modal-text_base">
                        <h3 id="dealInfoTitle">Тайтл</h3>

                        <p id="dealInfoText">Описание…</p>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

    <div class="customModal_2">
        <div class="modal fade activity_modal" id="boost" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">



                <div class="modal-content">

                    <div class="modal-close">

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                    </div>


                    <div class="modal-body">

                        <div class="modal-title">
                            <h3>RIX AI BOOST</h3>
                        </div>

                        <div class="modal-body__boost">
                            <p>Дата активации подписки: 25.02.2025</p>
                            <p>Дата окончания подписки: 25.03.2025</p>
                            <p>Текущий бонус подписки: 0.1% в день</p>
                            <p>Доход от подписки: 248 USDT</p>
                        </div>

                        <div class="modal-body__accordion">

                            <div class="accordion" id="accordionButton">
                                <div class="card">
                                    <div class="card-header" id="headingOne">

                                        <button class="" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            Подробнее
                                        </button>

                                    </div>

                                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionButton">
                                        <div class="card-body">
                                            При активации подписки с вашего основного баланса будет списана сумма в размере 250 USDT
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-body__next">
                            <a href="#" data-toggle="modal" data-target="#success-boost">Продолжить</a>
                        </div>



                    </div>


                </div>
            </div>
        </div>
    </div>


    <div class="customModal_2">
        <div class="modal fade activity_modal" id="success-boost" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">



                <div class="modal-content">

                    <div class="modal-close">

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                    </div>


                    <div class="modal-body">

                        <div class="success-boost__text">
                            <p>Поздравляем, вы активировали подписку <span>RIX AI BOOST</span></p>
                        </div>

                        <div class="modal-body__next">
                            <a href="#">Продолжить</a>
                        </div>

                    </div>


                </div>
            </div>
        </div>
    </div>


</div>

<script>
    document.getElementById('rixaiBoost').addEventListener('click', function(event) {
        event.preventDefault();
        showToast('Функция будет недоступна немного позже', 3000);
    });
</script>