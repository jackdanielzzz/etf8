require('./block/sideBar');
require('./block/password');
require('./block/slider');
require('./block/downloadInput');
require('./block/rolling');
require('./block/slider');
require('./block/video');
require('./block/accordion');
require('./block/lightbox');
require('./block/preloader');
require('./block/charts');
require('./block/select');

let currentRegionId = null;   // будем хранить выбранный регион
let currentCfg      = null;
const dealRequests  = window.DEAL_REQUESTS || {};

/* ===================== i18n helpers ===================== */
function getLangFromCookie() {
    const m = document.cookie.match(/(?:^|;\s*)lang=([^;]+)/);
    return (m ? decodeURIComponent(m[1]) : 'en').toLowerCase();
}
// RU множественное
function ruPluralDay(n){
    const n10 = n % 10, n100 = n % 100;
    if (n10 === 1 && n100 !== 11) return 'день';
    if (n10 >= 2 && n10 <= 4 && (n100 < 12 || n100 > 14)) return 'дня';
    return 'дней';
}
// Лейбл "день/дней/day/天/يوم"
function dayLabel(n, lang){
    switch (lang) {
        case 'ru': return ruPluralDay(n);
        case 'cn': return '天';
        case 'ar': return n === 1 ? 'يوم' : 'أيام';
        default:   return n === 1 ? 'day' : 'days';
    }
}
// Хвост для варианта со слэшем: " % / день"
function perSlash(lang){
    switch (lang) {
        case 'ru': return ' / день';
        case 'cn': return ' / 天';
        case 'ar': return ' / يوم';
        default:   return ' / day';
    }
}
// Хвост для обычного варианта: " % в день"
function perWord(lang){
    switch (lang) {
        case 'ru': return ' в день';
        case 'cn': return ' 每天';   // пример: "12.5 % 每天"
        case 'ar': return ' يوميًا'; // مثال: "٪12.5 يوميًا"
        default:   return ' per day';
    }
}

function requestPendingLabel(lang) {
    switch (lang) {
        case 'en': return 'REQUEST SUBMITTED';
        case 'cn': return '已提交申请';
        case 'ar': return 'تم إرسال الطلب';
        default:   return 'ЗАЯВКА ОТПРАВЛЕНА';
    }
}

function applyRequestState($modal, requiresRequest, requestStatus) {
    const $invest = $modal.find('.js-invest-btn');
    const $request = $modal.find('.js-request-btn');
    const lang = getLangFromCookie();

    const defaultText = $request.data('default-text') || $request.text();
    $request.data('default-text', defaultText);

    if (!requiresRequest) {
        $request.addClass('d-none').prop('disabled', true).text(defaultText);
        $invest.removeClass('d-none').prop('disabled', false);
        return;
    }

    // Заявка одобрена (1)
    const isApproved = requestStatus === 1;
    // Заявка ожидает (0)
    const isPending  = requestStatus === 0;
    // Заявки нет (undefined) или она отклонена (любое другое число, кроме 0 и 1)
    const isDefault = !isApproved && !isPending;

    // ВАЖНО: Мы будем использовать isPending и isDefault для управления UI

    if (isApproved) {
        // Статус 1: Заявка одобрена. Прячем кнопку заявки, показываем кнопку инвестирования.
        $request.addClass('d-none').prop('disabled', true).text(defaultText);
        $invest.removeClass('d-none');

    } else {
        // Статус 0 (Pending) или undefined (Нет заявки/Отклонена)
        const label = isPending ? requestPendingLabel(lang) : defaultText;
        const activeDealId = currentCfg && currentCfg.deal_id ? currentCfg.deal_id : $request.data('deal-id');

        $request
            .removeClass('d-none')
            .toggleClass('disabled', isPending) // disabled только если isPending (0)
            .data('deal-id', activeDealId)
            .text(label)
            .prop('disabled', isPending);       // prop('disabled', true) только если isPending (0)

        // Кнопка инвестирования должна быть скрыта и неактивна, если статус не 1.
        $invest.addClass('d-none').prop('disabled', true);
    }
}
/* ======================================================== */

