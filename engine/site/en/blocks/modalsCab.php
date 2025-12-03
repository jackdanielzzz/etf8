

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
                                        <span class="modal-function_text">Statistics</span>
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
                                        <span class="modal-function_text">Exchange</span>
                                    </a>
                                </div>
                            </div>

                        </div>








                    </div>

                    <div class="modal-table">

                        <table>
                            <thead>
                            <tr>
                                <th>Denomination</th>
                                <th>Rate</th>
                                <th>Amendment in 24 hours</th>
                                <th class="text-right">My Balance</th>
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
                                    <td class="text-right">—<!-- OR WITHDRAW THE REAL BALANCE --></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>

                    </div>

                    <div class="table-mobile">

                        <div class="table-mobile_top">
                            <div class="table-mobile_item">
                                <p>Denomination</p>
                                <span class="text_blue">Rate</span>
                            </div>

                            <div class="table-mobile_item">
                                <p>24-hour change</p>
                                <span>My Balance</span>
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
                                        <!-- верхний блок: тикер + курс -->
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

                                        <!-- Bottom Block: Change + Balance -->
                                        <div class="table-mobile_bottomBlock">
                                            <div class="table-mobile_bottomLeft2">
                                                <p class="<?= $class; ?>">
                                                    <?= $sign . number_format($q['percent_change'], 2, ',', ' ') ?> %
                                                </p>
                                            </div>
                                            <div class="table-mobile_bottomRight2">
                                                <p>—<!-- Balance --></p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                        </div>

                    </div>

                    <div class="modal-button">

                        <div class="btn-1">
                            <a href="#" data-toggle="modal" data-dismiss="modal" data-target="#replenish">Top up </a>
                        </div>

                        <div class="btn-1">
                            <a href="#" data-toggle="modal" data-target="#conclusion">Withdrawal</a>
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
                        <h3>Top up</h3>
                    </div>

                    <div class="modal-form">
                        <div class="form">
                            <div class="form-group">
                                <?php
                                // db.php already gives $db (PDO).  Take only active wallets:
                                global $pdo;

                                $sql = "
                                        SELECT  w.config_name,  -- value для <option>
                                            w.full_name,        -- human-readable name
                                            w.value,            -- THE WALLET ADDRESS ITSELF
                                            q.price             -- current price (can be NULL)
                                        FROM _wallets  w
                                        LEFT JOIN asset_quotes q
                                           ON q.symbol = w.quotes_symbol
                                        WHERE w.status = 0
                                        ORDER BY w.id
                                        ";
                                $wallets = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
                                ?>
                                <div class="modal-select">
                                    <label for="currency">Select Currency</label>

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
                                    <label for="topUpAmount">Enter the deposit amount (in USD)</label>

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
                            Continue
                        </button>

                        <button class="btn-support">Support
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
                        <h3 id="topUpTitle">USDT Deposit</h3>
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
                                            <p id="networkHint" style="color: red">Make sure the funds you are sending are on the TRC-20 network</p>
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
                                    <label for="hash" id="payToThisAddress">Fund this wallet</label>

                                    <p class="js-copy-referral" id="walletAddress" data-url="">...</p>
                                </div>
                            </div>
                            <div class="form-group">

                                <div class="modal-select">
                                    <label for="hash">Enter the transaction hash after depositing</label>

                                    <input type="text"
                                           id="txHash"
                                           class="form-control"
                                           placeholder="Enter the transaction hash"
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
                            Replenished
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
                        <h3>Wait for your application to be processed</h3>
                    </div>


                    <div class="modal-button_2 mt-5">
                        <button>
                            <a href="#" class="btn-hash"> Resume</a>
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
                        <h3>Withdrawal request</h3>
                    </div>

                    <div class="modal-qr_2">
                        <div class="modal-qr_right">

                            <div class="totalInfo-left_block mt-0">
