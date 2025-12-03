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

                                    <h5>الترويجي</h5>
<!--                                    <h5>0 شركاء</h5>-->
                                    <br>
                                    <p>إجمالي المكتسبة: <?= moneyFormat($totalPromoAccrued) ?> USDT</p>

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
                                            <p><?= moneyFormat($balancePromo) ?></p>
                                            <span>Usdt</span>
                                        </div>
                                    </div>

                                    <div class="tabs-2_item__button">
                                        <a href="#" data-toggle="modal" data-target="#text_2">استدلال</a>
                                    </div>



                                </div>


                            </div>
                            <div class="tabs-3">

                                <div class="tabs-3_background">
<!--                                    <div class="tabs-3_title">-->
<!--                                        <h5>الترويجي</h5>-->
<!--                                    </div>-->

                                    <div class="tabs-3_description">
                                        <p>مرحبا بكم في القسم الرسمي للأنشطة الترويجية ETFRIX. ستجد هنا عروضا خاصة وحملات مؤقتة ومبادرات تابعة حيث يمكن للمستخدمين الحصول على مكافآت إضافية وتفضيلات وامتيازات إضافية للمشاركة في العروض الترويجية.<p>

                                        <p>ETFRIX الترويجي ليس مجرد كتلة تسويقية ، ولكنه آلية حوافز وولاء كاملة مدمجة في النظام البيئي الاستثماري للشركة.<p>

                                        <p>في هذا القسم ، سيظهر ما يلي بانتظام:<p>

                                        <p>🔹برامج المكافآت الموسمية<p>

                                        <p>🔹 العرض الترويجي للعملاء الجدد والنشطين<p>

                                        <p>🔹 الهدايا والحملات التابعة<p>

                                        <p>🔹 ظروف مؤقتة مع زيادة الغلة<p>

                                        <p>🔹 فرص للحصول على الرموز المميزة الداخلية أو NFTs أو الوصول المبكر أو المكافآت القائمة على النسبة المئوية<p>

                                        <p>المشاركة في العرض الترويجي هي فرصتك لزيادة الربحية واختبار أدوات النظام الأساسي الجديدة والحصول على امتيازات إضافية من ETFRIX.<p>
                                        <p><p>

                                        <p>ترقبوا - العروض محدودة الوقت ومتاحة فقط للمستخدمين المسجلين.</p>
                                    </div>
                                </div>

<!--                                <div class="tabs-3_promo">-->
<!--                                    <div class="tabs-3_item">-->
<!---->
<!--                                        <div class="tabs-3_left">-->
<!--                                            <div class="tabs-3_img">-->
<!--                                                <img src="/img/photo-promo.jpg" alt="">-->
<!--                                            </div>-->
<!--                                        </div>-->
<!---->
<!--                                        <div class="tabs-3_right">-->
<!---->
<!--                                            <div class="tabs-3_rightTitle">-->
<!--                                                <h5>الاسم الترويجي</h5>-->
<!--                                            </div>-->
<!---->
<!--                                            <div class="tabs-3_rightDescription">-->
<!--                                                <p>سيكون القسم نشطا طوال عمر المنصة ، وتعديل عروض وشروط المشاركة في البرامج المختلفة. يهدف هذا الاتجاه إلى زيادة الوعي بالعلامة التجارية ، وزيادة ولاء الشركاء ، ومكافأة المشاركين النشطين بمكافآت تحقق الدخل.</p>-->
<!--                                            </div>-->
<!---->
<!--                                        </div>-->
<!---->
<!--                                    </div>-->
<!--                                </div>-->

                                <div class="tabs-3_promo">
                                    <div class="tabs-3_item">

                                        <div class="tabs-3_left">
                                            <div class="tabs-3_img">
                                                <img src="/img/photo-promo2.jpg" alt="Promo METAVERSE">
                                            </div>
                                        </div>

                                        <div class="tabs-3_right">

                                            <div class="tabs-3_rightTitle">
                                                <h5>METAVERSE</h5>
                                            </div>

                                            <div class="tabs-3_rightDescription">
                                                <p class="promo-intro">
                                                    <strong>ETFRIX</strong> تطلق <strong>عرضا ترويجيا جديدا - METAVERSE</strong>
                                                </p>
                                                <p class="promo-period">
                                                    <em>الفترة:</em> يوليو 24&nbsp;—&nbsp;أغسطس 24&nbsp;, 2025
                                                </p>
                                                <p class="promo-desc">
                                                    METAVERSE هي فرصة لتلقي مكافآت مقابل الإجراءات البسيطة والمشاركة الواضحة في تطوير ETFRIX.
                                                </p>

                                                <br>
                                                <h6>شروط المشاركة:</h6>
                                                <ul class="promo-desc">
                                                    <li class="promo-desc">انتقل إلى Instagram الرسمي ل ETFRIX:
                                                        <a href="https://instagram.com/etfrix.ltd" target="_blank" rel="noopener">instagram.com/etfrix.ltd</a>
                                                    </li>
                                                    <li>البحث عن ومشاهدة الفيديو الترويجي ل METAVERSE</li>
                                                    <li>الاشتراك في حساب</li>
                                                    <li>أعد نشر الفيديو في قصتك على Instagram وقم بوضع علامة على @etfrix.ltd
                                                        <br>أو إعادة النشر في سجل Telegram
                                                    </li>
                                                    <li>قم بدعوة شريكين لتنشيط الحد الأدنى من تكوينات RIX DEX الذكاء الاصطناعي</li>
                                                </ul>

                                                <p class="promo-bonus">
                                                    حصل <strong>50 دولار أمريكي</strong> مكافاه.
                                                    <br>لا يوجد حد - لكل شريكين إضافيين مدعوين - <strong>50 دولارا أمريكيا آخر</strong>.
                                                </p>

                                                <p class="promo-note">
                                                    يجب إكمال جميع الإجراءات خلال الفترة الترويجية. المشاركة متاحة لجميع مستخدمي المنصة.
                                                </p>
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
                const res  = await fetch('/api/transfer_promo_balance', {
                    method: 'POST',
                    headers: {'X-Requested-With': 'XMLHttpRequest'}
                });
                const json = await res.json();

                if (json.success) {
                    titleEl.textContent = 'تتم الترجمة';
                    msgEl.textContent   = `تمت الترجمة ${moneyFormat(json.amount)} USDT إلى الرصيد الرئيسي`;
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