// Держим body заблокированным при переключении модалок
$(document).on('hidden.bs.modal', '.modal', function () {
    if ($('.modal.show').length) {
        $('body').addClass('modal-open');
    }
});

// Аккуратная работа со стеком модалок (если открываются подряд)
$(document).on('show.bs.modal', '.modal', function () {
    const z = 1040 + 10 * $('.modal:visible').length; // 1040 — как у backdrop/твоего blur
    $(this).css('z-index', z);
    setTimeout(() => {
        $('.modal-backdrop').not('.modal-stack')
            .css('z-index', z - 1)
            .addClass('modal-stack');
    }, 0);
});

function recalcRevenue($modal) {
    const cfg = $modal.find('.js-invest-btn').data('deal-cfg');
    const sum = parseFloat( $modal.find('.onlyDigits').val() );
    if (!cfg || !Number.isFinite(sum)) {
        $modal.find('#dealRevenue').text('—');
    } else {
        // здесь rate_without_RIX — число типа 0.35 (35%)
        const dailyRate = cfg.rate_without_RIX / 100;   // 0.0035
        const revenue   = sum * dailyRate * cfg.term_days;  // прибыль
        // если нужно вернуть ещё и тело вклада:
        const totalPayout = sum + revenue;
        $modal.find('#dealRevenue')
            .text(revenue.toLocaleString('ru-RU', { minimumFractionDigits: 2 })
                .replace(',', '.') + ' USDT'
            );
    }
}

