<div class="page-footer">
    <footer class="footerAccount">
        <div class="content">

            <div class="footerTop">
                <div class="footerTop-left">
                    <div class="footerTop-left_block">
                        <div class="footerTop-left_logo">
                            <a href="/ar" class="logo"></a>
                        </div>

                        <div class="footerTop-left_language language-accordion">

                            <select name="currency-2" id="currency-5" class="form-control select-single-2">
                                <option value="en">EN</option>
                                <option value="ru">RU</option>
                                <option value="cn">CH</option>
                                <option value="ar">AR</option>
                            </select>

                        </div>
                    </div>
                </div>

                <div class="footerTop-right">
                    <div class="footerTop-right_title">
                        <p>دعم:</p>
                    </div>

                    <div class="footerTop-right_block">

                        <a href="https://t.me/<?= $_ENV['TG_SUPPORT'] ?>" class="footerTop-right_item">
                            <i class="icon-tg"></i>
                        </a>

                        <a href="mailto:<?= $_ENV['SUPPORT_EMAIL'] ?>" class="footerTop-right_item">
                            <i class="icon-mail"></i>
                        </a>
                    </div>
                </div>
            </div>



        </div>


        <div class="copyright">
            <div class="content">

                <div class="copyright-content">
                    <div class="copyright-text">
                        <p>© ETFRIX All Rights Reserved</p>
                    </div>

                    <div class="copyright-link">
                        <a href="#">سياسة الخصوصية</a>
                    </div>
                </div>


            </div>
        </div>
    </footer>
