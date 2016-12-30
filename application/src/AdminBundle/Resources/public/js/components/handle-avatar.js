(function ($) {
    function AvatarModal(opts) {
        var $element = this, options = {};

        var _create = function () {
            var defaultOptions = {
            };

            options = $.extend(true, {}, defaultOptions, $element.data(), opts);
            $element.find(".avatar_file").change(loadImage);
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
                    file.closest(".wrapp_avar_btn").find('img').attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

        _create();

        return this;
    }

    $.fn.avatarModal = AvatarModal;
})(jQuery);
