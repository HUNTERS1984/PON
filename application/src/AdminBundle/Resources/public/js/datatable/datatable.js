var Datatable = function () {

    var tableOptions; // main options
    var dataTable; // datatable object
    var table; // actual table jquery object
    var tableContainer; // actual table container object
    var tableWrapper; // actual table wrapper jquery object
    var tableInitialized = false;
    var ajaxParams = {}; // set filter mode
    var $this;

    return {
        //main function to initiate the module
        init: function (options) {

            if (!$().dataTable) {
                return;
            }

            $this = this;

            // default settings
            options = $.extend(true, {
                src: "", // actual table
                loadingMessage: 'Loading...',
                dataTable: {
                    "dom": "<'row'<'col-md-8 col-sm-12'l><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r><'table-responsive't><'row'<'col-sm-6'i><'col-sm-6'p>>", // datatable layout
                    "pageLength": 10, // default records per page
                    "orderCellsTop": true,
                    "order": [],
                    "columnDefs": [{ // define columns sorting options(by default all columns are sortable extept the first checkbox column)
                        'orderable': false,
                        'targets': 'datatable-nosort'
                    }]
                }
            }, options);

            tableOptions = options;

            // create table's jquery object
            table = $(options.src);
            tableContainer = table.parents(".table-container");

            // apply the special class that used to restyle the default datatable
            var tmp = $.fn.dataTableExt.oStdClasses;

            $.fn.dataTableExt.oStdClasses.sWrapper = $.fn.dataTableExt.oStdClasses.sWrapper + " dataTables_extended_wrapper";
            $.fn.dataTableExt.oStdClasses.sFilterInput = "form-control input-xs input-sm input-inline";
            $.fn.dataTableExt.oStdClasses.sLengthSelect = "form-control input-xs input-sm input-inline";

            // initialize a datatable
            dataTable = table.DataTable(options.dataTable);

            var colvis = new $.fn.dataTable.Buttons(dataTable, {
                buttons: [
                    {
                        extend: 'colvis',
                        className: 'btn-sm',
                        text: '<i class="glyphicon glyphicon-refresh"></i>',
                        columns: ':not(:first-child)'
                    }
                ]
            });

            // revert back to default
            $.fn.dataTableExt.oStdClasses.sWrapper = tmp.sWrapper;
            $.fn.dataTableExt.oStdClasses.sFilterInput = tmp.sFilterInput;
            $.fn.dataTableExt.oStdClasses.sLengthSelect = tmp.sLengthSelect;

            // get table wrapper
            tableWrapper = table.parents('.dataTables_wrapper');

            // build table group actions panel
            if ($('.table-actions-wrapper', tableContainer).size() === 1) {
                $('.table-group-actions', tableWrapper).html($('.table-actions-wrapper', tableContainer).html()); // place the panel inside the wrapper
                dataTable.buttons(0, null).container().appendTo($('.table-group-actions .columns'));
                $('.table-actions-wrapper', tableContainer).remove(); // remove the template container
            }
        },

        getDataTable: function () {
            return dataTable;
        },

        getTableWrapper: function () {
            return tableWrapper;
        },

        gettableContainer: function () {
            return tableContainer;
        },

        getTable: function () {
            return table;
        }
    };
};