<!-- footer start-->
<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 footer-copyright">
                <p class="mb-0">2024 © Matrix Bot Admin Panel</p>
            </div>
            <div class="col-md-6">
                <p class="pull-right mb-0">Made with <i class="fa fa-heart font-secondary"></i></p>
            </div>
        </div>
    </div>
</footer>
</div>
</div>
<!-- latest jquery-->
<script src="/assets/js/jquery-3.5.1.min.js"></script>
<!-- feather icon js-->
<script src="/assets/js/icons/feather-icon/feather.min.js"></script>
<script src="/assets/js/icons/feather-icon/feather-icon.js"></script>
<!-- Sidebar jquery-->
<script src="/assets/js/sidebar-menu.js"></script>
<script src="/assets/js/config.js"></script>
<!-- Bootstrap js-->
<script src="/assets/js/bootstrap/popper.min.js"></script>
<script src="/assets/js/bootstrap/bootstrap.min.js"></script>
<!-- Plugins JS start-->
<script src="/assets/js/datatable/datatables/jquery.dataTables.min.js"></script>
<script src="/assets/js/datatable/datatables/datatable.custom.js"></script>
<script src="/assets/js/tooltip-init.js"></script>
<script src="/assets/js/rating/jquery.barrating.js"></script>
<script src="/assets/js/rating/rating-script.js"></script>
<script src="/assets/js/slick-slider/slick.min.js"></script>
<script src="/assets/js/slick-slider/slick-theme.js"></script>
<script src="/assets/js/dropzone/dropzone.js"></script>
<script src="/assets/js/dropzone/dropzone-script.js"></script>

<!-- Plugins JS start-->
<script src="/assets/js/datepicker/date-picker/datepicker.js"></script>
<script src="/assets/js/datepicker/date-picker/datepicker.en.js"></script>
<script src="/assets/js/datepicker/date-picker/datepicker.custom.js"></script>

<script src="/assets/js/datatable/datatables/jquery.dataTables.min.js"></script>
<script src="/assets/js/datatable/datatable-extension/dataTables.buttons.min.js"></script>
<script src="/assets/js/datatable/datatable-extension/jszip.min.js"></script>
<script src="/assets/js/datatable/datatable-extension/buttons.colVis.min.js"></script>
<script src="/assets/js/datatable/datatable-extension/pdfmake.min.js"></script>
<script src="/assets/js/datatable/datatable-extension/vfs_fonts.js"></script>
<script src="/assets/js/datatable/datatable-extension/dataTables.autoFill.min.js"></script>
<script src="/assets/js/datatable/datatable-extension/dataTables.select.min.js"></script>
<script src="/assets/js/datatable/datatable-extension/buttons.bootstrap4.min.js"></script>
<script src="/assets/js/datatable/datatable-extension/buttons.html5.min.js"></script>
<script src="/assets/js/datatable/datatable-extension/buttons.print.min.js"></script>
<script src="/assets/js/datatable/datatable-extension/dataTables.responsive.min.js"></script>
<script src="/assets/js/datatable/datatable-extension/responsive.bootstrap4.min.js"></script>
<script src="/assets/js/datatable/datatable-extension/dataTables.keyTable.min.js"></script>
<script src="/assets/js/datatable/datatable-extension/dataTables.colReorder.min.js"></script>
<script src="/assets/js/datatable/datatable-extension/dataTables.fixedHeader.min.js"></script>
<script src="/assets/js/datatable/datatable-extension/dataTables.rowReorder.min.js"></script>
<script src="/assets/js/datatable/datatable-extension/dataTables.scroller.min.js"></script>
<script src="/assets/js/datatable/datatable-extension/custom.js"></script>



<!-- Plugins JS Ends-->
<!-- Theme js-->
<script src="/assets/js/script.js"></script>
<script src="/assets/js/theme-customizer/customizer.js"></script>

<?php $url = $_SERVER['REQUEST_URI']; // Получаем текущий URL
if (strpos($url, 'admin/faq') !== false) { ?>
    <script src="https://cdn.ckeditor.com/4.20.0/standard-all/ckeditor.js"></script>
<?php } ?>



<script>
    $('#previewSend').on('change', function() {
        $(this).closest('form').submit();
    });

        // Получаем элементы формы и поля ввода даты
        const dateForm = document.getElementById('dateForm');
        const startDateInput = document.getElementById('startDateInput');

        // Добавляем обработчик события изменения значения в поле ввода даты
        startDateInput.addEventListener('change', function() {
        // Отправляем форму при выборе даты
        dateForm.submit();
    });

    <?php $url = $_SERVER['REQUEST_URI']; // Получаем текущий URL
        if (strpos($url, 'admin/faq') !== false) { ?>
        CKEDITOR.replace('faq_text', {
            height: 600,
            allowedContent: 'h2[*]{*}(*);h5[*]{*}(*); div[*]{*}'
        });
    <?php } ?>


    $('#plusButton').on('click', function() {
        let imagesCount = parseInt($('#imagesCount').val(), 10) + 1;
        $('#imagesCount').val(imagesCount);

        let uploadDivCount = $('#elements-upload');

        let div = document.createElement("div");
        div.setAttribute('class', 'additionally-uploads mb-3');
        div.setAttribute('id', 'uploads-div-' + imagesCount);
        div.setAttribute('style', 'display: flex; margin-bottom: 30px;');

        let img_full_new = document.createElement("input");
        img_full_new.setAttribute('class', 'form-control');
        img_full_new.setAttribute('name', 'img_full_new_' + imagesCount);
        img_full_new.setAttribute('type', 'file');
        img_full_new.setAttribute('accept', 'image/*');
        img_full_new.setAttribute('style', 'margin-bottom: 30px;');

        let button = document.createElement("button");
        button.setAttribute('class', 'btn btn-danger');
        button.setAttribute('id', 'removeButton_' + imagesCount);
        button.setAttribute('type', 'button');
        button.setAttribute('onclick', 'removeElement(this)');
        button.setAttribute('style', 'margin-left: 10px; width: 5.884px; height: 34.996528px;');
        button.append("-");

        div.append(img_full_new);
        div.append(button);
        uploadDivCount.append(div);

        for (let i = 0; i < uploadDivCount.length; ++i) {
            let item = uploadDivCount[i];
            console.log(item)
            // alert(i+1);
            // item.innerHTML = 'this is value';
        }
        // $(this).closest('form').submit();
    });

    function removeElement(e) {
        let element = e;
        element.closest('div').remove();
        let imagesCount = parseInt($('#imagesCount').val(), 10) - 1;
        $('#imagesCount').val(imagesCount);
    }

</script>



<!-- login js-->
<!-- Plugin used-->
</body>
</html>