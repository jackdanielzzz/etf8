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
                                                <h5>Total revenue</h5>
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
                                                    <h5>Per diem income</h5>
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
                                                    <h5>Quarterly revenue</h5>
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
                                                    ['ETF Asset Amount',    $deal['principal']],
                                                    ['Total profitability',    $deal['accrued_amount']]
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
                                                    <div class="totalInfo-date-Item"><p>Start Date:</p>
                                                        <span><?= date('d.m.Y', strtotime($deal['start_date'])) ?></span></div>
                                                    <div class="totalInfo-date-Item"><p>End Date:</p>
                                                        <span><?= date('d.m.Y', strtotime($deal['end_date'])) ?></span></div>
                                                </div>
                                            </div><!-- /.totalInfo-left -->

                                            <div class="totalInfo-center"></div>


                                            <div class="totalInfo-right">
                                                <div class="totalInfo-right_top">
                                                    <div class="totalInfo-right_block">
                                                        <div class="totalInfo-right_left">
                                                            <div class="totalInfo-right_title"><h5>Configuration ETF</h5></div>
                                                            <div class="totalInfo-right_caption">
                                                                <span><?= htmlspecialchars($deal['product']) ?></span>
                                                            </div>
                                                        </div>

                                                        <div class="totalInfo-right_right">
                                                            <div class="totalInfo-right_title"><h5>Daily income:</h5></div>
                                                            <div class="totalInfo-left_block">
                                                                <div class="totalInfo-left_icon"><i class="icon-teph"></i></div>
                                                                <div class="totalInfo-left_text">
                                                                    <p><?= ($dayIncome) ?></p><span> USDT</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="totalInfo-right_button">
                                                    <a href="#" data-toggle="modal" data-target="#text"
                                                       data-title="<?= htmlspecialchars($deal['product']) ?>"
                                                       data-note="<?= htmlspecialchars($deal['config_note'] ?? '', ENT_QUOTES) ?>">
                                                        More
                                                    </a>
                                                </div>

                                                <div class="totalInfo-status-Item">
                                                    <p>Configuration status: <?= $deal['status'] ?></p>
                                                </div>
                                            </div><!-- /.totalInfo-right -->

                                        </div><!-- /.totalInfo -->


                                        <?php if ($isNew): ?>
                                            <div class="totalClose">
                                                <div class="totalClose-icon"><i class="icon-check2"></i></div>
                                                <div class="totalClose-text"><p>Congratulations, you have a new configuration</p></div>
                                                <div class="totalClose-button"><button type="button" class="js-deal-ok">OK</button></div>
                                            </div>
                                        <?php elseif ($deal['status'] === 'completed' && $isClosed): ?>
                                            <div class="totalClose">
                                                <div class="totalClose-icon"><i class="icon-check2"></i></div>
                                                <div class="totalClose-text">
                                                    <p>The configuration is closed – you have earned <?= moneyFormat($deal['accrued_amount']) ?> USDT</p>
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
                                        <a href="#">Show completed configurations</a>
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
                        <h3 id="dealInfoTitle">Title</h3>

                        <p id="dealInfoText">Description…</p>
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
                            <p>Subscription activation date: 25.02.2025</p>
                            <p>Subscription end date: 25.03.2025</p>
                            <p>Current Subscription Bonus: 0.1% daily</p>
                            <p>Subscription Revenue: 248 USDT</p>
                        </div>

                        <div class="modal-body__accordion">

                            <div class="accordion" id="accordionButton">
                                <div class="card">
                                    <div class="card-header" id="headingOne">

                                        <button class="" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            More
                                        </button>

                                    </div>

                                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionButton">
                                        <div class="card-body">
                                            When you activate your subscription, an amount of 250 USDT will be deducted from your main balance
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-body__next">
                            <a href="#" data-toggle="modal" data-target="#success-boost">Continue</a>
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
                            <p>Congratulations, you have activated your subscription <span>RIX AI BOOST</span></p>
                        </div>

                        <div class="modal-body__next">
                            <a href="#">Continue</a>
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
        showToast('The feature will not be available a little later', 3000);
    });
</script>