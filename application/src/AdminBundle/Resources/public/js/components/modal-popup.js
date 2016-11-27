(function ($) {
    function ModalPopup(opts, $element) {
        var options = {};
        var $modal = {};

        var _create = function () {
            var defaultOptions = {
                modal: '#load_popup_modal_show_id',
                link: '.click_to_load_modal_popup'
            };

            options = $.extend(true, {}, defaultOptions, $element.data(), opts);
            $modal = $element.find(options.modal);
            $element.find(options.link).click(_onClick);
            $element.find(options.modal).on('hidden.bs.modal', _onHidden());
        };

        var _onClick = function () {

                var $this = $(this);
                var link = $this.attr('href');
                $modal.load(link,{},
                function(){
                    $modal.modal('show');
                });
        };

        var _onHidden = function () {
            $modal.html('');
        };

        _create();
    }

    $.fn.modalPopup = function (opts) {
        this.each(function () {
            ModalPopup(opts, $(this));
        });

        return this;
    };
})(jQuery);
