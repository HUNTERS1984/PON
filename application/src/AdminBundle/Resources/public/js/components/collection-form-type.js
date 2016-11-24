(function ($) {
    function CollectionFormType(opts) {
        var $element = this, options = {};

        var _create = function () {
            var defaultOptions = {
                currentIndex: 0,
                replaceKey: '__name__',
                addBtn: '> .btn-add-item',
                removeBtn: '.btn-remove-item',
                rowClass: '.collection-item',
                template: '',
                onRowAdded: false
            };

            options = $.extend(true, {}, defaultOptions, $element.data(), opts);

            $element.find(options.addBtn).click(_addNew);
            $element.find(options.removeBtn).click(_remove);
        };

        var _addNew = function () {
            var $this = $(this);
            var row = $(_getRowTemplate(options.template, options.replaceKey, options.currentIndex));

            _attachRowEvents(row).insertBefore($this);

            if ($.isFunction(options.onRowAdded)) {
                options.onRowAdded(row);
            }

            options.currentIndex++;
        };

        var _remove = function () {
            $(this).closest(options.rowClass).remove();

            var i = 1;
            $element.find('.item-index').each(function () {
                $(this).text(i);
                i++;
            });

            options.currentIndex--;
        };

        var _getRowTemplate = function (template, replaceKey, currentIndex) {
            return template.replace(new RegExp(replaceKey, 'g'), currentIndex);
        };

        var _attachRowEvents = function (row) {
            row.find(options.removeBtn).click(_remove);

            return row;
        };

        _create();

        return this;
    }

    $.fn.collectionFormType = CollectionFormType;
})(jQuery);
