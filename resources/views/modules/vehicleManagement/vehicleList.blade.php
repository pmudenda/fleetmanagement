@php use App\Helpers\StatusHelper; @endphp
@extends('layouts.app')
@push('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endpush
@section('content')
    <x-content-header :pageTitle="'Vehicle Register'"/>
    <section class="content">
        <div class="card">

            <div class="card-header">
                <div class="card-title">
                    <button class="btn btn-primary btn-sm text-left"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapseOne"
                            aria-expanded="true"
                            aria-controls="collapseOne">
                        <i class="fas fa-filter"></i>
                        Filters
                    </button>
                </div>
                <div class="card-toolbar justify-content-end">
                    <div class="d-flex" kt_table-toolbar="base">

                        <a href="{{route('new.vehicle')}}"
                           class="btn btn-success">
                            <i class="fas fa-plus"></i>
                            Onboard Vehicle
                        </a>
                    </div>
                </div>
            </div>

            <!--begin::Card body-->
            <div class="card-body pt-0">
                <div class="accordion" id="accordionExample">
                    <div class="row">
                        <div id="collapseOne"
                             class="collapse"
                             aria-labelledby="headingOne"
                             data-parent="#accordionExample">
                            <div class="card-body px-0">
                                <div class="list-qbe">
                                    <div class="qbeinner" style="">
                                        <div class="qbe-toolbar" style="visibility: visible;">
                                            {{-- <a class="toolbarbutton">
                                                 <i class="fa fa-save"></i>
                                                 Save</a>
                                             <a class="toolbarbutton">
                                                 <i class="fa fa-save"></i>
                                                 Save As
                                             </a>
                                             <a class="toolbarbutton">
                                                 <i class="fa fa-trash-o"></i>
                                                 Delete
                                             </a>--}}
                                            <a class="btn btn-danger btn-sm toolbarbutton">
                                                <i class="fa fa-undo"></i>
                                                Reset
                                            </a>
                                            <a class="btn btn-success btn-sm toolbarbutton">
                                                <i class="fa fa-thumbs-down"></i>
                                                Clear
                                            </a>
                                            <a class="btn btn-success btn-sm toolbarbutton listrefreshbutton">
                                                <i class="fa fa-hand-grab-o"></i>
                                                Get
                                                Records
                                            </a>
                                        </div>

                                        <div class="d-flex">
                                            <div data-qbefield="systemname"
                                                 class="qbefield">
                                                {{--<i class="fa fa-remove"
                                                   title="Remove this field"
                                                   style="cursor:pointer;">
                                                </i>--}}
                                                <label class="qbefieldlabel" title="Module">Module</label>
                                                <div class="qbeoperator">
                                                    <select class="qbeoperator">
                                                        <option value="0">is</option>
                                                        <option value="16">is not</option>
                                                        <option value="1">starts with</option>
                                                        <option value="2">ends with</option>
                                                        <option value="3">contains</option>
                                                        <option value="44">does not start with</option>
                                                        <option value="45">does not end with</option>
                                                        <option value="46">does not contain</option>
                                                        <option value="6">is empty</option>
                                                        <option value="5">is not empty</option>
                                                        <option value="17">in</option>
                                                        <option value="18">not in</option>
                                                    </select>
                                                </div>
                                                <input type="text"
                                                       class="qbeinput qbeinputin"
                                                       style=""/>
                                                <span class="qbelabelin" style="display:none">
                                                (use ; to separate values)
                                            </span>
                                            </div>
                                            <div data-qbefield="tasknumber"
                                                 class="qbefield">
                                                {{--<i class="fa fa-remove"
                                                   title="Remove this field"
                                                   style="cursor:pointer;">
                                                </i>--}}
                                                <label
                                                    class="qbefieldlabel" title="Task #">Task #
                                                </label>
                                                <div class="qbeoperator">
                                                    <select class="qbeoperator">
                                                        <option value="12">&gt;=</option>
                                                        <option value="11">&lt;=</option>
                                                        <option value="10">&gt;</option>
                                                        <option value="9">&lt;</option>
                                                        <option value="0">is</option>
                                                        <option value="4">is between</option>
                                                        <option value="6">is empty</option>
                                                        <option value="5">is not empty</option>
                                                    </select>
                                                </div>
                                                <input type="text"
                                                       class="qbeinput">
                                                <input type="text"
                                                       class="qbeinputupper"
                                                       style="display:none">
                                            </div>
                                            <div
                                                data-qbefield="originatoruser"
                                                class="qbefield">
                                                {{--<i class="fa fa-remove"
                                                   title="Remove this field"
                                                   style="cursor:pointer;"></i>--}}
                                                <label
                                                    class="qbefieldlabel" title="Originator">
                                                    Originator
                                                </label>
                                                <div class="qbeoperator">
                                                    <select class="qbeoperator">
                                                        <option value="0">is</option>
                                                        <option value="16">is not</option>
                                                        <option value="13">Is Current User</option>
                                                        <option value="41">User's Employees</option>
                                                        <option value="1">starts with</option>
                                                        <option value="2">ends with</option>
                                                        <option value="3">contains</option>
                                                        <option value="44">does not start with</option>
                                                        <option value="45">does not end with</option>
                                                        <option value="46">does not contain</option>
                                                        <option value="6">is empty</option>
                                                        <option value="5">is not empty</option>
                                                        <option value="17">in</option>
                                                        <option value="18">not in</option>
                                                    </select>
                                                </div>
                                                <input type="text"
                                                       class="qbeinput qbeinputin"
                                                       style=""/>
                                                <span
                                                    class="qbelabelin"
                                                    style="display:none">
                                                (use ; to separate values)
                                            </span>
                                                <i class="fa fa-fw fa-user"
                                                   style="cursor:pointer;"
                                                   title="Select User"></i>
                                            </div>
                                            <div data-qbefield="dateopened"
                                                 class="qbefield">
                                                {{-- <i class="fa fa-remove"
                                                    title="Remove this field"
                                                    style="cursor:pointer;"></i>--}}
                                                <label
                                                    class="qbefieldlabel"
                                                    title="Date Opened">
                                                    Date Opened
                                                </label>
                                                <div class="qbeoperator">
                                                    <select class="qbeoperator">
                                                        <option value="12">&gt;=</option>
                                                        <option value="11">&lt;=</option>
                                                        <option value="10">&gt;</option>
                                                        <option value="9">&lt;</option>
                                                        <option value="0">is</option>
                                                        <option value="4">is between</option>
                                                        <option value="14">&lt;=current date</option>
                                                        <option value="15">&gt;=current date</option>
                                                        <option value="6">is empty</option>
                                                        <option value="5">is not empty</option>
                                                        <option value="21">this month</option>
                                                        <option value="22">last month</option>
                                                        <option value="23">Next Month</option>
                                                        <option value="25">Yesterday</option>
                                                        <option value="26">Today</option>
                                                        <option value="27">Tomorrow</option>
                                                        <option value="28">Next 7 Days</option>
                                                        <option value="29">Last 7 Days</option>
                                                        <option value="30">Next Week</option>
                                                        <option value="31">This Week</option>
                                                        <option value="32">Last Week</option>
                                                        <option value="33">Next X Weeks</option>
                                                        <option value="34">Last X Weeks</option>
                                                        <option value="35">Next X Months</option>
                                                        <option value="36">Last X Months</option>
                                                        <option value="38">Next X Years</option>
                                                        <option value="37">Last X Years</option>
                                                        <option value="39">Year to Date</option>
                                                        <option value="42">This Year</option>
                                                        <option value="43">Previous Year</option>
                                                    </select>
                                                </div>
                                                <input type="text"
                                                       class="qbeinput adddatepicker hasDatepicker"
                                                       id="dp1675929085275">
                                                <input
                                                    type="text"
                                                    class="qbeinputdatenum"
                                                    style="display:none">
                                                <input
                                                    type="text"
                                                    class="qbeinputupper adddatepicker hasDatepicker"
                                                    style="display:none"
                                                    id="dp1675929085276">
                                            </div>
                                            <div data-qbefield="tasksubject" class="qbefield">
                                                {{-- <i class="fa fa-remove" title="Remove this field"
                                                    style="cursor:pointer;"></i>--}}
                                                <label
                                                    class="qbefieldlabel" title="Subject">Subject
                                                </label>
                                                <div class="qbeoperator">
                                                    <select class="qbeoperator">
                                                        <option value="0">is</option>
                                                        <option value="16">is not</option>
                                                        <option value="1">starts with</option>
                                                        <option value="2">ends with</option>
                                                        <option value="3">contains</option>
                                                        <option value="44">does not start with</option>
                                                        <option value="45">does not end with</option>
                                                        <option value="46">does not contain</option>
                                                        <option value="6">is empty</option>
                                                        <option value="5">is not empty</option>
                                                        <option value="17">in</option>
                                                        <option value="18">not in</option>
                                                    </select>
                                                </div>
                                                <input type="text" class="qbeinput qbeinputin" style=""><span
                                                    class="qbelabelin"
                                                    style="display:none">
                                                (use ; to separate values)
                                            </span>
                                            </div>
                                            <div data-qbefield="assigneeuser" class="qbefield">
                                                {{-- <i class="fa fa-remove"
                                                    title="Remove this field"
                                                    style="cursor:pointer;"></i>--}}
                                                <label
                                                    class="qbefieldlabel" title="Assignee">
                                                    Assignee
                                                </label>
                                                <div class="qbeoperator">
                                                    <select class="qbeoperator">
                                                        <option value="0">is</option>
                                                        <option value="16">is not</option>
                                                        <option value="13">Is Current User</option>
                                                        <option value="41">User's Employees</option>
                                                        <option value="1">starts with</option>
                                                        <option value="2">ends with</option>
                                                        <option value="3">contains</option>
                                                        <option value="44">does not start with</option>
                                                        <option value="45">does not end with</option>
                                                        <option value="46">does not contain</option>
                                                        <option value="6">is empty</option>
                                                        <option value="5">is not empty</option>
                                                        <option value="17">in</option>
                                                        <option value="18">not in</option>
                                                    </select>
                                                </div>
                                                <input type="text" class="qbeinput qbeinputin" style="">
                                                <span
                                                    class="qbelabelin"
                                                    style="display:none">
                                                (use ; to separate values)
                                            </span>
                                                <i
                                                    class="fa fa-fw fa-user"
                                                    style="cursor:pointer;"
                                                    title="Select User">
                                                </i>
                                            </div>
                                            <div data-qbefield="datedue" class="qbefield">
                                                {{--<i class="fa fa-remove" title="Remove this field"
                                                   style="cursor:pointer;"></i>--}}
                                                <label
                                                    class="qbefieldlabel" title="Date Due">
                                                    Date Due
                                                </label>
                                                <div class="qbeoperator">
                                                    <select class="qbeoperator">
                                                        <option value="12">&gt;=</option>
                                                        <option value="11">&lt;=</option>
                                                        <option value="10">&gt;</option>
                                                        <option value="9">&lt;</option>
                                                        <option value="0">is</option>
                                                        <option value="4">is between</option>
                                                        <option value="14">&lt;=current date</option>
                                                        <option value="15">&gt;=current date</option>
                                                        <option value="6">is empty</option>
                                                        <option value="5">is not empty</option>
                                                        <option value="21">this month</option>
                                                        <option value="22">last month</option>
                                                        <option value="23">Next Month</option>
                                                        <option value="25">Yesterday</option>
                                                        <option value="26">Today</option>
                                                        <option value="27">Tomorrow</option>
                                                        <option value="28">Next 7 Days</option>
                                                        <option value="29">Last 7 Days</option>
                                                        <option value="30">Next Week</option>
                                                        <option value="31">This Week</option>
                                                        <option value="32">Last Week</option>
                                                        <option value="33">Next X Weeks</option>
                                                        <option value="34">Last X Weeks</option>
                                                        <option value="35">Next X Months</option>
                                                        <option value="36">Last X Months</option>
                                                        <option value="38">Next X Years</option>
                                                        <option value="37">Last X Years</option>
                                                        <option value="39">Year to Date</option>
                                                        <option value="42">This Year</option>
                                                        <option value="43">Previous Year</option>
                                                    </select>
                                                </div>
                                                <input type="text"
                                                       class="qbeinput adddatepicker hasDatepicker"
                                                       id="dp1675929085277"/>
                                                <input
                                                    type="text"
                                                    class="qbeinputdatenum"
                                                    style="display:none"/>
                                                <input
                                                    type="text"
                                                    class="qbeinputupper adddatepicker hasDatepicker"
                                                    style="display:none"
                                                    id="dp1675929085278"/>
                                            </div>
                                            <div data-qbefield="datecompleted"
                                                 class="qbefield">
                                                {{--<i class="fa fa-remove"
                                                   title="Remove this field"
                                                   style="cursor:pointer;"></i>--}}
                                                <label class="qbefieldlabel"
                                                       title="Date Completed">
                                                    Date Completed
                                                </label>
                                                <div class="qbeoperator">
                                                    <select class="qbeoperator">
                                                        <option value="12">&gt;=</option>
                                                        <option value="11">&lt;=</option>
                                                        <option value="10">&gt;</option>
                                                        <option value="9">&lt;</option>
                                                        <option value="0">is</option>
                                                        <option value="4">is between</option>
                                                        <option value="14">&lt;=current date</option>
                                                        <option value="15">&gt;=current date</option>
                                                        <option value="6">is empty</option>
                                                        <option value="5">is not empty</option>
                                                        <option value="21">this month</option>
                                                        <option value="22">last month</option>
                                                        <option value="23">Next Month</option>
                                                        <option value="25">Yesterday</option>
                                                        <option value="26">Today</option>
                                                        <option value="27">Tomorrow</option>
                                                        <option value="28">Next 7 Days</option>
                                                        <option value="29">Last 7 Days</option>
                                                        <option value="30">Next Week</option>
                                                        <option value="31">This Week</option>
                                                        <option value="32">Last Week</option>
                                                        <option value="33">Next X Weeks</option>
                                                        <option value="34">Last X Weeks</option>
                                                        <option value="35">Next X Months</option>
                                                        <option value="36">Last X Months</option>
                                                        <option value="38">Next X Years</option>
                                                        <option value="37">Last X Years</option>
                                                        <option value="39">Year to Date</option>
                                                        <option value="42">This Year</option>
                                                        <option value="43">Previous Year</option>
                                                    </select>
                                                </div>
                                                <input type="text"
                                                       class="qbeinput adddatepicker hasDatepicker"
                                                       id="dp1675929085279">
                                                <input
                                                    type="text"
                                                    class="qbeinputdatenum"
                                                    style="display:none">
                                                <input
                                                    type="text"
                                                    class="qbeinputupper adddatepicker hasDatepicker"
                                                    style="display:none"
                                                    id="dp1675929085280">
                                            </div>
                                            <div data-qbefield="dateclosed"
                                                 class="qbefield">
                                                {{--<i class="fa fa-remove"
                                                   title="Remove this field"
                                                   style="cursor:pointer;">
                                                </i>--}}
                                                <label
                                                    class="qbefieldlabel" title="Date Closed">Date Closed</label>
                                                <div class="qbeoperator">
                                                    <select class="qbeoperator">
                                                        <option value="12">&gt;=</option>
                                                        <option value="11">&lt;=</option>
                                                        <option value="10">&gt;</option>
                                                        <option value="9">&lt;</option>
                                                        <option value="0">is</option>
                                                        <option value="4">is between</option>
                                                        <option value="14">&lt;=current date</option>
                                                        <option value="15">&gt;=current date</option>
                                                        <option value="6">is empty</option>
                                                        <option value="5">is not empty</option>
                                                        <option value="21">this month</option>
                                                        <option value="22">last month</option>
                                                        <option value="23">Next Month</option>
                                                        <option value="25">Yesterday</option>
                                                        <option value="26">Today</option>
                                                        <option value="27">Tomorrow</option>
                                                        <option value="28">Next 7 Days</option>
                                                        <option value="29">Last 7 Days</option>
                                                        <option value="30">Next Week</option>
                                                        <option value="31">This Week</option>
                                                        <option value="32">Last Week</option>
                                                        <option value="33">Next X Weeks</option>
                                                        <option value="34">Last X Weeks</option>
                                                        <option value="35">Next X Months</option>
                                                        <option value="36">Last X Months</option>
                                                        <option value="38">Next X Years</option>
                                                        <option value="37">Last X Years</option>
                                                        <option value="39">Year to Date</option>
                                                        <option value="42">This Year</option>
                                                        <option value="43">Previous Year</option>
                                                    </select>
                                                </div>
                                                <input type="text" class="qbeinput adddatepicker hasDatepicker"
                                                       id="dp1675929085281">
                                                <input
                                                    type="text" class="qbeinputdatenum" style="display:none">
                                                <input
                                                    type="text"
                                                    class="qbeinputupper adddatepicker hasDatepicker"
                                                    style="display:none"
                                                    id="dp1675929085282"/>
                                            </div>
                                            <div data-qbefield="result" class="qbefield">
                                                {{--<i class="fa fa-remove"
                                                   title="Remove this field"
                                                   style="cursor:pointer;"></i>--}}
                                                <label
                                                    class="qbefieldlabel" title="Action">
                                                    Action
                                                </label>
                                                <div class="qbeoperator"><select class="qbeoperator">
                                                        <option value="0">is</option>
                                                        <option value="16">is not</option>
                                                        <option value="1">starts with</option>
                                                        <option value="2">ends with</option>
                                                        <option value="3">contains</option>
                                                        <option value="44">does not start with</option>
                                                        <option value="45">does not end with</option>
                                                        <option value="46">does not contain</option>
                                                        <option value="6">is empty</option>
                                                        <option value="5">is not empty</option>
                                                        <option value="17">in</option>
                                                        <option value="18">not in</option>
                                                    </select>
                                                </div>
                                                <input type="text" class="qbeinput qbeinputin" style="">
                                                <span
                                                    class="qbelabelin"
                                                    style="display:none">(use ; to separate values)
                                            </span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--begin::Table-->
                <div class="table-responsive">
                    <table aria-label="vehicles Tables"
                           class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer"
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
                                Model
                            </th>

                            <th>
                                Type
                            </th>
                            <th>
                                Reg. Number
                            </th>

                            <th>
                                Onboarded By
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Onboarding Status
                            </th>

                            <th>
                                Date Registered
                            </th>

                            <th>
                                Has Tom Card
                            </th>

                            <th>
                                Actions
                            </th>
                        </tr>
                        </thead>


                        <tbody class="text-gray-600 fw-semibold">
                        @foreach($vehicleList as $vehicle)
                            <tr>
                                <td>
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="list-row-checkbox" type="checkbox" value="item.guid"/>
                                    </div>
                                </td>

                                <td>
                                    <a href="#" class="text-gray-800 text-hover-primary mb-1">
                                        {{$vehicle->brand_name}}
                                    </a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-800 text-hover-primary mb-1">
                                        {{$vehicle->model_name}} : {{$vehicle->model_code}}
                                    </a>
                                </td>

                                <td>
                                    <a href="#" class="text-gray-800 text-hover-primary mb-1">
                                        {{$vehicle->body_type_name}}
                                    </a>
                                </td>

                                <td>
                                    <a href="#" class="text-gray-800 text-hover-primary mb-1">
                                        {{$vehicle->registration_number}}
                                    </a>
                                </td>

                                <td>
                                    <a href="#" class="text-gray-800 text-hover-primary mb-1">
                                        {{$vehicle->onboarded_by}}
                                    </a>
                                </td>

                                <td>
                                    @if($vehicle->status == StatusHelper::active())
                                        <div class="badge badge-light-success">
                                            ACTIVE
                                        </div>
                                    @elseif($vehicle->status == StatusHelper::vehicleInWorkshop())
                                        <div class="badge badge-light-danger">
                                            IN WORKSHOP
                                        </div>
                                    @elseif($vehicle->status == StatusHelper::vehicleInactive())
                                        <div class="badge badge-light-danger">
                                            INACTIVE
                                        </div>
                                    @else
                                        <div class="badge badge-light-warning">
                                            {{$vehicle->status_name}}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if($vehicle->on_boarding_status == '030')
                                        <div class="badge badge-light-success">
                                            COMPLETE
                                        </div>
                                    @else
                                        @if ($vehicle->on_boarding_status == '100')
                                            <div class="badge badge-light-warning">
                                                Pending General Data Entry
                                            </div>
                                        @elseif ($vehicle->on_boarding_status == '101')
                                            <div style="text-transform: capitalize" class="badge badge-light-warning">
                                                Pending Technical Data Entry
                                            </div>
                                        @elseif ($vehicle->on_boarding_status == "102")
                                            <div style="text-transform: capitalize" class="badge badge-light-warning">
                                                Pending Accessories Checkin
                                            </div>
                                        @elseif ($vehicle->on_boarding_status == "103")
                                            <div style="text-transform: capitalize" class="badge badge-light-warning">
                                                Pending Costing Data Entry
                                            </div>
                                        @elseif ($vehicle->on_boarding_status == "104")
                                            <div style="text-transform: capitalize" class="badge badge-light-warning">
                                                Pending Assignment
                                            </div>
                                        @endif
                                    @endif
                                </td>

                                <td>
                                    {{$vehicle->created_at }}
                                </td>

                                <td>
                                    @if($vehicle->has_tom_card == 'Y')
                                        <div class="badge badge-success">
                                            YES
                                        </div>
                                    @else
                                        <div class="badge badge-danger">
                                            No
                                        </div>
                                    @endif
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
                                            {{-- @can(config('rights.edit_vehicle'))--}}
                                            @if($vehicle->on_boarding_status == StatusHelper::onboardingComplete())
                                                <li>
                                                    <a class="dropdown-item" data-kt-action="edit"
                                                       href="{{URL::signedRoute('view.vehicle', ['step' => 6, 'reference' => $vehicle->header_id, 'edit'=> true])}}">
                                                        Edit
                                                    </a>
                                                </li>

                                                <li>
                                                    <a class="dropdown-item" data-kt-action="edit"
                                                       href="{{URL::signedRoute('vehicle.show', [
                                                            'step' => 2,
                                                            'reference' => $vehicle->header_id,
                                                            'edit'=> true])}}">
                                                        Over View
                                                    </a>
                                                </li>
                                            @endif
                                            @if($vehicle->on_boarding_status != StatusHelper::onboardingComplete())
                                                <li>
                                                    <a class="dropdown-item"
                                                       href="{{URL::signedRoute('resume.onboarding',['reference' => $vehicle->header_id])}}">
                                                        Complete Onboarding
                                                    </a>
                                                </li>
                                            @endif
                                            {{--@endcan--}}
                                        </ul>
                                    </div>
                                </td>

                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{asset('application/modules/vehicleManagement/assets/js/vehicle_list.js')}}"></script>
    <script>
        (function (appInstance) {
            appInstance.initDatatable("#kt_brands_table", false);
        })(window.tmsApp || {});
    </script>
@endpush
