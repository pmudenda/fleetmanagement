(function (tmsApp, $) {

    let myModalEl = document.getElementById('kt_modal_add_brand');
    let myModal = new bootstrap.Modal(myModalEl);

    myModalEl.addEventListener('hidden.bs.modal', function (event) {
        document.querySelector('[name="addRecordForm"]').reset();
    });

    $(document).on('keyup paste', '#brand_name', function () {
        this.value = this.value.toLocaleUpperCase();
    });


    tmsApp.appFormValidator('form[name="addRecordForm"]',
        {
            'brand_name': {
                required: 'brand name is required',
                maxlength: 50
            }
        },
        {
            'brand_name': {
                required: 'Brand name is required',
                maxlength: 'Brand can not be more than 50 characters'
            }
        },
    );

    function addRecordToTable() {
        setTimeout(function (response) {

            Vue.set(app.$data.brands, app.$data.brands.length + 1, response.payload);

            //app.destroyDataTable();
            /* setTimeout(function () {
                 app.initDatatable();
             }, 300);*/
        }, 2000);
    }

    function submitVehicleBrand() {
        let $form = document.querySelector('form[name="addRecordForm"]')

        if (!$($form).valid()) {
            toastr.warning(
                "Sorry, the data did not pass validation check, Brand name is required, check the data and try again.",
                "Validation Failure"
            );
            return;
        }

        tmsApp.confirm('Vehicle Brand',
            'Are you sure you want to submit the request ?',
            'Yes',
            'No, Cancel',
            function () {
                bootstrap.Modal.getOrCreateInstance(document.querySelector('#kt_modal_add_brand')).hide();
                setTimeout(function () {
                    tmsApp.asyncPostFormData(
                        $form.action,
                        new FormData($form),
                        function (asyncResponse) {
                            if (asyncResponse.hasOwnProperty('success') && !asyncResponse.success) {
                                if (asyncResponse.hasOwnProperty('errors')) {
                                    tmsApp.printErrorMsg(asyncResponse.errors);
                                    return
                                }

                                setTimeout(function () {
                                    tmsApp.systemError(
                                        'Vehicle Make Record Creation',
                                        asyncResponse['message'],
                                        function () {
                                        }, 'error');
                                }, 300);
                                toastr.error(
                                    asyncResponse.message
                                );
                                return;
                            }
                            let message = 'Record Created Successfully';
                            tmsApp.showSystemMessage(
                                'Record Creation',
                                message,
                                function () {
                                    setTimeout(
                                        function () {
                                            window.location.reload();
                                        }, 500
                                    );
                                }, 'success');
                        },
                        function (xhr, settings, errorThrown) {
                            setTimeout(function () {
                                tmsApp.showErrorMessages(xhr, 'Record Creation');
                            }, 300)
                        }
                    );
                }, 300)

            }, function () {
        });
    }

    $(document).on('click', '#submitAddFormRecord', function (e) {
        submitVehicleBrand();
    });

}(window.tmsApp || {}, jQuery));

let options = {
    'el': '#app_main',
    data: {
        search: null,
        brands: [],
        table: null,
        datatable: null,
        modalEl: null,
        modal: null,
        validator: null,
        form: null,
        isEnabled: true,
        brand_name: null,
        status: null,
        statusList: [],
    },
    methods: {

        getBrands() {
            $.get(document.querySelector('#newBrandEndpoint').value)
                .done(function (response) {
                    // Populate results
                    if (response.state === 'failure') {
                        //show errors
                        toastr.error('Connection error, no data found')
                        return;
                    }

                    app.brands = response.payload;

                    app.$nextTick(function () {
                        app.initDatatable();
                    });
                })
                .fail(function (error) {
                    // notify of error
                    toastr.error(
                        'Connection error. Could not retrieve data, some feature might not work.')
                });
        },

        destroyDataTable() {
            this.datatable.destroy();
        },

        initDatatable: function () {
            const tableRows = this.table.querySelectorAll('tbody tr');

            tableRows.forEach(row => {
                const dateColumn = row.querySelectorAll('td');
                if (dateColumn.length < 4) {
                    return;
                }

                const realDate = moment(dateColumn[3].innerHTML, "DD MMM YYYY, LT").format();
                // select date from 4th column in table
                dateColumn[3].setAttribute('data-order', realDate);
            });

            this.datatable = $(this.table).DataTable({
                /*"info": false,*/
                'order': [],
                "pageLength": 10,
                "lengthChange": false,
                'columnDefs': [
                    {
                        orderable: false,
                        targets: 0
                    },
                    {
                        orderable: false,
                        targets: 4
                    }
                ]
            });

            this.datatable.on('draw', function () {
            });
        },

        add: function () {
            // Select modal buttons
            const closeButton = this.modalEl.querySelector('#kt_modal_add_close');
            const cancelButton = this.modalEl.querySelector('#kt_modal_add_cancel');
            //const submitButton = this.modalEl.querySelector('#kt_modal_add_submit');

            // Cancel button action
            cancelButton.addEventListener('click', function (e) {
                e.preventDefault();

                Swal.fire({
                    text: "Are you sure you would like to cancel?"
                    , icon: "warning"
                    , showCancelButton: true
                    , buttonsStyling: false
                    , confirmButtonText: "Yes, cancel it!"
                    , cancelButtonText: "No, return"
                    , customClass: {
                        confirmButton: "btn btn-primary"
                        , cancelButton: "btn btn-active-light"
                    }
                }).then(function (result) {
                    if (result.value) {
                        app.modal.hide();
                    } else if (result.dismiss === 'cancel') {

                    }
                });
            });
        },

    },

    filters: {
        formatToFriendlyDate(value) {
            if (!value) return value;
            return new Date(value).toDateString();
        }
    },

    created() {
        this.getBrands();
    },

    mounted() {

        this.modalEl = document.getElementById('kt_modal_add_brand');

        this.modal = new bootstrap.Modal(this.modalEl);

        this.table = document.querySelector('#kt_brands_table');

        this.add();

        this.modalEl.addEventListener('hidden.bs.modal', function (event) {
            document.querySelector('[name="addRecordForm"]').reset();
        })
    }
}
let app = new Vue(options);

