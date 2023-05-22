@extends('layouts.app')


@push('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endpush

@section('content')

    <x-content-header :pageTitle="'Driver On Boarding'" :activeCrumb="'OnBoarding'" :link="'home'"
                      :linkText="'System Users'"/>

    <!-- Main content -->
    <section class="content">
        <x-error-view/>

        <div class="container-fluid">
            <!-- Main row -->
            <div class="row">
                <!-- Left col -->
                <div class="col-md-12 pl-0">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <h4>Driver Management</h4>
                            </div>
                            <div id="actionButtonsContainer" class="card-toolbar justify-content-end">
                                <button type="button" id="submitRequisitionBtn" class="btn btn-success btn-sm mr-3 when_odo_valid"
                                        disabled>
                                    <i class="fas fa-save"></i> Submit
                                </button>
                                <button type="button" id="resetRequisitionBtn" class="btn btn-danger btn-sm mr-3">
                                    <i class="fas fa-undo"></i> Cancel
                                </button>

                            </div>
                        </div>
                        <div class="card-body p-2">
                            <div class="table-responsive mt-10 ">

                                <div class="card-body py-4 min-h-600px pt-0">
                                    <label class="app-required-marker"></label>
                                    <x-error-view/>
                                    <form name="tms_driver_definition" method="post">
                                        @csrf
                                        <div class="card-header">
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-6 col-md-5">
                                                    <div class="container-fluid pl-0">
                                                        <div class="row">
                                                            <div class="form-group row">
                                                                <label
                                                                    class="col-xs-12 col-sm-6 col-md-5 col-lg-3 app-field-label"
                                                                    for="staff_no">Find By:
                                                                </label>
                                                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                                    <div class="input-group">
                                                                        <input type="text"
                                                                               data-action="{{route('user.search')}}"
                                                                               class="form-control form-control-sm"
                                                                               id="staff_number"
                                                                               placeholder="Enter staff number"
                                                                               name="staff_number" required/>
                                                                        <div class="input-group-addon">
                                                                            <button type="button" id="employeeSearchBtn"
                                                                                    name="userSearchBtn"
                                                                                    class="btn btn-primary btn-sm border-radius-0">
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
                                        <div class="card-body user-data pl-0">
                                            <div class="row">
                                                <div class="card-title pl-2">
                                                    <h4>Employee Details</h4>
                                                    <hr/>
                                                </div>
                                            </div>
                                            <div class="container-fluid mt-5">
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-6 col-md-5">
                                                        <div class="container-fluid pl-0">
                                                            <div class="row">
                                                                <div class="form-group row">
                                                                    <label
                                                                        class="col-xs-12 col-sm-6 col-md-5 col-lg-3 field-required"
                                                                        for="staff_name">
                                                                        Name:
                                                                    </label>
                                                                    <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                                        <input type="text"
                                                                               class="form-control form-control-sm"
                                                                               id="nane"
                                                                               name="name" required readonly>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12 col-sm-6 col-md-5">
                                                        <div class="container-fluid pl-0">
                                                            <div class="row">
                                                                <div class="form-group row">
                                                                    <label
                                                                        class="col-xs-12 col-sm-6 col-md-5 col-lg-3 field-required"
                                                                        for="mobile_no">Grade:</label>
                                                                    <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                                        <input type="text"
                                                                               class="form-control form-control-sm"
                                                                               id="grade"
                                                                               readonly name="grade" autocomplete="off">
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
                                                                    <label
                                                                        class="col-xs-12 col-sm-6 col-md-5 col-lg-3 field-required"
                                                                        for="staff_name">
                                                                        Position:
                                                                    </label>
                                                                    <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                                        <input type="text"
                                                                               class="form-control form-control-sm"
                                                                               id="job_title"
                                                                               name="job_title"
                                                                               required
                                                                               readonly>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--Directorate And Department-->
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-6 col-md-5">
                                                        <div class="container-fluid pl-0">
                                                            <div class="row">
                                                                <div class="form-group row">
                                                                    <label
                                                                        class="col-xs-12 col-sm-6 col-md-5 col-lg-3 field-required"
                                                                        for="department">
                                                                        Department :
                                                                    </label>
                                                                    <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                                        <input type="text"
                                                                               class="form-control form-control-sm"
                                                                               id="department"
                                                                               name="department"
                                                                               readonly
                                                                               required>
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
                                                                    <label
                                                                        class="col-xs-12 col-sm-6 col-md-5 col-lg-3 field-required"
                                                                        for="staff_name">
                                                                        Location:
                                                                    </label>
                                                                    <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                                        <input type="hidden" name="cost_center_code">
                                                                        <input type="hidden" name="nrc">
                                                                        <input type="text"
                                                                               class="form-select form-control-sm"
                                                                               id="location"
                                                                               name="location" required readonly/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="card-title pl-2">
                                                <h4>License Details</h4>
                                                <hr/>
                                            </div>
                                        </div>
                                        <div class="card-body user-data pl-0 pt-0">

                                            <div class="container-fluid mt-5">
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-6 col-md-5">
                                                        <div class="container-fluid pl-0">
                                                            <div class="row">
                                                                <div class="form-group row">
                                                                    <label
                                                                        class="col-xs-12 col-sm-6 col-md-5 col-lg-3 field-required"
                                                                        for="staff_license">
                                                                        License No:
                                                                    </label>
                                                                    <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                                        <input type="text"
                                                                               class="form-control form-control-sm"
                                                                               id="license_number" name="license_number"
                                                                               required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12 col-sm-6 col-md-5">
                                                        <div class="container-fluid pl-0">
                                                            <div class="row">
                                                                <div class="form-group row">
                                                                    <label
                                                                        class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                                        for="staff_name">
                                                                        Date Issued:
                                                                    </label>
                                                                    <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                                        <input type="date"
                                                                               max="{{ date('Y-m-d', strtotime(\Carbon\Carbon::now())) }}"
                                                                               class="form-control form-control-sm"
                                                                               id="license_date_issued" name="license_date_issued" required>
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
                                                                    <label
                                                                        class="col-xs-12 col-sm-6 col-md-5 col-lg-3 field-required"
                                                                        for="staff_name">
                                                                        Expiry Date:
                                                                    </label>
                                                                    <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                                        <input type="date"
                                                                               class="form-control form-control-sm"
                                                                               id="license_date_expiry" name="license_date_expiry" required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12 col-sm-6 col-md-5">
                                                        <div class="container-fluid pl-0">
                                                            <div class="row">
                                                                <div class="form-group row">
                                                                    <label
                                                                        class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                                        for="staff_name">
                                                                        License Category:
                                                                    </label>
                                                                    <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                                        <select
                                                                            id="license_class"
                                                                            name="license_class"
                                                                            class="form-select">
                                                                            @foreach($licenseClasses as $licenseClass)
                                                                                <option {{$licenseClass->code}}>{{$licenseClass->name}}</option>
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

                                        <div class="row">
                                            <div class="card-title pl-2">
                                                <h4>Permit Details</h4>
                                                <hr/>
                                            </div>
                                        </div>
                                        <div class="card-body user-data pl-0 pt-0">

                                            <div class="container-fluid mt-5">
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-6 col-md-5">
                                                        <div class="container-fluid pl-0">
                                                            <div class="row">
                                                                <div class="form-group row">
                                                                    <label
                                                                        class="col-xs-12 col-sm-6 col-md-5 col-lg-3 field-required"
                                                                        for="staff_license">
                                                                        Permit No:
                                                                    </label>
                                                                    <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                                        <input type="text"
                                                                               class="form-control form-control-sm"
                                                                               id="permit_number"
                                                                               name="permit_number"
                                                                               required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12 col-sm-6 col-md-5">
                                                        <div class="container-fluid pl-0">
                                                            <div class="row">
                                                                <div class="form-group row">
                                                                    <label
                                                                        class="col-xs-12 col-sm-6 col-md-5 col-lg-3 field-required"
                                                                        for="staff_name">
                                                                        Date Issued:
                                                                    </label>
                                                                    <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                                        <input type="date"
                                                                               max="{{ date('Y-m-d', strtotime(\Carbon\Carbon::now())) }}"
                                                                               class="form-control form-control-sm"
                                                                               id="permit_date_issued"
                                                                               name="permit_date_issued"
                                                                               required>
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
                                                                    <label
                                                                        class="col-xs-12 col-sm-6 col-md-5 col-lg-3 field-required"
                                                                        for="staff_name">
                                                                        Expiry Date:
                                                                    </label>
                                                                    <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                                        <input type="date"
                                                                               class="form-control form-control-sm"
                                                                               id="permit_date_expiry"
                                                                               name="permit_date_expiry"
                                                                               required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12 col-sm-6 col-md-5">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </section>

@endsection

@push('scripts')

    <!-- DataTables  & Plugins -->
    @include('layouts.partials.dataTableScripts')
    <!-- page script -->
    <script>
        (function (tmsApp, $) {

            Inputmask({
                "mask": "99999999"
            }).mask("#permit_number");

            Inputmask({
                "mask": "99999999"
            }).mask("#license_number");

            $('#staff_number').on('keyup paste enter change', function () {
                if (!this.value || this.value.length < 5) {
                    return;
                }
                setTimeout(function () {
                    findEmployee();
                }, 300);
            });

            $('#employeeSearchBtn').on('click', function () {
                const staff_number = document.querySelector('#staff_number').value
                if (!staff_number || staff_number.length < 5) {
                    toastr.warning('Invalid Staff Number Provided')
                    return;
                }
                setTimeout(function () {
                    findEmployee();
                }, 300);
            });

            function populateEmployeeDetails(response) {
                let data = response;

                if (!data.con_per_no) {
                    tmsApp.showToast('User Not Found', 'error')
                    return;
                }
                document.querySelector('[name="name"]').value = data?.name;
                document.querySelector('[name="grade"]').value = data?.grade;
                document.querySelector('[name="job_title"]').value = data?.job_title;
                document.querySelector('[name="location"]').value = data?.location;
                document.querySelector('[name="department"]').value = data?.functional_section;


                //document.querySelector('[name="staff_email"]').value = data?.staff_email;
                //document.querySelector('[name="staff_number"]').value = data?.con_per_no;

                //document.querySelector('[name="cc_code"]').value = data?.cc_code;
                //document.querySelector('[name="bu_code"]').value = data?.bu_code;
                //document.querySelector('[name="cost_center_code"]').value = data?.cc_code;
                //document.querySelector('[name="business_unit_code"]').value = data?.bu_code;
                //document.querySelector('[name="login_name"]').value = data?.con_per_no;
                //document.querySelector('[name="directorate"]').value = data?.directorate;
                //document.querySelector('[name="mobile_no"]').value = data?.mobile_no;

                document.querySelector('[name="nrc"]').value = data?.nrc;
                document.querySelector('#actionButtonsContainer').style.display = null;
            }


            function findEmployee() {
                const staff_number = document.querySelector('#staff_number').value
                let formData = new FormData();
                formData.append('searchCriteria', staff_number);

                fetch(
                    document.querySelector("#staff_number").getAttribute('data-action'),
                    {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        body: formData,
                        referrer: window.baseUrl,
                        mode: 'cors',
                        credentials: 'same-origin',
                    }
                )
                    .then((response) => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }

                        return response.json();
                    })
                    .then(response => {
                        console.log(response);

                        if (!response.success) {
                            toastr.error(response.message);
                            return;
                        }

                        let optionListStr = '';
                        if (Array.isArray(response.payload)) {
                            response.payload.forEach(function (item) {
                                optionListStr += `<option value="${item['con_per_no']}">${item['con_per_no']} =>${item.name}</option>`;
                            })

                            $('#employee_list').html(optionListStr);
                            return;
                        }

                        populateEmployeeDetails(response.payload);
                    })
                    .catch(function (error) {
                        tmsApp.showErrorMessages('', '');
                    });
            }


            tmsApp.appFormValidator('form[name="tms_driver_definition"]',
                {
                    staff_number: {
                        required: true,
                        maxlength: 10,
                        minlength: 5
                    }
                },
                {
                    'staff_number': {
                        required: "You have not provided employee staff number",
                        maxlength: 'Staff number can not be more than 10 characters'
                    },
                }
            );


        })(window.tmsApp, jQuery);
    </script>

@endpush
