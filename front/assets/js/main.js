$( document ).ready(function() {

    document.addEventListener("DOMContentLoaded", function () {
        var scrollbar = document.body.clientWidth - window.innerWidth + 'px';
        console.log(scrollbar);
        document.querySelector('#start-btn').addEventListener('click', function () {
            document.body.style.overflow = 'hidden';
            document.querySelector('#openModal').style.marginLeft = scrollbar;
        });
        document.querySelector('[href="#close"]').addEventListener('click', function () {
            document.body.style.overflow = 'visible';
            document.querySelector('#openModal').style.marginLeft = '0px';
        });
    });

    $('#start-btn').on('click', function(){
        window.location.href = document.location.protocol + '//' + document.domain + '/' + '#openModal';
    });

    /*----------------------------------------------------------------------------------------------------------------------------------------------------------*/

    $('#choose-file-btn').on('click', function (event) {
        event.stopPropagation();
        event.preventDefault();
        $('input[type=file]').click();
    });

    $('input[type=file]').on('change', function () {
        let reg = new RegExp("\\\\.*\\\\(.*)$", "g");
        let matches = reg.exec($('input[type=file]').val());
        if (matches[1] !== 'undefined') {
            $('#choose-file-btn').css('display', 'none');
            $('#choose-file-btn').after('<span id="upload_file_name">' + matches[1] + '</span>');
        }
    });

    var files;

    $('input[type=file]').change(function () {
        files = this.files;
    });

    $('#upload-file-btn').on('click', function (event) {
        event.stopPropagation();
        event.preventDefault();

        $('.form-submit-section').prepend('<span id="uploading-wait-msg">Uploading...</span>');

        var data = new FormData();
        $.each(files, function (key, value) {
            data.append(key, value);
        });

        $.ajax({
            url: '/index/upload',
            type: 'POST',
            data: data,
            cache: false,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function (response, textStatus, jqXHR) {
                $('#uploading-wait-msg').remove();
                if (typeof response.hash === 'undefined') {
                    $('.uploading-error-msg').remove();
                    $('.form-submit-section').prepend('<span class="uploading-error-msg">Error: ' + response.error + '</span><br>');
                } else {
                    $('#form-section').hide();
                    $('#success-uploading-section').show();
                    $('#file-hash').append(response.hash.substring(0, 33));
                    console.log('Error server response: ' + response.success);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $('#uploading-wait-msg').remove();

                console.log('Error ajax request: ' + textStatus);
            }
        });
    });

    $('#close-file-btn').on('click', function () {
        window.location.href = '#close';
        $('#success-uploading-section').css('display', 'none');
        $('#form-section').css('display', 'block');

        $('#choose-file-btn').css('display', 'inline-block');
        $('#upload_file_name').remove();
    });

    $('.close').on('click', function () {
        window.location.href = '#close';
        $('.uploading-error-msg').css('display', 'none');
        $('#form-section').css('display', 'block');

        $('#choose-file-btn').css('display', 'inline-block');
        $('#upload_file_name').remove();
    });
});