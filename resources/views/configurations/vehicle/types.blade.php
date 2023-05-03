@extends('layouts.layout')
@push('styles')
@endpush
@section('content')
    <div class="card">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                <span class="svg-icon svg-icon-1 position-absolute ms-6">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1"
                              transform="rotate(45 17.0365 15.1223)" fill="currentColor"></rect>
                        <path
                            d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                            fill="currentColor"></path>
                    </svg>
                </span>
                    <input type="text" v-model="search" data-kt-table-filter="search"
                           class="form-control form-control-solid w-250px ps-14" placeholder="Search Body Types">
                </div>
            </div>

            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <!--begin::Toolbar-->
                <div class="d-flex justify-content-end" kt_table-toolbar="base">
                    <!--begin::Filter-->
                    <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click"
                            data-kt-menu-placement="bottom-end">
                    <span class="svg-icon svg-icon-2">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z"
                                fill="currentColor"></path>
                        </svg>
                    </span>
                        Filter
                    </button>

                    <!--begin::Menu 1-->
                    <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true">
                        <!--begin::Header-->
                        <div class="px-7 py-5">
                            <div class="fs-5 text-dark fw-bold">Filter Options</div>
                        </div>

                        <div class="separator border-gray-200"></div>

                        <div class="px-7 py-5" data-kt-table-filter="form">
                            <div class="mb-10">
                                <label for="data-kt-table-filter_month"
                                       class="form-label fs-6 fw-semibold">Month:</label>
                                <select id="data-kt-table-filter_month" class="form-select form-select-solid fw-bold"
                                        data-kt-select2="true" data-placeholder="Select option" data-allow-clear="true"
                                        data-kt-table-filter="month" data-hide-search="true"
                                        data-select2-id="select2-data-10-tvzx" tabindex="-1" aria-hidden="true">
                                    <option data-select2-id="select2-data-12-scpe"></option>
                                    <option value="jan">January</option>
                                    <option value="feb">February</option>
                                    <option value="mar">March</option>
                                    <option value="apr">April</option>
                                    <option value="may">May</option>
                                    <option value="jun">June</option>
                                    <option value="jul">July</option>
                                    <option value="aug">August</option>
                                    <option value="sep">September</option>
                                    <option value="oct">October</option>
                                    <option value="nov">November</option>
                                    <option value="dec">December</option>
                                </select>
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="mb-10">
                                <label for="data-kt-table-filter_status"
                                       class="form-label fs-6 fw-semibold">Status:</label>
                                <select id="data-kt-table-filter_status" class="form-select form-select-solid fw-bold"
                                        data-kt-select2="true" data-placeholder="Select option" data-allow-clear="true"
                                        data-kt-table-filter="status" data-hide-search="true"
                                        data-select2-id="select2-data-13-gx72" tabindex="-1" aria-hidden="true">
                                    <option data-select2-id="select2-data-15-256v"></option>
                                    <option value="Active">Active</option>
                                    <option value="Expiring">Expiring</option>
                                    <option value="Suspended">Suspended</option>
                                </select>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="reset"
                                        class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6"
                                        data-kt-menu-dismiss="true" data-kt-table-filter="reset">Reset
                                </button>
                                <button type="submit" class="btn btn-primary fw-semibold px-6"
                                        data-kt-menu-dismiss="true" data-kt-table-filter="filter">Apply
                                </button>
                            </div>
                        </div>
                    </div>


                    <button type="button" data-bs-toggle="modal" data-bs-target="#kt_modal_add"
                            class="btn btn-primary">
                    <span class="svg-icon svg-icon-2"><svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                           xmlns="http://www.w3.org/2000/svg">
                            <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1"
                                  transform="rotate(-90 11.364 20.364)" fill="currentColor"></rect>
                            <rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="currentColor"></rect>
                        </svg></span>
                        Add Vehicle Body Types
                    </button>
                </div>

                <div class="d-flex justify-content-end align-items-center d-none" kt_table-toolbar="selected">
                    <div class="fw-bold me-5">
                        <span class="me-2" kt_table-select="selected_count"></span> Selected
                    </div>

                    <button type="button" class="btn btn-danger" kt_table-select="delete_selected">
                        Delete Selected
                    </button>
                </div>

            </div>

        </div>

        <!--begin::Card body-->
        <div class="card-body pt-0">

            <!--begin::Table-->
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer" id="kt_brands_table">
                    <thead>
                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                        <th>
                            <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                <input class="list-row-checkbox" type="checkbox" data-kt-check="true"
                                       data-kt-check-target="#kt_brands_table .form-check-input" value="all"/>
                            </div>
                        </th>

                        <th>
                            Body Type
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Created Date
                        </th>

                        <th>
                            Actions
                        </th>
                    </tr>
                    </thead>

                    <tbody class="text-gray-600 fw-semibold">
                    <tr v-for="(item, index) in bodyTypes">

                        <td>
                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                <input class="list-row-checkbox" type="checkbox" :value="item.guid"/>
                            </div>
                        </td>

                        <td>
                            <a href="#" class="text-gray-800 text-hover-primary mb-1">
                                @{{ item.body_type_name }}
                            </a>
                        </td>

                        <td>
                            <div v-if="item.status.toLowerCase() === '01'" class="badge badge-light-success">
                                Active
                            </div>
                            <div v-else-if="item.status.toLowerCase() === 'expiring'" class="badge badge-light-warning">
                                Expiring
                            </div>
                            <div v-else-if="item.status.toLowerCase() === 'suspended'" class="badge badge-light-danger">
                                Suspended
                            </div>
                            <div v-else class="badge badge-danger">
                                @{{ item.status.toLowerCase() }}
                            </div>
                        </td>


                        <td>
                            @{{ item.date_created | formatToFriendlyDate }}
                        </td>

                        <td class="text-start">
                            <a href="#" class="btn btn-light btn-active-light-primary btn-sm"
                               data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                Actions
                                <span class="svg-icon svg-icon-5 m-0"><svg width="24" height="24" viewBox="0 0 24 24"
                                                                           fill="none"
                                                                           xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z"
                                            fill="currentColor"></path>
                                    </svg>
                                </span>

                                <div
                                    class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                    data-kt-menu="true">

                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3">
                                            Edit
                                        </a>
                                    </div>

                                    <div class="menu-item px-3">
                                        <a href="#" data-kt-action="remove" data-kt-table-filter="delete_row"
                                           class="menu-link px-3">
                                            Delete
                                        </a>
                                    </div>

                                </div>
                            </a>
                        </td>

                    </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- Add Brand Modal -->

    <div class="modal fade" id="kt_modal_add" tabindex="-1" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <form class="form" action="#" id="kt_modal_add_form">
                    <div class="modal-header">
                        <h2 class="fw-bold">Add a Vehicle Body Type</h2>

                        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <span class="svg-icon svg-icon-1">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                      transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
                                <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)"
                                      fill="currentColor"></rect>
                            </svg>

                        </span>
                        </div>
                    </div>

                    <div class="modal-body py-10 px-lg-17">

                        <div class="scroll-y mh-300px me-n7 pe-7">
                            <div class="fv-row">
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <label for="body_type_name"
                                           class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span class="required">Name</span>
                                        <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                           aria-label="Specify body type name"
                                           data-bs-original-title="Specify body type name"
                                           data-kt-initialized="1"></i>
                                    </label>

                                    <input type="text" class="form-control form-control-solid" placeholder="e.g Sedan, "
                                           v-model="body_type_name" id="body_type_name" name="body_type_name"/>
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                </div>

                                <div class="d-flex flex-stack">

                                    <div class="me-5">
                                        <label class="fs-6 fw-semibold form-label">Is Active ?</label>
                                    </div>

                                    <label class="form-check form-switch form-check-custom form-check-solid">
                                        <input class="form-check-input" v-model='isEnabled' type="checkbox" value="1"
                                               checked="checked">
                                        <span class="form-check-label fw-semibold text-muted">
                                        <span v-if="isEnabled">Yes</span>
                                        <span v-else="isEnabled">No</span>
                                    </span>
                                    </label>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer flex-center">

                        <button type="reset" id="kt_modal_add_cancel" class="btn btn-light me-3">
                            Discard
                        </button>
                        <button type="button" v-on:click="submitData" id="kt_modal_add_submit" class="btn btn-primary">
                        <span class="indicator-label">
                            Submit
                        </span>
                            <span class="indicator-progress">
                            Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <input type="hidden" id="bodyTypesEndpoint" name="bodyTypesEndpoint" value="{{ route('body-types.index') }}">