<!--                                <div class="totalInfo-left_icon">-->
<!--                                    <i class="icon-teph"></i>-->
<!--                                </div>-->

                                <div class="totalInfo-left_text">
                                    <p>Withdrawal in USDT ERC-20</p>
                                </div>
                            </div>

                            <div class="modal-qr_form">
                                <div class="form">
                                    <div class="form-group">

                                        <div class="modal-select">
                                            <label for="address-conclusion">Enter your USDT address (on the ERC-20 network!)</label>

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
                                    <label for="sum-withdrawal">Enter the amount</label>

                                    <input type="text" id="sum-withdrawal" class="form-control onlyDigits js-sum-input" placeholder="">
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="modal-button">
                        <button>
                            <a href="#" class="btn-continue_1" data-method="USDT TRC-20">Continue</a>
                        </button>

                        <button>
                            <a href="https://t.me/<?= $_ENV['TG_SUPPORT'] ?>" class="btn-support_1">Support</a>
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
                        <h3>Swap</h3>
                    </div>

                    <div class="modal-exchange">

                        <div class="modal-exchange_item exchangeBackground-1">

                            <div class="form-group">

                                <div class="modal-flex_input">
                                    <div class="modal-select">
                                        <label for="select">I have 0 Bitcoin</label>

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
                                            <span>I want Teather USD</span>
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
                        <button>MAX</button>
                    </div>

                    <div class="modal-exchangeButton">
                        <a href="#">Swap</a>
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
                        <h3>RIXWALLET Statistics</h3>
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
                                        <span>Arrivals</span>
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
                                        <span>Withdrawal</span>
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
                                        <span>Swap</span>
                                    </div>
                                </div>

                            </div>


                        </div>

                        <div class="modal-table modal-background__table">

                            <table id="walletStatsTable">
                                <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Action</th>
                                    <th>Amount</th>
                                    <th>Source/Destination</th>
                                    <th>Status</th>
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
                        <h3>ETF Trading Hub</h3>
                    </div>

                    <div class="modal-button">

                        <div class="modal-button_item">
                            <a href="#" data-toggle="modal" data-target="#arbitration_3">
                                <div>
                                    <span>Global</span>
                                    <p>ETF</p>
                                </div>
                            </a>
                        </div>

                        <div class="modal-button_item">
                            <a href="#" data-toggle="modal" data-target="#arbitration_4">
                                <div>
                                    <span>ARBITRAGE</span>
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
                        <h3>ETF Trading Hub</h3>
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
                                        <p>Financial Region <?= htmlspecialchars($region['region_name']) ?></p>
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
                        <h3>ETF Trading Hub</h3>
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
                                    <p>Start Date:</p>
                                </div>

                                <div class="modal-currency_span">
                                    <span id="dealStart"></span>
                                </div>
                            </div>

                            <div class="modal-currency_item">
                                <div class="modal-currency_p">
                                    <p>End Date:</p>
                                </div>

                                <div class="modal-currency_span">
                                    <span id="dealEnd"></span>
                                </div>
                            </div>

                            <div class="modal-currency_item">
                                <div class="modal-currency_p">
                                    <p>Duration:</p>
                                </div>

                                <div class="modal-currency_span">
                                    <span id="dealTime"></span>
                                </div>
                            </div>

                            <div class="modal-currency_item">
                                <div class="modal-currency_p">
                                    <p>ETF Value:</p>
                                </div>

                                <div class="modal-currency_span">
                                    <span id="dealAmount"></span>
                                </div>
                            </div>

                            <div class="modal-currency_item">
                                <div class="modal-currency_p">
                                    <p>Projected returns:</p>
                                </div>

                                <div class="modal-currency_span">
                                    <span id="dealRate"></span>
                                </div>
                            </div>

                            <div class="modal-currency_item">
                                <div class="modal-currency_p">
                                    <p>Projected revenue:</p>
                                </div>

                                <div class="modal-currency_span">
                                    <span id="dealRevenue"></span>
                                </div>
                            </div>

                            <div class="modal-currency_button">
                                <a href="#" id="dealMore" data-toggle="modal" data-target="#text">More</a>
                            </div>

                        </div>

                        <div class="modal-currency_right">
                            <div class="modal-text">
                                <div class="modal-text_title">
                                    <h3>The amount of your assets</h3>
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
                                        <label for="sum">USDT Investment Amount</label>

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
                        <a href="#" class="btn-invest js-invest-btn disabled">INVEST</a>
                    </div>

                </div>


            </div>
        </div>
    </div>
</div>




