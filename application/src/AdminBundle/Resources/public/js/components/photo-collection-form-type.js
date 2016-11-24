(function ($) {
    function PhotoCollectionFormType(opts) {
        var $element = this, options = {};

        var _create = function () {
            var defaultOptions = {
                currentIndex: 0,
                replaceKey: '__name__',
                addBtn: '.collection_btn_add',
                removeBtn: '.collection_image_close',
                rowClass: '.collection_image',
                template: '',
                onRowAdded: false
            };

            options = $.extend(true, {}, defaultOptions, $element.data(), opts);
            $element.find(options.addBtn).click(_addNew);
            $element.find(options.removeBtn).click(_remove);
            $element.find(".collection_image_file").change(loadImage);
        };

        var _addNew = function () {
            var $this = $(this);
            var row = $(_getRowTemplate(options.template, options.replaceKey, options.currentIndex));
            var parent = $this.closest('.collection_row_item').find('.collection_preview_image');
            row = _attachRowEvents(row);
            parent.append(row);
            $this.val('');

            if ($.isFunction(options.onRowAdded)) {
                options.onRowAdded(row);
            }
            options.currentIndex++;
        };

        var loadImage = function(){
            var $this = $(this);
            readUrl($this);
        }

        var readUrl = function readURL(file) {
            var input = file[0];
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    file.closest(".collection_image").find('img').attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

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
            row.find(".collection_image_file").change(loadImage);

            return row;
        };

        _create();

        return this;
    }

    $.fn.photoCollectionFormType = PhotoCollectionFormType;
})(jQuery);
