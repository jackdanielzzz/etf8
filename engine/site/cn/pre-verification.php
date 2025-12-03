<div class="page-content">
        

    <div class="figure-3"></div>
    <div class="figure-4"></div>

    <div class="verification">
        <div class="content">
            <div class="verification-content">
                <div class="verification-title">
                    <h5>验证</h5>
                </div>

                <div class="verification-file ">

                    <div class="verification-item">

                        <div class="verification-number">
                            <span>01</span>
                        </div>

                        <div class="verification-text">
                            <p>护照首页或驾照/身份证正面照片</p>
                        </div>

                        <div class="verification-select">
                            <input type="file" class="custom-file" data-slot="1">
                        </div>

                        <div class="verification-caption">
                            <span>接受的分辨率：jpeg。jpg。png。最大大小 5MB </span>
                        </div>

                    </div>

                    <div class="verification-item">

                        <div class="verification-number">
                            <span>02</span>
                        </div>

                        <div class="verification-text">
                            <p>护照第二页或驾照/身份证背面照片</p>
                        </div>

                        <div class="verification-select">
                            <input type="file" class="custom-file" data-slot="2">
                        </div>

                        <div class="verification-caption">
                            <span>接受的分辨率：jpeg。jpg。png。最大大小 5MB</span>
                        </div>

                    </div>

                    <div class="verification-item">

                        <div class="verification-number">
                            <span>03</span>
                        </div>

                        <div class="verification-text">
                            <p>用护照（第二页）或手中的驾照/身份证背面自拍，以便全脸和证件可见</p>
                        </div>

                        <div class="verification-select">
                            <input type="file" class="custom-file" data-slot="3">
                        </div>

                        <div class="verification-caption">
                            <span>接受的分辨率：jpeg。jpg。png。最大大小 5MB</span>
                        </div>

                    </div>

                    <div class="verification-item">

                        <div class="verification-number">
                            <span>04</span>
                        </div>

                        <div class="verification-text">
                            <p>居住证明（护照登记页、水电费账单、银行对账单、租赁协议）</p>
                        </div>

                        <div class="verification-select">
                            <input type="file" class="custom-file" data-slot="4">
                        </div>

                        <div class="verification-caption">
                            <span>接受的分辨率：jpeg。jpg。png。最大大小 5MB</span>
                        </div>

                    </div>


                </div>

                <div class="verification-overlay d-none">

                    <div class="verification-success">
                        <div class="verification-success__icon">
                            <i class="icon-successBig"></i>
                        </div>

                        <p class="verification-success__text">
                            您的数据已提交以供验证。预计在 24 小时内回复注册时指定的电子邮件。确认验证后，您将收到一个登录个人帐户的链接。
                        </p>

                        <button class="verification-success__btn">好</button>
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
                        alert('来自服务器的响应格式不正确');
                        return;
                    }

                    if (res.ok && json.success) {
                        input.classList.add('custom-file_success');

                        // если все 4 инпута успешны — показываем оверлей
                        const successCount = document.querySelectorAll('input.custom-file.custom-file_success').length;
                        if (successCount === inputs.length) {
                            showVerificationOverlay();
                        }
                    } else {
                        console.error('Upload error:', json.error || res.statusText);
                        alert('文件上传错误: ' + (json.error || res.statusText));
                    }

                } catch (e) {
                    console.error('Fetch error:', e);
                    alert('下载文件时出现网络错误');
                }
            });
        });
    });
</script>