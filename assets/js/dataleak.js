jQuery(document).ready(function ($) {
    'use strict';

    var cmplz_email_interval = 0;
    var progress = cmplz_dataleak.progress;
    var progressBar = $('.cmplz-progress-bar');
    var cmplz_stop = false;

    function cmplz_run_batch() {

            if (cmplz_stop) return;
            var subject = $('#cmplz_subject').val();
            var sender = $('#cmplz_sender').val();

            $.get(
                cmplz_dataleak.admin_url,
                {
                    action: 'get_email_batch_progress',
                    post_id: cmplz_dataleak.post_id,
                    subject: subject,
                    sender: sender,
                },
                function (response) {
                    var obj;
                    if (response) {
                        obj = jQuery.parseJSON(response);
                        progress = parseInt(obj['progress']);
                        if (progress >= 100) {
                            progress = 100;
                            $('#cmplz_close_tb_window').prop('disabled', true);
                            $('#cmplz-send-data').html(cmplz_dataleak.complete_string);
                            cmplz_stop = true;
                        } else {
                            cmplz_email_interval = 1000;
                            window.setTimeout(cmplz_run_batch, cmplz_email_interval);
                        }
                        progressBar.css({width: progress + '%'});


                    }
                });
        }


    progressBar.css({width: progress + '%'});

    $(document).on('click', '#cmplz-start-mail', function() {
        window.setTimeout(cmplz_run_batch, cmplz_email_interval);
        $('#cmplz-start-mail').prop('disabled', true);

    });

    $(document).on('click', '#cmplz_close_tb_window', function() {
        cmplz_stop = true;
        $('#cmplz-start-mail').prop('disabled', false);
        tb_remove();
    });


});