$(function () {
    // при первом открытии модалки сразу выставляем баланс
    $('#userBalance').text(Number(window.BALANCE)
        .toLocaleString('ru-RU', { minimumFractionDigits: 1, maximumFractionDigits: 1 })
        .replace(',', '.'));

    // выбор региона (как и раньше)
    $('.js-region').on('click', function () {
        currentRegionId = $(this).data('region-id');

        const rCfg = window.DEALS[currentRegionId] || {};

        // подписываем названия продуктов
        $('#dealSmall  p').text(rCfg.Small?.product  || '—');
        $('#dealMedium p').text(rCfg.Medium?.product || '—');
        $('#dealLarge  p').text(rCfg.Large?.product  || '—');

        /* ---------- НОВОЕ: записываем deal_id в data-атрибут ---------- */
        // если нужного размера нет — стираем атрибут, чтобы не осталось «старого» id
        $('#dealSmall')
            .attr('data-deal-id',  rCfg.Small?.deal_id )

        $('#dealMedium')
            .attr('data-deal-id',  rCfg.Medium?.deal_id)

        $('#dealLarge')
            .attr('data-deal-id',  rCfg.Large?.deal_id )

        /* ---------------------------------------------------- */

        $('#dealFlash  p').text(rCfg.Flash?.product || '—');
        $('#dealFlash').attr('data-deal-id', rCfg.Flash?.deal_id);

        // очищаем детали
        resetDetails();
        currentCfg = null;             // сбрасываем выбранный ETF

        // Находим все поля ввода суммы и вызываем для каждого validateSum
        $('.onlyDigits').each(function() {
            validateSum($(this));
        });
    });

    // клики по 3м кнопкам
    $('.js-deal').on('click', function () {

        const $modal = $(this).closest('.modal');   // ← текущая модалка

        /* ---- подсветка выбранной кнопки (остаётся) ---- */
        $modal.find('.js-deal').removeClass('check-btn');
        $(this).addClass('check-btn');

        // **НОВЫЙ БЛОК**: если регион ещё не задан, читаем его из data-region-id

        const regionIdFromBtn = $(this).data('region-id');
        if (regionIdFromBtn) {
            currentRegionId = regionIdFromBtn;
        }

        if (!currentRegionId) return;

        const size = $(this).data('size');
        const idx  = +($(this).data('index') || 0);
        const cfg  = size === 'Flash'
            ? (window.DEALS[currentRegionId]?.Flash || [])[idx]
            : window.DEALS[currentRegionId]?.[size];

        if (!cfg) return;

        /* ---- заполняем поля ТОЛЬКО внутри этой же модалки ---- */
        const today = new Date();
        $modal.find('#dealStart').text(formatDate(today));
        $modal.find('#dealEnd')  .text(formatDate(addDays(today, cfg.term_days)));
        $modal.find('#dealAmount').text(`${cfg.amount_min} – ${cfg.amount_max} USDT`);

        // NEW: локаль из cookie + правильные подписи
        const lang = getLangFromCookie();
        $modal.find('#dealTime').text(`${cfg.term_days} ${dayLabel(cfg.term_days, lang)}`);
        $modal.find('#dealRate').text(
            size === 'Flash'
                ? `${(cfg.rate_without_RIX_min*100).toFixed(1)} – ${(cfg.rate_without_RIX_max*100).toFixed(1)} %${perSlash(lang)}`
                : `${(cfg.rate_without_RIX).toFixed(2)} %${perWord(lang)}`
        );

        // оставляем как было
        $modal.find('#dealRevenue').text('—');

        $modal.find('#dealMore')
            .data('title', cfg.product)
            .data('note',  cfg.config_note);

        const selectedDealId = $(this).data('deal-id');
        // Читаем напрямую из window, так как к моменту клика футер точно загрузился
        const globalDealRequests = window.DEAL_REQUESTS || {};
        const requestStatus = globalDealRequests[selectedDealId];
        currentCfg = {...cfg, deal_id: selectedDealId, request_status: requestStatus};


        const $sumInput = $modal.find('.onlyDigits');
        $sumInput
            .data('min', cfg.amount_min)
            .data('maxInvest', cfg.amount_max);

        const $invest = $modal.find('.js-invest-btn');
        const $request = $modal.find('.js-request-btn');

        const requiresRequest = Boolean(cfg.need_confirm);

        $invest
            .data('deal-cfg', cfg)
            .data('deal-id', selectedDealId)
            .data('size',    $(this).data('size'));

        applyRequestState($modal, requiresRequest, requestStatus);


        validateSum($sumInput);     // проверим текущий ввод
        recalcRevenue($modal);
    });

    // при открытии «Подробнее» подставляем данные
    $(document).on('click', '[data-target="#text"]', function () {
        $('#dealInfoTitle').text($(this).data('title') || '');
        $('#dealInfoText').html($(this).data('note')  || '');
    });


    // helpers
    function resetDetails() {
        $('#dealStart,#dealEnd,#dealAmount,#dealTime,#dealRate,#dealRevenue').text('—');
    }
    function addDays(d, days){ const x=new Date(d); x.setDate(x.getDate()+days); return x; }
    function pad(n){ return n<10?'0'+n:n; }
    function formatDate(d){ return pad(d.getDate())+'.'+pad(d.getMonth()+1)+'.'+d.getFullYear(); }

});

/* ------------------------- валидация -------------------------- */
/** Отключаем кнопку: и класс, и DOM-атрибут */
function disable ($el) {
    $el.addClass('disabled').prop('disabled', true);
}

/** Включаем кнопку */
function enable ($el) {
    $el.removeClass('disabled').prop('disabled', false);
}

function validateSum ($sumInput) { // Принимаем input как аргумент
    const $modal = $sumInput.closest('.modal');
    const $btn   = $modal.find('.js-invest-btn');

    if (!currentCfg) { // currentCfg все еще глобальный, это ОК
        disable($btn);
        return;
    }

    if (currentCfg.need_confirm && currentCfg.request_status !== 1) {
        disable($btn);
        return;
    }

    const val = parseInt($sumInput.val(), 10); // Читаем значение из ПРАВИЛЬНОГО input

    const isOk = Number.isFinite(val) &&
        val >= currentCfg.amount_min &&
        val <= currentCfg.amount_max &&
        val <= Number(window.BALANCE);

    isOk ? enable($btn) : disable($btn);
}

