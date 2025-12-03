$(() => {
    $('.select-single').select2({
        minimumResultsForSearch: -1,
        templateSelection: formatText,
        templateResult: formatText
    });
    function formatText (icon) {
        return $('<span class="flex-icon"><i class="icon ' + $(icon.element).data('icon') + '"></i> ' + icon.text + '</span>');
    }
});

$(() => {
    $('.select-single-2').select2({ minimumResultsForSearch: -1 });
});
$(() => {
    $('.select-single-3').select2({ minimumResultsForSearch: -1 });
});
$(() => {
    $('.select-single-4').select2({ minimumResultsForSearch: -1 });
});

// === Переключатель языка для нескольких селектов ===
// где у тебя инициализируется .select-single-2
$(() => {
    $('.select-single-2').each(function () {
        const $s = $(this);
        $s.select2({
            minimumResultsForSearch: -1,
            width: 'resolve',                 // берём ширину из CSS
            dropdownParent: $(document.body)  // чтобы список не резался контейнерами хедера
        });
    });

    // чтобы переключатель работал и на #currency-1 в хедере
    initLangSwitcher('#currency-1, #currency-2, #currency-4, #currency-5');
});

function initLangSwitcher(selector) {
    const SUP = ['en','ru','cn','ar'];   // поддерживаемые языки
    const STRATEGY = 'path';             // 'path' | 'query' | 'subdomain'
    const $controls = $(selector).filter('select');
    if (!$controls.length) return;

    // --- cookie helpers ---
    function getCookie(name){
        const m = document.cookie.match(new RegExp('(?:^|; )' + name.replace(/([.$?*|{}()[\]\\/+^])/g,'\\$1') + '=([^;]*)'));
        return m ? decodeURIComponent(m[1]) : '';
    }
    function setLangCookie(lang){
        const oneYear = 60 * 60 * 24 * 365;
        document.cookie = `lang=${lang}; Max-Age=${oneYear}; Path=/; SameSite=Lax${location.protocol === 'https:' ? '; Secure' : ''}`;
    }

    // Нормализуем value в <option> (если в вёрстке пустые/нестандартые)
    $controls.find('option').each(function(){
        let v = String(this.value || '').toLowerCase().trim();
        if (!SUP.includes(v)) {
            const t = (this.textContent || '').trim().toLowerCase();
            const map = { en:'en', ru:'ru', ch:'cn', cn:'cn', zh:'cn', '中文':'cn', ar:'ar', 'العربية':'ar' };
            v = map[t] || v;
            if (!SUP.includes(v)) v = 'en';
            this.value = v;
        }
    });

    // Определяем текущий язык (URL → query → subdomain → cookie → en)
    const current = (function detectLang(){
        const u = new URL(location.href);
        const seg1 = (u.pathname.split('/')[1] || '').toLowerCase();
        if (SUP.includes(seg1)) return seg1;

        const q = (u.searchParams.get('lang') || '').toLowerCase();
        if (SUP.includes(q)) return q;

        const m = u.hostname.match(/^([a-z]{2})\./i);
        if (m && SUP.includes(m[1].toLowerCase())) return m[1].toLowerCase();

        const c = (getCookie('lang') || '').toLowerCase();
        if (SUP.includes(c)) return c;

        return 'en';
    })();

    // Проставляем значение всем селектам БЕЗ редиректа (до подписки на события)
    $controls.each(function(){
        const $sel = $(this);
        $sel.val(current);
        if ($sel.hasClass('select2-hidden-accessible')) {
            $sel.trigger('change.select2'); // синхронизируем отрисовку Select2
        }
    });

    function redirectTo(lang){
        if (!SUP.includes(lang) || lang === current) return;

        // 1) сохраняем выбор
        setLangCookie(lang);

        // 2) редирект в выбранную языковую ветку
        const u = new URL(location.href);
        if (STRATEGY === 'path') {
            const parts = u.pathname.split('/');
            if (SUP.includes((parts[1] || '').toLowerCase())) parts[1] = lang;
            else parts.splice(1, 0, lang);
            u.pathname = parts.join('/').replace(/\/{2,}/g, '/');
            u.searchParams.delete('lang');
        } else if (STRATEGY === 'query') {
            u.searchParams.set('lang', lang);
        } else if (STRATEGY === 'subdomain') {
            u.hostname = lang + '.' + u.hostname.replace(/^([a-z]{2}\.)/, '');
        }

        location.assign(u.toString());
    }

    // Один общий обработчик для всех контролов
    $controls.on('change select2:select', function(){
        redirectTo($(this).val());
    });
}