@endsection

@push('scripts')
    <script>
        let app = new Vue({
            'el': '#kt_app_main'
            , data: {
                search: null
                , bodyTypes: []
                , table: null
                , datatable: null
                , modalEl: null
                , modal: null
                , validator: null
                , form: null
                , isEnabled: true
                , body_type_name: null

            }
            , methods: {

                getBodyTypes() {
                    axios.get(document.querySelector('#bodyTypesEndpoint').value)
                        .then(function (response) {
                            // Populate results
                            if (response.data.state === 'failure') {
                                //show errors
                                toastr.error('Connection error, no data found')
                                return;
                            }

                            app.bodyTypes = response.data.payload;

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

                editItem(item) {

                },

                postDeleteItem(parent, item) {
                    axios.delete(document.querySelector('#bodyTypesEndpoint').value, {
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
                                'body_type_name': {
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
                submitData: function () {
                    let radio = this.modalEl.querySelector('input[type="checkbox"]:checked');
                    if (this.validator) {
                        this.validator.validate().then(function (status) {
                            const submitButton = app.modalEl.querySelector('#kt_modal_add_submit');
                            console.log('validated!');
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
                    axios.post(document.querySelector('#bodyTypesEndpoint').value, {
                        body_type_name: app.body_type_name
                        , status: app.isEnabled ? 'active' : 'inactive'
                    }, {
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            , 'content-type': 'text/json'
                        }
                    })
                        .then(function (response) {
                            let data = response.data.payload;

                            setTimeout(function () {

                                // Remove loading indication
                                submitButton.removeAttribute('data-kt-indicator');

                                if (response.data.state === 'failure') {
                                    submitButton.disabled = false;

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
                                        window.location.reload();
                                    }
                                });
                            }, 2000);
                        })
                        .catch(function (error) {
                            // Show error message
                            submitButton.disabled = false;

                        });

                }
            }
            , filters: {
                formatToFriendlyDate(value) {
                    if (!value) return value;
                    return new Date(value).toDateString();
                }
            }
            , created() {
                this.getBodyTypes();
            }
            , mounted() {

                this.modalEl = document.getElementById('kt_modal_add');

                this.modal = new bootstrap.Modal(this.modalEl);

                this.table = document.querySelector('#kt_brands_table');

                this.form = document.querySelector('#kt_modal_add_form');

                this.add();

                this.handleFilter();
                this.handleSearch();
                this.initValidator();
                this.initDeleteButton();
            }
        })

    </script>
@endpush
