

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
                                        <span class="modal-function_text">统计学</span>
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
                                        <span class="modal-function_text">交换</span>
                                    </a>
                                </div>
                            </div>

                        </div>








                    </div>

                    <div class="modal-table">

                        <table>
                            <thead>
                            <tr>
                                <th>名字</th>
                                <th>课程</th>
                                <th>24小时内修改.</th>
                                <th class="text-right">我的余额</th>
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
                                    <td class="text-right">—<!-- 或提取实际余额 --></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>

                    </div>

                    <div class="table-mobile">

                        <div class="table-mobile_top">
                            <div class="table-mobile_item">
                                <p>名字</p>
                                <span class="text_blue">课程</span>
                            </div>

                            <div class="table-mobile_item">
                                <p>24小时零钱</p>
                                <span>我的余额</span>
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


                                        <div class="table-mobile_bottomBlock">
                                            <div class="table-mobile_bottomLeft2">
                                                <p class="<?= $class; ?>">
                                                    <?= $sign . number_format($q['percent_change'], 2, ',', ' ') ?> %
                                                </p>
                                            </div>
                                            <div class="table-mobile_bottomRight2">
                                                <p>—<!-- 平衡 --></p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                        </div>

                    </div>

                    <div class="modal-button">

                        <div class="btn-1">
                            <a href="#" data-toggle="modal" data-dismiss="modal" data-target="#replenish">补充</a>
                        </div>

                        <div class="btn-1">
                            <a href="#" data-toggle="modal" data-target="#conclusion">推理</a>
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
                        <h3>补充</h3>
                    </div>

                    <div class="modal-form">
                        <div class="form">
                            <div class="form-group">
                                <?php

                                global $pdo;

                                $sql = "
                                        SELECT  w.config_name,  -- value для <option>
                                            w.full_name,        -- 人类可读的名称
                                            w.value,            -- 钱包地址本身
                                            q.price             -- 当前价格（可以为 NULL）
                                        FROM _wallets  w
                                        LEFT JOIN asset_quotes q
                                           ON q.symbol = w.quotes_symbol
                                        WHERE w.status = 0
                                        ORDER BY w.id
                                        ";
                                $wallets = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
                                ?>
                                <div class="modal-select">
                                    <label for="currency">选择货币</label>

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
                                    <label for="topUpAmount">输入存款金额（美元）</label>

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
                            继续
                        </button>

                        <button class="btn-support">支持
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
                        <h3 id="topUpTitle">USDT充值</h3>
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
                                            <p id="networkHint" style="color: red">确保您发送的资金在 TRC-20 网络上</p>
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
                                    <label for="hash" id="payToThisAddress">为这个钱包注资</label>

                                    <p class="js-copy-referral" id="walletAddress" data-url="">...</p>
                                </div>
                            </div>
                            <div class="form-group">

                                <div class="modal-select">
                                    <label for="hash">存款后输入交易哈希</label>

                                    <input type="text"
                                           id="txHash"
                                           class="form-control"
                                           placeholder="输入交易哈希"
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
                            补充
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
                        <h3>等待您的申请得到处理</h3>
                    </div>


                    <div class="modal-button_2 mt-5">
                        <button>
                            <a href="#" class="btn-hash"> 继续</a>
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
                        <h3>提款请求</h3>
                    </div>

                    <div class="modal-qr_2">
                        <div class="modal-qr_right">

                            <div class="totalInfo-left_block mt-0">
