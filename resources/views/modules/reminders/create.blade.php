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

    <x-content-header :pageTitle="'Vehicle Renewal Reminder'" :activeCrumb="'Reminders'" :link="'reminder.list'"
                      :linkText="'Renewal Reminders'"/>
    <section class="content">
        <div class="container-fluid">
            <!-- Main row -->
            <div class="row">
                <!-- Left col -->
                <div class="col-md-8 pl-0">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <h4>Renewal Reminder Entry</h4>
                            </div>
                            <div id="actionButtonsContainer" class="card-toolbar justify-content-end">
                                <label class="app-required-marker"></label>
                            </div>
                        </div>
                        <div class="card-body" style="padding: 0.5rem 1rem;">
                            <form class="" name="newRenewalReminderForm"
                                  action="{{route('reminder.save')}}"
                                  id="newRenewalReminderForm"
                                  method="post">
                                @csrf
                                <input type="hidden" name="relatedReference" id="relatedReference"
                                       value="{{$relatedReference ?? ''}}"/>
                                <div class="errorTxt"></div>
                                <x-error-view></x-error-view>
                                <table class="app_form_table table">
                                    <tr>
                                        <td>
                                            <label class="app-field-label">
                                                Vehicle <span class="text-danger">*</span>
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

                                        </td>
                                        <td>

                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <label class="app-field-label field-required">
                                                Vehicle Renewal Type
                                            </label>
                                        </td>
                                        <td>
                                            <div class="app-field-input" data-field="">
                                                <div class="input-group">
                                                    <select id="reminderRenewalType"
                                                            name="reminderRenewalType"
                                                            required
                                                            class="form-select form-select-sm">
                                                        <option disabled value=""></option>
                                                        <option value="01">Insurance</option>
                                                        <option value="02">Road Tax(Motor Vehicle License)</option>
                                                        <option value="02">Fitness(Road-worthiness)</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </td>
                                        <td>

                                        </td>
                                        <td class="pl-5">

                                        </td>

                                    </tr>

                                    <tr>
                                        <td>
                                            <label class="app-field-label field-required">
                                                Due Date
                                            </label>
                                        </td>
                                        <td>
                                            <div class="input-group date">
                                                <input type="text"
                                                       required
                                                       name="reminderDueDate"
                                                       id="reminderDueDate"
                                                       class="form-control datetimepicker"
                                                />
                                                <div class="input-group-append"
                                                     data-target="#dateIssued"
                                                     data-action="openDatePicker">
                                                        <span type="button"
                                                              data-action="openDatePicker"
                                                              class="input-group-text ui-datepicker-trigger">
                                                            <i data-action="openDatePicker"
                                                               class="fa fa-calendar"></i>
                                                        </span>
                                                </div>
                                                <button type="button" data-action="clearDate"
                                                        class="input-group-text">
                                                    <i data-action="clearDate" class="fa fa-eraser"></i>
                                                </button>
                                            </div>
                                        </td>

                                        <td class="pl-5">
                                            {{-- <label class="app-field-label" data-field="specificlocationofia">
                                                 Expiry Date <span class="text-danger">*</span>
                                             </label>--}}
                                        </td>

                                        <td>
                                            {{-- <div class="input-group date">
                                                 <input type="text"
                                                        name="expiryDate"
                                                        id="expiryDate"
                                                        autocomplete="off"
                                                        class="form-control datetimepicker"
                                                        data-target="#dateOpened"
                                                        required/>
                                                 <div class="input-group-append"
                                                      data-target="#dateOpened"
                                                      data-action="openDatePicker">
                                                     <span data-action="openDatePicker"
                                                           class="input-group-text">
                                                         <i data-action="expiryDate"
                                                            class="fa fa-calendar">
                                                         </i>
                                                     </span>
                                                 </div>
                                                 <button type="button"
                                                         data-action="clearDate"
                                                         class="input-group-text">
                                                     <i data-action="clearDate"
                                                        class="fa fa-eraser">
                                                     </i>
                                                 </button>
                                             </div>--}}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <label class="app-field-label">
                                                Due Soon Threshold
                                                <i title="How long before due date would you like to notify watchers that the renewal will be due "
                                                   data-toggle="tooltip"
                                                   class="ml-3 fa fa-question-circle-o text-muted"
                                                   style="font-size: 18px;"></i>
                                            </label>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div class="col-6">
                                                    <input type="number"
                                                           name="dueThreshold"
                                                           id="dueThreshold"
                                                           min="1"
                                                           step="1"
                                                           required
                                                           class="form-control form-control-sm"/>
                                                </div>
                                                <div class="col-6">
                                                    <select id="reminderRenewalType"
                                                            name="reminderRenewalType"
                                                            required
                                                            class="form-select form-select-sm">
                                                        <option disabled value=""></option>
                                                        <option value="01">Day(s)</option>
                                                        <option value="02">Week(s)</option>
                                                        <option value="03">Months(s)</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="pl-5">
                                            <label class="hq-field field-required" data-field="">

                                            </label>
                                        </td>
                                        <td>
                                            <div class="app-field-input" data-field="">
                                                <div class="input-group">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="2">
                                            <label class="app-field-label-">
                                                <input type="checkbox" name="notificationsEnabled"
                                                       class="checkbox"/>
                                                Notifications
                                            </label>
                                            <p>When enabled, notifications may be sent to each Watcher via mail</p>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <label class="app-field-label mb-0" data-field="responseHead">
                                                Watchers
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <div class="app-field-input" data-field="responseHead">
                                                <div class="input-group">
                                                    <select type="text"
                                                            id="watchers"
                                                            multiple
                                                            required
                                                            name="watchers"
                                                            class="form-control watchers"></select>
                                                    <input type="hidden"
                                                           data-assignmenttype="single"
                                                           data-inputfield="responseHeadId"
                                                           id="responseHeadId"
                                                           name="responseHeadId"/>
                                                    <div class="input-group-append">
                                                        <div data-assignmenttype="single"
                                                             data-inputfield="responseHead"
                                                             data-field="userSelection"
                                                             class="input-group-text">
                                                            <i class="fa fa-users"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="4">
                                            <label class="app-field-label mb-0" data-field="typeia">
                                                Comment
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="background: none;" colspan="4">
                                            <div class="app-field-input">
                                                    <textarea name="comments" id="comments"
                                                              placeholder="Add an optional comment"
                                                              class="form-control"></textarea>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                        <div class="card-footer d-flex justify-content-end" style="padding: 0.5rem 1rem;">
                            <button type="button" id="resetRequisitionBtn" class="btn btn-default btn-sm mr-3">
                                <i class="fas fa-undo"></i> Cancel
                            </button>

                            <button type="button" id="saveReminderBtn"
                                    class="btn btn-success btn-sm mr-3 when_odo_valid">
                                <i class="fas fa-save"></i> Save Renewal Reminder
                            </button>
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
    @include('layouts.partials.dataTableScripts')
    <script src="{{asset('libs/imageUpload/imageUpload.js')}}"></script>
    <!-- page script -->
    <script>
        (function (tmsApp, $) {

            $('.watchers').select2({});

            Inputmask({
                "mask": "99999999"
            }).mask("#permit_number");

            Inputmask({
                "mask": "99999999"
            }).mask("#license_number");

            tmsApp.appFormValidator('form[name="newRenewalReminderForm"]',
                {},
                {}
            );

            $("#saveReminderBtn").on('click', function () {
                let $form = document.forms['newRenewalReminderForm'];
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
                /*  maxDate: new Date(),*/
                dateFormat: 'dd/mm/yy',
            });

            $('[name="expiryDate"]').datepicker({
                /* minDate: new Date(),*/
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