</div>
</div>
</body>
<script>
    window.DEALS   = <?= json_encode($dealsByRegion,   JSON_UNESCAPED_UNICODE|JSON_HEX_TAG) ?>;
    window.DEAL_REQUESTS = <?= json_encode($dealRequests, JSON_UNESCAPED_UNICODE|JSON_HEX_TAG) ?>;
    window.BALANCE = <?= json_encode($balance, JSON_HEX_TAG) ?>;
    window.WALLET_STATS = <?= json_encode(
        $stats,
        JSON_UNESCAPED_UNICODE
        | JSON_HEX_TAG
        | JSON_PARTIAL_OUTPUT_ON_ERROR
    ); ?>;

    const chartData = <?= json_encode($chartData, JSON_NUMERIC_CHECK); ?>;
    const chartDataTeam  = <?= json_encode($chartDataTeam , JSON_NUMERIC_CHECK) ?>;
    window.APPROVE_PIE = {
        team   : <?= $teamPie   ?>,
        promo  : <?= $promoPie  ?>,
        deals : <?= $dealsPie ?>
    };

    function showToast(message, duration = 2000) {
        const toast = document.createElement('div');
        toast.className = 'toast';
        toast.textContent = message;
        document.body.appendChild(toast);

        requestAnimationFrame(() => toast.classList.add('show'));

        setTimeout(() => {
            toast.classList.remove('show');
            toast.addEventListener('transitionend', () => toast.remove(), { once: true });
        }, duration);
    }


    document.querySelectorAll('.js-copy-referral').forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();
            const url = link.dataset.url;
            navigator.clipboard.writeText(url)
                .then(() => showToast('تم نسخ الرابط إلى الحافظة!'))
                .catch(err => {
                    console.error(err);
                    showToast('فشل النسخ', 3000);
                });
        });
    });

    document.getElementById('transferContinue').addEventListener('click', e => {
        e.preventDefault();
        location.reload();
    });


    const closeBtn = document.querySelector('#text_2 .modal-close button.close');
    if (closeBtn) {
        closeBtn.addEventListener('click', e => {
            location.reload();
        });
    }

    $(function() {
        let $btnWrapper  = $('#switchInactiveDeals');
        let $btn         = $btnWrapper.find('a');
        let $closedDeals = $('.totalDeal.closed');

        if ($closedDeals.length > 0) {
            $btnWrapper.removeClass('d-none');
        }

        $btn.on('click', function(e) {
            e.preventDefault();
            if ($closedDeals.hasClass('d-none')) {
                $closedDeals.removeClass('d-none');
                $btn.text('إخفاء الصفقات غير النشطة');
            } else {
                $closedDeals.addClass('d-none');
                $btn.text('عرض الصفقات غير النشطة');
            }
        });
    });

    $(function() {

        $(document).on('click', '.js-copy', function() {
            const address = $(this).text().trim();
            alert("aa")


            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(address)
                    .then(() => {
                        $('#info').text('تم نسخه إلى الحافظة!');
                    })
                    .catch(err => {
                        console.error('نسخ إلى خطأ المخزن المؤقت:', err);
                    });
            } else {

                const $tmp = $('<textarea>');
                $('body').append($tmp);
                $tmp.val(address).select();
                try {
                    document.execCommand('copy');
                    $('#info').text('تم نسخه إلى الحافظة!');
                } catch (err) {
                    console.error('خطأ في النسخ (احتياطي):', err);
                }
                $tmp.remove();
            }
        });
    });

    $(function() {

        const $amountInput   = $('#topUpAmount');
        const $continueBtn   = $('#continueBtn');

        function validateAmount() {
            const v = $amountInput.val().replace(',', '.').trim();
            const ok = v !== '' && !isNaN(+v) && +v > 0;
            $continueBtn.prop('disabled', !ok).toggleClass('disabled', !ok);
            return ok;
        }

        $amountInput.on('input', validateAmount);


        function fillSecondModal () {
            const usd = parseFloat($amountInput.val().replace(',', '.'));
            const $o  = $('#depositMethod option:selected');

            const fullName = $o.data('full-name');
            const ticker   = $o.data('symbol');
            const address  = $o.data('address') || '';
            const price    = parseFloat($o.data('price')) || 0;


            const coins = price ? usd / price : 0;

            /* -- пишем во второй модал -- */
            $('#topUpTitle').text('تجديد موارد ' + ticker);
            $('#topUpAmountCoins').text(coins.toFixed(6));
            $('#topUpCoinTicker').text(ticker);

            /
            $('#topUpAmountUsdView').length && $('#topUpAmountUsdView').text(usd.toFixed(2));

            $('#walletAddress')
                .attr('data-url', address)
                .text(address || '—');

            // подсказка про сеть
            const net = (/ERC-20|TRC-20|Solana|TON/i.exec(fullName) || [''])[0];
            $('#payToThisAddress').text(`Пополните данный адрес ${ticker}`);
            $('#networkHint').text(
                net ? `تأكد من أن الأموال التي ترسلها متصلة بالإنترنت ${net}` : ''
            );
        }


        $continueBtn.on('click', function(e) {
            if (!validateAmount()) {
                e.preventDefault();
                $amountInput.addClass('is-invalid');
                return;
            }
            fillSecondModal();

            $('#replenish').modal('hide');
            $('#replenishContinue').modal('show');
        });


        $('#replenishContinue').on('shown.bs.modal', function() {
            const amount = parseFloat(($amountInput.val() || '').replace(',', '.')) || 0;

            const method =
                ($('input[name="method"]:checked').val() ||
                    $('#depositMethod').val() ||
                    (window.currentCfg && currentCfg.method) ||
                    'unknown');

            $.post('/api/process_statistics', {
                amount_usd: amount,
                method: method
            }).fail(function() {
                console.warn('فشل إرسال إحصائيات عرض المحفظة');
            });
        });



        const $hashInput    = $('#txHash');
        const $finalizeBtn  = $('#finalizeBtn');

        function validateHash() {
            const len = $hashInput.val().trim().length;
            const ok  = len >= 20;
            $finalizeBtn.prop('disabled', !ok).toggleClass('disabled', !ok);
            return ok;
        }

        $hashInput.on('input', validateHash);


        $finalizeBtn.on('click', function (e) {
            e.preventDefault();

            if (!validateHash()) {
                $('#txHash').addClass('is-invalid');
                return;
            }


            $('#replenishContinue').modal('hide');
            $('#processing').modal('show');

            /* ---------- собираем данные ---------- */
            const amountUsd = parseFloat($('#topUpAmount').val().replace(',', '.'));
            const amountCrypto = $('#topUpAmountCoins').text();
            const method       = $('#depositMethod').val() || 'USDT-TRC--20';
            const txHash       = $('#txHash').val().trim();

            $.ajax({
                url : '/api/process_input',
                type: 'POST',
                dataType: 'json',
                data: {
                    amount_usd: amountUsd,
                    amount_crypto: amountCrypto,
                    method:        method,
                    tx_hash:       txHash
                }
            })
                .done(function (res) {
                    if (res.status === 'success') {
                        $('#processing').modal('hide');
                        $('#replenishSuccessModal').modal('show');

                    } else {
                        alert('Ошибка: ' + (res.message || 'unknown'));
                    }
                })
                .fail(function (xhr) {
                    alert('Сервер: ' + xhr.status + ' – ' + xhr.responseText);
                })
                .always(function () {
                    $finalizeBtn.prop('disabled', false).text('تجديد');
                });


            $finalizeBtn.prop('disabled', true).text('أشخص…');
        });

    });



    $(document).on('click', '.btn-continue_1', function (e) {
        e.preventDefault();

        const $modal   = $(this).closest('.modal');
        const wallet   = $modal.find('#address-conclusion').val().trim();
        const amount   = parseFloat($modal.find('#sum-withdrawal').val());
        const method   = $(this).data('method');                  // "USDT TRC-20"


        if (!wallet) {
            alert('أدخل عنوان محفظتك');
            return;
        }
        if (isNaN(amount) || amount <= 0) {
            alert('أدخل المبلغ الصحيح');
            return;
        }


        $.post('/api/process_output', { wallet, amount, method }, function (res) {
            // $('#processing').modal('hide');

            if (res.status === 'success') {
                $modal.modal('hide'); ;
                $('#processing').modal('show');
            } else {
                alert('خطأ: ' + (res.message || 'مجهول'));
                $modal.show();
            }
        }, 'json')
            .fail(function (xhr) {
                $('#processing').modal('hide');
                alert('خطأ في الخادم: ' + xhr.status);
                console.log(xhr)
                $modal.show();
            });
    });


    $(document).on('submit', '#profileForm', function (e) {
        e.preventDefault();

        const $form = $(this);
        const $btn  = $form.find('.profile-button');

        $btn.prop('disabled', true).text('حفظ…');

        $.post('/api/update-profile', $form.serialize(), function (res) {
            if (res.ok) {
                window.location.reload();
            }

        }, 'json')
            .fail(xhr => alert('Сервер: ' + xhr.status + ' – ' + xhr.responseText))
            .always(() => $btn.prop('disabled', false).text('أنقذ'));
    });

</script>
</html>