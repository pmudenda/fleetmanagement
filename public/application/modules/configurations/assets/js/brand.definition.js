let app = new Vue({
    'el': '#kt_app_main'
    , data: {
        search: null
        , brands: []
        , table: null
        , datatable: null
        , modalEl: null
        , modal: null
        , validator: null
        , form: null
        , isEnabled: true
        , brand_name: null
        , status: null
        , statusList: []
    }
    , methods: {

        getBrands() {
            axios.get(document.querySelector('#newBrandEndpoint').value)
                .then(function (response) {
                    // Populate results
                    if (response.data.state === 'failure') {
                        //show errors
                        toastr.error('Connection error, no data found')
                        return;
                    }

                    app.brands = response.data.payload;

                    app.$nextTick(function () {
                        app.initDatatable();
                    });
                })
                .catch(function (error) {
                    // notify of error
                    toastr.error(
                        'Connection error. Could not retrieve data, some feature might not work.')
                });
        },

        find() {
            if (!this.search) {

            }
        },

        editItem(item) {
        },

        postDeleteItem(parent, item) {
            axios.delete(document.querySelector('#newBrandEndpoint').value, {
                headers: {
                    Authorization: 'Token'
                }
                , data: {
                    guid: item
                }
            })
                .then(function (response) {
                    if (response.data.state === 'failure') {
                        toastr.error(
                            'Connection error. Could not delete record');
                        return;
                    }

                    Swal.fire({
                        text: "You have deleted " + item.itemName +
                            "!."
                        , icon: "success"
                        , buttonsStyling: false
                        , confirmButtonText: "Ok, got it!"
                        , customClass: {
                            confirmButton: "btn fw-bold btn-primary"
                            ,
                        }
                    }).then(function () {
                        // Remove current row
                        app.datatable.row($(parent)).remove().draw();
                    });
                })
                .catch(function (error) {
                    toastr.error(
                        'Connection error. Could not retrieve data, some feature might not work.'
                    )
                })
        },

        initValidator() {
            this.validator = FormValidation.formValidation(
                this.form, {
                    fields: {
                        'brand_name': {
                            validators: {
                                notEmpty: {
                                    message: 'brand name is required'
                                }
                                , stringLength: {
                                    min: 3
                                    , max: 200
                                    , message: 'brand name must contain 3 to 50 characters'
                                }
                            }
                        },
                        "status": {
                            validators: {
                                notEmpty: {
                                    message: 'Status is required'
                                }
                            }
                        }
                    },

                    plugins: {
                        trigger: new FormValidation.plugins.Trigger({})
                        , bootstrap: new FormValidation.plugins.Bootstrap5({
                            rowSelector: '.fv-row'
                            , eleInvalidClass: ''
                            , eleValidClass: ''
                        })
                    }
                }
            );
        },

        destroyDataTable(){
            this.datatable.destroy();
        },

        initDatatable: function () {
            const tableRows = this.table.querySelectorAll('tbody tr');

            tableRows.forEach(row => {
                const dateColumn = row.querySelectorAll('td');
                if (dateColumn.length < 4) {
                    return;
                }

                const realDate = moment(dateColumn[3].innerHTML, "DD MMM YYYY, LT")
                    .format(); // select date from 4th column in table
                dateColumn[3].setAttribute('data-order', realDate);
            });

            // Disable ordering on column 0 (checkbox)
            // Disable ordering on column 6 (actions)
            this.datatable = $(this.table).DataTable({
                "info": false
                , 'order': []
                , "pageLength": 10
                , "lengthChange": false
                , 'columnDefs': [{
                    orderable: false
                    , targets: 0
                }, {
                    orderable: false
                    , targets: 4
                },

                ]
            });

            // Re-init functions on every table re-draw https://datatables.net/reference/event/draw
            this.datatable.on('draw', function () {
                //initToggleToolbar();
                //handleRowDeletion();
                //toggleToolbars();
            });
        },

        add: function () {
            // Select modal buttons
            const closeButton = this.modalEl.querySelector('#kt_modal_add_close');
            const cancelButton = this.modalEl.querySelector('#kt_modal_add_cancel');
            const submitButton = this.modalEl.querySelector('#kt_modal_add_submit');

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

        initDeleteButton: function () {
            KTUtil.on(this.table, '[data-kt-action="remove"]', 'click', function (e) {
                e.preventDefault();

                // Select parent row
                const parent = e.target.closest('tr');

                // Get customer name
                const itemName = parent.querySelectorAll('td')[1].innerText;
                const guid = parent.querySelector(
                    '[type="checkbox"]').value;
                Swal.fire({
                    text: "Are you sure you want to delete " + itemName +
                        "?"
                    , icon: "warning"
                    , showCancelButton: true
                    , buttonsStyling: false
                    , confirmButtonText: "Yes, delete!"
                    , cancelButtonText: "No, cancel"
                    , customClass: {
                        confirmButton: "btn fw-bold btn-danger"
                        , cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then(function (result) {
                    if (result.value) {
                        app.postDeleteItem(parent, guid);
                    } else if (result.dismiss === 'cancel') {

                    }
                });
            });
        },

        handleFilter: function () {
            // Select filter options
            const filterForm = document.querySelector('[data-kt-table-filter="form"]');
            const filterButton = filterForm.querySelector('[data-kt-table-filter="filter"]');
            const resetButton = filterForm.querySelector('[data-kt-table-filter="reset"]');
            const selectOptions = filterForm.querySelectorAll('select');

            // Filter datatable on submit
            filterButton.addEventListener('click', function () {
                let filterString = '';

                console.log('filtering table data')

                // Get filter values
                selectOptions.forEach((item, index) => {
                    if (item.value && item.value !== '') {
                        if (index !== 0) {
                            filterString += ' ';
                        }

                        // Build filter value options
                        filterString += item.value;
                    }
                });

                // Filter datatable https://datatables.net/reference/api/search()
                app.datatable.search(filterString).draw();
            });

            // Reset datatable
            resetButton.addEventListener('click', function () {
                // Reset filter form
                selectOptions.forEach((item, index) => {
                    // Reset Select2 dropdown https://select2.org/programmatic-control/add-select-clear-items
                    $(item).val(null).trigger('change');
                });

                // Filter https://datatables.net/reference/api/search()
                app.datatable.search('').draw();
            });
        },

        handleSearch: function () {
            const filterSearch = document.querySelector('[data-kt-table-filter="search"]');
            filterSearch.addEventListener('keyup', function (e) {
                app.datatable.search(e.target.value).draw();
            });
        },

        // Add customer button handler
        submitBrand: function () {
            let radio = this.modalEl.querySelector('input[type="checkbox"]:checked');
            if (this.validator) {
                this.validator.validate().then(function (status) {
                    const submitButton = app.modalEl.querySelector('#kt_modal_add_submit');

                    if (status === 'Valid') {
                        // Show loading indication
                        submitButton.setAttribute('data-kt-indicator', 'on');

                        // Disable button to avoid multiple click
                        submitButton.disabled = true;
                        app.postRequest();
                    } else {
                        toastr.warning(
                            'Sorry, looks like there are some errors detected, please try again.'
                        );
                    }
                });
            }
        },

        postRequest() {
            const submitButton = app.modalEl.querySelector('#kt_modal_add_submit');
            axios.post(document.querySelector('#newBrandEndpoint').value, {
                name: app.brand_name
                , status: document.querySelector('[name="status"]').value
            }, {
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    , 'content-type': 'text/json'
                }
            })
                .then(function (response) {
                    let data = response.data?.payload;

                    setTimeout(function () {

                        // Remove loading indication
                        submitButton.removeAttribute('data-kt-indicator');
                        submitButton.disabled = false;

                        if (response.data.state === 'failure') {

                            Swal.fire({
                                text: response.data.message
                                , icon: "error"
                                , buttonsStyling: false
                                , confirmButtonText: "Ok"
                                , customClass: {
                                    confirmButton: "btn fw-bold btn-primary"
                                    ,
                                }
                            });

                            return;
                        }

                        app.brands.push(data);

                        app.destroyDataTable();

                        // Show popup confirmation
                        let message = response.data.message;
                        Swal.fire({
                            text: message ||
                                "Request has been successfully submitted!"
                            , icon: "success"
                            , buttonsStyling: false
                            , confirmButtonText: "Ok, got it!"
                            , customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        }).then(function (result) {
                            if (result.isConfirmed) {
                                app.modal.hide();
                            }
                        });

                        app.initDatatable();
                    }, 2000);


                })
                .catch(function (error) {
                    // Show error message
                    submitButton.disabled = false;

                });

        },

        loadStatuses() {
            const status = ["Active ","Inactive", "Suspended", "Decommissioned"];
            this.statusList = status.sort();
        }
    }
    , filters: {
        formatToFriendlyDate(value) {
            if (!value) return value;

            return new Date(value).toDateString();
        }
    }
    , created() {
        this.getBrands();
    }
    , mounted() {

        this.modalEl = document.getElementById('kt_modal_add_brand');

        this.modal = new bootstrap.Modal(this.modalEl);

        this.table = document.querySelector('#kt_brands_table');

        this.form = document.querySelector('#kt_modal_add_form');

        this.add();

        this.handleFilter();
        this.handleSearch();
        this.initValidator();
        this.initDeleteButton();
        this.loadStatuses();

        this.modalEl.addEventListener('hidden.bs.modal', function (event) {
            app.form.reset();
        })
    }
})

