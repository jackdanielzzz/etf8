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

                                    <h5>Промо</h5>
                                    <!--                                    <h5>0 партнеров</h5>-->
                                    <br>
                                    <p>Всего заработано: <?= moneyFormat($totalPromoAccrued) ?> USDT</p>

                                </div>

                                <div class="tabs-2_item">
                                    <span class="line-left"></span>
                                </div>


                                <div class="tabs-2_item">

                                    <h4>Доступный доход</h4>

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
                                        <a href="#" data-toggle="modal" data-target="#text_2">Вывод</a>
                                    </div>



                                </div>


                            </div>
                            <div class="tabs-3">

                                <div class="tabs-3_background">
                                    <!--                                    <div class="tabs-3_title">-->
                                    <!--                                        <h5>Промо</h5>-->
                                    <!--                                    </div>-->

                                    <div class="tabs-3_description">
                                        <p>Добро пожаловать в официальный раздел промо-активностей компании ETFRIX. Здесь вы найдете специальные предложения, временные кампании и партнёрские инициативы, в рамках которых пользователи могут получать бонусные вознаграждения, преференции и дополнительные привилегии за участие в акциях.<p>

                                        <p>ETFRIX Promo — это не просто маркетинговый блок, а полноценный механизм стимулирования и лояльности, интегрированный в инвестиционную экосистему компании.<p>

                                        <p>В рамках этого раздела будут регулярно появляться:<p>

                                        <p>🔹 Сезонные бонус-программы<p>

                                        <p>🔹 Промо для новых и активных клиентов<p>

                                        <p>🔹 Партнёрские розыгрыши и кампании<p>

                                        <p>🔹 Временные условия с увеличенными доходностями<p>

                                        <p>🔹 Возможности получить внутренние токены, NFT, ранний доступ или процентные бонусы<p>

                                        <p>Участие в промо — это ваш шанс усилить доходность, тестировать новые инструменты платформы и получить дополнительные привилегии от ETFRIX.<p>
                                        <p><p>

                                        <p>Следите за обновлениями — предложения носят ограниченный по времени характер и предоставляются только зарегистрированным пользователям.</p>
                                    </div>
                                </div>


                                <?php $promos = getAllPromo(); ?>
                                <?php if (!empty($promos)): ?>
                                    <?php foreach ($promos as $promo):
                                        $title = $promo['news_title_ru'] ?? '';
                                        $image = $promo['image_path'] ?: '/img/photo-promo2.jpg';
                                        $markup = $promo['markup_ru'] ?: '';
                                        $rawText = $promo['raw_text_ru'] ?? '';
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

                                                    <?= $markup ?: $rawFallback ?: '<div class="tabs-3_rightDescription"><p class="promo-desc">Описание будет добавлено позже.</p></div>' ?>

                                                </div>

                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="tabs-3_promo">
                                        <div class="tabs-3_item">
                                            <div class="tabs-3_right" style="width: 100%">
                                                <div class="tabs-3_rightTitle">
                                                    <h5>Скоро появятся новые промо</h5>
                                                </div>
                                                <div class="tabs-3_rightDescription">
                                                    <p class="promo-desc">Мы готовим обновления раздела. Следите за новостями в админке.</p>
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
        /* ---- вывод team-баланса ---- */
        const btn = document.querySelector(
            '.tabs-2_item__button a[data-toggle][data-target="#text_2"]'
        );
        if (!btn) return;

        // упрощённый moneyFormat (замените на свою функцию)
        const moneyFormat = n => Number(n).toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

        btn.addEventListener('click', async e => {
            e.preventDefault();

            const modalEl  = document.getElementById('text_2');
            const titleEl  = modalEl.querySelector('#transferTitle');
            const msgEl    = modalEl.querySelector('#transferMsg');

            // блокируем кнопку, чтобы не жали дважды
            btn.classList.add('disabled');

            try {
                const res  = await fetch('/api/transfer_promo_balance', {
                    method: 'POST',
                    headers: {'X-Requested-With': 'XMLHttpRequest'}
                });
                const json = await res.json();

                if (json.success) {
                    titleEl.textContent = 'Перевод выполнен';
                    msgEl.textContent   = `Переведено ${moneyFormat(json.amount)} USDT на основной баланс`;
                } else if (json.error === 'nothing_to_transfer') {
                    titleEl.textContent = 'Нечего переводить';
                    msgEl.textContent   = 'Баланс команды равен 0';
                } else {
                    titleEl.textContent = 'Ошибка';
                    msgEl.textContent   = 'Что-то пошло не так. Попробуйте позже.';
                }
                // показываем модалку Bootstrap-ом
                $(modalEl).modal('show');

            } catch (err) {
                console.error(err);
                showToast('Сетевая ошибка', 3000);
            } finally {
                btn.classList.remove('disabled');
            }
        });

    });

</script>