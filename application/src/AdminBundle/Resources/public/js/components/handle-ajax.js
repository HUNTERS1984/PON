(function ($) {
    function AjaxModal(opts) {
        var $element = this, options = {};

        var _create = function () {
            var defaultOptions = {
                ajaxUrl: '',
                ajaxRedirect: '',
                ajaxType: 'POST',
                ajaxConfirm: '',
                ajaxChecked: '',
                ajaxCheckedField: ''
            };

            options = $.extend(true, {}, defaultOptions, $element.data(), opts);

            $element.click(_submit);
        };

        var _submit = function() {

            if(options.ajaxConfirm !='' && !confirm(options.ajaxConfirm)) {
                return false;
            }
            var data = null;
            if(options.ajaxChecked != '' && options.ajaxCheckedField !='' ) {
                var checkedVals = $(options.ajaxChecked+':checkbox:checked').map(function() {
                    return this.value;
                }).get();
                var data = new FormData();
                data.append("ids", checkedVals);
            }

            $.ajax({
                url: options.ajaxUrl,
                type: options.ajaxType,
                data: data,
                async: false,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    response = $.parseJSON(response);

                    if(!response.status) {
                        alert(response.message);
                        return false;
                    }



                    if(response.status) {
                        window.location.href = options.ajaxRedirect;
                        return true;
                    }
                    alert('Can not process data!');
                    return false;
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(thrownError);
                    return false;
                }
            });
            return false;
        };
        _create();

        return this;
    }

    $.fn.ajaxModal = AjaxModal;
})(jQuery);
