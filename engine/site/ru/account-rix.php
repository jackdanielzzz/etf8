<?php

require_once 'blocks/modalsCab.php';

/** @var array $currentUser */

$myNfts = getUserNfts($currentUser['uid']);
$nftLibrary = getNftLibrary();

$rarityConfig = [
    'diamond' => ['title' => 'BRILLIANT COLLECTION', 'class' => 'title-brilliant'],
    'gold'    => ['title' => 'GOLD COLLECTION', 'class' => 'title-gold'],
    'silver'  => ['title' => 'SILVER COLLECTION', 'class' => 'title-silver'],
    'bronze'  => ['title' => 'BRONZE COLLECTION', 'class' => 'title-bronze'],
];

$libraryByRarity = array_fill_keys(array_keys($rarityConfig), []);
foreach ($nftLibrary as $libraryItem) {
    $rarity = $libraryItem['rarity'] ?? '';
    if (!isset($libraryByRarity[$rarity])) {
        continue;
    }

    $libraryByRarity[$rarity][] = $libraryItem;
}

$ownedNfts = [];
foreach ($myNfts as $nft) {
    $libraryId = (int)($nft['library_id'] ?? 0);

    if (!isset($ownedNfts[$libraryId])) {
        $ownedNfts[$libraryId] = [
            'count' => 0,
            'name' => $nft['name'] ?? '',
            'image_path' => $nft['image_path'] ?? '',
            'description' => $nft['description_ru'] ?? '',
            'price' => $nft['price'] ?? 0,
        ];
    }

    $ownedNfts[$libraryId]['count']++;
}

$roulettePrizes = [];
if (!empty($currentUser['uid'])) {
    $roulettePrizes = getRoulettePrizesByUserId((int)$currentUser['uid']);
}

?>

