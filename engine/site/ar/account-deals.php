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
                                                <h5>إجمالي الإيرادات</h5>
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
                                                <span>تعزيز الذكاء الاصطناعي RIX</span>
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
                                                    <h5>الدخل اليومي</h5>
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
                                                    <h5>الإيرادات الفصلية</h5>
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


                                        <div class="totalInfo">


                                            <div class="totalInfo-left">
                                                <?php

                                                $items = [
                                                    ['مبلغ أصول ETF',    $deal['principal']],
                                                    ['إجمالي الربحية',    $deal['accrued_amount']]
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
                                                    <div class="totalInfo-date-Item"><p>تاريخ البدء:</p>
                                                        <span><?= date('d.m.Y', strtotime($deal['start_date'])) ?></span></div>
                                                    <div class="totalInfo-date-Item"><p>تاريخ الانتهاء:</p>
                                                        <span><?= date('d.m.Y', strtotime($deal['end_date'])) ?></span></div>
                                                </div>
                                            </div><!-- /.totalInfo-left -->

                                            <div class="totalInfo-center"></div>


                                            <div class="totalInfo-right">
                                                <div class="totalInfo-right_top">
                                                    <div class="totalInfo-right_block">
                                                        <div class="totalInfo-right_left">
                                                            <div class="totalInfo-right_title"><h5>التكوين ETF</h5></div>
                                                            <div class="totalInfo-right_caption">
                                                                <span><?= htmlspecialchars($deal['product']) ?></span>
                                                            </div>
                                                        </div>

                                                        <div class="totalInfo-right_right">
                                                            <div class="totalInfo-right_title"><h5>الدخل اليومي:</h5></div>
                                                            <div class="totalInfo-left_block">
                                                                <div class="totalInfo-left_icon"><i class="icon-teph"></i></div>
                                                                <div class="totalInfo-left_text">
                                                                    <p><?= moneyFormat($dayIncome) ?></p><span> USDT</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="totalInfo-right_button">
                                                    <a href="#" data-toggle="modal" data-target="#text"
                                                       data-title="<?= htmlspecialchars($deal['product']) ?>"
                                                       data-note="<?= htmlspecialchars($deal['config_note'] ?? '', ENT_QUOTES) ?>">
                                                        التعرف على المزيد
                                                    </a>
                                                </div>

                                                <div class="totalInfo-status-Item">
                                                    <p>حالة التكوين: <?= $deal['status'] ?></p>
                                                </div>
                                            </div><!-- /.totalInfo-right -->

                                        </div><!-- /.totalInfo -->


                                        <?php if ($isNew): ?>
                                            <div class="totalClose">
                                                <div class="totalClose-icon"><i class="icon-check2"></i></div>
                                                <div class="totalClose-text"><p>تهانينا، لديك تكوين جديد</p></div>
                                                <div class="totalClose-button"><button type="button" class="js-deal-ok">موافق</button></div>
                                            </div>
                                        <?php elseif ($deal['status'] === 'completed' && $isClosed): ?>
                                            <div class="totalClose">
                                                <div class="totalClose-icon"><i class="icon-check2"></i></div>
                                                <div class="totalClose-text">
                                                    <p>التكوين مغلق - لقد ربحت <?= moneyFormat($deal['accrued_amount']) ?> USDT</p>
                                                </div>
                                                <div class="totalClose-button"><button type="button" class="js-deal-close-ok">OK</button></div>
                                            </div>
                                        <?php endif; ?>
                                    </div><!-- /.totalDeal -->
                                    <?php
                                    return ob_get_clean();
                                }


                                $hasInactive = false;

                                foreach ($allDeals as $deal) {
                                    $isInactive = ($deal['status'] !== 'active' && $deal['is_closed'] == 0);
                                    if ($isInactive) $hasInactive = true;
                                    echo renderDealCard($deal, $dailyIncomeByDeal, $isInactive);
                                }


                                if ($hasInactive): ?>
                                    <div class="totalInfo-right_button" id="switchInactiveDeals">
                                        <a href="#">إظهار التكوينات المكتملة</a>
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
                        <h3 id="dealInfoTitle">عنوان</h3>

                        <p id="dealInfoText">وصف…</p>
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
                            <h3>RIX الذكاء الاصطناعي BOOST</h3>
                        </div>

                        <div class="modal-body__boost">
                            <p>تاريخ تفعيل الاشتراك: 25.02.2025</p>
                            <p>تاريخ انتهاء الاكتتاب: 25.03.2025</p>
                            <p>مكافأة الاشتراك الحالية: 0.1٪ يوميا</p>
                            <p>إيرادات الاشتراك: 248 USDT</p>
                        </div>

                        <div class="modal-body__accordion">

                            <div class="accordion" id="accordionButton">
                                <div class="card">
                                    <div class="card-header" id="headingOne">

                                        <button class="" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            التعرف على المزيد
                                        </button>

                                    </div>

                                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionButton">
                                        <div class="card-body">
                                            عند تفعيل اشتراكك، سيتم خصم مبلغ 250 دولارا أمريكيا من رصيدك الرئيسي
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-body__next">
                            <a href="#" data-toggle="modal" data-target="#success-boost">استمر</a>
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
                            <p>تهانينا ، لقد قمت بتنشيط اشتراك <span>RIX الذكاء الاصطناعي BOOST</span> الخاص بك</p>
                        </div>

                        <div class="modal-body__next">
                            <a href="#">استمر</a>
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
        showToast('لن تتوفر الميزة بعد ذلك بقليل', 3000);
    });
</script>