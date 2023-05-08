@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endpush
@section('content')
    <x-content-header :pageTitle="'Vehicle Model'" :activeCrumb="'Models'" :link="'home'"
                      :linkText="'Home'"/>

    <section class="content" id="app_main">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                </div>

                <!--begin::Card toolbar-->
                <div class="card-toolbar justify-content-end">
                    <!--begin::Toolbar-->
                    <div class="" data-kt-table-toolbar="base">
                        <button type="button" data-bs-toggle="modal" data-bs-target="#kt_modal_add"
                                class="btn btn-sm btn-primary">
                            <i class="fas fa-plus"></i>
                            Add
                        </button>
                    </div>
                </div>

            </div>

            <!--begin::Card body-->
            <div class="card-body pt-0">

                <!--begin::Table-->
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer" id="kt_table">
                        <thead>
                        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                            <th>
                                <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                    <input class="list-row-checkbox" type="checkbox" data-kt-check="true"
                                           data-kt-check-target="#kt_table .form-check-input" value="all"/>
                                </div>
                            </th>

                            <th>
                                Brand
                            </th>

                            <th>
                                Model
                            </th>

                            <th>
                                Code
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Date Registered
                            </th>

                            <th>
                                Actions
                            </th>
                        </tr>
                        </thead>

                        <tbody class="text-gray-600 fw-semibold">
                        <tr v-for="(item, index) in configuredModels">

                            <td>
                                <div class="form-check form-check-sm form-check-custom form-check-solid">
                                    <input class="list-row-checkbox" type="checkbox" :value="item.guid"/>
                                </div>
                            </td>

                            <td>
                                <a href="#" v-bind:data-id="item.brand_guid"
                                   class="text-gray-800 text-hover-primary mb-1">
                                    @{{ item.brand_name }}
                                </a>
                            </td>

                            <td>
                                <a href="#" v-bind:data-id="item.model_guid"
                                   class="text-gray-800 text-hover-primary mb-1">
                                    @{{ item.model_name }}
                                </a>
                            </td>

                            <td>
                                <a href="#" v-bind:data-id="item.model_guid"
                                   class="text-gray-800 text-hover-primary mb-1">
                                    @{{ item.model_code }}
                                </a>
                            </td>


                            <td>
                                <div v-if="item.status === 'active'" class="badge badge-light-success">
                                    Active
                                </div>
                                <div v-else-if="item.status === 'expiring'" class="badge badge-light-warning">
                                    Expiring
                                </div>
                                <div v-else-if="item.status === 'suspended'" class="badge badge-light-danger">
                                    Suspended
                                </div>
                                <div v-else class="badge badge-danger">
                                    No Set
                                </div>
                            </td>


                            <td>
                                @{{ item.date_created | formatToFriendlyDate }}
                            </td>

                            <td class="text-start">
                                <a href="#" class="btn btn-light btn-active-light-primary btn-sm"
                                   data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                    Actions
                                    <span class="svg-icon svg-icon-5 m-0"><svg width="24" height="24"
                                                                               viewBox="0 0 24 24" fill="none"
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

        <div class="modal fade" id="kt_modal_add" tabindex="-1" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-dialog-centered mw-650px">
                <div class="modal-content">
                    <form class="form" action="#" id="kt_modal_add_form">
                        <div class="modal-header">
                            <h2 class="fw-bold">Add a Vehicle Model</h2>

                            <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                            <span class="svg-icon svg-icon-1">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <rect opacity="0.5" x="6" y="17.3137" width="16" height="2"
                                          rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
                                    <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                          transform="rotate(45 7.41422 6)" fill="currentColor"></rect>
                                </svg>

                            </span>
                            </div>
                        </div>

                        <div class="modal-body py-10 px-lg-17">

                            <div class="scroll-y mh-300px me-n7 pe-7">
                                <div class="fv-row">

                                    <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                        <label for="brand_name"
                                               class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                            <span class="required">Brand</span>
                                            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                               aria-label="Specify brand" data-bs-original-title="Specify brand"
                                               data-kt-initialized="1"></i>
                                        </label>

                                        <select name="brand" id="data-kt-table-filter_month"
                                                class="form-select form-select-solid fw-bold" data-kt-select2="true"
                                                data-placeholder="Select option" data-allow-clear="true"
                                                data-kt-table-filter="brand" data-hide-search="true"
                                                data-select2-id="select2-data-17-tvzx" tabindex="-1" aria-hidden="true">
                                            <option data-select2-id="select2-data-19-scpe"></option>
                                            <option v-for="brand in brands" v:bind:key="brand.guid"
                                                    v-bind:value="brand.guid">
                                                @{{ brand.name }}
                                            </option>
                                        </select>
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>

                                    <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                        <label for="model_name"
                                               class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                            <span class="required">Model Name</span>
                                            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                               aria-label="Specify model name"
                                               data-bs-original-title="Specify model name"
                                               data-kt-initialized="1"></i>
                                        </label>

                                        <input type="text" class="form-control form-control-solid"
                                               placeholder="e.g Land Cruiser" v-model="model_name" id="model_name"
                                               name="model_name"/>
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>

                                    <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                        <label for="model_code"
                                               class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                            <span class="required">Model Code</span>
                                            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                               aria-label="Specify model code"
                                               data-bs-original-title="Specify model code"
                                               data-kt-initialized="1"></i>
                                        </label>

                                        <input type="text" class="form-control form-control-solid" placeholder="e.g 1GR"
                                               v-model="model_code" id="model_code" name="model_code"/>
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>

                                    <div class="d-flex flex-stack">

                                        <div class="me-5">
                                            <label class="fs-6 fw-semibold form-label">Is Active ?</label>
                                        </div>

                                        <label class="form-check form-switch form-check-custom form-check-solid">
                                            <input class="form-check-input" v-model='isEnabled' type="checkbox"
                                                   value="1" checked="checked">
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
                            <button type="button" v-on:click="submitBrand" id="kt_modal_add_submit"
                                    class="btn btn-primary">
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

        <input type="hidden" id="newBrandEndpoint" name="newBrandEndpoint" value="{{ route('brands') }}">
        <input type="hidden" id="modelEndpoint" name="modelEndpoint" value="{{ route('models') }}">
    </section>