// общий фильтр: только целые числа, не больше баланса
/* ================================================================= */
$(document).on('input', '.onlyDigits', function () {

    const $modal = $(this).closest('.modal');

    let v = this.value.replace(/\D/g, '');          // 1) убираем всё, кроме цифр
    v = v.replace(/^0+(?=\d)/, '');                 // 2) удаляем лидирующие нули

    const max = $(this).data('maxInvest')
        ? Number($(this).data('maxInvest'))
        : Number(window.BALANCE);                   // 3) ограничиваем балансом

    if (parseInt(v, 10) > max) v = String(max);

    this.value = v;
    validateSum($(this));                           // заново проверяем кнопку

    // ваш существующий код фильтрации и validateSum…
    recalcRevenue($modal);
});

// общий фильтр: только целые числа, не больше баланса
/* ================================================================= */
$(document).on('input', '.simpleDigits', function () {

    const $modal = $(this).closest('.modal');

    let v = this.value.replace(/\D/g, '');          // 1) убираем всё, кроме цифр
    v = v.replace(/^0+(?=\d)/, '');                 // 2) удаляем лидирующие нули

    this.value = v;

});



/* Делегированный обработчик — работает, даже если кнопка рисуется динамически */
$(document).on('click', '.js-invest-btn', function (e) {
    e.preventDefault();

    const $btn   = $(this);
    const $modal = $btn.closest('.modal');

    // кнопка выключена или сделка не выбрана?
    if ($btn.prop('disabled') || !currentCfg) return;

    /* ---------- отправляем запрос ---------- */
    $.post('/api/invest', {
        deal_id : currentCfg.deal_id,
        amount  : $modal.find('.onlyDigits').val()
    }, function (data) {

        if (data.ok) {
            // обновляем баланс
            window.BALANCE = data.new_bal;
            $('#userBalance').text(
                Number(data.new_bal).toLocaleString('ru-RU')
            );

            // закрываем модалку, очищаем ввод, блокируем кнопку
            $('#activity').modal('hide');
            $modal.find('.onlyDigits').val('');
            disable($btn);
            $('#coolDownError').addClass('d-none');

            window.location.reload();

        } else {
            if (data.error === 'cooldown') {
                $('#coolDownDate').text(data.available);
                $('#coolDownError').removeClass('d-none');
                $btn.addClass("disabled")
            } else if (data.error === 'blocked_by_large') {
                $('#blockedByLarge').removeClass('d-none');
                $btn.addClass("disabled")
            } else if (data.error === 'need_confirmation') {
                alert('This deal requires manual confirmation. Please send a request.');
            } else
            {
                alert('Error: ' + (data.error || 'unknown'));
            }

        }

    }, 'json').fail(function (xhr) {
        alert('Error ' + xhr.status + ': ' + xhr.responseText);
    });
});

$(document).on('click', '.js-request-btn', function (e) {
    e.preventDefault();

    const $btn = $(this);
    // Используем currentCfg, если он есть, иначе берем из data-атрибута
    const dealId = (currentCfg && currentCfg.deal_id) ? currentCfg.deal_id : $btn.data('deal-id');
    const $modal = $btn.closest('.modal');
    const lang = getLangFromCookie();

    // Ссылка на глобальный объект с заявками
    const globalDealRequests = window.DEAL_REQUESTS || {};

    if ($btn.prop('disabled') || !dealId) {
        return;
    }

    $btn.prop('disabled', true); // Блокируем кнопку сразу при нажатии

    $.post('/api/deal-request', {
        deal_id: dealId
    }, function (data) {

        if (data.ok) {
            // 1. Обновляем глобальный объект
            globalDealRequests[dealId] = 0;

            // 2. Обновляем текущую конфигурацию, если открыта эта же сделка
            if (currentCfg && currentCfg.deal_id === dealId) {
                currentCfg.request_status = 0;
            }

            // 3. Вызываем функцию обновления UI
            applyRequestState($modal, true, 0);

            // 4. Валидация
            validateSum($modal.find('.onlyDigits'));

        } else if (data.error === 'exists') {
            // Если заявка уже была, берем статус из ответа или из памяти
            const status = typeof data.status === 'number' ? data.status : (globalDealRequests[dealId] || 0);

            globalDealRequests[dealId] = status;

            if (currentCfg && currentCfg.deal_id === dealId) {
                currentCfg.request_status = status;
            }

            applyRequestState($modal, true, status);
            validateSum($modal.find('.onlyDigits'));

            if (status === 0) {
                alert(requestPendingLabel(lang));
            } else if (status === 1) {
                alert('Заявка уже одобрена. Вы можете инвестировать.');
            } else {
                alert('Заявка уже существует.');
            }
        } else {
            alert('Error: ' + (data.error || 'unknown'));
            $btn.prop('disabled', false); // Разблокируем, если ошибка не "exists"
        }
    }, 'json').fail(function (xhr) {
        alert('Error ' + xhr.status + ': ' + xhr.responseText);
        $btn.prop('disabled', false); // Разблокируем при сбое сети
    });
    // .always тут можно убрать или изменить логику, так как мы управляем кнопкой внутри success/fail
});

