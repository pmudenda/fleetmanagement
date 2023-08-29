@php use App\Helpers\StatusHelper;use Carbon\Carbon; @endphp
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

        .nav-tabs .nav-link.active, .nav-tabs .nav-item.show .nav-link {
            border-color: orange;
        }

        .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link {
            color: #495057;
            background-color: #fff;
            border-color: #dee2e6 #dee2e6 #fff;
        }
    </style>
@endpush

@section('content')

    <x-content-header :pageTitle="'Tom Card Assignment'"
                      :activeCrumb="'Assignment'"
                      :link="'assign.tom.card'"
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
                            <div class="card-toolbar  justify-content-end">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item" style="list-style: none; width: 178px;">
                                        <a class="nav-link active"
                                           data-toggle="tab"
                                           href="#assignments"
                                           role="tab">Assignments</a>
                                    </li>
                                    <li class="nav-item" style="list-style: none; width: 178px;">
                                        <a class="nav-link"
                                           data-toggle="tab"
                                           href="#assign"
                                           role="tab">Assign Tom Card</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="tab-content">

                            <div class="tab-pane active" id="assignments" role="tabpanel">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="row mt-2">
                                        <table id="TomCards"
                                               aria-label="Tom cards"
                                               class="table table-row-dashed align-middle">
                                            <thead>
                                            <tr>
                                                <th scope="row">Reg. No.</th>
                                                <th scope="row">Card No.</th>
                                                <th scope="row">State</th>
                                                <th scope="row">valid From</th>
                                                <th scope="row">Valid To</th>
                                                <th scope="row">Assigned By</th>
                                                <th scope="row">Justification</th>
                                                <th scope="row">Action</th>
                                            </tr>
                                            </thead>
                                            @foreach($tomCardAllocations as $tomCardAllocation)
                                                <tr>
                                                    <td>{{$tomCardAllocation->reg_no}}</td>
                                                    <td style="text-wrap: nowrap;">
                                                        {{$tomCardAllocation->card_number}}
                                                    </td>
                                                    <td>
                                                        @if($tomCardAllocation->status == '01')
                                                            <span class="badge badge-success p-2">
                                                                Active
                                                            </span>
                                                        @else
                                                            <span class="badge badge-danger p-2">
                                                                Inactive
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{
                                                            Carbon::parse($tomCardAllocation->period_from)
                                                            ->format('d/m/Y')
                                                        }}
                                                    </td>
                                                    <td>
                                                        {{
                                                            Carbon::parse($tomCardAllocation->period_to)
                                                        }}
                                                    </td>
                                                    <td>{{$tomCardAllocation->assigned_by_name}}</td>
                                                    <td>
                                                        @if($tomCardAllocation->status == '01')
                                                            {{$tomCardAllocation->assignment_justification}}
                                                        @else
                                                            {{$tomCardAllocation->revocation_justification}}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <button type="button"
                                                                data-form-url="{{route('revoke.assign.tom.card')}}"
                                                                data-id="{{$tomCardAllocation->id}}"
                                                                id="revokeTomCardBtn"
                                                                title="Revoke Assignment"
                                                                name="revokeTomCardBtn"
                                                                class="btn btn-danger">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="assign" role="tabpanel">
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
                                            <div class="row mt-5">
                                                <div class="col-6">
                                                    <div class="row mb-2">
                                                        <div class="col" data-id="table-td">
                                                            <label class="app-field-label">
                                                                Vehicle Registration Number
                                                                <span class="text-danger">*</span>
                                                            </label>
                                                        </div>
                                                        <div class="col" data-type="table-td">
                                                            <div class="app-field-input">
                                                                <div class="input-group">
                                                                    <input type="text"
                                                                           id="vehicleRegistration"
                                                                           required
                                                                           data-action="{{
                                                                   route('requisition.vehicle.details')
                                                                   }}"
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

                                                    {{--<div class="row mb-2">
                                                        <div class="col">
                                                            <label
                                                                    for="dateIssued"
                                                                    class="field-required">
                                                                Date Issued
                                                            </label>
                                                        </div>
                                                        <div class="col">
                                                            <div class="input-group date">
                                                                <input type="date"
                                                                       min="{{date('Y-m-d', strtotime(Carbon::now()))}}"
                                                                       name="dateIssued"
                                                                       id="dateIssued"
                                                                       autocomplete="off"
                                                                       class="form-control"/>
                                                                <div class="input-group-append">
                                                                <span type="button"
                                                                      class="input-group-text">
                                                                    <i class="fa fa-calendar"></i>
                                                                </span>
                                                                </div>
                                                                <button type="button" data-action="clearDate"
                                                                        class="input-group-text">
                                                                    <i data-action="clearDate" class="fa fa-eraser"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>--}}

                                                    <div class="row mb-2">
                                                        <div class="col">
                                                            <label for="expiryDate"
                                                                   class="app-field-label field-required">
                                                                Expiry
                                                            </label>
                                                        </div>

                                                        <div class="col">
                                                            <div class="input-group date">
                                                                <input type="text"
                                                                       name="expiryDate"
                                                                       id="expiryDate"
                                                                       class="form-control"
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
                                                                Justification
                                                            </label>
                                                        </td>
                                                    </div>

                                                    <div class="row mb-2">
                                                        <div class="col" data-id="table-td" style="background: none;">
                                                            <div class="app-field-input">
                                                    <textarea name="comments"
                                                              id="comments"
                                                              minlength="20"
                                                              maxlength="255"
                                                              class="form-control"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-3">
                                                    <div id="image_view" class="card text-center my-2"
                                                         style="display: none;">
                                                        <div class="form-group">
                                                            <div class="imagePreview"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-3">
                                                    <div id="vehicleDetailsContainer" style="display: none;"
                                                         class="col-xs-12 col-sm-12 col-md-12">
                                                        <table aria-label="vehicle summary details"
                                                               class="table">
                                                            <thead>
                                                            <tr>
                                                                <th colspan="2"><strong>Summary</strong></th>
                                                            </tr>
                                                            </thead>
                                                            <tbody id="vehicleDetails" class="vehicleDetails">
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>

                                    </div>
                                    <div class="card-footer">
                                        <div id="actionButtonsContainer" class="card-toolbar justify-content-end">
                                            <button type="button"
                                                    id="submitTomCardBtn"
                                                    disabled
                                                    class="btn btn-success disabled btn-sm mr-3 when_odo_valid">
                                                <i class="fas fa-save"></i> Save
                                            </button>
                                            <button type="button" id="resetRequisitionBtn"
                                                    class="btn btn-danger btn-sm mr-3">
                                                <i class="fas fa-undo"></i> Clear Data
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
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
            Inputmask.extendDefinitions({
                M: {
                    validator: "0[1-9]|1[012]",
                    cardinality: 2,
                    placeholder: 'm',
                    prevalidator: [{
                        validator: function (chrs, maskset, pos, strict, opts) {
                            var isNumeric = new RegExp("[0-9]");
                            if (!isNumeric.test(chrs)) return false;
                            if (chrs > "1") {
                                maskset.buffer[pos] = "0";
                                return {
                                    "pos": pos + 1,
                                    "c": chrs,
                                };
                            } else return true;
                        },
                        cardinality: 1
                    }]
                },
                Y: {
                    validator: "\\d{2}",
                    cardinality: 2,
                    placeholder: 'y',
                }
            });

            Inputmask({
                "mask": "999999 999999999999"
            }).mask("#cardNumber");

            Inputmask({
                "mask": "A{2,3} 9{1,4}"
            }).mask("#vehicleRegistration");

            Inputmask({
                "mask": "M/Y"
            }).mask("#expiryDate");

            tmsApp.initDatatable("#TomCards", false, true, []);

            tmsApp.appFormValidator('form[name="newTomCardForm"]',
                {
                    cardNumber: {
                        required: true
                    },
                    expiryDate: {
                        required: true
                    },
                    dateIssued: {
                        required: true
                    },
                    comments: {
                        required: true,
                        minlength: 50,
                        maxlength: 255
                    }
                },
                {
                    cardNumber: {
                        required: "Tom Card Number Is Mandatory"
                    },
                    dateIssued: {
                        required: true
                    },
                    comments: {
                        required: "Justification for assigning tom card to vehicle is required",
                        minlength: "The Justification should not be less than 50 characters",
                        maxlength: "The Justification should not be more than 255 characters"
                    }
                }
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
                                let vehicle_tom_card_message = response_data.payload['vehicle_tom_card_message'];

                                if (!vehicle || !vehicle.brand_name) {
                                    return;
                                }

                                if (vehicle['status'] !== document.querySelector('[name="vehicleActive"]').value) {
                                    $('.when_odo_valid').addClass('disabled').attr('disabled', true);
                                    tmsApp.showSystemMessage("Vehicle State",
                                        'Vehicle Is Not Active, ' +
                                        'Please Contact Fleet Master System Administrator ' +
                                        'on 3309,3350,3351,3306, ' +
                                        'fleetmaster@zesco.co.com',
                                        () => {
                                        },
                                        "error");
                                    return;
                                }

                                if (vehicle['has_tom_card'] === 'Y') {
                                    $('.when_odo_valid').addClass('disabled').attr('disabled', true);
                                    setTimeout(function () {
                                        tmsApp.showSystemMessage(
                                            "Vehicle Has A Tom Card ",
                                            vehicle_tom_card_message,
                                            null,
                                            "error"
                                        );

                                    }, 300)
                                    return;
                                }

                                let vLabel = vehicle['body_type_name']
                                    + ' ' + vehicle['brand_name']
                                    + ' ' + vehicle['model_name']
                                    + ' ' + vehicle['model_code'];
                                $("#vehicle_description").val(vLabel);
                                $("#vehicle_status").text(vehicle['status_name']);

                                let row = `<tr>
                                                <th>Make</th>
                                                <td id="make">
                                                    ${vehicle['brand_name']}
                                                </td>
                                           </tr>
                                            <tr>
                                                <th>Model</th>
                                                <td id="model">
                                                    ${vehicle['model_name']} ${vehicle['model_code']}
                                                </td>
                                           </tr>
                                            <tr style="">
                                                     <th>Type</th>
                                                     <td id="registration">
                                                        ${vehicle['body_type_name']}
                                                     </td>
                                            </tr>
                                            <tr style="">
                                                     <th>State:</th>
                                                     <td id="registration">
                                                         ${vehicle['status_name']}
                                                     </td>
                                            </tr>`;

                                $('tbody#vehicleDetails').html(row);
                                $('.when_odo_valid').removeClass('disabled').attr('disabled', false);
                                if (images && images.length > 0) {
                                    document.querySelector('#vehicleDetailsContainer').style.display = null;
                                    document.querySelector('#image_view').style.display = null;

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
                                $('.when_odo_valid').addClass('disabled').attr('disabled', true);
                            }
                        },
                        function (xhr) {
                            $('.when_odo_valid').addClass('disabled').attr('disabled', true);
                            tmsApp.systemError('System Message',
                                'We could not complete processing your request, please try again later');
                        }
                    )
                }

                getVehicleDetails();
            });

            $(document).on('input', '[name="comments"]', function () {
                this.value = this.value?.toUpperCase();
            })

            $(document).on('click', "#submitTomCardBtn", function () {
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

            $(document).on('click', "#revokeTomCardBtn", function () {
                const recordId = $(this).attr('data-id');
                const postUrl = $(this).attr('data-form-url');
                let formData = new FormData();
                formData.append('record', recordId);

                tmsApp.confirm(
                    'Tom Card Revocation',
                    'Are you sure you want to revoke the assigned Tom card ?',
                    'Yes',
                    'No',
                    async function () {
                        const {value: justification} = await Swal.fire({
                            input: 'textarea',
                            inputLabel: 'Message',
                            inputPlaceholder: 'Type your justification here...',
                            inputAttributes: {
                                'aria-label': 'Type your justification here',
                                'required': true
                            },
                            showCancelButton: true
                        })

                        if (!justification) {
                            return
                        }

                        formData.append('justification', justification);
                        window.top.tmsApp.asyncPostFormData(
                            postUrl,
                            formData,
                            function (asyncResponse) {

                                if (asyncResponse.hasOwnProperty('state') && asyncResponse['state'] === 'success') {
                                    setTimeout(function () {
                                        tmsApp.showSystemMessage(
                                            'Tom Card Revocation',
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
                                            'Tom Card Revocation',
                                            asyncResponse['message'],
                                            function () {
                                            }, 'error');
                                    }, 300);
                                }
                            },
                            function (xhr, settings, errorThrown) {
                                setTimeout(function () {
                                    if ('responseJSON' in xhr) {
                                        if (xhr.responseJSON.hasOwnProperty('errors')) {
                                            tmsApp.printErrorMsg(xhr.responseJSON.errors);
                                        }
                                        if (xhr.responseJSON.hasOwnProperty('message')) {
                                            tmsApp.systemError(
                                                'Tom Card Revocation',
                                                xhr.responseJSON['message']
                                            );
                                        }
                                        return;
                                    }

                                    tmsApp.systemError(
                                        'Tom Card Revocation',
                                        'We could not complete processing your request, please try again later');
                                }, 300)
                            }
                        )
                    },
                    function () {
                    }
                );
            });

            let clearDateButtons = document.querySelectorAll('[data-action="clearDate"]');
            clearDateButtons.forEach(function (button) {
                button.addEventListener('click', function (event) {
                    let input = $(event.target).parent().find('input');
                    if (input.length === 0) {
                        input = $(this).parent().parent().find('input')
                    }
                    $(input).val(null);
                });
            });

        })(window.tmsApp, jQuery);
    </script>

@endpush