<!-- Flash trade (100-200 USDT, 1 day, 3-6%/day) -->
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

                    <!-- ───── header ───── -->
                    <div class="modal-title"><h3 id="flashDexTitle">RIX AI ARBITRAGE</h3></div>

                    <!-- ───── singleTradeSelectionButton───── -->
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

                    <!-- ───── blockWithDatesRangeYield───── -->
                    <div class="modal-currency">

                        <div class="modal-currency_left">
                            <div class="modal-currency_item">
                                <div class="modal-currency_p"><p>Start Date:</p></div>
                                <div class="modal-currency_span"><span id="dealStart">—</span></div>
                            </div>

                            <div class="modal-currency_item">
                                <div class="modal-currency_p"><p>End Date:</p></div>
                                <div class="modal-currency_span"><span id="dealEnd">—</span></div>
                            </div>

                            <div class="modal-currency_item">
                                <div class="modal-currency_p"><p>Trade amount:</p></div>
                                <div class="modal-currency_span"><span id="dealAmount"></span></div>
                            </div>

                            <div class="modal-currency_item">
                                <div class="modal-currency_p"><p>Expected Revenue:</p></div>
                                <div class="modal-currency_span"><span id="dealRate"></span></div>
                            </div>

                            <div class="modal-currency_button">
                                <a href="#" id="dealMore" data-toggle="modal" data-target="#text">More</a>
                            </div>
                        </div><!-- /.modal-currency_left -->

                        <div class="modal-currency_right">
                            <div class="modal-text">
                                <div class="modal-text_title"><h3>The amount of your assets</h3></div>
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
                                        <label for="sum">USDT Investment Amount</label>
                                        <input  type="text" name="sum" class="form-control onlyDigits js-sum-input"
                                                placeholder="0" value=""
                                                data-min="100" data-maxInvest="200">
                                    </div>
                                </div>
                            </form>
                        </div><!-- /.modal-currency_right -->
                    </div><!-- /.modal-currency -->

                    <!-- ───── Invest button ───── -->
                    <div class="modal-button">
                        <a href="#" class="btn-invest js-invest-btn disabled">Invest</a>
                    </div>

                    <div class="modal-currency_item d-none" id="coolDownError">
                        <div class="modal-currency_p">
                            <p style="color:red;margin-top:10px">
                                Error! The next RIX AI type deal will be available
                                <span id="coolDownDate"></span>
                            </p>
                        </div>
                    </div>

                    <div class="modal-currency_item d-none" id="blockedByLarge">
                        <div class="modal-currency_p">
                            <p style="color: red; margin-top: 10px">Error! After opening any trade from a region, it is not possible to launch a RIXAI trade.</p>
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
                        <h3 id="transferTitle">Статус</h3>
                        <p  id="transferMsg"></p>

                        <div class="modal-body__next">
                            <a href="#" id="transferContinue">OK</a>
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
                        <h3>Lev Albedo – Guide of Celestial Revelations</h3>
                    </div>


                    <p>As the first rays of sunlight break through the morning mist, the dazzling white figure of a lion emerges from the cloudtops. Its head, carved from a flawless diamond, shines like a crystal in the rays of the rising sun. Albedo is followed by a flock of lions, as if guided by a mysterious force that calls them to new heights.</p>
                    <p><strong>Habitat</strong><br>
                        Albedo dwells in the boundless heavenly realm, where every step is like a stepping stone to something great. Its paths are said to intersect with the fate lines of those seeking liberation from fears – whether it is the fear of poverty or the fear of being alone.</p>
                    <p><strong>Character and Symbolism</strong>This lion embodies purity of intention and supreme hope. Its snow-white fur reflects the belief that everyone can rise above the gray routine and find their way to well-being. The diamond head symbolizes an enlightened consciousness that does not succumb to the darkness of doubt. Albedo leads his pride forward, showing that there is always a clear sky behind the clouds.</p>
                    <p>People who meet Albedo begin to believe that nothing can keep them from achieving what they want. His presence encourages them to put aside fears and see that the world is much wider than any limitations. The shine of a diamond in its mane reminds us that there is no poverty more terrible than that which lives in the soul, and there are no treasures more precious than the light we find within ourselves.</p>
                    <p>Albedo's mission is to become a guiding light for those who seek inner freedom and prosperity. His path teaches that in order to rise above the clouds of everyday life, you need to dare to take the first step towards your own radiance.</p>



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
                        <h3>ALFREDO</h3>
                    </div>

                    <div class="modal-desc__text">
                        <p>Graphics Unit Price – 0.057 USDT <br>
                            Your collection - 4 units</p>

                        <p>Sale is possible from 10 units</p>
                    </div>

                    <div class="form-group">

                        <div class="modal-select">
                            <label for="topUpAmountSale">Enter the quantity to be sold</label>

                            <input type="text" id="topUpAmountSale" class="form-control simpleDigits" placeholder="0">
                        </div>
                    </div>

                    <div class="modal-desc__sale">
                        <p>You will receive after the sale: 4 USDT</p>
                    </div>

                    <div class="modal-descButton">
                        <a href="#" data-toggle="modal" data-target="#sale">Sell</a>
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
                        <h3>Unit NFT Sale Has Been Successfully Confirmed</h3>

                        <p>Expect to be credited to your current balance</p>
                    </div>

                    <div class="modal-body__next">
                        <a href="#">Ок</a>
                    </div>

                </div>


            </div>
        </div>
    </div>
</div>


</div>