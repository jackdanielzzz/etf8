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

                        <div class="tab-pane fade show active" id="v-pills-team" role="tabpanel" aria-labelledby="v-pills-team-tab">
                            <div class="tabs-2">

                                <div class="tabs-2_top">

                                    <div class="tabs-2_item">
                                        <?php
                                        /** @var $currentUser */
                                        $userInputs = moneyFormat(getAmountInputsByUserId($currentUser['uid']));
                                        ?>
                                        <h5>فريق</h5>
                                        <h4>شركاء: <?= $isVirtual ? $currentUser['v_total_partners'] + $recursiveReferralsCount : $recursiveReferralsCount  ?></h4>
                                        <p style="margin-bottom: 0px;margin-top: 18px;">الإيداع الفردي: <?= $userInputs ?> USDT</p>
                                        <p>مكافأة الإحالة من دخل الهيكل: <span><?= $currentUser['rating_bonus'] ?></span></p>
                                        <p style="margin-bottom: 0px">إجمالي المكتسبة: <?= moneyFormat($totalTeamAccrued) ?> USDT</p>
                                    </div>

                                    <div class="tabs-2_item">
                                        <span class="line-left"></span>
                                    </div>


                                    <div class="tabs-2_item">

                                        <h4>الدخل المتاح</h4>

                                        <div class="tabs-2_sum">
                                            <div class="tabs-2_simIcon">
                                                <i class="icon-teph"></i>
                                            </div>

                                            <div class="tabs-2_text">
                                                <p><?= moneyFormat($balanceTeam) ?></p>
                                                <span>Usdt</span>
                                            </div>
                                        </div>

                                        <div class="tabs-2_item__button">
                                            <a href="#" data-toggle="modal" data-target="#text_2">استدلال</a>
                                        </div>



                                    </div>

                                    <div class="totalClose d-none">
                                        <div class="totalClose-icon"><i class="icon-check2"></i></div>
                                        <div class="totalClose-text"><p>تهانينا ، لقد انتقلت إلى المستوى التالي <span id = "newLevel" >L1</span> وحصلت على <span id = "earnedUsdt" > 50</span> USDT</p></div>
                                        <div class="totalClose-button"><button type="button" class="js-deal-ok">موافق</button></div>
                                    </div>

                                </div>


                                <div class="tabs-2_center">

                                    <div class="tabs-2_centerTop">

                                        <div class="tabs-2_centerItem" >
                                            <p>المؤهلات الحالية: <span><?= $currentUser['rating'] ?></span></p>
                                        </div>

                                        <div class="tabs-2_bottomBlock">
                                            <div class="tabs-2_bottomItem">
                                                <p>الشركاء النشطون:</p>
                                                <span><?= $isVirtual ? $currentUser['v_active_partners'] + $activeReferralsCount : $activeReferralsCount ?></span>
                                            </div>

                                            <?php
                                            $teamDeposit = 0;
                                            foreach ($recursiveReferrals3LevelsForUser as $levelRefs) {
                                                foreach ($levelRefs as $ref) {
                                                    $teamDeposit += getAmountInputsByUserId($ref['uid']);
                                                }
                                            }

                                            $teamDepositFormatted = moneyFormat($teamDeposit);
                                            ?>

                                            <div class="tabs-2_bottomItem">
                                                <p>إيداع الفريق:</p>
                                                <span><?=  $isVirtual ? $currentUser['v_team_depo'] : $teamDepositFormatted ?> USDT</span>
                                            </div>

