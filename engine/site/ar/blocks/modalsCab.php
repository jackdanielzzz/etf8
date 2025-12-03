

<div class="customModal">
    <div class="modal fade activity_modal" id="activity" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">

                <div class="modal-close">

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>


                <div class="modal-body">

                    <div class="modal-background">
                        <div class="modal-function">

                            <div class="modal-function_item order-md-1 order-2">

                                <div class="modal-function_button">
                                    <a href="#" data-toggle="modal" data-target="#statistic">
                                    <span class="modal-function_icon">
                                        <i class="icon-bar"></i>
                                    </span>
                                        <span class="modal-function_text">ستاتيسيتكا</span>
                                    </a>
                                </div>

                            </div>

                            <div class="modal-function_item order-md-1 order-1">

                                <div class="total-graphic">
                                    <figure class="highcharts-block">
                                        <div id="chartsModal"></div>
                                    </figure>


                                    <div class="total-circle"></div>
                                </div>

                            </div>

                            <div class="modal-function_item order-md-1 order-3">

                                <div class="modal-function_button">
                                    <a href="#" data-toggle="modal" data-target="#exchange">
                                    <span class="modal-function_icon">
                                        <i class="icon-exchange"></i>
                                    </span>
                                        <span class="modal-function_text">تبادل</span>
                                    </a>
                                </div>
                            </div>

                        </div>








                    </div>

                    <div class="modal-table">

                        <table>
                            <thead>
                            <tr>
                                <th>اسم</th>
                                <th>دورة</th>
                                <th>التعديل خلال 24 ساعة</th>
                                <th class="text-right">رصيدي</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php foreach ($assetQuotes as $q): ?>
                                <?php
                                $isUp   = $q['percent_change'] >= 0;
                                $class  = $isUp ? 'text_green' : 'text_red';
                                $sign   = $isUp ? '+' : '';
                                ?>
                                <tr>
                                    <td>
                                        <div class="td-first">
                                            <div class="td-first_icon">
                                                <i class="icon-<?= strtolower(assetCleanSymbol($q['symbol'], true)) ?>"></i>
                                            </div>
                                            <div class="td-first_text">
                                                <p><?= htmlspecialchars(assetCleanSymbol($q['symbol'], true)) ?></p>
                                                <span><?= assetTitle($q['symbol']) ?></span>
                                            </div>
                                        </div>
                                    </td>

                                    <td><?= number_format($q['price'], 2, ',', ' ') ?> $</td>
                                    <td class="<?= $class; ?>"><?= $sign . number_format($q['percent_change'], 2, ',', ' ') ?> %</td>
                                    <td class="text-right">—<!-- أو سحب الرصيد الحقيقي --></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>

                    </div>

                    <div class="table-mobile">

                        <div class="table-mobile_top">
                            <div class="table-mobile_item">
                                <p>اسم</p>
                                <span class="text_blue">دورة</span>
                            </div>

                            <div class="table-mobile_item">
                                <p>تغيير على مدار 24 ساعة</p>
                                <span>رصيدي</span>
                            </div>
                        </div>

                        <div class="table-mobile_bottom">

                            <div class="table-mobile_bottom">
                                <?php foreach ($assetQuotes as $q): ?>
                                    <?php
                                    $isUp   = $q['percent_change'] >= 0;
                                    $class  = $isUp ? 'text_green' : 'text_red';
                                    $sign   = $isUp ? '+' : '';
                                    ?>
                                    <div class="table-mobile_bottomItem">
                                        <!-- Top Block: شريط + الدورة التدريبية -->
                                        <div class="table-mobile_bottomBlock">
                                            <div class="table-mobile_bottomLeft">
                                                <div class="table-mobile_bottomIcon">
                                                    <i class="icon-<?= strtolower(assetCleanSymbol($q['symbol'], true)) ?>Mobile"></i>
                                                </div>
                                                <div class="table-mobile_bottomText">
                                                    <p><?= htmlspecialchars(assetCleanSymbol($q['symbol'], true)) ?></p>
                                                    <span><?= assetTitle($q['symbol']) ?></span>
                                                </div>
                                            </div>
                                            <div class="table-mobile_bottomRight">
                                                <p><?= number_format($q['price'], 2, ',', ' ') ?> $</p>
                                            </div>
                                        </div>

                                        <!-- الكتلة السفلية: التغيير + الرصيد -->
                                        <div class="table-mobile_bottomBlock">
                                            <div class="table-mobile_bottomLeft2">
                                                <p class="<?= $class; ?>">
                                                    <?= $sign . number_format($q['percent_change'], 2, ',', ' ') ?> %
                                                </p>
                                            </div>
                                            <div class="table-mobile_bottomRight2">
                                                <p>—<!-- توازن --></p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                        </div>

                    </div>

                    <div class="modal-button">

                        <div class="btn-1">
                            <a href="#" data-toggle="modal" data-dismiss="modal" data-target="#replenish">تجديد</a>
                        </div>

                        <div class="btn-1">
                            <a href="#" data-toggle="modal" data-target="#conclusion">استدلال</a>
                        </div>

                    </div>


                </div>


            </div>
        </div>
    </div>
