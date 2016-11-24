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

    var handlePhotoCollection = function ($element) {
        if ($().collectionFormType) {
            $element.find('*[data-toggle=photoCollectionFormType]').each(function () {
                $(this).photoCollectionFormType({
                    onRowAdded: App.initComponents
                });
            });
        }
    };


    //* END:CORE HANDLERS *//

    return {

        //main function to initiate the theme
        init: function () {
            handleInit();
            handleCollection($document);
            handlePhotoCollection($document);
            handleSubmitModal($document);
        },

        //main function to initiate core javascript after ajax complete
        initAjax: function () {
            handleCollection($document);
            handlePhotoCollection($document);
            handleSubmitModal($document);
        },

        //init main components
        initComponents: function ($element) {
            handleCollection($element);
            handlePhotoCollection($element);
            handleSubmitModal($element);
        }
    };

}();

jQuery(document).ready(function () {
    App.init(); // init metronic core componets
});