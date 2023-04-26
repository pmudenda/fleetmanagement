@extends('layouts.app')
@push('styles')
@endpush
@section('content')

    <x-content-header/>
    <section class="content">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <h4>New Stores Requisition</h4>
                </div>
                <div class="card-tools">
                    <button type="button" class="btn btn-success btn-sm mr-3">
                        <i class="fas fa-save"></i>  Submit
                    </button>
                    <button type="button" class="btn btn-danger btn-sm mr-3">
                        <i class="fas fa-undo"></i> Cancel
                    </button>

                </div>
            </div>

            <div class="card-bod pb-4 min-h-600px pt-0">

                <x-error-view/>

                <form name="db2" action="{{route('user.store')}}" method="post">
                    @csrf
                    <div class="card-body user-data">
                        <label class="app-required-marker"></label>
                        <div class="container-fluid mt-2">
                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-5">
                                    <div class="container-fluid pl-0">
                                        <div class="row">
                                            <div class="form-group row">
                                                <label
                                                    class="col-xs-12 col-sm-6 col-md-5 col-lg-4 app-field-label field-required"
                                                    for="staff_no">Registration #:
                                                </label>
                                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control form-control-sm"
                                                               id="vehicle_registration"
                                                               placeholder="Vehicle Registration e.g AAB 6757"
                                                               name="vehicle_registration" required>
                                                        <div class="input-group-addon">
                                                            <button type="button" id="userSearchBtn"
                                                                    name="userSearchBtn"
                                                                    class="btn btn-outline-primary btn-sm ">
                                                                <i class="fas fa-search"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-6 col-md-5">
                                    <div class="container-fluid pl-0">
                                        <div class="row">
                                            <div class="form-group row">
                                                <label class="col-xs-12 col-sm-6 col-md-5 col-lg-3"
                                                       for="staff_name">
                                                </label>
                                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-7">
                                                    <input type="text" class="form-control form-control-sm"
                                                           id="first_name"
                                                           name="first_name"
                                                           required readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-5">
                                    <div class="container-fluid pl-0">
                                        <div class="row">
                                            <div class="form-group row">
                                                <div
                                                    class="col-xs-12 col-sm-6 col-md-5 col-lg-4 control-input-wrapper">
                                                    <div class="control-input">
                                                        <div class="link-field ui-front" style="position: relative;">
                                                            <label class="form-check-inline">
                                                                <input type="radio"
                                                                       class="list-row-checkbox bold mr-3"
                                                                       name="isCostCenterBasedRequisition"
                                                                       value="CostCenterBasedRequisition"
                                                                       checked>
                                                                Cost Center
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                    <input type="text" class="form-control form-control-sm"
                                                           id="first_name"
                                                           name="first_name"
                                                           required readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-6 col-md-5">
                                    <div class="container-fluid pl-0">
                                        <div class="row">
                                            <div class="form-group row">
                                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-10">
                                                    <input type="text" class="form-control form-control-sm"
                                                           id="last_name"
                                                           name="last_name"
                                                           required readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-5">
                                    <div class="container-fluid pl-0">
                                        <div class="row">
                                            <div class="form-group row">
                                                <div
                                                    class=" col-xs-12 col-sm-6 col-md-5 col-lg-4 control-input-wrapper">
                                                    <div class="control-input">
                                                        <div class="link-field ui-front"
                                                             style="position: relative;">
                                                            <label class="form-check-inline">
                                                                <input type="radio"
                                                                       class="list-row-checkbox bold mr-3"
                                                                       data-bs-toggle="modal"
                                                                       autocomplete="off"
                                                                       data-bs-target="#searchEmployeeModal"
                                                                       data-assignmenttype="single"
                                                                       data-inputfield="staff_supervisor"
                                                                       name="isProjectBasedRequisition"
                                                                       value="projectBasedRequisition">
                                                                Project
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                    <input type="text" class="form-control form-control-sm"
                                                           id="staff_email"
                                                           name="staff_email"
                                                           required readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-5">
                                    <div class="container-fluid pl-0">
                                        <div class="row">
                                            <div class="form-group row">
                                                <label class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                       for="staff_name">
                                                    Requisition Type:
                                                </label>
                                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                    <select name="user_role_id" id="user_role_id"
                                                            class="form-control form-select-sm"
                                                            required>
                                                        <option value=""> --Select--</option>
                                                        @foreach ($requisitionTypes as $requisitionType)
                                                            <option
                                                                value="{{$requisitionType->code}}">{{$requisitionType->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-6 col-md-5">
                                    <div class="container-fluid pl-0">
                                        <div class="row">
                                            <div class="form-group row">
                                                <label class="col-xs-12 col-sm-6 col-md-5 col-lg-4"
                                                       for="staff_name">
                                                    Odometer Reading :
                                                </label>
                                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                    <input type="text" class="form-control form-control-sm"
                                                           id="odometer"
                                                           name="odometer"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-5">
                                    <div class="container-fluid pl-0">
                                        <div class="row">
                                            <div class="form-group row">
                                                <label class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                       for="mobile_no">Allocation Per Week:</label>
                                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control form-control-sm"
                                                               id="staff_number"
                                                               name="staff_number"
                                                               autocomplete="off"
                                                        >
                                                        <div class="input-group-text">
                                                            Ltr
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-5">
                                    <div class="container-fluid pl-0">
                                        <div class="row">
                                            <div class="form-group row">
                                                <label class="col-xs-12 col-sm-6 col-md-5 col-lg-4"
                                                       for="mobile_no">Request Date:(<small>Is this necessary ?</small>)</label>
                                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                    <input type="text" class="form-control form-control-sm"
                                                           id="mobile_no"
                                                           name="mobile_no">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-5">
                                    <div class="container-fluid pl-0">
                                        <div class="row">
                                            <div class="form-group row">
                                                <label class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                       for="user_unit">
                                                    Next Refueling Date :
                                                </label>
                                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                    <input type="text" class="form-control form-control-sm"
                                                           id="user_unit"
                                                           name="user_unit"
                                                           readonly required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-5">
                                    <div class="container-fluid pl-0">
                                        <div class="row">
                                            <div class="form-group row">
                                                <label class="col-xs-12 col-sm-6 col-md-5 col-lg-4"
                                                       for="mobile_no">Purpose:</label>
                                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-8">
                                                        <textarea type="text"
                                                                  id="justification"
                                                                  name="justification"
                                                                  style="height: 129px;"
                                                                  class="form-control form-control-sm">
                                                        </textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-6 col-md-5">
                                    <div class="container-fluid pl-0">
                                        <div class="row">
                                            <div class="form-group row">
                                                {{--<label class="col-xs-12 col-sm-6 col-md-5 col-lg-3 field-required"
                                                       for="user_type_id">Group :</label>
                                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                    <select name="user_role_id" id="user_role_id"
                                                            class="form-control form-select-sm"
                                                            required>
                                                        <option value=""> --Choose Group--</option>

                                                    </select>
                                                </div>--}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="container-fluid">
                           <div class="table-responsive mt-3">
                               <table class="table table-bordered">
                                   <thead>
                                   <tr class="bg-dark">
                                       <th>Material Description</th>
                                       <th>Project Number</th>
                                       <th>Qty</th>
                                       <th>Unit Of Measure</th>
                                       <th>Price</th>
                                       <th>Amount</th>
                                   </tr>
                                   </thead>
                                   <tbody>
                                   <tr>
                                       <td>Material Description</td>
                                       <td>Project Number</td>
                                       <td>Qty</td>
                                       <td>Unit Of Measure</td>
                                       <td>Price</td>
                                       <td>Amount(ZMW)</td>
                                   </tr>
                                   </tbody>
                                   <tfoot>
                                   <tr>
                                       <td></td>
                                       <td class="text-right"><strong>Total Quantity</strong></td>
                                       <td><span id="totalQty"></span></td>
                                       <td></td>
                                       <td class="text-right"><strong>Total Amount</strong></td>
                                       <td><span id="totalAmount"></span></td>
                                   </tr>
                                   </tfoot>
                               </table>
                           </div>
                        </div>
                    </div>


                </form>

                <input type="hidden" value="{{ route('user.search') }}" id="newUserSearchUrl">
            </div>
        </div>
    </section>
@endsection
@push('scripts')

    <script src="{{asset('assets/plugins/time-ago/time-ago.js')}}"></script>
    <script>
        window.TimeAgo.addDefaultLocale({
            locale: 'en',
            now: {
                now: {
                    current: "now",
                    future: "in a moment",
                    past: "just now"
                }
            },
            long: {
                year: {
                    past: {
                        one: "{0} year ago",
                        other: "{0} years ago"
                    },
                    future: {
                        one: "in {0} year",
                        other: "in {0} years"
                    }
                },
            }
        })
    </script>
    {{--  <script src="{{asset('application/modules/userManagement/users/add_user.js')}}"></script>
      <script src="{{asset('application/modules/userManagement/users/table.js')}}"></script>
      <script src="{{asset('application/modules/userManagement/users/users-search.js')}}"></script>--}}
@endpush