<!--                                            <div class="tabs-2_bottomItem">-->
<!--                                                --><?php
//                                                /** @var $currentUser */
//                                                $userInputs = moneyFormat(getAmountInputsByUserId($currentUser['uid']));
//                                                ?>
<!--                                                <p>الإيداع الفردي:</p>-->
<!--                                                <span>--><?//= $userInputs ?><!-- USDT</span>-->
<!--                                            </div>-->
                                        </div>

                                    </div>


                                    <div class="tabs-2_centerBottom">

                                        <div class="tabs-2_bottomTitle">
                                            <?php
                                                $nextLevel = getNextLevel($currentUser['rating']);
                                            ?>
                                            <h5>شروط التدريب المتقدم حتى: <span><?= $nextLevel['lvl'] ?></span></h5>

                                            <div class="tabs-2_bottomBlock">
                                                <div class="tabs-2_bottomItem">
                                                    <p>الشركاء النشطون:</p>
                                                    <span><?= $nextLevel['min_active_partners'] ?></span>
                                                </div>

                                                <div class="tabs-2_bottomItem">
                                                    <p>إيداع الفريق:</p>
                                                    <span><?= $nextLevel['total_deposit_usd'] ?> USDT</span>
                                                </div>

                                            </div>
                                        </div>

                                    </div>

                                    <div class="tabs-2__conditions">
                                        <div class="tabs-2_linkText">
                                            <p>رابط الإحالة الخاص بك:
                                                <a
                                                        href="<?='https://' . $_ENV['CLEAR_URL'] . '/referral?code=' . $currentUser['activation']?>"
                                                        class="js-copy-referral"
                                                        data-url="<?='https://' . $_ENV['CLEAR_URL'] . '/referral?code=' . $currentUser['activation']?>"
                                                >
                                                    <?='https://' . $_ENV['CLEAR_URL'] . '/referral?code=' . $currentUser['activation']?>
                                                </a>
                                            </p>
                                        </div>
                                        <p>رمز الإحالة الخاص بك: <?= $currentUser['activation'] ?></p>
                                    </div>

                                </div>

                                <div class="tabs-2_table">

                                    <div class="tabs-2_tableTop">
                                        <div class="tabs-2_table-title">
                                            <h5>إيداع الفريق</h5>
                                        </div>

                                        <div class="tabs-2_tableBlock">
                                            <div class="tabs-2_tableIcon">
                                                <i class="icon-wallet"></i>
                                            </div>

                                            <div class="tabs-2_tableText">
                                                <p><?=  $isVirtual ? $currentUser['v_team_depo'] : $teamDepositFormatted ?></p>
                                                <span>Usdt</span>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="tabs-2_team">
                                        <table>
                                            <thead>
                                            <tr>
                                                <th>سطر</th>
                                                <th>شريك</th>
                                                <th>أودع</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php


                                            if (! empty($recursiveReferrals3LevelsForUser)) {
                                                $level = 0;
                                                foreach ($recursiveReferrals3LevelsForUser as $referrals) {
                                                    $level++;
                                                    foreach ($referrals as $ref) {
                                                        $deposit = getAmountInputsByUserId($ref['uid']);
                                                        $contact = $ref['telegram'] ?? $ref['email'];
                                                        $name    = $ref['name']    ?? $ref['email'];
                                                        ?>
                                                        <tr>
                                                            <td><?= $level ?></td>
                                                            <td><?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?></td>
                                                            <td><?= $deposit ?> USDT</td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                            }
                                            ?>
                                            </tbody>
                                        </table>



                                    </div>
<!--                                    <div class="affiliate-table_arrow2"></div>-->


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

document.addEventListener('DOMContentLoaded', () => {
    const btn = document.querySelector(
        '.tabs-2_item__button a[data-toggle][data-target="#text_2"]'
    );
    if (!btn) return;

    const moneyFormat = n => Number(n).toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    btn.addEventListener('click', async e => {
        e.preventDefault();

        const modalEl  = document.getElementById('text_2');
        const titleEl  = modalEl.querySelector('#transferTitle');
        const msgEl    = modalEl.querySelector('#transferMsg');

        btn.classList.add('disabled');

        try {
            const res  = await fetch('/api/transfer_team_balance', {
                method: 'POST',
                headers: {'X-Requested-With': 'XMLHttpRequest'}
            });
            const json = await res.json();

            if (json.success) {
                titleEl.textContent = 'تتم الترجمة';
                msgEl.textContent   = `تم تحويل ${moneyFormat(json.amount)} USDT إلى الرصيد الرئيسي`;
            } else if (json.error === 'nothing_to_transfer') {
                titleEl.textContent = 'لا شيء للترجمة';
                msgEl.textContent   = 'رصيد الفريق هو 0';
            } else {
                titleEl.textContent = 'خطأ';
                msgEl.textContent   = 'حدث خطأ ما. حاول مرة أخرى لاحقا.';
            }
            $(modalEl).modal('show');

        } catch (err) {
            console.error(err);
            showToast('خطأ في الشبكة', 3000);
        } finally {
            btn.classList.remove('disabled');
        }
    });

});

</script>