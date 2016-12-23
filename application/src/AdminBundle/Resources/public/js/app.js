/**
 Core script to handle the entire theme and core functions
 **/
var App = function () {
    var $document = $(document);
    // IE mode
    var isRTL = false;
    var isIE8 = false;
    var isIE9 = false;
    var isIE10 = false;

    // initializes main settings
    var handleInit = function () {

        if ($('body').css('direction') === 'rtl') {
            isRTL = true;
        }

        isIE8 = !!navigator.userAgent.match(/MSIE 8.0/);
        isIE9 = !!navigator.userAgent.match(/MSIE 9.0/);
        isIE10 = !!navigator.userAgent.match(/MSIE 10.0/);

        if (isIE10) {
            $('html').addClass('ie10'); // detect IE10 version
        }

        if (isIE10 || isIE9 || isIE8) {
            $('html').addClass('ie'); // detect IE10 version
        }
    };

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
        $element.find('*[class=ajaxModal]').each(function () {
            $(this).ajaxModal({
                onRowAdded: App.initComponents
            });
        });
    };

    var handleOpenModalPopup = function($element) {
        $element.find('*[class=click_to_load_modal_popup]').each(function () {
            $(this).modalPopup({
                onRowAdded: App.initComponents
            });
        });
    }

    var handlePhotoCollection = function ($element) {
        if ($().collectionFormType) {
            $element.find('*[data-toggle=photoCollectionFormType]').each(function () {
                $(this).photoCollectionFormType({
                    onRowAdded: App.initComponents
                });
            });
        }
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


    //* END:CORE HANDLERS *//

    return {

        //main function to initiate the theme
        init: function () {
            handleInit();
            handleStoreSelect($document);
            handleNewsCategorySelect($document);
            handleCategorySelect($document);
            handleCollection($document);
            handlePhotoCollection($document);
            handleSubmitModal($document);
            handleOpenModalPopup($document);
            handleDeliveryTimeVisibility($document);
            handleStoreVisibility($document);
            handleAjaxModal($document);
        },

        //main function to initiate core javascript after ajax complete
        initAjax: function () {
            handleStoreSelect($document);
            handleNewsCategorySelect($document);
            handleCategorySelect($document);
            handleCollection($document);
            handlePhotoCollection($document);
            handleSubmitModal($document);
            handleOpenModalPopup($document);
            handleDeliveryTimeVisibility($document);
            handleStoreVisibility($document);
            handleAjaxModal($document);
        },

        //init main components
        initComponents: function ($element) {
            handleStoreSelect($element);
            handleNewsCategorySelect($element);
            handleCategorySelect($element);
            handleCollection($element);
            handlePhotoCollection($element);
            handleSubmitModal($element);
            handleOpenModalPopup($element);
            handleDeliveryTimeVisibility($element);
            handleStoreVisibility($element);
            handleAjaxModal($element);
        }
    };

}();

jQuery(document).ready(function () {
    App.init(); // init metronic core componets
});