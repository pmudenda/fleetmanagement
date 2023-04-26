@extends('layouts.tasks.layout')

@push('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('dashboard/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet"
          href="{{ asset('dashboard/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('dashboard/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endpush


@section('content')

    <div id="newUser">

        <x-content-header :pageTitle="'New User Form'" :activeCrumb="'Define User'" :link="'home'" :linkText="'Home'"/>
    </div>

    <section class="content">
        <x-error-view/>
        <div class="card">
            <form name="db2" action="{{route('user.store')}}" method="post">
                @csrf
                <div class="card-header">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-5">
                            <div class="container-fluid pl-0">
                                <div class="row">
                                    <div class="form-group row">
                                        <label class="col-xs-12 col-sm-6 col-md-5 col-lg-3 app-field-label"
                                               for="staff_no">Find By:
                                        </label>
                                        <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                            <div class="input-group">
                                                <input type="text" class="form-control form-control-sm"
                                                       id="search_criteria"
                                                       placeholder="Find by full name | username | email "
                                                       name="search_criteria" required>
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
                    </div>
                </div>
                <div class="card-body user-data d-none">
                    <label class="app-required-marker"></label>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-5">
                                <div class="container-fluid pl-0">
                                    <div class="row">
                                        <div class="form-group row">
                                            <label class="col-xs-12 col-sm-6 col-md-5 col-lg-3 field-required"
                                                   for="staff_name">
                                                First Name:
                                            </label>
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
                                            <label class="col-xs-12 col-sm-6 col-md-5 col-lg-3 field-required"
                                                   for="staff_email"> Last Name:
                                            </label>
                                            <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                <input type="text" class="form-control form-control-sm" id="last_name"
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
                                            <label class="col-xs-12 col-sm-6 col-md-5 col-lg-3 field-required"
                                                   for="staff_email"> Email Address:
                                            </label>
                                            <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                <input type="text" class="form-control form-control-sm" id="staff_email"
                                                       name="staff_email"
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
                                            <label class="col-xs-12 col-sm-6 col-md-5 col-lg-3 field-required"
                                                   for="staff_name">
                                                Login Name:
                                            </label>
                                            <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                <input type="text" class="form-control form-control-sm"
                                                       id="login_name"
                                                       name="login_name"
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
                                            <label class="col-xs-12 col-sm-6 col-md-5 col-lg-3 field-required"
                                                   for="mobile_no">Staff Number:</label>
                                            <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                <input type="text" class="form-control form-control-sm"
                                                       id="staff_number"
                                                       name="staff_number"
                                                       autocomplete="off"
                                                >
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
                                                   for="mobile_no">Mobile Number:</label>
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
                                            <label class="col-xs-12 col-sm-6 col-md-5 col-lg-3 field-required"
                                                   for="directorate"> Directorate:
                                            </label>
                                            <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                <input type="text" class="form-control form-control-sm" id="directorate"
                                                       name="directorate" readonly required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-6 col-md-5">
                                <div class="container-fluid pl-0">
                                    <div class="row">
                                        <div class="form-group row">
                                            <label class="col-xs-12 col-sm-6 col-md-5 col-lg-3 field-required"
                                                   for="user_unit">
                                                Department :
                                            </label>
                                            <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                <input type="text" class="form-control form-control-sm" id="user_unit"
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
                                            <label class="col-xs-12 col-sm-6 col-md-5 col-lg-3"
                                                   for="mobile_no">Supervisor:</label>
                                            <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                <div class="input-group">
                                                    <input type="text"
                                                           id="staff_supervisor"
                                                           name="staff_supervisor"
                                                           data-bs-toggle="modal"
                                                           autocomplete="off"
                                                           data-bs-target="#searchEmployeeModal"
                                                           data-assignmenttype="single"
                                                           data-inputfield="staff_supervisor"
                                                           class="form-control form-control-sm"/>

                                                    <input type="hidden"
                                                           data-assignmenttype="single"
                                                           data-inputfield="staff_supervisorId"
                                                           id="staff_supervisorId"
                                                           name="staff_supervisorId"/>
                                                    <div class="input-group-append">
                                                        <div
                                                            data-assignmenttype="single"
                                                            data-inputfield="nonConformanceOriginator"
                                                            data-field="userSelection"
                                                            class="input-group-text">
                                                            <i class="fa fa-user"></i>
                                                        </div>
                                                        <div style="cursor: pointer;" title="clear selection"
                                                             data-action="clearUsers"
                                                             class="input-group-text">
                                                            <i data-action="clearUsers" class="fa fa-eraser"></i>
                                                        </div>
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
                                            <label class="col-xs-12 col-sm-6 col-md-5 col-lg-3 field-required"
                                                   for="user_type_id">Group :</label>
                                            <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                <select name="user_role_id" id="user_role_id"
                                                        class="form-control form-select-sm"
                                                        required>
                                                    <option value=""> --Choose Group--</option>
                                                    @foreach ($roles as $groupName)
                                                        @if($groupName == 'default')
                                                            <option selected
                                                                    value="{{$groupName->id}}">{{$groupName->description}}</option>
                                                        @else
                                                            <option
                                                                value="{{$groupName->id}}">{{$groupName->description}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <input type="hidden" value="{{ route('user.search') }}" id="newUserSearchUrl">
@endsection


@push('scripts')
    <script src="{{asset('assets/js/modules/common/user.search.js')}}"></script>
    <script src="{{asset('assets/js/modules/nonconformance/selectedUserConfirmation.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('.app-toolbars a').on('click', function (event) {
                let toolbar = $('.app-toolbars');
                switch (this.id) {
                    case 'submit':
                        util.loadContentWindow(this.dataset.link)
                        break;
                    case 'cancelEditLink':
                        console.log('cancelled')
                        break;
                    default:
                        console.log(this.id);
                        break
                }
            });

            $(document).on('click', "#userSearchBtn", function () {
                let search_key = document.querySelector('#search_criteria').value
                if (search_key && search_key.replace(/^\s+|\s+$/gm, '').length > 0) {
                    findUser(search_key);
                } else {
                    window.top.sysApp.showToast('Invalid data in Search Input', 'warning')
                }
            });
        })

        function findUser(val) {
            const route = document.querySelector("#newUserSearchUrl").value;
            $.ajax({
                type: 'POST',
                url: route,
                headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                data: {search_criteria: val},
                dataType: 'json',
                encode: true
            })
                .done(function (data) {

                    if ('state' in data && data['state'] === 'success') {
                        console.log(data);

                        /*$("#directorate").val(data.employee.directorate);
                        $("#user_unit").val(data.employee.functional_section);
                        $("#staff_name_search").val(data.employee.name);
                        $("#staff_email").val(data.employee.staff_email);
                        $("#mobile_no").val(data.employee.mobile_no);*/

                    } else {
                        window.top.sysApp.showToast(data['message'], 'error')
                    }

                });
        }
    </script>
@endpush