<!--                                <div class="totalInfo-left_icon">-->
<!--                                    <i class="icon-teph"></i>-->
<!--                                </div>-->

                                <div class="totalInfo-left_text">
                                    <p>USDT ERC-20 提现</p>
                                </div>
                            </div>

                            <div class="modal-qr_form">
                                <div class="form">
                                    <div class="form-group">

                                        <div class="modal-select">
                                            <label for="address-conclusion">输入您的 USDT 地址（在 ERC-20 网络上！</label>

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
                                    <label for="sum-withdrawal">输入金额</label>

                                    <input type="text" id="sum-withdrawal" class="form-control onlyDigits js-sum-input" placeholder="">
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="modal-button">
                        <button>
                            <a href="#" class="btn-continue_1" data-method="USDT TRC-20">继续</a>
                        </button>

                        <button>
                            <a href="https://t.me/<?= $_ENV['TG_SUPPORT'] ?>" class="btn-support_1">支持</a>
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
                        <h3>交换</h3>
                    </div>

                    <div class="modal-exchange">

                        <div class="modal-exchange_item exchangeBackground-1">

                            <div class="form-group">

                                <div class="modal-flex_input">
                                    <div class="modal-select">
                                        <label for="select">我有 0 个比特币</label>

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
                                            <span>我想要 Teather USD</span>
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
                        <button>麦克斯</button>
                    </div>

                    <div class="modal-exchangeButton">
                        <a href="#">交换</a>
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
                        <h3>RIXWALLET统计</h3>
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
                                        <span>收据</span>
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
                                        <span>推理</span>
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
                                        <span>交换</span>
                                    </div>
                                </div>

                            </div>


                        </div>

                        <div class="modal-table modal-background__table">

                            <table id="walletStatsTable">
                                <thead>
                                <tr>
                                    <th>日期</th>
                                    <th>行动</th>
                                    <th>和</th>
                                    <th>源/目标</th>
                                    <th>地位</th>
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
                        <h3>ETF交易中心</h3>
                    </div>

                    <div class="modal-button">

                        <div class="modal-button_item">
                            <a href="#" data-toggle="modal" data-target="#arbitration_3">
                                <div>
                                    <span>全球</span>
                                    <p>ETF</p>
                                </div>
                            </a>
                        </div>

                        <div class="modal-button_item">
                            <a href="#" data-toggle="modal" data-target="#arbitration_4">
                                <div>
                                    <span>仲裁</span>
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
                        <h3>ETF交易中心</h3>
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
                                        <p>金融区 <?= htmlspecialchars($region['region_name']) ?></p>
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
                        <h3>ETF交易中心</h3>
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
                                    <p>开始日期：</p>
                                </div>

                                <div class="modal-currency_span">
                                    <span id="dealStart"></span>
                                </div>
                            </div>

                            <div class="modal-currency_item">
                                <div class="modal-currency_p">
                                    <p>结束日期：</p>
                                </div>

                                <div class="modal-currency_span">
                                    <span id="dealEnd"></span>
                                </div>
                            </div>

                            <div class="modal-currency_item">
                                <div class="modal-currency_p">
                                    <p>期间：</p>
                                </div>

                                <div class="modal-currency_span">
                                    <span id="dealTime"></span>
                                </div>
                            </div>

                            <div class="modal-currency_item">
                                <div class="modal-currency_p">
                                    <p>ETF价格：</p>
                                </div>

                                <div class="modal-currency_span">
                                    <span id="dealAmount"></span>
                                </div>
                            </div>

                            <div class="modal-currency_item">
                                <div class="modal-currency_p">
                                    <p>预计盈利能力：</p>
                                </div>

                                <div class="modal-currency_span">
                                    <span id="dealRate"></span>
                                </div>
                            </div>

                            <div class="modal-currency_item">
                                <div class="modal-currency_p">
                                    <p>预计收入：</p>
                                </div>

                                <div class="modal-currency_span">
                                    <span id="dealRevenue"></span>
                                </div>
                            </div>

                            <div class="modal-currency_button">
                                <a href="#" id="dealMore" data-toggle="modal" data-target="#text">了解更多信息</a>
                            </div>

                        </div>

                        <div class="modal-currency_right">
                            <div class="modal-text">
                                <div class="modal-text_title">
                                    <h3>您的资产金额</h3>
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
                                        <label for="sum">USDT投资金额</label>

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
                        <a href="#" class="btn-invest js-invest-btn disabled">投资</a>
                    </div>

                </div>


            </div>
        </div>
    </div>