$(document).on('click', '.js-deal-ok', function () {
    const $banner = $(this).closest('.totalClose');   // сам баннер
    const $card   = $(this).closest('.totalDeal');    // корень карточки
    const id      = $card.data('user-deal-id');       // id строки user_deals

    $.post('/api/approve-new-deal',
        { user_deal_id: id },
        function (data) {
            const res = (typeof data === 'string') ? JSON.parse(data) : data;

            if (res.ok) {
                $banner.fadeOut(200, function () { $(this).remove(); });
            }
        },
        'json'
    ).fail(function (xhr) {
        console.error('approve-new-deal error', xhr);
    });
});

$(document).on('click', '.js-deal-close-ok', function () {
    const $banner = $(this).closest('.totalClose');
    const $card = $(this).closest('.totalDeal');
    const id      = $(this).closest('.totalDeal').data('user-deal-id');

    $.post('/api/approve-closed-deal', { user_deal_id: id }, function (res) {
        if (res.ok) {
            if (res.ok) $card.fadeOut(200, () => $card.remove());
        }
    }, 'json').fail(xhr => console.error('approve-closed-deal', xhr));
});

$(document).on('keydown', '.onlyDigits', function (e) {
    if (e.which === 13 || e.key === 'Enter') {
        e.preventDefault();
        const $sum   = $(this);
        const $modal = $sum.closest('.modal');
        const $btn   = $modal.find('.js-invest-btn');
        if (!$btn.prop('disabled')) {
            $btn.trigger('click');
        }
    }
});


/////////////////

$(document).on('click', '.btn-hash', function(e) {
    e.preventDefault();
    // прячем текущий модал (если нужно)
    $('#replenishSuccessModal').modal('hide');
    // перезагружаем страницу
    window.location.reload();
});

/* ----- RIXWALLET statistic modal ----- */
(function ($) {

    /** шаблон одной строки */
    const rowTpl = r => `<tr>
      <td>${r.date}</td>
      <td>${r.action}</td>
      <td>${r.amount}</td>
      <td>${r.source}</td>
      <td class="${r.status_class}">${r.status}</td>
  </tr>`;

    /** рендер таблицы внутри конкретной модалки */
    function render($modal) {
        const $tBody   = $modal.find('#walletStatsTable tbody');
        const showInp  = $modal.find('#toggleInputs').prop('checked');
        const showOut  = $modal.find('#toggleOutputs').prop('checked');

        $tBody.empty();

        if (showInp) window.WALLET_STATS.inputs .forEach(r => $tBody.append(rowTpl(r)));
        if (showOut) window.WALLET_STATS.outputs.forEach(r => $tBody.append(rowTpl(r)));
    }

    /* первый рендер — когда окно уже полностью раскрыто */
    $(document).on('shown.bs.modal', '#statistic', function () {
        render($(this));
    });

    /* клики по чек-боксам внутри этой же модалки */
    $(document).on('change', '#statistic #toggleInputs, #statistic #toggleOutputs', function () {
        render($(this).closest('#statistic'));
    });

})(jQuery);

$(function(){
    if (window.location.hash === '#activity') {
        $('#activity').modal('show');
    }
});

$(function(){
    if (window.location.hash === '#arbitration') {
        $('#arbitration').modal('show');
    }
});
