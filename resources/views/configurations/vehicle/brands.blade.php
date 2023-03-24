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
                    <input type="text" v-model="search" v-on:input="find" data-kt-table-filter="search"
                           class="form-control form-control-solid w-250px ps-14" placeholder="Search Brands">
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


                    <button style="display: none;" type="button" class="btn btn-light-primary me-3"
                            data-bs-toggle="modal" data-bs-target="#kt_export_modal">
                    <span class="svg-icon svg-icon-2"><svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                           xmlns="http://www.w3.org/2000/svg">
                            <rect opacity="0.3" x="12.75" y="4.25" width="12" height="2" rx="1"
                                  transform="rotate(90 12.75 4.25)" fill="currentColor"></rect>
                            <path
                                d="M12.0573 6.11875L13.5203 7.87435C13.9121 8.34457 14.6232 8.37683 15.056 7.94401C15.4457 7.5543 15.4641 6.92836 15.0979 6.51643L12.4974 3.59084C12.0996 3.14332 11.4004 3.14332 11.0026 3.59084L8.40206 6.51643C8.0359 6.92836 8.0543 7.5543 8.44401 7.94401C8.87683 8.37683 9.58785 8.34458 9.9797 7.87435L11.4427 6.11875C11.6026 5.92684 11.8974 5.92684 12.0573 6.11875Z"
                                fill="currentColor"></path>
                            <path opacity="0.3"
                                  d="M18.75 8.25H17.75C17.1977 8.25 16.75 8.69772 16.75 9.25C16.75 9.80228 17.1977 10.25 17.75 10.25C18.3023 10.25 18.75 10.6977 18.75 11.25V18.25C18.75 18.8023 18.3023 19.25 17.75 19.25H5.75C5.19772 19.25 4.75 18.8023 4.75 18.25V11.25C4.75 10.6977 5.19771 10.25 5.75 10.25C6.30229 10.25 6.75 9.80228 6.75 9.25C6.75 8.69772 6.30229 8.25 5.75 8.25H4.75C3.64543 8.25 2.75 9.14543 2.75 10.25V19.25C2.75 20.3546 3.64543 21.25 4.75 21.25H18.75C19.8546 21.25 20.75 20.3546 20.75 19.25V10.25C20.75 9.14543 19.8546 8.25 18.75 8.25Z"
                                  fill="currentColor"></path>
                        </svg>
                    </span>
                        Export
                    </button>

                    <button type="button" data-bs-toggle="modal" data-bs-target="#kt_modal_add_brand"
                            class="btn btn-primary">
                    <span class="svg-icon svg-icon-2"><svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                           xmlns="http://www.w3.org/2000/svg">
                            <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1"
                                  transform="rotate(-90 11.364 20.364)" fill="currentColor"></rect>
                            <rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="currentColor"></rect>
                        </svg></span>
                        Add Vehicle Brand
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
                            Brand
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
                    <tr v-for="(item, index) in brands">

                        <td>
                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                <input class="list-row-checkbox" type="checkbox" :value="item.guid"/>
                            </div>
                        </td>

                        <td>
                            <a href="#" class="text-gray-800 text-hover-primary mb-1">
                                @{{ item.name }}
                            </a>
                        </td>

                        <td>
                            <div v-if="item.status.toLowerCase() === 'active'" class="badge badge-light-success">
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
                            @{{ item.created_at | formatToFriendlyDate }}
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

    <!-- Export Modal -->

    <div class="modal fade" id="kt_export_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="fw-bold">Export Brands</h2>

                    <div id="kt_export_close" class="btn btn-icon btn-sm btn-active-icon-primary">
                    <span class="svg-icon svg-icon-1">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                  transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)"
                                  fill="currentColor"></rect>
                        </svg>

                    </span>
                    </div>
                </div>

                <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                    <form id="kt_export_form" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="#">
                        <div class="fv-row mb-10">
                            <label class="fs-5 fw-semibold form-label mb-5">
                                Select Export Format:
                            </label>

                            <select name="format" data-control="select2" data-placeholder="Select a format"
                                    data-hide-search="true" class="form-select form-select-solid"
                                    data-select2-id="select2-data-22-ayme" tabindex="-1" aria-hidden="true">
                                <option value="excell" data-select2-id="select2-data-24-ianp">Excel</option>
                                <option value="pdf">PDF</option>
                                <option value="cvs">CVS</option>
                                <option value="zip">ZIP</option>
                            </select>

                        </div>

                        <div class="fv-row mb-10 fv-plugins-icon-container">
                            <label class="fs-5 fw-semibold form-label mb-5">Select Date Range:</label>

                            <input class="form-control form-control-solid flatpickr-input" placeholder="Pick a date"
                                   name="date" type="hidden">
                        </div>

                        <div class="text-center">
                            <button type="reset" id="kt_export_cancel" class="btn btn-light me-3">
                                Discard
                            </button>

                            <button type="submit" id="kt_export_submit" class="btn btn-primary">
                            <span class="indicator-label">
                                Submit
                            </span>
                                <span class="indicator-progress">
                                Please wait...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- Add Brand Modal -->

    <div class="modal fade" id="kt_modal_add_brand" tabindex="-1" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <form class="form" action="#" id="kt_modal_add_form">
                    <div class="modal-header">
                        <h2 class="fw-bold">Add a Vehicle Make (Brand)</h2>

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
                                    <label for="brand_name"
                                           class="d-flex align-items-center fs-6 fw-semibold form-label mb-2 control-label">
                                        <span class="required">Name</span>
                                        <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                           aria-label="Specify brand name" data-bs-original-title="Specify brand name"
                                           data-kt-initialized="1"></i>
                                    </label>

                                    <input type="text" autocomplete="off"
                                           maxlength="140"
                                           class="input-with-feedback form-control form-control-solid" placeholder="e.g Toyota"
                                           v-model="brand_name" id="brand_name" name="brand_name"/>
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                </div>

                                <div class="form-group">
                                    <div class="clearfix">
                                        <label for="status" class="control-label reqd" style="padding-right: 0px;">Status</label>
                                        <span class="help"></span>
                                    </div>
                                    <div class="control-input-wrapper">
                                        <div class="control-input flex align-center">
                                            <select name="status" id="status"
                                                    autocomplete="off"
                                                    required
                                                    class="input-with-feedback form-control ellipsis bold"
                                                    maxlength="140"
                                                    data-fieldtype="Select"
                                                    data-fieldname="status"
                                                    placeholder=""
                                                    data-doctype="VehicleBrand">
                                                <option value="">--Select--</option>
                                                <option v-for="status in statusList" :value="status">@{{ status }}</option>
                                            </select>
                                        </div>
                                        <div class="control-value like-disabled-input bold" style="display: none;">
                                            Active
                                        </div>
                                        <p class="help-box small text-muted"></p>
                                    </div>
                                </div>

                                <div class="d-flex flex-stack d-none">

                                    <div class="me-5">
                                        <label class="fs-6 fw-semibold form-label">Is Brand Active ?</label>
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
                        <button type="button" v-on:click="submitBrand" id="kt_modal_add_submit" class="btn btn-primary">
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
@endsection

@push('scripts')
    <script src="{{ asset('application/modules/configurations/assets/js/brand.definition.js') }}"></script>
    <script src="{{ asset('assets/js/export.js') }}"></script>
@endpush
