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

    <x-content-header :pageTitle="'Tom Card Assignment'" :activeCrumb="'OnBoarding'" :link="'list.tom.card'"
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
                        <div class="card-body p-2">
                            <form class="" name="newETollCardForm"
                                  action="{{route('save.assign.tom.card')}}"
                                  id="newETollCardForm"
                                  method="post">
                                @csrf
                                <input type="hidden" name="relatedReference" id="relatedReference"
                                       value="{{$relatedReference ?? ''}}"/>
                                <div class="errorTxt"></div>
                                <x-error-view></x-error-view>

                                <label class="app-required-marker"></label>

                                <fieldset style="" class="form-group border p-3">
                                    <legend>General Information:</legend>
                                    <table class="app_form_table table">
                                        <tr>
                                            <td>
                                                <label class="app-field-label">
                                                    Vehicle Registration Number
                                                    <span class="text-danger">*</span>
                                                </label>
                                            </td>
                                            <td>
                                                <div class="app-field-input" data-field="taskOriginator">
                                                    <div class="input-group">
                                                        <input type="text"
                                                               id="vehicleRegistration"
                                                               required
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
                                            </td>
                                            <td class="pl-5">
                                            </td>
                                            <td>
                                            </td>
                                        </tr>

                                        {{--<tr>
                                            <td>
                                                <label class="app-field-label">
                                                    NRFA Batch Number <span class="text-danger">*</span>
                                                </label>
                                            </td>
                                            <td>
                                                <div class="app-field-input" data-field="taskOriginator">
                                                    <div class="input-group">
                                                        <input type="text"
                                                               id="batchNumber"
                                                               required
                                                               autocomplete="off"
                                                               name="batchNumber"
                                                               class="form-control"/>
                                                        <div class="input-group-append">
                                                            <button type="button" data-assignmenttype="single"
                                                                    data-inputfield="taskOriginator"
                                                                    data-field="userSelection"
                                                                    class="input-group-text">
                                                                <i class="fa fc-day-number"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="pl-5">
                                                <label class="field-required app-field-label-">
                                                    Scheme
                                                </label>
                                            </td>
                                            <td>
                                                <div class="app-field-input" data-field="dateoriginated">
                                                    <div class="input-group">
                                                        <select
                                                                name="cardScheme"
                                                                class="form-select">
                                                            <option value="ST">Standard</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>--}}

                                        <tr>
                                            <td>
                                                <label class="app-field-label field-required">
                                                    Card Number
                                                </label>
                                            </td>
                                            <td>
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
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <label class="app-field-label field-required">
                                                    Date Issued
                                                </label>
                                            </td>
                                            <td>
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
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="pl-5">
                                                <label class="app-field-label" data-field="specificlocationofia">
                                                    Expiry Date <span class="text-danger">*</span>
                                                </label>
                                            </td>

                                            <td>
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
                                            </td>

                                        </tr>

                                        <tr>
                                            <td colspan="4">
                                                <label class="app-field-label" data-field="typeia">
                                                    Comments
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background: none;" colspan="4">
                                                <div class="app-field-input">
                                                    <textarea name="comments" id="comments"
                                                              class="form-control"></textarea>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
    <x-employee-search-modal/>
    <input type="hidden" value="{{route('license.details.verification')}}" id="rtsaLicenseVerificationEndPoint">
@endsection

@push('scripts')
    <script
            src="{{asset('application/modules/userManagement/employee.search.js').'?v='.Carbon::now()->format('his')}}"></script>
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

            tmsApp.appFormValidator('form[name="newETollCardForm"]',
                {},
                {}
            );

            $("#submitRequisitionBtn").on('click', function () {
                let $form = document.forms['newETollCardForm'];
                if (!$($form).valid()) {
                    return;
                }

                $('.print-error-msg').css('display', 'none');
                let formData = new FormData($form);
                tmsApp.confirm(
                    'eToll Card onboarding',
                    'Are you sure you want to onboard the data ?',
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
                                            'eToll Card Saved',
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
                                            'eToll Card onboarding',
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
                                                'eToll Card onboarding',
                                                xhr.responseJSON['message']
                                            );
                                        }
                                        return;
                                    }

                                    tmsApp.systemError(
                                        'eToll Card Saving',
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
