@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endpush
@section('content')
    <x-content-header :pageTitle="'Vehicle Make'" :activeCrumb="'Brands'" :link="'home'"
                      :linkText="'Home'"/>

    <section class="content" id="app_main">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                </div>
                <!--begin::Card toolbar-->
                <div class="card-toolbar justify-content-end">
                    <div class="d-flex" kt_table-toolbar="base">
                        <button type="button" data-bs-toggle="modal" data-bs-target="#kt_modal_add_brand"
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
                    <table class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer"
                           id="kt_brands_table">
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
                                <div v-if="item.status.toLowerCase() === '01'" class="badge badge-light-success">
                                    Active
                                </div>

                                <div v-else
                                     class="badge badge-light-danger">
                                    Inactive
                                </div>
                            </td>


                            <td>
                                @{{ item.date_created | formatToFriendlyDate }}
                            </td>

                            <td class="text-start">
                                <div class="dropdown">
                                    <button class="btn btn-light btn-active-light-primary btn-sm dropdown-toggle"
                                            type="button"
                                            id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                        Actions
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li>
                                            <a class="dropdown-item"
                                               data-zfm-action="edit"
                                               href="#">
                                                Edit
                                            </a>
                                        </li>

                                        <li>
                                            <a class="dropdown-item"
                                               href="#"
                                               data-zfm-action="remove"
                                               data-kt-table-filter="delete_row">
                                                Delete
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        <!-- Add Brand Modal -->

        <div class="modal fade" id="kt_modal_add_brand" tabindex="-1" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-dialog-centered mw-650px">
                <div class="modal-content">
                    <form class="form" action="{{ route('brands.save') }}" method="post" name="addRecordForm">
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
                                               aria-label="Specify brand name"
                                               data-bs-original-title="Specify brand name"
                                               data-kt-initialized="1"></i>
                                        </label>

                                        <input type="text" autocomplete="off"
                                               maxlength="140"
                                               class="input-with-feedback form-control form-control-solid"
                                               placeholder="e.g Toyota"
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
                                                    <option v-for="status in statusList" :value="status.code">@{{
                                                        status.name }}
                                                    </option>
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
                                            <input class="form-check-input" v-model='isEnabled' type="checkbox"
                                                   value="1"
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

                        <div class="modal-footer justify-content-end">
                            <button type="reset" id="kt_modal_add_cancel"
                                    class="btn btn-sm btn-danger me-3">
                                Discard
                            </button>

                            <button type="button"
                                    id="submitAddFormRecord"
                                    class="btn btn-sm btn-success">
                                <i class="fas fa-save"></i>
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <input type="hidden" id="newBrandEndpoint" name="newBrandEndpoint" value="{{ route('brands.get') }}">
    </section>
@endsection

@push('scripts')
    @include('layouts.partials.dataTableScripts')
    <script src="{{ asset('application/modules/configurations/assets/js/brand.definition.js') }}"></script>
@endpush