@endsection
@push('scripts')
    @include('layouts.partials.dataTableScripts')
    <script>
        let app = new Vue({
            'el': '#app_main',
            data: {
                search: null,
                brands: [],
                configuredModels: [],
                table: null,
                datatable: null,
                modalEl: null,
                modal: null,
                validator: null,
                form: null,

                isEnabled: true,
                brand: null,
                model_name: null,
                model_code: null,
            },
            methods: {

                initToggleToolbar: () => {

                    const checkboxes = table.querySelectorAll('[type="checkbox"]');
                    toolbarBase = document.querySelector('[data-kt-table-toolbar="base"]');
                    toolbarSelected = document.querySelector('[data-kt-table-toolbar="selected"]');
                    selectedCount = document.querySelector(
                        '[data-kt-table-select="selected_count"]');
                    const deleteSelected = document.querySelector(
                        '[data-kt-table-select="delete_selected"]');

                    checkboxes.forEach(c => {
                        // Checkbox on click event
                        c.addEventListener('click', function () {
                            setTimeout(function () {
                                toggleToolbars();
                            }, 50);
                        });
                    });

                    // Deleted selected rows
                    deleteSelected.addEventListener('click', function () {
                        Swal.fire({
                            text: "Are you sure you want to delete selected customers?",
                            icon: "warning",
                            showCancelButton: true,
                            buttonsStyling: false,
                            confirmButtonText: "Yes, delete!",
                            cancelButtonText: "No, cancel",
                            customClass: {
                                confirmButton: "btn fw-bold btn-danger",
                                cancelButton: "btn fw-bold btn-active-light-primary"
                            }
                        }).then(function (result) {
                            if (result.value) {
                                Swal.fire({
                                    text: "You have deleted all selected customers!.",
                                    icon: "success",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton: "btn fw-bold btn-primary",
                                    }
                                }).then(function () {
                                    // Remove all selected customers
                                    checkboxes.forEach(c => {
                                        if (c.checked) {
                                            app.datatable.row($(c.closest(
                                                'tbody tr'))).remove()
                                                .draw();
                                        }
                                    });

                                    // Remove header checked box
                                    const headerCheckbox = table.querySelectorAll(
                                        '[type="checkbox"]')[0];
                                    headerCheckbox.checked = false;
                                }).then(function () {
                                    app.toggleToolbars(); // Detect checked checkboxes
                                    app.initToggleToolbar(0.320); // Re-init toolbar to recalculate checkboxes
                                });
                            } else if (result.dismiss === 'cancel') {
                                Swal.fire({
                                    text: "Selected customers was not deleted.",
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton: "btn fw-bold btn-primary",
                                    }
                                });
                            }
                        });
                    });
                },

                toggleToolbars: () => {
                    // Select refreshed checkbox DOM elements
                    const allCheckboxes = table.querySelectorAll('tbody [type="checkbox"]');

                    // Detect checkboxes state & count
                    let checkedState = false;
                    let count = 0;

                    // Count checked boxes
                    allCheckboxes.forEach(c => {
                        if (c.checked) {
                            checkedState = true;
                            count++;
                        }
                    });

                    // Toggle toolbars
                    if (checkedState) {
                        selectedCount.innerHTML = count;
                        toolbarBase.classList.add('d-none');
                        toolbarSelected.classList.remove('d-none');
                    } else {
                        toolbarBase.classList.remove('d-none');
                        toolbarSelected.classList.add('d-none');
                    }
                },

                // Delete subscirption
                handleRowDeletion: function () {
                    // Select all delete buttons
                    const deleteButtons = table.querySelectorAll(
                        '[data-kt-table-filter="delete_row"]');

                    deleteButtons.forEach(d => {
                        // Delete button on click
                        d.addEventListener('click', function (e) {
                            e.preventDefault();

                            // Select parent row
                            const parent = e.target.closest('tr');

                            // Get customer name
                            const customerName = parent.querySelectorAll('td')[1].innerText;

                            Swal.fire({
                                text: "Are you sure you want to delete " +
                                    customerName + "?",
                                icon: "warning",
                                showCancelButton: true,
                                buttonsStyling: false,
                                confirmButtonText: "Yes, delete!",
                                cancelButtonText: "No, cancel",
                                customClass: {
                                    confirmButton: "btn fw-bold btn-danger",
                                    cancelButton: "btn fw-bold btn-active-light-primary"
                                }
                            }).then(function (result) {
                                if (result.value) {
                                    Swal.fire({
                                        text: "You have deleted " +
                                            customerName + "!.",
                                        icon: "success",
                                        buttonsStyling: false,
                                        confirmButtonText: "Ok, got it!",
                                        customClass: {
                                            confirmButton: "btn fw-bold btn-primary",
                                        }
                                    }).then(function () {
                                        // Remove current row
                                        datatable.row($(parent)).remove()
                                            .draw();
                                    }).then(function () {
                                        // Detect checked checkboxes
                                        toggleToolbars();
                                    });
                                } else if (result.dismiss === 'cancel') {
                                    Swal.fire({
                                        text: customerName +
                                            " was not deleted.",
                                        icon: "error",
                                        buttonsStyling: false,
                                        confirmButtonText: "Ok, got it!",
                                        customClass: {
                                            confirmButton: "btn fw-bold btn-primary",
                                        }
                                    });
                                }
                            });
                        })
                    });
                },

                getBrands() {
                    $.get(document.querySelector('#newBrandEndpoint').value)
                        .done(function (response) {
                            // Populate results
                            if (response.state === 'failure') {
                                //show errors
                                toastr.error('Connection error, no vehicle brand data found')
                                return;
                            }

                            app.brands = response.payload;
                        })
                        .fail(function (error) {
                            // notify of error
                            toastr.error(
                                'Connection error. Could not retrieve data, some feature might not work.')
                        });
                },

                getConfiguredModels() {
                    $.get(document.querySelector('#modelEndpoint').value)
                        .done(function (response) {
                            // Populate results
                            if (response.state === 'failure') {
                                //show errors
                                toastr.error('Connection error, no data found')
                                return;
                            }

                            app.configuredModels = response.payload;

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

                postDeleteItem(parent, item) {
                    axios.delete(document.querySelector('#modelEndpoint').value, {
                        headers: {
                            Authorization: 'Token'
                        },
                        data: {
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
                                    "!.",
                                icon: "success",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn fw-bold btn-primary",
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
                                'brand': {
                                    validators: {
                                        notEmpty: {
                                            message: 'vehicle brand name is required'
                                        }
                                    }
                                },
                                'model_name': {
                                    validators: {
                                        notEmpty: {
                                            message: 'model name is required'
                                        },
                                        stringLength: {
                                            min: 3,
                                            max: 50,
                                            message: 'model name must contain 3 to 50 characters'
                                        }
                                    }
                                },
                                'model_code': {
                                    validators: {
                                        notEmpty: {
                                            message: 'model code is required'
                                        },
                                        stringLength: {
                                            min: 3,
                                            max: 15,
                                            message: 'model code must contain 3 to 15 characters'
                                        }
                                    }
                                }
                            },

                            plugins: {
                                trigger: new FormValidation.plugins.Trigger({}),
                                bootstrap: new FormValidation.plugins.Bootstrap5({
                                    rowSelector: '.fv-row',
                                    eleInvalidClass: '',
                                    eleValidClass: ''
                                })
                            }
                        }
                    );
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

                        const realDate = window.moment(dateColumn[3].innerHTML, "DD MMM YYYY, LT")
                            .format();

                        dateColumn[3].setAttribute('data-order', realDate);
                    });

                    // Disable ordering on column 0 (checkbox)
                    // Disable ordering on column 6 (actions)
                    this.datatable = $(this.table).DataTable({
                        "info": false,
                        'order': [],
                        "pageLength": 10,
                        "lengthChange": false,
                        'columnDefs': [{
                            orderable: false,
                            targets: 0
                        }, {
                            orderable: false,
                            targets: 4
                        },

                        ]
                    });

                    this.datatable.on('draw', function () {
                        //initToggleToolbar();
                        //handleRowDeletion();
                        //toggleToolbars();
                    });
                },

                add: function () {
                    // Select modal buttons
                    //const closeButton = this.modalEl.querySelector('#kt_modal_add_close');
                    const cancelButton = this.modalEl.querySelector('#kt_modal_add_cancel');
                    //const submitButton = this.modalEl.querySelector('#kt_modal_add_submit');

                    // Cancel button action
                    cancelButton.addEventListener('click', function (e) {
                        e.preventDefault();

                        Swal.fire({
                            text: "Are you sure you would like to cancel?",
                            icon: "warning",
                            showCancelButton: true,
                            buttonsStyling: false,
                            confirmButtonText: "Yes, cancel it!",
                            cancelButtonText: "No, return",
                            customClass: {
                                confirmButton: "btn btn-primary",
                                cancelButton: "btn btn-active-light"
                            }
                        }).then(function (result) {
                            if (result.value) {
                                app.modal.hide();
                            } else if (result.dismiss === 'cancel') {

                            }
                        });
                    });
                },

                /* initDeleteButton: function () {
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
                                 "?",
                             icon: "warning",
                             showCancelButton: true,
                             buttonsStyling: false,
                             confirmButtonText: "Yes, delete!",
                             cancelButtonText: "No, cancel",
                             customClass: {
                                 confirmButton: "btn fw-bold btn-danger",
                                 cancelButton: "btn fw-bold btn-active-light-primary"
                             }
                         }).then(function (result) {
                             if (result.value) {
                                 app.postDeleteItem(parent, guid);
                             } else if (result.dismiss === 'cancel') {

                             }
                         });
                     });
                 },*/

                /* handleFilter: function () {
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
                 },*/

                // Add customer button handler
                submitBrand: function () {
                    let radio = this.modalEl.querySelector('input[type="checkbox"]:checked');
                    const submitButton = app.modalEl.querySelector('#kt_modal_add_submit');
                    console.log('validated!');
                    // Show loading indication
                    submitButton.setAttribute('data-kt-indicator', 'on');

                    // Disable button to avoid multiple click
                    submitButton.disabled = true;
                    app.postRequest();

                    // toastr.warning(
                    //     'Sorry, looks like there are some errors detected, please try again.'
                    // );

                },

                postRequest() {
                    const submitButton = app.modalEl.querySelector('#kt_modal_add_submit');
                    $.post(document.querySelector('#modelEndpoint').value,
                        {
                        brand_name: $('[name="brand"] :selected').text().trim(),
                        brand_guid: $('[name="brand"] :selected').val(),
                        model_name: app.model_name,
                        model_code: app.model_code,
                        status: app.isEnabled ? 'active' : 'inactive'
                      },
                        {
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'content-type': 'text/json'
                        }
                    })
                        .done(function (response) {
                            let data = response.data.payload;
                            setTimeout(function () {
                                // Remove loading indication
                                submitButton.removeAttribute(
                                    'data-kt-indicator');

                                submitButton.disabled = false;

                                if (response.data.state === 'failure') {
                                    Swal.fire({
                                        text: response.data.message,
                                        icon: "error",
                                        buttonsStyling: false,
                                        confirmButtonText: "Ok",
                                        customClass: {
                                            confirmButton: "btn fw-bold btn-primary",
                                        }
                                    });

                                    return;
                                }


                                app.configuredModels.push(data);
                                app.destroyDataTable()

                                // Show popup confirmation
                                let message = response.data.message;
                                Swal.fire({
                                    text: message ||
                                        "Request has been successfully submitted!",
                                    icon: "success",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                }).then(function (result) {
                                    if (result.isConfirmed) {
                                        app.modal.hide();
                                        //window.location.reload();
                                    }
                                });

                                setTimeout(function () {
                                    app.initDatatable();
                                }, 300);


                            }, 2000);
                        })
                        .fail(function (error) {
                            // Show error message
                            submitButton.disabled = false;

                        });

                }
            },
            filters: {
                formatToFriendlyDate(value) {
                    if (!value) return value;

                    return new Date(value).toDateString();
                }
            },
            created() {
                this.getBrands();
                this.getConfiguredModels();
            },
            mounted() {

                this.modalEl = document.getElementById('kt_modal_add');

                this.modal = new bootstrap.Modal(this.modalEl);

                this.table = document.querySelector('#kt_table');

                this.form = document.querySelector('#kt_modal_add_form');

                this.add();

                //this.handleFilter();
                //this.handleSearch();
                //this.initValidator();
                //this.initDeleteButton();
                //initToggleToolbar();
            }
        })
    </script>
@endpush
