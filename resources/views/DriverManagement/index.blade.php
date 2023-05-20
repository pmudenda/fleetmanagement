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
                        </div>
                        <div class="card-body p-2">
                            <div class="table-responsive mt-10 ">

                                <div class="card-body py-4 min-h-600px">

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
                                                                               name="staff_number" required>
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
                                            <label class="app-required-marker"></label>
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
                                                                               id="job_title" name="job_title" required
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
                                                                        for="user_unit">
                                                                        Department :
                                                                    </label>
                                                                    <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                                        <input type="text"
                                                                               class="form-control form-control-sm"
                                                                               id="user_unit" name="user_unit" readonly
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
                                                                        <select type="text"
                                                                                class="form-select form-control-sm"
                                                                                id="cc_code"
                                                                                name="cc_code" required disabled>
                                                                            <option></option>
                                                                            {{-- @foreach ($costCenters as $costCenter)
                                                                            <option
                                                                                value="{{$costCenter->code_cost_center}}">
                                                                                {{$costCenter->code_cost_center}}
                                                                                -> {{$costCenter->description}}</option>
                                                                            @endforeach --}}
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card-title">
                                            <h4>Driver license</h4>
                                        </div>
                                        <div class="card-body user-data pl-0">

                                            <div class="container-fluid mt-5">
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-6 col-md-5">
                                                        <div class="container-fluid pl-0">
                                                            <div class="row">
                                                                <div class="form-group row">
                                                                    <label
                                                                        class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                                        for="staff_license">
                                                                        License No:
                                                                    </label>
                                                                    <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                                        <input type="text"
                                                                               class="form-control form-control-sm"
                                                                               id="staff_license" name="staff_license"
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
                                                                               class="form-control form-control-sm"
                                                                               id="job_title" name="job_title" required>
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
                                                                        class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                                        for="staff_name">
                                                                        Expiry Date:
                                                                    </label>
                                                                    <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                                        <input type="date"
                                                                               class="form-control form-control-sm"
                                                                               id="job_title" name="job_title" required>
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
                                                                        Driver License:
                                                                    </label>
                                                                    <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                                        <input type="file" class="custom-file-input"
                                                                               id="customFile">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>


                                            </div>
                                        </div>

                                        <div class="card-title">
                                            <h4>Permit</h4>
                                        </div>
                                        <div class="card-body user-data pl-0">

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
                                                                               id="staff_license" name="staff_license"
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
                                                                               class="form-control form-control-sm"
                                                                               id="job_title" name="job_title" required>
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
                                                                               id="job_title" name="job_title" required>
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
                                                                        Permit:
                                                                    </label>
                                                                    <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                                        <input type="file"
                                                                               class="custom-file-input"
                                                                               id="customFile">
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

            $('#staff_number').on('keyup paste enter', function () {
                if (!this.value || this.value.replace('_', '').length < 5) {
                    return;
                }
                setTimeout(function () {
                    findEmployee();
                }, 300);
            });

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
                        //c
                        console.log(response);

                        if (!response.success || response.payload.length == 0) {

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

                        document.querySelector('#driver_name').value = response.payload.name;
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
