<div class="page-content">
        

    <div class="figure-3"></div>
    <div class="figure-4"></div>

    <div class="verification">
        <div class="content">
            <div class="verification-content">
                <div class="verification-title">
                    <h5>verification</h5>
                </div>

                <div class="verification-file ">

                    <div class="verification-item">

                        <div class="verification-number">
                            <span>01</span>
                        </div>

                        <div class="verification-text">
                            <p>صورة للصفحة الأولى من جواز السفر أو الجانب الأمامي من رخصة القيادة / بطاقة الهوية</p>
                        </div>

                        <div class="verification-select">
                            <input type="file" class="custom-file" data-slot="1">
                        </div>

                        <div class="verification-caption">
                            <span>درجات الدقة المقبولة: jpeg. jpg. png. الحد الأقصى للحجم 5 ميجابايت </span>
                        </div>

                    </div>

                    <div class="verification-item">

                        <div class="verification-number">
                            <span>02</span>
                        </div>

                        <div class="verification-text">
                            <p>صورة للصفحة الثانية من جواز السفر أو ظهر رخصة القيادة / بطاقة الهوية</p>
                        </div>

                        <div class="verification-select">
                            <input type="file" class="custom-file" data-slot="2">
                        </div>

                        <div class="verification-caption">
                            <span>درجات الدقة المقبولة: jpeg. jpg. png. الحد الأقصى للحجم 5 ميجابايت</span>
                        </div>

                    </div>

                    <div class="verification-item">

                        <div class="verification-number">
                            <span>03</span>
                        </div>

                        <div class="verification-text">
                            <p>صورة سيلفي مع جواز سفرك (الصفحة الثانية) أو مع ظهر رخصة القيادة / بطاقة الهوية في يدك بحيث يكون الوجه الكامل والمستند مرئيين</p>
                        </div>

                        <div class="verification-select">
                            <input type="file" class="custom-file" data-slot="3">
                        </div>

                        <div class="verification-caption">
                            <span>درجات الدقة المقبولة: jpeg. jpg. png. الحد الأقصى للحجم 5 ميجابايت</span>
                        </div>

                    </div>

                    <div class="verification-item">

                        <div class="verification-number">
                            <span>04</span>
                        </div>

                        <div class="verification-text">
                            <p>إثبات الإقامة (صفحة تسجيل جواز السفر ، فاتورة الخدمات ، كشف الحساب المصرفي ، عقد الإيجار)</p>
                        </div>

                        <div class="verification-select">
                            <input type="file" class="custom-file" data-slot="4">
                        </div>

                        <div class="verification-caption">
                            <span>درجات الدقة المقبولة: jpeg. jpg. png. الحد الأقصى للحجم 5 ميجابايت</span>
                        </div>

                    </div>


                </div>

                <div class="verification-overlay d-none">

                    <div class="verification-success">
                        <div class="verification-success__icon">
                            <i class="icon-successBig"></i>
                        </div>

                        <p class="verification-success__text">
                            تم إرسال بياناتك للتحقق. توقع ردا في غضون 24 ساعة على البريد الإلكتروني المحدد أثناء التسجيل. بعد تأكيد التحقق ، ستتلقى رابطا لتسجيل الدخول إلى حسابك الشخصي.
                        </p>

                        <button class="verification-success__btn">OK</button>
                    </div>

                </div>


            </div>
        </div>
    </div>


</div>

<script>
    function showVerificationOverlay () {
        document.body.classList.add('verification-lock');
        document.querySelector('.verification-overlay')?.classList.remove('d-none');
    }

    function hideVerificationOverlay () {
        document.body.classList.remove('verification-lock');
        document.querySelector('.verification-overlay')?.classList.add('d-none');
    }

    document.addEventListener('click', e => {
        if (e.target.closest('.verification-success__btn')) {
            hideVerificationOverlay();
            window.location.href = '/login';
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        const inputs = document.querySelectorAll('input.custom-file');

        if (Array.isArray(window.preFilledVerificationSlots)) {
            inputs.forEach(input => {
                if (window.preFilledVerificationSlots.includes(input.dataset.slot)) {
                    input.classList.add('custom-file_success');
                }
            });
            if (window.preFilledVerificationSlots.length === inputs.length) {
                showVerificationOverlay();
            }
        }

        inputs.forEach(input => {
            input.addEventListener('change', async () => {
                if (!input.files.length) return;

                const fd = new FormData();
                fd.append('file', input.files[0]);
                fd.append('slot', input.dataset.slot);

                try {
                    const res  = await fetch('/api/upload_verification', {
                        method: 'POST',
                        body:   fd,
                        credentials: 'same-origin'
                    });

                    const text = await res.text();
                    console.log('RAW RESPONSE:', text);

                    let json;
                    try {
                        json = JSON.parse(text);
                    } catch {
                        console.error('Invalid JSON:', text);
                        alert('تنسيق استجابة غير صحيح من الخادم');
                        return;
                    }

                    if (res.ok && json.success) {
                        input.classList.add('custom-file_success');


                        const successCount = document.querySelectorAll('input.custom-file.custom-file_success').length;
                        if (successCount === inputs.length) {
                            showVerificationOverlay();
                        }
                    } else {
                        console.error('Upload error:', json.error || res.statusText);
                        alert('خطأ في تحميل الملف: ' + (json.error || res.statusText));
                    }

                } catch (e) {
                    console.error('Fetch error:', e);
                    alert('خطأ في الشبكة عند تنزيل ملف');
                }
            });
        });
    });
</script>