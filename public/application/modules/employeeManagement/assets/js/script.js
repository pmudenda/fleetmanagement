(function($) {
    "use strict";
    var driverdata = $('#driverinfo').DataTable({
        dom: 'Bfrtip',
        buttons: [{
            extend: 'copy',
            className: 'btn-success',
            footer: true
        }, {
            extend: 'csv',
            title: 'Driver_Performance',
            className: 'btn-success',
            footer: true
        }, {
            extend: 'excel',
            title: 'Driver_Performance',
            className: 'btn-success',
            footer: true
        }, {
            extend: 'pdf',
            title: 'Driver_Performance',
            className: 'btn-success',
            footer: true
        }, {
            extend: 'print',
            className: 'btn-success',
            footer: true
        }, {
            extend: 'colvis',
            className: 'btn-success',
            footer: true
        }],
        responsive: true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: baseurl + "employeeManagement/Driver_controller/datadriverlist",
            type: "post",
            error: function() {
                $("#employee_grid_processing").css("display", "none");
                $('[data-toggle="tooltip"]').tooltip();
            }
        },
        lengthChange: false,
    });
    $('div.dataTables_filter').addClass('right');
}(jQuery));
(function($) {
    "use strict";
    var driverexinfodata = $('#driverexinfo').DataTable({
        dom: 'Bfrtip',
        buttons: [{
            extend: 'copy',
            className: 'btn-success',
            footer: true
        }, {
            extend: 'csv',
            title: 'Driver_Performance',
            className: 'btn-success',
            footer: true
        }, {
            extend: 'excel',
            title: 'Driver_Performance',
            className: 'btn-success',
            footer: true
        }, {
            extend: 'pdf',
            title: 'Driver_Performance',
            className: 'btn-success',
            footer: true
        }, {
            extend: 'print',
            className: 'btn-success',
            footer: true
        }, {
            extend: 'colvis',
            className: 'btn-success',
            footer: true
        }],
        responsive: true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: baseurl + "employeeManagement/Driver_controller/datadriverplist",
            type: "post",
            error: function() {
                $("#employee_grid_processing").css("display", "none");
                $('[data-toggle="tooltip"]').tooltip();
            }
        },
        lengthChange: false,
    });
}(jQuery));
