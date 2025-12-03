

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
                                        <span class="modal-function_text">Статистика</span>
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
                                        <span class="modal-function_text">Обмен</span>
                                    </a>
                                </div>
                            </div>

                        </div>








                    </div>

                    <div class="modal-table">

                        <table>
                            <thead>
                            <tr>
                                <th>Наименование</th>
                                <th>Курс</th>
                                <th>Изменение за 24 ч.</th>
                                <th class="text-right">Мой баланс</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php foreach ($assetQuotes as $q): ?>
                                <?php
                                $isUp   = $q['percent_change'] >= 0;
                                $class  = $isUp ? 'text_green' : 'text_red';
                                $sign   = $isUp ? '+' : '';
                                $balanceAmount = altcoinBalanceForSymbol($userAltcoins, $q['symbol']);
                                $balanceFormatted = $balanceAmount === null
                                    ? '—'
                                    : number_format($balanceAmount, 2, ',', ' ');
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
                                    <td class="text-right"><?= $balanceFormatted ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>

                    </div>

                    <div class="table-mobile">

                        <div class="table-mobile_top">
                            <div class="table-mobile_item">
                                <p>Наименование</p>
                                <span class="text_blue">Курс</span>
                            </div>

                            <div class="table-mobile_item">
                                <p>Изменение за 24 часа</p>
                                <span>Мой баланс</span>
                            </div>
                        </div>

                        <div class="table-mobile_bottom">

                            <div class="table-mobile_bottom">
                                <?php foreach ($assetQuotes as $q): ?>
                                    <?php
                                    $isUp   = $q['percent_change'] >= 0;
                                    $class  = $isUp ? 'text_green' : 'text_red';
                                    $sign   = $isUp ? '+' : '';
                                    $balanceAmount = altcoinBalanceForSymbol($userAltcoins, $q['symbol']);
                                    $balanceFormatted = $balanceAmount === null
                                        ? '—'
                                        : number_format($balanceAmount, 2, ',', ' ');
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

                                        <!-- нижний блок: изменение + баланс -->
                                        <div class="table-mobile_bottomBlock">
                                            <div class="table-mobile_bottomLeft2">
                                                <p class="<?= $class; ?>">
                                                    <?= $sign . number_format($q['percent_change'], 2, ',', ' ') ?> %
                                                </p>
                                            </div>
                                            <div class="table-mobile_bottomRight2">
                                                <p><?= $balanceFormatted ?></p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                        </div>

                    </div>

                    <div class="modal-button">

                        <div class="btn-1">
                            <a href="#" data-toggle="modal" data-dismiss="modal" data-target="#replenish">Пополнить</a>
                        </div>

                        <div class="btn-1">
                            <a href="#" data-toggle="modal" data-target="#conclusion">Вывод</a>
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
                        <h3>Пополнить</h3>
                    </div>

                    <div class="modal-form">
                        <div class="form">
                            <div class="form-group">
                                <?php
                                // db.php уже даёт $db (PDO).  Берём только активные кошельки:
                                global $pdo;

                                $sql = "
                                        SELECT  w.config_name,  -- value для <option>
                                            w.full_name,        -- человеко-читаемое название
                                            w.value,            -- сам адрес кошелька
                                            q.price             -- текущая цена (может быть NULL)
                                        FROM _wallets  w
                                        LEFT JOIN asset_quotes q
                                           ON q.symbol = w.quotes_symbol
                                        WHERE w.status = 0
                                        ORDER BY w.id
                                        ";
                                $wallets = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
                                ?>
                                <div class="modal-select">
                                    <label for="currency">Выберите валюту</label>

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
                                    <label for="topUpAmount">Введите сумму пополнения(в USD)</label>

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

                        <button class="btn-support">Поддержка
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
                        <h3 id="topUpTitle">Пополнение USDT</h3>
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
                                            <p id="networkHint" style="color: red">Убедитесь, что средства, которые вы отправляете находятся в сети TRC-20</p>
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
                                    <label for="hash" id="payToThisAddress">Пополните этот кошелек</label>

                                    <p class="js-copy-referral" id="walletAddress" data-url="">...</p>
                                </div>
                            </div>
                            <div class="form-group">

                                <div class="modal-select">
                                    <label for="hash">Введите хеш транзакции после пополнения</label>

                                    <input type="text"
                                           id="txHash"
                                           class="form-control"
                                           placeholder="Введите хеш транзакции"
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
                            Пополнил
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
                        <h3>Ожидайте, Ваша заявка в обработке</h3>
                    </div>


                    <div class="modal-button_2 mt-5">
                        <button>
                            <a href="#" class="btn-hash"> Продолжить</a>
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
                        <h3>Заявка на вывод средств</h3>
                    </div>

                    <div class="modal-qr_2">
                        <div class="modal-qr_right">

                            <div class="totalInfo-left_block mt-0">
                                <!--                                <div class="totalInfo-left_icon">-->
                                <!--                                    <i class="icon-teph"></i>-->
                                <!--                                </div>-->

                                <div class="totalInfo-left_text">
                                    <p>Вывод в USDT ERC-20</p>
                                </div>
                            </div>

                            <div class="modal-qr_form">
                                <div class="form">
                                    <div class="form-group">

                                        <div class="modal-select">
                                            <label for="address-conclusion">Введите адрес USDT(в сети ERC-20!)</label>

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
                                    <label for="sum-withdrawal">Введите сумму</label>

                                    <input type="text" id="sum-withdrawal" class="form-control onlyDigits js-sum-input" placeholder="">
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="modal-button">
                        <button>
                            <a href="#" class="btn-continue_1" data-method="USDT TRC-20">Продолжить</a>
                        </button>

                        <button>
                            <a href="https://t.me/<?= $_ENV['TG_SUPPORT'] ?>" class="btn-support_1">Поддержка</a>
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
                        <h3>Обменять</h3>
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
                        <a href="#">Обменять</a>
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
                        <h3>Статистика RIXWALLET</h3>
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
                                        <span>Поступления</span>
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
                                        <span>Вывод</span>
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
                                        <span>Обмен</span>
                                    </div>
                                </div>

                            </div>


                        </div>

                        <div class="modal-table modal-background__table">

                            <table id="walletStatsTable">
                                <thead>
                                <tr>
                                    <th>Дата</th>
                                    <th>Действие</th>
                                    <th>Сумма</th>
                                    <th>Источник/Назначение</th>
                                    <th>Статус</th>
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
                                        <p>Финансовый регион <?= htmlspecialchars($region['region_name']) ?></p>
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
                                    <p>Дата начала:</p>
                                </div>

                                <div class="modal-currency_span">
                                    <span id="dealStart"></span>
                                </div>
                            </div>

                            <div class="modal-currency_item">
                                <div class="modal-currency_p">
                                    <p>Дата окончания:</p>
                                </div>

                                <div class="modal-currency_span">
                                    <span id="dealEnd"></span>
                                </div>
                            </div>

                            <div class="modal-currency_item">
                                <div class="modal-currency_p">
                                    <p>Длительность:</p>
                                </div>

                                <div class="modal-currency_span">
                                    <span id="dealTime"></span>
                                </div>
                            </div>

                            <div class="modal-currency_item">
                                <div class="modal-currency_p">
                                    <p>Стоимость ETF:</p>
                                </div>

                                <div class="modal-currency_span">
                                    <span id="dealAmount"></span>
                                </div>
                            </div>

                            <div class="modal-currency_item">
                                <div class="modal-currency_p">
                                    <p>Прогнозируемая доходность:</p>
                                </div>

                                <div class="modal-currency_span">
                                    <span id="dealRate"></span>
                                </div>
                            </div>

                            <div class="modal-currency_item">
                                <div class="modal-currency_p">
                                    <p>Прогнозируемый доход:</p>
                                </div>

                                <div class="modal-currency_span">
                                    <span id="dealRevenue"></span>
                                </div>
                            </div>

                            <div class="modal-currency_button">
                                <a href="#" id="dealMore" data-toggle="modal" data-target="#text">Подробнее</a>
                            </div>

                        </div>

                        <div class="modal-currency_right">
                            <div class="modal-text">
                                <div class="modal-text_title">
                                    <h3>Сумма ваших активов</h3>
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
                                        <label for="sum">Сумма инвестиций USDT</label>

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
                        <a href="#" class="btn-invest js-invest-btn disabled">ИНВЕСТИРОВАТЬ</a>
                        <a href="#" class="btn-invest js-request-btn d-none">ПОДАТЬ ЗАЯВКУ НА УЧАСТИЕ</a>
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

                    <div class="modal-title"><h3 id="flashDexTitle">RIX AI ARBITRAGE</h3></div>

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
                                <div class="modal-currency_p"><p>Дата начала:</p></div>
                                <div class="modal-currency_span"><span id="dealStart">—</span></div>
                            </div>

                            <div class="modal-currency_item">
                                <div class="modal-currency_p"><p>Дата окончания:</p></div>
                                <div class="modal-currency_span"><span id="dealEnd">—</span></div>
                            </div>

                            <div class="modal-currency_item">
                                <div class="modal-currency_p"><p>Сумма сделки:</p></div>
                                <div class="modal-currency_span"><span id="dealAmount"></span></div>
                            </div>

                            <div class="modal-currency_item">
                                <div class="modal-currency_p"><p>Ожидаемый доход:</p></div>
                                <div class="modal-currency_span"><span id="dealRate"></span></div>
                            </div>

                            <div class="modal-currency_button">
                                <a href="#" id="dealMore" data-toggle="modal" data-target="#text">Подробнее</a>
                            </div>
                        </div>

                        <div class="modal-currency_right">
                            <div class="modal-text">
                                <div class="modal-text_title"><h3>Сумма ваших активов</h3></div>
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
                                        <label for="sum">Сумма инвестиций USDT</label>
                                        <input  type="text" name="sum" class="form-control onlyDigits js-sum-input"
                                                placeholder="0" value=""
                                                data-min="100" data-maxInvest="200">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="modal-button">
                        <a href="#" class="btn-invest js-request-btn d-none">ПОДАТЬ ЗАЯВКУ НА УЧАСТИЕ</a>
                        <a href="#" class="btn-invest js-invest-btn disabled">ИНВЕСТИРОВАТЬ</a>
                    </div>

                    <div class="modal-currency_item d-none" id="coolDownError">
                        <div class="modal-currency_p">
                            <p style="color:red;margin-top:10px">
                                Ошибка! Следующая сделка типа RIX AI будет доступна
                                <span id="coolDownDate"></span>
                            </p>
                        </div>
                    </div>

                    <div class="modal-currency_item d-none" id="blockedByLarge">
                        <div class="modal-currency_p">
                            <p style="color: red; margin-top: 10px">Ошибка! После открытия любой сделки с региона, запустить сделку типа RIXAI нельзя.</p>
                        </div>
                    </div>

                </div>
            </div>
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

