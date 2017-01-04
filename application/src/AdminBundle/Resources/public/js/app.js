/**
 Core script to handle the entire theme and core functions
 **/
var App = function () {
    var $document = $(document);

    var handleStoreSelect = function ($element) {
        if ($().select2) {
            $element.find('select[data-toggle=store]').storeSelect();
        }
    };

    var handleNewsCategorySelect = function ($element) {
        if ($().select2) {
            $element.find('select[data-toggle=news_category]').newsCategorySelect();
        }
    };

    var handleCategorySelect = function ($element) {
        if ($().select2) {
            $element.find('select[data-toggle=category]').categorySelect();
        }
    };

    var handleCollection = function ($element) {
        if ($().collectionFormType) {
            $element.find('*[data-toggle=collectionFormType]').each(function () {
                $(this).collectionFormType({
                    onRowAdded: App.initComponents
                });
            });
        }
    };

    var handleSubmitModal = function ($element) {
        $element.find('*[class=submitModal]').each(function () {
            $(this).submitModal({
                onRowAdded: App.initComponents
            });
        });
    };

    var handleAjaxModal = function ($element) {
        $element.find('*[data-ajax-modal=ajaxModal]').each(function () {
            $(this).ajaxModal({
                onRowAdded: App.initComponents
            });
        });
    };

    var handlePhotoCollection = function ($element) {
        if ($().collectionFormType) {
            $element.find('*[data-toggle=photoCollectionFormType]').each(function () {
                $(this).photoCollectionFormType({
                    onRowAdded: App.initComponents
                });
            });
        }
    };

    var handleCopyLink = function ($element) {
        $('.btn-copy').on('click', function(event){
            event.preventDefault();
            var $temp = $(".qrlink");
            $temp.select();
            document.execCommand("copy");
        });
    };

    var handleDeliveryTimeVisibility = function ($element) {
        $element.find('.delivery_time_ability').each(function () {
            var $container = $(this),
                $selectBox = $container.find('.push_delivery_time'),
                $block = $container.find('.delivery_time');
            $selectBox.change(setDeliveryTimeVisibility);
            setDeliveryTimeVisibility();

            function setDeliveryTimeVisibility() {
                if ($selectBox.val() == 'now') {
                    $block.hide();
                } else {
                    $block.show();
                }
            }
        });
    };

    var handleStoreVisibility = function ($element) {
        $element.find('.form-role-store').each(function () {
            var $container = $(this),
                $selectBox = $container.find('.form-role'),
                $block = $container.find('.form-store');
            $selectBox.change(setStoreVisibility);
            setStoreVisibility();

            function setStoreVisibility() {
                if ($selectBox.val() == 'ROLE_CLIENT') {
                    $block.show();
                } else {
                    $block.hide();
                }
            }
        });
    };

    var handleChangeAvatarImage = function ($element) {
        $element.find('.wrapp_avar_btn').each(function () {
            $(this).avatarModal({
                onRowAdded: App.initComponents
            });
        });
    }


    //* END:CORE HANDLERS *//

    return {

        //main function to initiate the theme
        init: function () {
            handleStoreSelect($document);
            handleNewsCategorySelect($document);
            handleCategorySelect($document);
            handleCollection($document);
            handlePhotoCollection($document);
            handleSubmitModal($document);
            handleDeliveryTimeVisibility($document);
            handleStoreVisibility($document);
            handleAjaxModal($document);
            handleCopyLink($document);
            handleChangeAvatarImage($document);
        }
    };

}();

jQuery(document).ready(function () {
    App.init(); // init metronic core componets
});