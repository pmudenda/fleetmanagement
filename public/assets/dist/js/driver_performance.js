(function ($) {
    "use strict";
    var driverexinfodata = $('#driverexinfo2').DataTable({
        dom: 'Bfrtip',
        buttons: [{extend: 'copy', className: 'btn-success', footer: true}, {
            extend: 'csv',
            title: 'Driver_Performance',
            className: 'btn-success',
            footer: true
        }, {extend: 'excel', title: 'Driver_Performance', className: 'btn-success', footer: true}, {
            extend: 'pdf',
            title: 'Driver_Performance',
            className: 'btn-success',
            footer: true
        }, {extend: 'print', className: 'btn-success', footer: true}, {
            extend: 'colvis',
            className: 'btn-success',
            footer: true
        }],
        responsive: true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: baseurl + "employeeManagement/Driver_controller/datadriverplist", type: "post", error: function () {
                $("#employee_grid_processing").css("display", "none");
                $('[data-toggle="tooltip"]').tooltip();
            }
        },
        lengthChange: false,
    });
    $('div.dataTables_filter').addClass('right');
}(jQuery));
$(document).ready(function () {
    $(".basic-single").select2();
    $('.timepicker').daterangepicker({
        singleDatePicker: true,
        timePicker: true,
        timePicker24Hour: false,
        "locale": {"format": "hh:mm A"}
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
    $('.datetimepicker').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        minYear: 1901,
        "drops": "up",
        locale: {format: 'YYYY-MM-DD'},
        maxYear: parseInt(moment().format('YYYY'), 10)
    }, function (start, end, label) {
        var years = moment().diff(start, 'years');
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
    $('.form-check-input').bootstrapToggle();
    $('.skin-minimal .i-check input').iCheck({
        checkboxClass: 'icheckbox_minimal',
        radioClass: 'iradio_minimal',
        increaseArea: '20%'
    });
    $('.skin-square .i-check input').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green'
    });
    $('.skin-flat .i-check input').iCheck({checkboxClass: 'icheckbox_flat-red', radioClass: 'iradio_flat-red'});
    $('.skin-line .i-check input').each(function () {
        var self = $(this), label = self.next(), label_text = label.text();
        label.remove();
        self.iCheck({
            checkboxClass: 'icheckbox_line-blue',
            radioClass: 'iradio_line-blue',
            insert: '<div class="icheck_line-icon"></div>' + label_text
        });
    });
});
