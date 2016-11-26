(function ($) {
    function StoreSelect(opts, $element) {
        var options = {};

        var _create = function () {
            var defaultOptions = {
                ajax: {
                    processResults: function (data, params) {
                        params.page = params.page || 1;

                        var items = $.map(data.data, function (document) {
                            return {
                                id: document.id,
                                text: document.title
                            }
                        });

                        return {
                            results: items,
                            pagination: {
                                more: data.pagination.last_page > data.pagination.current_page
                            }
                        };
                    }
                }
            };

            options = $.extend(true, {}, defaultOptions, $element.data(), opts);
            $element.select2(options).on('select2:select', _onSelect);
        };

        var _onSelect = function (e) {
        };

        _create();
    }

    $.fn.storeSelect = function (opts) {
        this.each(function () {
            StoreSelect(opts, $(this));
        });

        return this;
    };
})(jQuery);
