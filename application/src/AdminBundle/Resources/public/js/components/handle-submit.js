(function ($) {
    function SubmitModal(opts) {
        var $element = this, options = {};

        var _create = function () {
            var defaultOptions = {
                ajaxUrl: '',
                ajaxRedirect: '',
                modal: ''
            };

            options = $.extend(true, {}, defaultOptions, $element.data(), opts);

            $element.submit(_submit);
        };

        var _submit = function() {
            var $this = $(this);
            var formData = new FormData($(this)[0]);

            $.ajax({
                url: options.ajaxUrl,
                type: 'POST',
                data: formData,
                async: true,
                cache: true,
                contentType: false,
                processData: false,
                beforeSend: function( ) {
                    $('div.modal-dialog').block({
                        message: 'Processing...',
                        baseZ: 2000
                    });
                    $('div.tab-content').block({
                        message: 'Processing...',
                        baseZ: 2000
                    });
                },
                success: function (response) {
                    $.unblockUI();
                    response = $.parseJSON(response);

                    if(!response.status) {
                        $('#error_message').html(response.message);
                        return false;
                    }

                    if(options.modal != '') {
                        $(options.modal).modal('show');
                    }

                    if(response.status) {
                        window.location.href = options.ajaxRedirect;
                        return true;
                    }

                    $('#error_message').html('Can not process data!');
                    return false;
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    $('#error_message').html(thrownError);
                    return false;
                },
                complete: function(){
                    $.unblockUI();
                }
            });
            return false;
        };
        _create();

        return this;
    }

    $.fn.submitModal = SubmitModal;
})(jQuery);