<div class="page-content">

    <div class="figure-15"></div>

    <div class="content">
        <div class="tabs">

            <div class="tabs-content">

                <div class="d-flex align-items-start tabs-mobile">

                    <?php require_once 'blocks/leftTabCab.php'; ?>

                    <div class="tab-content" id="v-pills-tabContent">

                        <div class="tab-pane fade show active" id="v-pills-collection" role="tabpanel" aria-labelledby="v-pills-collection-tab">
                            <div class="tabs-4">

                                <div class="tabs-4_top">
                                    <div class="tabs-4_topTitle">
                                        <h3>RIX Collection</h3>
                                    </div>

                                    <div class="tabs-4_topDescription">
                                        <p>
                                            <strong>RIX Collection</strong> — это эксклюзивный раздел инвестиционной платформы <strong>ETFRIX</strong>, в котором игровые механики интегрированы в финансовую экосистему. Участники получают доступ к интерактивным возможностям, бонусам и цифровым активам.
                                        </p>

                                        <h5>RIX Roulette</h5>
                                        <p>
                                            Интерактивная рулетка с ограниченным доступом, позволяющая пользователям получить:
                                        </p>
                                        <ul>
                                            <li>Денежные бонусы в криптовалютах и фиатных эквивалентах</li>
                                            <li>Уникальные NFT-трофеи от ETFRIX</li>
                                            <li>Повышенные процентные условия по сделкам</li>
                                            <li>Доступ к закрытым инвестиционным стратегиям</li>
                                        </ul>
                                        <p>
                                            Рулетка работает на основе алгоритмического выбора и используется для повышения лояльности и вовлечённости пользователей.
                                        </p>

                                        <h3>NFT-коллекция RIX</h3>
                                        <p>
                                            Внутри раздела доступна коллекция цифровых активов, созданная эксклюзивно для участников ETFRIX. NFT можно:
                                        </p>
                                        <ul>
                                            <li>Получить через систему розыгрыша (RIX Roulette)</li>
                                            <li>Коллекционировать и использовать как подтверждение достижений</li>
                                            <li>Активировать для получения специальных привилегий</li>
                                            <li>Использовать в качестве маркеров статуса в системе</li>
                                        </ul>
                                        <p>
                                            Каждая NFT является лимитированной и фиксируется в личном кабинете пользователя.
                                        </p>

                                        <h3>Функциональная роль</h3>
                                        <p>
                                            Раздел <strong>RIX Collection</strong> — не просто развлекательный элемент. Он является частью стратегической модели построения активного сообщества, где каждый участник получает доступ к уникальным инструментам и усиливает своё положение внутри инвестиционной экосистемы ETFRIX.
                                        </p>

                                        <p>
                                            <strong>Приглашая партнеров по вашей Roulette link вы предоставляете новым партнерам возможность словить удачу сыграв в RIX Roulette 3 раза после чего ваши партнеры смогут пройти регистрации забрав выигранные призы, так же вы получаете 3 бесплатные вращенимя за каждого партнера который зарегистрируется по Вашей Roulette link. Ниже вы найдете вашу персональную Roulette link, а так же кнопку перехода к RIX Roulette.</strong>
                                        </p>
                                    </div>

                                    <div class="accountBar-state_item" style="margin-top: 20px">
                                        <div class="accountBar-item_icon">
                                            <i class="icon-state6"></i>
                                        </div>

                                        <div class="accountBar-item_text">
                                            <p>RIX Referral Spin Link</p>
                                            <a
                                                    href="<?='https://' . 'roulette.' . $_ENV['CLEAR_URL'] . '/referral?code=' . $currentUser['activation']?>"
                                                    class="js-copy-referral"
                                                    data-url="<?='https://' . 'roulette.' . $_ENV['CLEAR_URL'] . '/referral?code=' . $currentUser['activation']?>"
                                            >
                                                <?='https://' . 'roulette.' . $_ENV['CLEAR_URL'] . '/referral?code..'?>
                                            </a>
                                        </div>
                                    </div>

                                        <div class="tabs-4_topButton">
                                            <a href="<?='https://' . 'roulette.' . $_ENV['CLEAR_URL'] . '/game?auth-user=' . $currentUser['activation']?>">RIX Roulette</a>
                                        </div>
                                </div>

                                <div class="tabs-4_collection">

                                    <?php foreach ($rarityConfig as $rarity => $config): ?>
                                        <div class="tabs-4_collectionItem">

                                            <div class="tabs-4_collectionTitle <?= $config['class']; ?>">
                                                <h5><?= $config['title']; ?></h5>
                                            </div>

                                            <div class="tabs-4_collectionBlock">
                                                <?php foreach ($libraryByRarity[$rarity] as $libraryItem):
                                                    $libraryId = (int)($libraryItem['id'] ?? 0);
                                                    $owned = $ownedNfts[$libraryId] ?? null;
                                                    $hasItem = $owned && $owned['count'] > 0;
                                                    $itemName = $owned['name'] ?? ($libraryItem['name'] ?? '');
                                                    $itemImage = $hasItem ? ($owned['image_path'] ?? $libraryItem['image_path'] ?? '/img/collection-img.png') : '/img/collection-img.png';
                                                    $itemDescription = $owned['description'] ?? ($libraryItem['description_ru'] ?? '');
                                                    $itemPrice = $owned['price'] ?? ($libraryItem['price'] ?? 0);
                                                    $itemCount = $owned['count'] ?? 0;
                                                    ?>
                                                    <div class="tabs-4_collectionBlockItem<?= $hasItem ? '' : ' tabs-4_collectionBlockItem_empty'; ?>">
                                                        <?php if ($hasItem): ?>
                                                            <a href="<?= htmlspecialchars($itemImage, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>" rel="lightbox-j" tabindex="0">
                                                                <img src="<?= htmlspecialchars($itemImage, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>" alt="<?= htmlspecialchars($itemName, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>">
                                                            </a>

                                                            <div class="modal-body__accordion">

                                                                <div class="accordion" >
                                                                    <div class="card">
                                                                        <div class="card-header">
                                                                            <a
                                                                                    href="#"
                                                                                    class="js-nft-description"
                                                                                    data-toggle="modal"
                                                                                    data-target="#description"
                                                                                    data-name="<?= htmlspecialchars($itemName, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>"
                                                                                    data-description="<?= htmlspecialchars((string)$itemDescription, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>"
                                                                            >Описание</a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="tabs-4_collectionBlock__button">
                                                                <a
                                                                        href="#"
                                                                        class="js-nft-sell"
                                                                        data-toggle="modal"
                                                                        data-target="#description_open"
                                                                        data-name="<?= htmlspecialchars($itemName, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>"
                                                                        data-price="<?= htmlspecialchars((string)$itemPrice, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>"
                                                                        data-count="<?= htmlspecialchars((string)$itemCount, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>"
                                                                        data-library-id="<?= $libraryId; ?>"
                                                                >Подробнее</a>
                                                            </div>
                                                        <?php else: ?>
                                                            <img src="/img/collection-img.png" alt="">
                                                        <?php endif; ?>

                                                    </div>
                                                <?php endforeach; ?>
                                            </div>

                                        </div>
                                    <?php endforeach; ?>

                                </div>

                                <div class="tabs-4_table">

                                    <div class="tabs-4_tableTitle">
                                        <h5>
                                            Мои награды RIX Roulette
                                        </h5>

                                        <table>
                                            <thead>
                                            <tr>
                                                <th>Дата</th>
                                                <th>Награда</th>
                                                <th>Статус</th>
                                            </tr>
                                            </thead>

                                            <tbody>
                                            <?php if (empty($roulettePrizes)): ?>
                                                <tr>
                                                    <td colspan="3" class="text-center">У вас пока нет наград.</td>
                                                </tr>
                                            <?php else:
                                                foreach ($roulettePrizes as $row):

                                                    $ts = strtotime((string)$row['created_at']);
                                                    $date = $ts ? date('d.m.Y H:i', $ts) : '';
                                                    $rawName = $row['item_name'] ?? $row['prize_token'] ?? '';
                                                    $name = htmlspecialchars($rawName, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                                                    ?>
                                                    <tr>
                                                        <td><?= $date ?></td>
                                                        <td><?= $name ?></td>
                                                        <td>Зачислено</td>
                                                    </tr>
                                                <?php endforeach;
                                            endif; ?>
                                            </tbody>
                                        </table>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div>


</div>

<script>
    (function () {
        const descriptionModal = document.getElementById('description');
        const descriptionTitle = descriptionModal ? descriptionModal.querySelector('.js-nft-modal-title') : null;
        const descriptionBody = descriptionModal ? descriptionModal.querySelector('.js-nft-modal-description') : null;

        const saleModal = document.getElementById('description_open');
        const saleTitle = saleModal ? saleModal.querySelector('.js-sale-title') : null;
        const salePrice = saleModal ? saleModal.querySelector('.js-sale-price') : null;
        const saleCount = saleModal ? saleModal.querySelector('.js-sale-count') : null;
        const saleUnitLabel = saleModal ? saleModal.querySelector('.js-sale-unit-label') : null;
        const saleTotal = saleModal ? saleModal.querySelector('.js-sale-total') : null;
        const saleMessage = saleModal ? saleModal.querySelector('.js-sale-message') : null;
        const saleInput = saleModal ? saleModal.querySelector('#topUpAmountSale') : null;
        const saleForm = saleModal ? saleModal.querySelector('.js-sale-form') : null;
        const saleSubmit = saleModal ? saleModal.querySelector('#saleSubmit') : null;

        let currentPrice = 0;
        let currentCount = 0;
        let currentLibraryId = null;
        let currentTrigger = null;

        function getUnitLabel(count) {
            const value = Math.abs(count) % 100;
            const lastDigit = value % 10;

            if (value > 10 && value < 20) {
                return 'юнитов';
            }

            if (lastDigit > 1 && lastDigit < 5) {
                return 'юнита';
            }

            if (lastDigit === 1) {
                return 'юнит';
            }

            return 'юнитов';
        }

        function recalcSaleTotal() {
            if (!saleTotal || !saleInput) {
                return;
            }

            const qty = parseFloat(saleInput.value.replace(',', '.')) || 0;
            const total = qty * currentPrice;
            saleTotal.textContent = total ? total.toFixed(3) : '0';
        }

        function setSaleMessage(text, status = 'error') {
            if (!saleMessage) {
                return;
            }

            saleMessage.textContent = text || '';
            saleMessage.hidden = !text;
            saleMessage.dataset.status = status;
        }

        function toggleSaleButton() {
            if (!saleSubmit || !saleInput) {
                return;
            }

            const qty = parseInt(saleInput.value || '0', 10) || 0;
            const isValidQty = qty >= 10 && qty <= currentCount;
            const canSell = currentCount >= 10 && isValidQty && currentLibraryId !== null;

            saleSubmit.disabled = !canSell;
            saleSubmit.classList.toggle('disabled', !canSell);
            saleSubmit.setAttribute('aria-disabled', canSell ? 'false' : 'true');

            if (!currentLibraryId) {
                setSaleMessage('Не удалось определить NFT для продажи. Попробуйте открыть карточку заново.');
                return;
            }

            if (currentCount < 10) {
                setSaleMessage('Продажа доступна, когда в коллекции от 10 юнитов.');
                return;
            }

            if (qty < 10) {
                setSaleMessage('Минимальное количество для продажи — 10 юнитов.');
                return;
            }

            if (qty > currentCount) {
                setSaleMessage('В коллекции нет такого количества юнитов.');
                return;
            }

            setSaleMessage('');
        }

        function refreshSaleState() {
            recalcSaleTotal();
            toggleSaleButton();
        }

        document.querySelectorAll('.js-nft-description').forEach(function (trigger) {
            trigger.addEventListener('click', function (event) {
                event.preventDefault();

                if (descriptionTitle) {
                    descriptionTitle.textContent = trigger.dataset.name || '';
                }

                if (descriptionBody) {
                    descriptionBody.innerHTML = trigger.dataset.description || '';
                }

                if (window.jQuery) {
                    window.jQuery('#description').modal('show');
                }
            });
        });

        document.querySelectorAll('.js-nft-sell').forEach(function (trigger) {
            trigger.addEventListener('click', function (event) {
                event.preventDefault();

                currentTrigger = trigger;
                currentPrice = parseFloat(trigger.dataset.price || '0') || 0;
                const count = parseInt(trigger.dataset.count || '0', 10) || 0;
                currentCount = count;
                currentLibraryId = trigger.dataset.libraryId ? parseInt(trigger.dataset.libraryId, 10) : null;

                if (saleTitle) {
                    saleTitle.textContent = trigger.dataset.name || '';
                }

                if (salePrice) {
                    salePrice.textContent = currentPrice ? currentPrice.toFixed(3) : '0';
                }

                if (saleUnitLabel) {
                    saleUnitLabel.textContent = getUnitLabel(count);
                }

                if (saleCount) {
                    saleCount.textContent = count;
                }

                if (saleInput) {
                    saleInput.value = '0';
                    const defaultQty = count >= 10 ? 10 : 0;
                    saleInput.value = String(defaultQty);
                    saleInput.setAttribute('min', '10');
                    saleInput.setAttribute('max', String(count));
                }

                refreshSaleState();

                if (window.jQuery) {
                    window.jQuery('#description_open').modal('show');
                }
            });
        });

        async function handleSaleSubmit(event) {
            event.preventDefault();

            if (!saleInput || !saleSubmit) {
                return;
            }

            const qty = parseInt(saleInput.value || '0', 10) || 0;

            if (!currentLibraryId || qty < 10 || qty > currentCount) {
                toggleSaleButton();
                return;
            }

            saleSubmit.disabled = true;
            saleSubmit.classList.add('disabled');
            setSaleMessage('');

            try {
                const response = await fetch('/api/sell-nft', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        library_id: currentLibraryId,
                        quantity: qty,
                    }),
                });

                const data = await response.json().catch(() => null);

                if (!response.ok || !data || !data.ok) {
                    throw new Error((data && (data.message || data.error)) || 'Не удалось продать NFT');
                }

                currentCount = Math.max(0, currentCount - qty);

                if (saleCount) {
                    saleCount.textContent = currentCount;
                }

                if (saleUnitLabel) {
                    saleUnitLabel.textContent = getUnitLabel(currentCount);
                }

                if (currentTrigger) {
                    currentTrigger.dataset.count = String(currentCount);
                }

                recalcSaleTotal();
                toggleSaleButton();

                if (window.jQuery) {
                    window.jQuery('#description_open').modal('hide');
                    window.jQuery('#sale').modal('show');
                }

                if (typeof showToast === 'function') {
                    showToast('Карточки успешно проданы', 3000);
                }
            } catch (e) {
                const errorText = e.message || 'Не удалось продать NFT';

                if (typeof showToast === 'function') {
                    showToast(errorText, 3000);
                }

                setSaleMessage(errorText);
            } finally {
                toggleSaleButton();
            }
        }

        if (saleInput) {
            saleInput.addEventListener('input', refreshSaleState);
        }

        if (saleForm) {
            saleForm.addEventListener('submit', handleSaleSubmit);
        }

    })();
</script>
<script>
    const rouletteLink = document.getElementById('rixRoulette');
    if (rouletteLink) {
        rouletteLink.addEventListener('click', function(event) {
            event.preventDefault();
            showToast('Функция будет недоступна немного позже', 3000);
        });
    }
</script>