<!--                    <div class="modal-body__desc--title">-->
<!--                        <h3 class="js-nft-modal-title"></h3>-->
<!--                    </div>-->

                    <div class="modal-desc__text js-nft-modal-description"></div>


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

                <div class="modal-title">
                    <h3 class="js-sale-title"></h3>
                </div>

                <form id="saleForm" class="js-sale-form">
                    <div class="modal-desc__text">
                        <p>Цена на графический юнит - <span class="js-sale-price">0</span> USDT <br>
                            Ваша коллекция - <span class="js-sale-count">0</span> <span class="js-sale-unit-label">юнитов</span></p>

                        <p>Продажа возможно от 10 юнитов</p>
                    </div>

                    <div class="form-group">
                        <div class="modal-select">
                            <label for="topUpAmountSale">Введите количество к продаже</label>
                            <input type="number"
                                   id="topUpAmountSale"
                                   name="amount"
                                   class="form-control simpleDigits"
                                   placeholder="0"
                                   min="10"
                                   step="1">
                        </div>
                    </div>

                    <div class="modal-desc__sale">
                        <p>Вы получите после продажи: <span class="js-sale-total">0</span> USDT</p>
                    </div>

                    <div
                            class="modal-desc__message js-sale-message"
                            role="alert"
                            aria-live="polite"
                            aria-atomic="true"
                            hidden
                    ></div>

                    <div class="modal-descButton">
                        <button type="submit"
                                id="saleSubmit"
                                class="btn-sale">
                            ПРОДАТЬ
                        </button>
                    </div>
                </form>




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
                        <h3>Продажа NFT юнитов была успешно подтверждена</h3>

                        <p>Ожидайте начисление на ваш Актуальный баланс</p>
                    </div>

                    <div class="modal-body__next">
                        <a href="/ru/account/rix">Ок</a>
                    </div>

                </div>


            </div>
        </div>
    </div>
</div>


</div>