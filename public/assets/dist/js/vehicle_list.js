(function ($) {
    "use strict";
    var tableBootstrap4Style = {
        initialize: function () {
            this.bootstrap4Styling();
            this.bootstrap4Modal();
            this.print();
        }, bootstrap4Styling: function () {
            $('.bootstrap4-styling').DataTable();
        }, bootstrap4Modal: function () {
            $('.bootstrap4-modal').DataTable({
                responsive: {
                    details: {
                        display: $.fn.dataTable.Responsive.display.modal({
                            header: function (row) {
                                var data = row.data();
                                return 'Details for ' + data[0] + ' ' + data[1];
                            }
                        }), renderer: $.fn.dataTable.Responsive.renderer.tableAll({tableClass: 'table'})
                    }
                }
            });
        }, print: function () {
            var vehictable = $('#vehicinfo').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    url: baseurl + "vehiclemgt/Vehicle_management/vehiclesearch",
                    type: "post",
                    "data": function (data) {
                        data.vehicle = $('#vehicle').val();
                        data.vehicle_typesr = $('#vehicle_typesr').val();
                        data.ownershipsr = $('#ownershipsr').val();
                        data.vendorsr = $('#vendorsr').val();
                        data.join_datefrsh = $('#registration_date_fr').val();
                        data.joining_d_to = $('#registration_date_to').val();
                    },
                    error: function () {
                        $("#employee_grid_processing").css("display", "none");
                        $('[data-toggle="tooltip"]').tooltip();
                    }
                },
                lengthChange: false,
            });
            new $.fn.dataTable.Buttons(vehictable, {
                buttons: [{
                    extend: 'copy',
                    className: 'btn-success'
                }, {extend: 'excel', className: 'btn-success'}, {
                    extend: 'pdf',
                    className: 'btn-success'
                }, {extend: 'print', className: 'btn-success'}, {extend: 'colvis', className: 'btn-success'}],
            });
            vehictable.buttons().container().appendTo('#vehicinfo_wrapper .col-md-6:eq(0)');
            $('#btn-filter').click(function () {
                vehictable.ajax.reload();
            });
            $('#btn-reset').click(function () {
                $('#vehicle').val('').trigger('change');
                $('#vehicle_typesr').val('').trigger('change');
                $('#ownershipsr').val('').trigger('change');
                $('#vendorsr').val('').trigger('change');
                $('#registration_date_fr').val('');
                $('#registration_date_to').val('');
                vehictable.ajax.reload();
            });
        }
    };
    $(document).ready(function () {
        "use strict";
        tableBootstrap4Style.initialize();
        $(".basic-single").select2();
        $('.timepicker').daterangepicker({
            singleDatePicker: true,
            timePicker: true,
            timePicker24Hour: false,
            "locale": {"format": "hh:mm A"}
        });
        $('.datetimepicker').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            minYear: 1901,
            "drops": "down",
            locale: {format: 'YYYY-MM-DD'},
            maxYear: parseInt(moment().format('YYYY'), 10)
        }, function (start, end, label) {
            var years = moment().diff(start, 'years');
        });
        $('.newdatetimepicker').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false,
            minYear: 1901,
            maxDate: '2100',
            "drops": "down",
            locale: {format: 'YYYY-MM-DD'},
            maxYear: parseInt(moment().format('YYYY'), 10)
        }, function (start, end, label) {
            var years = moment().diff(start, 'years');
        });
        $('.newdatetimepicker').on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
        });
        $('.newdatetimepicker').on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
        });
        $('.datetimepickerwd').daterangepicker({
            singleDatePicker: true,
            "timePicker": true,
            showDropdowns: true,
            "timePicker24Hour": true,
            minYear: 1901,
            "drops": "up",
            locale: {format: 'YYYY-MM-DD HH:mm'},
            maxYear: parseInt(moment().format('YYYY'), 10)
        }, function (start, end, label) {
            var years = moment().diff(start, 'years');
        });
    });
}(jQuery));
$("#driver").change(function () {
    var id = $('#driver option:selected').data('id');
    $("#dirverid").val(id);
});