</div>


<div class="customModal_2">
    <div class="modal fade activity_modal" id="replenish" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">



            <div class="modal-content">

                <div class="figure-17"></div>

                <div class="modal-close">

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>


                <div class="modal-body">

                    <div class="modal-title">
                        <h3>تجديد</h3>
                    </div>

                    <div class="modal-form">
                        <div class="form">
                            <div class="form-group">
                                <?php

                                global $pdo;

                                $sql = "
                                        SELECT  w.config_name,  -- value для <option>
                                            w.full_name,        -- اسم يمكن للإنسان قراءته
                                            w.value,            -- عنوان المحفظة نفسه
                                            q.price             -- السعر الحالي (يمكن أن يكون فارغ)
                                        FROM _wallets  w
                                        LEFT JOIN asset_quotes q
                                           ON q.symbol = w.quotes_symbol
                                        WHERE w.status = 0
                                        ORDER BY w.id
                                        ";
                                $wallets = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
                                ?>
                                <div class="modal-select">
                                    <label for="currency">اختر العملة</label>

                                    <div class="modal-selectBlock2">
                                        <select name="currency-2" id="depositMethod" class="form-control select-single-2">
                                            <?php foreach ($wallets as $w): ?>
                                                <option value="<?= htmlspecialchars($w['full_name']) ?>"
                                                        data-address="<?= $w['value'] ?>"
                                                        data-full-name="<?= htmlspecialchars($w['full_name']) ?>"
                                                        data-symbol="<?= strtoupper(
                                                            preg_match('/\(([A-Z0-9]+)\)/', $w['full_name'], $m) ? $m[1] : $w['config_name']
                                                        ) ?>"
                                                        data-price="<?= $w['price'] ?>">
                                                    <?= htmlspecialchars($w['full_name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">

                                <div class="modal-select">
                                    <label for="topUpAmount">أدخل مبلغ الإيداع (بالدولار الأمريكي)</label>

                                    <input  type="text"
                                            id="topUpAmount"
                                            class="form-control"
                                            placeholder="0">
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="modal-button">
                        <button type="button"
                                class="btn-continue disabled"
                                data-toggle="modal"
                                data-target="#replenishContinue"
                                id="continueBtn"
                                disabled>
                            Продолжить
                        </button>

                        <button class="btn-support">دعم
                        </button>
                    </div>

                </div>


            </div>
        </div>
    </div>
</div>


<div class="customModal_2">
    <div class="modal fade activity_modal" id="replenishContinue" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">



            <div class="modal-content modal-content_2">

                <div class="figure-17"></div>

                <div class="modal-close">

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>


                <div class="modal-body">

                    <div class="modal-title">
                        <h3 id="topUpTitle">إيداع USDT</h3>
                    </div>

                    <div class="modal-qr">
<!--                        <div class="modal-qr_left">-->
<!--                            <img src="/img/qr.png" alt="">-->
<!--                        </div>-->

                        <div class="modal-qr_right">

                            <div class="totalInfo-left_block mt-0">
<!--                                <div class="totalInfo-left_icon">-->
<!--                                    <i class="icon-teph"></i>-->
<!--                                </div>-->

                                <div class="totalInfo-left_text">
                                    <p id="topUpAmountCoins">0.00</p>
                                    <span id="topUpCoinTicker">USDT</span>
                                </div>
                            </div>

                            <div class="modal-qr_form">
                                <div class="form">
                                    <div class="form-group">

                                        <div class="modal-select">
                                            <p id="networkHint" style="color: red">تأكد من أن الأموال التي ترسلها موجودة على شبكة TRC-20</p>
                                        </div>
                                    </div>

                                </div>
                            </div>




                        </div>
                    </div>

                    <div class="modal-qr_hash">
                        <div class="form">
                            <div class="form-group">

                                <div class="modal-select">
                                    <label for="hash" id="payToThisAddress">تمويل هذه المحفظة</label>

                                    <p class="js-copy-referral" id="walletAddress" data-url="">...</p>
                                </div>
                            </div>
                            <div class="form-group">

                                <div class="modal-select">
                                    <label for="hash">أدخل تجزئة المعاملة بعد الإيداع</label>

                                    <input type="text"
                                           id="txHash"
                                           class="form-control"
                                           placeholder="أدخل تجزئة المعاملة"
                                           required
                                    >
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="modal-button_2">
                        <button type="button"
                                class="btn btn-primary btn-continue disabled"
                                id="finalizeBtn"
                                disabled>
                            تجديد
                        </button>
                    </div>


                </div>


            </div>
        </div>
    </div>
</div>


<div class="customModal_2">
    <div class="modal fade activity_modal" id="processing" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">



            <div class="modal-content modal-content_2">

                <div class="modal-close">

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>


                <div class="modal-body">

                    <div class="modal-title">
                        <h3>انتظر حتى تتم معالجة طلبك</h3>
                    </div>


                    <div class="modal-button_2 mt-5">
                        <button>
                            <a href="#" class="btn-hash"> استمر</a>
                        </button>
                    </div>


                </div>


            </div>
        </div>
    </div>
</div>








<div class="customModal_2">
    <div class="modal fade activity_modal" id="conclusion" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">



            <div class="modal-content modal-content_2">

                <div class="figure-17"></div>

                <div class="modal-close">

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>


                <div class="modal-body" id="withdrawModalBody">

                    <div class="modal-title">
                        <h3>طلب السحب</h3>
                    </div>

                    <div class="modal-qr_2">
                        <div class="modal-qr_right">

                            <div class="totalInfo-left_block mt-0">
<!--                                <div class="totalInfo-left_icon">-->
<!--                                    <i class="icon-teph"></i>-->
<!--                                </div>-->

                                <div class="totalInfo-left_text">
                                    <p>السحب في USDT ERC-20</p>
                                </div>
                            </div>

                            <div class="modal-qr_form">
                                <div class="form">
                                    <div class="form-group">

                                        <div class="modal-select">
                                            <label for="address-conclusion">أدخل عنوان USDT الخاص بك (على شبكة ERC-20!)</label>

                                            <input type="text" id="address-conclusion" class="form-control" placeholder="" >
                                        </div>
                                    </div>

                                </div>
                            </div>




                        </div>
                    </div>

                    <div class="modal-qr_hash mt-1">
                        <div class="form">
                            <div class="form-group">

                                <div class="modal-select">
                                    <label for="sum-withdrawal">أدخل المبلغ</label>

                                    <input type="text" id="sum-withdrawal" class="form-control onlyDigits js-sum-input" placeholder="">
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="modal-button">
                        <button>
                            <a href="#" class="btn-continue_1" data-method="USDT TRC-20">استمر</a>
                        </button>

                        <button>
                            <a href="https://t.me/<?= $_ENV['TG_SUPPORT'] ?>" class="btn-support_1">دعم</a>
                        </button>
                    </div>


                </div>


            </div>
        </div>
    </div>
</div>





<div class="customModal_3">
    <div class="modal fade activity_modal" id="exchange" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">



            <div class="modal-content">

                <div class="figure-17"></div>

                <div class="modal-close">

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>


                <div class="modal-body">

                    <div class="modal-title">
                        <h3>تبادل</h3>
                    </div>

                    <div class="modal-exchange">

                        <div class="modal-exchange_item exchangeBackground-1">

                            <div class="form-group">

                                <div class="modal-flex_input">
                                    <div class="modal-select">
                                        <label for="select">لدي 0 بيتكوين</label>

                                        <div class="modal-selectBlock">
                                            <select name="select" id="select" class="select-single">
                                                <option value="BTC" data-icon="icon-btc">BTC</option>
                                            </select>
                                        </div>


                                    </div>

                                    <div class="modal-number">
                                        <input type="text" placeholder="0.00" value="0.00" class="form-control2">
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="modal-exchange_item exchangeBackground-2">

                            <div class="form-group">

                                <div class="modal-flex_input">

                                    <div class="modal-disabled">

                                        <div class="modal-disabled_title">
                                            <span>أريد Teather USD</span>
                                        </div>

                                        <div class="modal-disabledFlex">
                                            <div class="modal-disabledIcon">
                                                <i class="icon-teph"></i>
                                            </div>

                                            <div class="modal-disabledText">
                                                USDT
                                            </div>
                                        </div>


                                    </div>

                                    <div class="modal-number">
                                        <input type="text" placeholder="0.00" value="0.00" class="form-control3" disabled>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="modal-max">
                        <button>ماكس</button>
                    </div>

                    <div class="modal-exchangeButton">
                        <a href="#">تبادل</a>
                    </div>

                </div>


            </div>
        </div>
    </div>
</div>



<div class="customModal customModal_fix">
    <div class="modal fade activity_modal" id="statistic" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">

                <div class="figure-18"></div>

                <div class="modal-close">

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>


                <div class="modal-body">

                    <div class="modal-title__statistic">
                        <h3>إحصائيات RIXWALLET</h3>
                    </div>

                    <div class="modal-background">

                        <div class="modal-switch modal-switch__statistic">

                            <div class="modal-switch_item">

                                <div class="modal-switch_block">
                                    <label class="switch">
                                        <input type="checkbox" id="toggleInputs" checked>
                                        <span class="switch-slider round"></span>
                                    </label>

                                    <div class="modal-switch_label">
                                        <span>وصل</span>
                                    </div>
                                </div>


                            </div>

                            <div class="modal-switch_item">

                                <div class="modal-switch_block">
                                    <label class="switch">
                                        <input type="checkbox" id="toggleOutputs" checked>
                                        <span class="switch-slider round"></span>
                                    </label>

                                    <div class="modal-switch_label">
                                        <span>استدلال</span>
                                    </div>
                                </div>


                            </div>

                            <div class="modal-switch_item">

                                <div class="modal-switch_block">
                                    <label class="switch">
                                        <input type="checkbox">
                                        <span class="switch-slider round"></span>
                                    </label>

                                    <div class="modal-switch_label">
                                        <span>تبادل</span>
                                    </div>
                                </div>

                            </div>


                        </div>

                        <div class="modal-table modal-background__table">

                            <table id="walletStatsTable">
                                <thead>
                                <tr>
                                    <th>تاريخ</th>
                                    <th>فعل</th>
                                    <th>مجموع</th>
                                    <th>المصدر / الوجهة</th>
                                    <th>حالة</th>
                                </tr>
                                </thead>

                                <tbody></tbody>
                            </table>

                        </div>

                    </div>

                </div>


            </div>
        </div>
    </div>
</div>




<div class="customModal_4">
    <div class="modal fade activity_modal" id="arbitration" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">



            <div class="modal-content">

                <div class="figure-18"></div>

                <div class="modal-close">

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>


                <div class="modal-body">

                    <div class="modal-title">
                        <h3>مركز تداول ETF</h3>
                    </div>

                    <div class="modal-button">

                        <div class="modal-button_item">
                            <a href="#" data-toggle="modal" data-target="#arbitration_3">
                                <div>
                                    <span>العالمية</span>
                                    <p>ETF</p>
                                </div>
                            </a>
                        </div>

                        <div class="modal-button_item">
                            <a href="#" data-toggle="modal" data-target="#arbitration_4">
                                <div>
                                    <span>تحكيم</span>
                                    <p>RIX AI</p>
                                </div>
                            </a>
                        </div>


                    </div>

                </div>


            </div>
        </div>
    </div>
</div>

<div class="customModal_4">
    <div class="modal fade activity_modal" id="arbitration_3" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">



            <div class="modal-content">

                <div class="figure-18"></div>

                <div class="modal-close">

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>


                <div class="modal-body">

                    <div class="modal-title">
                        <h3>مركز تداول ETF</h3>
                    </div>

                    <div class="modal-button__arbitration">

                        <?php foreach ($regions as $idx => $region): ?>
                            <div class="modal-button_item<?= $idx >= 2 ? ' mt-lg-3 mt-0' : '' ?>">
                                <a  href="#"
                                    class="js-region"
                                    data-toggle="modal"
                                    data-target="#arbitration_2"
                                    data-region-id="<?= (int)$region['region_id'] ?>">
                                    <div>
                                        <p>المنطقة المالية <?= htmlspecialchars($region['region_name']) ?></p>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>

                    </div>

                </div>


            </div>
        </div>
    </div>
</div>

<div class="customModal_5">
    <div class="modal fade activity_modal" id="arbitration_2" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">



            <div class="modal-content">

                <div class="figure-19"></div>

                <div class="modal-back">
                    <a href="#" data-toggle="modal" data-target="#arbitration_2" class="back-arrow back-myarrow "></a>
                </div>

                <div class="modal-close">

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>


                <div class="modal-body">

                    <div class="modal-title">
                        <h3>مركز تداول ETF</h3>
                    </div>

                    <div class="modal-center">

                        <button class="modal-center_item js-deal" data-size="Small"  id="dealSmall" data-deal-id=""><p></p></button>
                        <button class="modal-center_item js-deal" data-size="Medium" id="dealMedium" data-deal-id=""><p></p></button>
                        <button class="modal-center_item js-deal" data-size="Large"  id="dealLarge" data-deal-id=""><p></p></button>

                    </div>

                    <div class="modal-currency">

                        <div class="modal-currency_left">
                            <div class="modal-currency_item">
                                <div class="modal-currency_p">
                                    <p>تاريخ البدء:</p>
                                </div>

                                <div class="modal-currency_span">
                                    <span id="dealStart"></span>
                                </div>
                            </div>

                            <div class="modal-currency_item">
                                <div class="modal-currency_p">
                                    <p>تاريخ الانتهاء:</p>
                                </div>

                                <div class="modal-currency_span">
                                    <span id="dealEnd"></span>
                                </div>
                            </div>

                            <div class="modal-currency_item">
                                <div class="modal-currency_p">
                                    <p>مدة:</p>
                                </div>

                                <div class="modal-currency_span">
                                    <span id="dealTime"></span>
                                </div>
                            </div>

                            <div class="modal-currency_item">
                                <div class="modal-currency_p">
                                    <p>سعر ETF:</p>
                                </div>

                                <div class="modal-currency_span">
                                    <span id="dealAmount"></span>
                                </div>
                            </div>

                            <div class="modal-currency_item">
                                <div class="modal-currency_p">
                                    <p>الربحية المتوقعة:</p>
                                </div>

                                <div class="modal-currency_span">
                                    <span id="dealRate"></span>
                                </div>
                            </div>

                            <div class="modal-currency_item">
                                <div class="modal-currency_p">
                                    <p>الإيرادات المتوقعة:</p>
                                </div>

                                <div class="modal-currency_span">
                                    <span id="dealRevenue"></span>
                                </div>
                            </div>

                            <div class="modal-currency_button">
                                <a href="#" id="dealMore" data-toggle="modal" data-target="#text">التعرف على المزيد</a>
                            </div>

                        </div>

                        <div class="modal-currency_right">
                            <div class="modal-text">
                                <div class="modal-text_title">
                                    <h3>مقدار أصولك</h3>
                                </div>

                                <div class="modal-text_sum">
                                    <div class="modal-text_icon">
                                        <i class="icon-tephBig"></i>
                                    </div>

                                    <div class="modal-text_caption">
                                        <p id="userBalance"></p>
                                        <span>Usdt</span>
                                    </div>
                                </div>
                            </div>
                            <form class="form">

                                <div class="form-group">

                                    <div class="modal-select">
                                        <label for="sum">مبلغ استثمار USDT</label>

                                        <input  type="text"
                                                class="form-control onlyDigits js-sum-input"
                                                step="1"
                                                min="0"
                                                placeholder="0"
                                                data-max="<?= $balance ?>"
                                                inputmode="decimal" pattern="[0-9]*">
                                    </div>
                                </div>


                            </form>
                        </div>


                    </div>



                    <div class="modal-button">
                        <a href="#" class="btn-invest js-invest-btn disabled">استثمر</a>
                    </div>

                </div>


            </div>
        </div>
    </div>
</div>




<
<div class="customModal_5">
    <div class="modal fade activity_modal" id="arbitration_4"
         tabindex="-1" role="dialog" aria-labelledby="flashDexTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">

                <div class="figure-19"></div>

                <div class="modal-close">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">


                    <div class="modal-title"><h3 id="flashDexTitle">مراجحة الذكاء الاصطناعي RIX</h3></div>


                    <div class="modal-center">
                        <?php
                        if (!empty($flashDeals[1])): ?>
                            <?php foreach ($flashDeals[1] as $idx => $deal):
                                $disabled = "";
                                $style = "";
                                if ($deal['term_days'] === 0) {
                                    $style = "opacity: 0.4; pointer-events: none; cursor: default;";
                                    $disabled = "disabled";
                                }
                                ?>
                                <button
                                    class="modal-center_item modal-center_item__height js-deal"
                                    data-size="Flash"
                                    data-index="<?= $idx ?>"
                                    data-region-id="1"
                                    data-deal-id="<?= (int)$deal['deal_id'] ?>"
                                    style="<?= $style ?>"
                                    <?= $disabled ?>
                                >
                                    <p><?= htmlspecialchars($deal['product']) ?></p>
                                </button>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>


                    <div class="modal-currency">

                        <div class="modal-currency_left">
                            <div class="modal-currency_item">
                                <div class="modal-currency_p"><p>تاريخ البدء:</p></div>
                                <div class="modal-currency_span"><span id="dealStart">—</span></div>
                            </div>

                            <div class="modal-currency_item">
                                <div class="modal-currency_p"><p>تاريخ الانتهاء:</p></div>
                                <div class="modal-currency_span"><span id="dealEnd">—</span></div>
                            </div>

                            <div class="modal-currency_item">
                                <div class="modal-currency_p"><p>مبلغ التجارة:</p></div>
                                <div class="modal-currency_span"><span id="dealAmount"></span></div>
                            </div>

                            <div class="modal-currency_item">
                                <div class="modal-currency_p"><p>Оالدخل المتوقع:</p></div>
                                <div class="modal-currency_span"><span id="dealRate"></span></div>
                            </div>

                            <div class="modal-currency_button">
                                <a href="#" id="dealMore" data-toggle="modal" data-target="#text">التعرف على المزيد</a>
                            </div>
                        </div><!-- /.modal-currency_left -->

                        <div class="modal-currency_right">
                            <div class="modal-text">
                                <div class="modal-text_title"><h3>مقدار أصولك</h3></div>
                                <div class="modal-text_sum">
                                    <div class="modal-text_icon"><i class="icon-tephBig"></i></div>
                                    <div class="modal-text_caption">
                                        <p><?= number_format($currentUser['balance'],1,'.',' ') ?></p>
                                        <span>USDT</span>
                                    </div>
                                </div>
                            </div>

                            <form class="form">
                                <div class="form-group">
                                    <div class="modal-select">
                                        <label for="sum">مبلغ استثمار USDT</label>
                                        <input  type="text" name="sum" class="form-control onlyDigits js-sum-input"
                                                placeholder="0" value=""
                                                data-min="100" data-maxInvest="200">
                                    </div>
                                </div>
                            </form>
                        </div><!-- /.modal-currency_right -->
                    </div><!-- /.modal-currency -->


                    <div class="modal-button">
                        <a href="#" class="btn-invest js-invest-btn disabled">استثمر</a>
                    </div>

                    <div class="modal-currency_item d-none" id="coolDownError">
                        <div class="modal-currency_p">
                            <p style="color:red;margin-top:10px">
                                خطأ! ستكون صفقة RIX من نوع الذكاء الاصطناعي التالية متاحة
                                <span id="coolDownDate"></span>
                            </p>
                        </div>
                    </div>

                    <div class="modal-currency_item d-none" id="blockedByLarge">
                        <div class="modal-currency_p">
                            <p style="color: red; margin-top: 10px">خطأ! بعد فتح أي صفقة من منطقة ما ، لا يمكن إطلاق صفقة RIXAI.</p>
                        </div>
                    </div>

                </div><!-- /.modal-body -->
            </div><!-- /.modal-content -->
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

                    <div class="modal-text_null">
                        <h3 id="transferTitle">حالة</h3>
                        <p  id="transferMsg"></p>

                        <div class="modal-body__next">
                            <a href="#" id="transferContinue">موافق</a>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>


<div class="customModal_5">
    <div class="modal fade activity_modal" id="description" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">



            <div class="modal-content modal-content_2">

                <div class="modal-close">

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="modal-body__desc--title">
                        <h3>ليف ألبيدو - دليل الوحي السماوي</h3>
                    </div>


                    <p>عندما تخترق أشعة الشمس الأولى ضباب الصباح ، يخرج الشكل الأبيض المبهر لأسد من قمم السحب. يضيء رأسه ، المنحوت من ماسة لا تشوبها شائبة ، مثل الكريستال في أشعة الشمس المشرقة. يتبع البياض قطيع من الأسود ، كما لو كان يسترشد بقوة غامضة تدعوهم إلى آفاق جديدة.</p>
                    <p><strong>الموئل</strong><br>
                        يسكن البيدو في عالم السماوي الذي لا حدود له ، حيث تكون كل خطوة بمثابة نقطة انطلاق لشيء عظيم. يقال إن مساراتها تتقاطع مع خطوط مصير أولئك الذين يسعون إلى التحرر من المخاوف - سواء كان ذلك الخوف من الفقر أو الخوف من البقاء وحيدا.</p>
                    <p><strong>الشخصية والرمزية</strong>يجسد هذا الأسد نقاء النية والأمل الأعلى. يعكس فروه الأبيض الثلجي الاعتقاد بأن كل شخص يمكنه الارتقاء فوق الروتين الرمادي وإيجاد طريقه إلى الرفاهية. يرمز رأس الماس إلى وعي مستنير لا يستسلم لظلام الشك. يقود ألبيدو كبرياءه إلى الأمام ، مما يدل على أن هناك دائما سماء صافية خلف السحب.</p>
                    <p>يبدأ الأشخاص الذين يلتقون بالبيدو في الاعتقاد بأنه لا يوجد شيء يمكن أن يمنعهم من تحقيق ما يريدون. يشجعهم وجوده على تنحية المخاوف جانبا ورؤية أن العالم أوسع بكثير من أي قيود. يذكرنا لمعان الماس في بدته بأنه لا يوجد فقر أفظع من ذلك الذي يعيش في النفس ، ولا توجد كنوز أغلى من النور الذي نجده في أنفسنا.</p>
                    <p>تتمثل مهمة البيدو في أن تصبح ضوءا إرشاديا لأولئك الذين يسعون إلى الحرية الداخلية والازدهار. يعلم طريقه أنه من أجل الارتفاع فوق غيوم الحياة اليومية ، عليك أن تجرؤ على اتخاذ الخطوة الأولى نحو إشراقك.</p>



                </div>


            </div>
        </div>
    </div>
</div>

<div class="customModal_2">
    <div class="modal fade activity_modal" id="description_open" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">



            <div class="modal-content modal-content_2">

                <div class="modal-close">

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>


                <div class="modal-body">

                    <div class="modal-title">
                        <h3>الفريدو</h3>
                    </div>

                    <div class="modal-desc__text">
                        <p>سعر وحدة الرسومات - 0.057 USDT <br>
                            مجموعتك - 4 وحدات</p>

                        <p>البيع ممكن من 10 وحدات</p>
                    </div>

                    <div class="form-group">

                        <div class="modal-select">
                            <label for="topUpAmountSale">أدخل الكمية المراد بيعها</label>

                            <input type="text" id="topUpAmountSale" class="form-control simpleDigits" placeholder="0">
                        </div>
                    </div>

                    <div class="modal-desc__sale">
                        <p>سوف تتلقى بعد البيع: 4 USDT</p>
                    </div>

                    <div class="modal-descButton">
                        <a href="#" data-toggle="modal" data-target="#sale">Продать</a>
                    </div>

                </div>


            </div>
        </div>
    </div>
</div>

<div class="customModal_2">
    <div class="modal fade activity_modal" id="sale" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">



            <div class="modal-content modal-content_2">

                <div class="modal-close">

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>


                <div class="modal-body">

                    <div class="success-boost__text">
                        <h3>تم تأكيد بيع وحدة NFT بنجاح</h3>

                        <p>توقع أن تضاف إلى رصيدك الحالي</p>
                    </div>

                    <div class="modal-body__next">
                        <a href="#">موافق</a>
                    </div>

                </div>


            </div>
        </div>
    </div>
</div>


</div>