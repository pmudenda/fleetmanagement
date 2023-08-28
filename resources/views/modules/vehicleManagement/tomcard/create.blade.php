@php use Carbon\Carbon; @endphp
@extends('layouts.app')


@push('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
    <style>
        label {
            display: flex;
            align-items: center;
        }

        span::after {
            padding-left: 5px;
        }

        input:invalid + span::after {
            content: "✖";
        }

        input:valid + span::after {
            content: "✓";
        }
    </style>
@endpush

@section('content')

    <x-content-header :pageTitle="'Tom Card Assignment'"
                      :activeCrumb="'Assignment'"
                      :link="'list.tom.card'"
                      :linkText="'Tom Card'"/>
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
                                <h4>Tom Card Management</h4>
                            </div>
                        </div>
                        <form class="" name="newTomCardForm"
                              action="{{route('save.assign.tom.card')}}"
                              id="newTomCardForm"
                              method="post">
                            @csrf
                            <div class="card-body p-2">

                                <div class="errorTxt"></div>
                                <x-error-view></x-error-view>

                                <label class="app-required-marker"></label>

                                <fieldset style="" class="form-group border p-3">
                                    <legend>General Information:</legend>
                                   <div class="row">
                                       <div class="col-6">
                                           <div class="row mb-2">
                                               <div class="col" data-id="table-td">
                                                   <label class="app-field-label">
                                                       Vehicle Registration Number
                                                       <span class="text-danger">*</span>
                                                   </label>
                                               </div>
                                               <div class="col" data-type="table-td">
                                                   <div class="app-field-input" data-field="taskOriginator">
                                                       <div class="input-group">
                                                           <input type="text"
                                                                  id="vehicleRegistration"
                                                                  required
                                                                  data-action="{{route('requisition.vehicle.details')}}"
                                                                  autocomplete="off"
                                                                  name="vehicleRegistration"
                                                                  class="form-control"/>
                                                           <div class="input-group-append">
                                                               <button type="button"
                                                                       class="input-group-text">
                                                                   <i class="fas fa-car"></i>
                                                               </button>
                                                           </div>
                                                       </div>
                                                   </div>
                                               </div>
                                           </div>

                                           <div class="row mb-2">
                                               <div class="col">
                                                   <label class="app-field-label field-required">
                                                       Card Number
                                                   </label>
                                               </div>
                                               <div class="col">
                                                   <div class="input-group">
                                                       <input type="text"
                                                              name="cardNumber"
                                                              id="cardNumber"
                                                              autocomplete="off"
                                                              class="form-control"
                                                              required/>
                                                       <div class="input-group-append">
                                                        <span class="input-group-text">
                                                            <i class="fas fa-credit-card"></i>
                                                        </span>
                                                       </div>
                                                   </div>
                                               </div>
                                           </div>

                                           <div class="row mb-2">
                                               <div class="col">
                                                   <label class="app-field-label field-required">
                                                       Date Issued
                                                   </label>
                                               </div>
                                               <div class="col">
                                                   <div class="input-group date">
                                                       <input type="text"
                                                              name="dateIssued"
                                                              id="dateIssued"
                                                              autocomplete="off"
                                                              class="form-control datetimepicker"
                                                              required/>
                                                       <div class="input-group-append"
                                                            data-target="#dateIssued"
                                                            data-action="openDatePicker">
                                                        <span type="button"
                                                              data-action="openDatePicker"
                                                              class="input-group-text ui-datepicker-trigger">
                                                            <i data-action="datetimepicker"
                                                               class="fa fa-calendar"></i>
                                                        </span>
                                                       </div>
                                                       <button type="button" data-action="clearDate"
                                                               class="input-group-text">
                                                           <i data-action="clearDate" class="fa fa-eraser"></i>
                                                       </button>
                                                   </div>
                                               </div>
                                           </div>

                                           <div class="row mb-2">
                                               <div class="col">
                                                   <label class="app-field-label" data-field="specificlocationofia">
                                                       Expiry Date <span class="text-danger">*</span>
                                                   </label>
                                               </div>

                                               <div class="col">
                                                   <div class="input-group date">
                                                       <input type="text"
                                                              name="expiryDate"
                                                              id="expiryDate"
                                                              autocomplete="off"
                                                              class="form-control datetimepicker"
                                                              data-target="#dateOpened"
                                                              required/>
                                                       <div class="input-group-append">
                                                        <span class="input-group-text">
                                                            <i class="fa fa-calendar"></i>
                                                        </span>
                                                       </div>
                                                   </div>
                                               </div>

                                           </div>

                                           <div class="row mb-2">
                                               <td colspan="4">
                                                   <label class="app-field-label" data-field="typeia">
                                                       Comments
                                                   </label>
                                               </td>
                                           </div>

                                           <div class="row mb-2">
                                               <div class="col" data-id="table-td" style="background: none;">
                                                   <div class="app-field-input">
                                                    <textarea name="comments" id="comments"
                                                              class="form-control"></textarea>
                                                   </div>
                                               </div>
                                           </div>
                                       </div>
                                       <div class="col-3">
                                           <div id="vehicleDetailsContainer" style="display: none;"
                                                class="col-xs-12 col-sm-12 col-md-12">
                                               {{--<h1>Vehicle Details</h1>
                                                <table class="table">
                                                    <tbody id="vehicleDetails" class="vehicleDetails">
                                                    </tbody>
                                                </table>--}}
                                           </div>

                                           <div id="image_view" class="card text-center my-2" style="display: none;">
                                               {{--  <h2 class="fs-2x fw-bold mb-10">Front View</h2>--}}
                                               <div class="form-group">
                                                   <div class="imagePreview"></div>
                                               </div>
                                           </div>
                                       </div>
                                   </div>
                                </fieldset>

                            </div>
                            <div class="card-footer">
                                <div id="actionButtonsContainer" class="card-toolbar justify-content-end">
                                    <button type="button" id="submitRequisitionBtn"
                                            class="btn btn-success btn-sm mr-3 when_odo_valid">
                                        <i class="fas fa-save"></i> Save
                                    </button>
                                    <button type="button" id="resetRequisitionBtn" class="btn btn-danger btn-sm mr-3">
                                        <i class="fas fa-undo"></i> Reset
                                    </button>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </section>
    <input type="hidden" value="{{StatusHelper::active()}}" name="vehicleActive" id="vehicleActive"/>
    <x-employee-search-modal/>
@endsection

@push('scripts')
    <script src="{{asset('application/modules/userManagement/employee.search.js')}}"></script>
    <script src="{{asset('libs/imageUpload/imageUpload.js')}}"></script>
    <!-- page script -->
    <script>
        (function (tmsApp, $) {

            Inputmask({
                "mask": "9999 9999 9999 9999"
            }).mask("#cardNumber");

            Inputmask({
                "mask": "99/9999"
            }).mask("#expiryDate");

            Inputmask({
                "mask": "A{2,3} 9{1,4}"
            }).mask("#vehicleRegistration");

            tmsApp.appFormValidator('form[name="newTomCardForm"]',
                {},
                {}
            );

            $(document).on('change', '#vehicleRegistration', function () {
                function getVehicleDetails() {
                    const numberPlate = document.querySelector('#vehicleRegistration').value
                    let formData = new FormData();
                    formData.append('vehicle_registration', numberPlate);

                    tmsApp.asyncGetFormData(
                        $('#vehicleRegistration').attr('data-action') + '?vehicle_registration=' + numberPlate,
                        formData,
                        function (response_data) {
                            if (response_data.success === 'true' || response_data.success === true) {
                                let vehicle = response_data.payload['vehicle'];
                                let images = response_data.payload['images'];
                                let vehicle_state = response_data.payload['vehicle_state'];
                                let vehicle_tom_card_message = response_data.payload['vehicle_tom_card_message'];

                                if (!vehicle || !vehicle.brand_name) {
                                    return;
                                }

                                if (vehicle['status'] !== document.querySelector('[name="vehicleActive"]').value) {
                                    tmsApp.showSystemMessage("Vehicle State",
                                        vehicle_state,
                                        () => {
                                        },
                                        "error");
                                    return;
                                }

                                if (vehicle['has_tom_card'] === 'Y') {
                                    tmsApp.showSystemMessage("Vehicle Has A Tom Card Already",
                                        vehicle_tom_card_message,
                                        () => {
                                        },
                                        "error");
                                    return;
                                }

                                let vLabel = vehicle['body_type_name']
                                    + ' ' + vehicle['brand_name']
                                    + ' ' + vehicle['model_name']
                                    + ' ' + vehicle['model_code'];
                                $("#vehicle_description").val(vLabel);
                                $("#vehicle_status").text(vehicle['status_name']);

                                if (images && images.length > 0) {
                                    let frontViewImages = images.filter((image) => {
                                        return image['file_type'] === 'Front View';
                                    })
                                    let imagePath = frontViewImages[0]?.path;
                                    document.querySelector(".imagePreview")
                                        .style.backgroundImage = "url(/storage" + imagePath + ")";
                                }
                            } else {
                                let $message = response_data['message']
                                    ? response_data['message']
                                    : ' No Vehicle Found, Check your input and try again';
                                tmsApp.systemError('Vehicle', $message);
                            }
                        },
                        function (xhr) {
                            tmsApp.systemError('System Message',
                                'We could not complete processing your request, please try again later');
                        }
                    )
                }

                getVehicleDetails();
            });

            $("#submitRequisitionBtn").on('click', function () {
                let $form = document.forms['newTomCardForm'];
                if (!$($form).valid()) {
                    return;
                }

                $('.print-error-msg').css('display', 'none');
                let formData = new FormData($form);
                tmsApp.confirm(
                    'Tom Card Assignment',
                    'Are you sure you want to assign the Tom card ?',
                    'Yes',
                    'No',
                    function () {
                        window.top.tmsApp.asyncPostFormData(
                            $form.action,
                            formData,
                            function (asyncResponse) {

                                if (asyncResponse.hasOwnProperty('state') && asyncResponse['state'] === 'success') {
                                    setTimeout(function () {
                                        tmsApp.showSystemMessage(
                                            'Tom Card Assignment',
                                            asyncResponse['message'],
                                            function () {
                                                window.location.reload();
                                            },
                                            'success'
                                        );
                                    }, 300);
                                } else {
                                    if (asyncResponse.hasOwnProperty('errors')) {
                                        tmsApp.printErrorMsg(asyncResponse.errors);
                                        return
                                    }
                                    setTimeout(function () {
                                        tmsApp.systemError(
                                            'Tom Card Assignment',
                                            asyncResponse['message'],
                                            function () {
                                            }, 'error');
                                    }, 300);
                                }
                            },
                            function (xhr, settings, errorThrown) {
                                console.log(errorThrown)
                                setTimeout(function () {
                                    if ('responseJSON' in xhr) {
                                        if (xhr.responseJSON.hasOwnProperty('errors')) {
                                            tmsApp.printErrorMsg(xhr.responseJSON.errors);
                                        }
                                        if (xhr.responseJSON.hasOwnProperty('message')) {
                                            tmsApp.systemError(
                                                'Tom Card Assignment',
                                                xhr.responseJSON['message']
                                            );
                                        }
                                        return;
                                    }

                                    tmsApp.systemError(
                                        'Tom Card Assignment',
                                        'We could not complete processing your request, please try again later');
                                }, 300)
                            }
                        )
                    },
                    function () {
                    }
                );
            });

            $('[name="dateIssued"]').datepicker({
                maxDate: new Date(),
                dateFormat: 'dd/mm/yy',
            });

            $(document).on('click', '[data-action="openDatePicker"]', function () {
                $(".datetimepicker-opened").datepicker("show");
            });

            let clearDateBtns = document.querySelectorAll('[data-action="clearDate"]');
            clearDateBtns.forEach(function (button) {
                button.addEventListener('click', function (event) {
                    let input = $(event.target).parent().find('input');
                    if (input.length === 0) {
                        input = $(this).parent().parent().find('input')
                    }
                    $(input).datepicker('setDate', null);
                });
            });

        })(window.tmsApp, jQuery);
    </script>

@endpush
