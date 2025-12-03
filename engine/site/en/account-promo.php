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

                        <div class="tab-pane fade show active" id="v-pills-promo" role="tabpanel" aria-labelledby="v-pills-promo-tab">
                            <div class="tabs-2_top">

                                <div class="tabs-2_item">

                                    <h5>Promo</h5>
                                    <!--                                    <h5>0 partners</h5>-->
                                    <br>
                                    <p>Total Earned: <?= moneyFormat($totalPromoAccrued) ?> USDT</p>

                                </div>

                                <div class="tabs-2_item">
                                    <span class="line-left"></span>
                                </div>


                                <div class="tabs-2_item">

                                    <h4>Available income</h4>

                                    <div class="tabs-2_sum">
                                        <div class="tabs-2_simIcon">
                                            <i class="icon-teph"></i>
                                        </div>

                                        <div class="tabs-2_text">
                                            <p><?= moneyFormat($balancePromo) ?></p>
                                            <span>Usdt</span>
                                        </div>
                                    </div>

                                    <div class="tabs-2_item__button">
                                        <a href="#" data-toggle="modal" data-target="#text_2">Withdrawal</a>
                                    </div>



                                </div>


                            </div>
                            <div class="tabs-3">

                                <div class="tabs-3_background">
                                    <!--                                    <div class="tabs-3_title">-->
                                    <!--                                        <h5>Promo</h5>-->
                                    <!--                                    </div>-->

                                    <div class="tabs-3_description">
                                        <p>Welcome to the official section of ETFRIX promotional activities. Here you will find special offers, temporary campaigns and affiliate initiatives where users can receive bonus rewards, preferences and additional privileges for participating in promotions.<p>

                                        <p>ETFRIX Promo is not just a marketing block, but a full-fledged incentive and loyalty mechanism integrated into the company's investment ecosystem.<p>

                                        <p>Within this section, there will be regular:<p>

                                        <p>🔹 Seasonal bonus programs<p>

                                        <p>🔹 Promo for new and active customers<p>

                                        <p>🔹 Affiliate giveaways and campaigns<p>

                                        <p>🔹 Temporary conditions with increased yields<p>

                                        <p>🔹 Opportunities to get internal tokens, NFTs, early access, or percentage-based bonuses<p>

                                        <p>Participation in the promo is your chance to increase profitability, test new platform tools and get additional privileges from ETFRIX.<p>
                                        <p><p>

                                        <p>Stay tuned - offers are limited in time and are available only to registered users.</p>
                                    </div>
                                </div>

                                <?php $promos = getAllPromo(); ?>
                                <?php if (!empty($promos)): ?>
                                    <?php foreach ($promos as $promo):
                                        $title = $promo['news_title_en'] ?? '';
                                        $image = $promo['image_path'] ?: '/img/photo-promo2.jpg';
                                        $markup = $promo['markup_en'] ?: '';
                                        $rawText = $promo['raw_text_en'] ?? '';
                                        $rawFallback = $rawText !== ''
                                            ? '<div class="tabs-3_rightDescription"><p class="promo-desc">' . nl2br(htmlspecialchars($rawText)) . '</p></div>'
                                            : '';
                                        if ($markup !== '' && stripos($markup, 'tabs-3_rightDescription') === false) {
                                            $markup = '<div class="tabs-3_rightDescription">' . $markup . '</div>';
                                        }
                                        ?>
                                        <div class="tabs-3_promo">
                                            <div class="tabs-3_item">

                                                <div class="tabs-3_left">
                                                    <div class="tabs-3_img">
                                                        <img src="<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($title) ?>">
                                                    </div>
                                                </div>

                                                <div class="tabs-3_right">

                                                    <div class="tabs-3_rightTitle">
                                                        <h5><?= htmlspecialchars($title) ?></h5>
                                                    </div>

                                                    <?= $markup ?: $rawFallback ?: '<div class="tabs-3_rightDescription"><p class="promo-desc">The description will be added soon.</p></div>' ?>

                                                </div>

                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="tabs-3_promo">
                                        <div class="tabs-3_item">
                                            <div class="tabs-3_right" style="width: 100%">
                                                <div class="tabs-3_rightTitle">
                                                    <h5>New promos are coming soon</h5>
                                                </div>
                                                <div class="tabs-3_rightDescription">
                                                    <p class="promo-desc">We are preparing new offers. Please check back later.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                            </div>

                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div>


</div>

<script>

    document.addEventListener('DOMContentLoaded', () => {
        /* ---- Withdrawal of team balance ---- */
        const btn = document.querySelector(
            '.tabs-2_item__button a[data-toggle][data-target="#text_2"]'
        );
        if (!btn) return;

        // simplified moneyFormat (replace with your own function)
        const moneyFormat = n => Number(n).toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

        btn.addEventListener('click', async e => {
            e.preventDefault();

            const modalEl  = document.getElementById('text_2');
            const titleEl  = modalEl.querySelector('#transferTitle');
            const msgEl    = modalEl.querySelector('#transferMsg');

            // Lock the button so that you don't press twice
            btn.classList.add('disabled');

            try {
                const res  = await fetch('/api/transfer_promo_balance', {
                    method: 'POST',
                    headers: {'X-Requested-With': 'XMLHttpRequest'}
                });
                const json = await res.json();

                if (json.success) {
                    titleEl.textContent = 'The translation is done';
                    msgEl.textContent   = ` ${moneyFormat(json.amount)} USDT to the main balance`;
                } else if (json.error === 'nothing_to_transfer') {
                    titleEl.textContent = 'Nothing to transfer';
                    msgEl.textContent   = 'The balance of the team is equal to 0';
                } else {
                    titleEl.textContent = 'Error';
                    msgEl.textContent   = 'Something went wrong. Try again later.';
                }
                // показываем модалку Bootstrap-ом
                $(modalEl).modal('show');

            } catch (err) {
                console.error(err);
                showToast('Network error', 3000);
            } finally {
                btn.classList.remove('disabled');
            }
        });

    });

</script>