</div>





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


                    <div class="modal-title"><h3 id="flashDexTitle">RIX AI 套利</h3></div>


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
                                <div class="modal-currency_p"><p>开始日期:</p></div>
                                <div class="modal-currency_span"><span id="dealStart">—</span></div>
                            </div>

                            <div class="modal-currency_item">
                                <div class="modal-currency_p"><p>结束日期：</p></div>
                                <div class="modal-currency_span"><span id="dealEnd">—</span></div>
                            </div>

                            <div class="modal-currency_item">
                                <div class="modal-currency_p"><p>交易金额：</p></div>
                                <div class="modal-currency_span"><span id="dealAmount"></span></div>
                            </div>

                            <div class="modal-currency_item">
                                <div class="modal-currency_p"><p>预期收入：</p></div>
                                <div class="modal-currency_span"><span id="dealRate"></span></div>
                            </div>

                            <div class="modal-currency_button">
                                <a href="#" id="dealMore" data-toggle="modal" data-target="#text">了解更多信息</a>
                            </div>
                        </div><!-- /.modal-currency_left -->

                        <div class="modal-currency_right">
                            <div class="modal-text">
                                <div class="modal-text_title"><h3>您的资产金额</h3></div>
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
                                        <label for="sum">USDT投资金额</label>
                                        <input  type="text" name="sum" class="form-control onlyDigits js-sum-input"
                                                placeholder="0" value=""
                                                data-min="100" data-maxInvest="200">
                                    </div>
                                </div>
                            </form>
                        </div><!-- /.modal-currency_right -->
                    </div><!-- /.modal-currency -->


                    <div class="modal-button">
                        <a href="#" class="btn-invest js-invest-btn disabled">投资</a>
                    </div>

                    <div class="modal-currency_item d-none" id="coolDownError">
                        <div class="modal-currency_p">
                            <p style="color:red;margin-top:10px">
                                错误！下一个 RIX AI 类型的交易将可用
                                <span id="coolDownDate"></span>
                            </p>
                        </div>
                    </div>

                    <div class="modal-currency_item d-none" id="blockedByLarge">
                        <div class="modal-currency_p">
                            <p style="color: red; margin-top: 10px">错误！从某个地区开立任何交易后，无法启动 RIXAI 交易。</p>
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
                        <h3 id="transferTitle">地位</h3>
                        <p  id="transferMsg"></p>

                        <div class="modal-body__next">
                            <a href="#" id="transferContinue">好</a>
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
                        <h3>列夫·反照率 – 天体启示指南</h3>
                    </div>


                    <p>当第一缕阳光冲破晨雾时，一头耀眼的白色狮子身影从云顶中浮现出来。它的头部由一颗完美无瑕的钻石雕刻而成，在初升的太阳的光芒下像水晶一样闪闪发光。反照率身后跟着一群狮子，仿佛受到一种神秘力量的引导，将它们召唤到新的高度。</p>
                    <p><strong>生境</strong><br>
                        反照率居住在无边无际的天国，每一步都像是通往伟大事业的垫脚石。据说，它的道路与那些寻求从恐惧中解放出来的人的命运线相交——无论是对贫困的恐惧还是对孤独的恐惧。</p>
                    <p><strong>性格和象征意义</strong>这只狮子体现了纯洁的意图和至高无上的希望。它雪白的皮毛反映了每个人都可以超越灰色的日常生活并找到通往幸福的道路的信念。钻石头象征着一种开明的意识，不会屈服于怀疑的黑暗。反照率带领他的骄傲前进，表明云层背后总有晴朗的天空。</p>
                    <p>遇到反照率的人开始相信，没有什么能阻止他们实现自己想要的东西。他的存在鼓励他们抛开恐惧，看到世界比任何限制都要广阔得多。鬃毛上钻石的光芒提醒我们，没有比灵魂中的贫穷更可怕的了，没有比我们内心发现的光芒更珍贵的宝藏了。</p>
                    <p>阿贝多的使命是成为那些寻求内心自由和繁荣的人的指路明灯。他的道路告诉我们，为了超越日常生活的乌云，你需要敢于迈出迈向自己光芒的第一步。</p>



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
                        <h3>阿尔弗雷多</h3>
                    </div>

                    <div class="modal-desc__text">
                        <p>图形单价 – 0.057 USDT <br>
                            您的收藏 - 4 个单位</p>

                        <p>10 台起可销售</p>
                    </div>

                    <div class="form-group">

                        <div class="modal-select">
                            <label for="topUpAmountSale">输入要销售的数量</label>

                            <input type="text" id="topUpAmountSale" class="form-control simpleDigits" placeholder="0">
                        </div>
                    </div>

                    <div class="modal-desc__sale">
                        <p>售后您将收到：4 USDT</p>
                    </div>

                    <div class="modal-descButton">
                        <a href="#" data-toggle="modal" data-target="#sale">卖</a>
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
                        <h3>单位 NFT 销售已成功确认</h3>

                        <p>预计将记入您当前的余额</p>
                    </div>

                    <div class="modal-body__next">
                        <a href="#"><还行></还行></a>
                    </div>

                </div>


            </div>
        </div>
    </div>
</div>


</div>