@php use Carbon\Carbon; @endphp
@extends('layouts.app')
@push('styles')
    <link href="{{asset("assets/plugins/select2/css/select2.min.css")}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset("assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css")}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{asset("libs/steps/jquery-steps.css")}}" rel="stylesheet" type="text/css"/>
    <style>
        th {
            white-space: nowrap;
        }

        /**===NO WRAP ON TABLE =====**/
        table.dataTable.nowrap th,
        table.dataTable.nowrap td {
            white-space: nowrap;
        }

        .select2 {
            width: 100% !important;
        }
    </style>
@endpush
@section('content')

    <x-content-header
        :activeCrumb="'New Job Card'"
        :linkText="'Job Card'"
        :pageTitle="'Workshop Management'"/>
    <section class="content">
        <div class="card">
            <div class="card-header">
                <div class="card-title">

                    <h4>Workshop Job Card</h4>
                    @if(!empty($details) && !empty($details->job_card_no))
                        <span class="ml-2 indicator-pill whitespace-nowrap green">
                            <span>Saved</span>
                        </span>
                    @else
                        <span class="ml-2 indicator-pill whitespace-nowrap orange"><span>Not Saved</span></span>
                    @endif
                </div>
                @if(!empty($details) && !empty($details->job_card_no))
                    <div class="card-toolbar justify-content-end">
                        JOB CARD NUMBER: <span class="text-orange">{{ $details->job_card_no ?? '' }}</span>
                    </div>
                @endif

            </div>

            <div class="card-body pb-4 min-h-600px pt-0">

                <x-error-view/>

                <label class="app-required-marker"></label>
                <form name="jobCardForm"
                      id="jobCardForm"
                      action="{{route('save.workshop.requisition')}}"
                      method="post">
                    @csrf
                    <h1>Job Card Details</h1>
                    <section>
                        @include('modules.requisitions.maintenance.tabs.job_card_header')
                    </section>

                    <h1>Accessories Checkin & Movement</h1>
                    <section>
                        @include('modules.requisitions.maintenance.tabs.accessories')
                    </section>

                    <h1>Defects</h1>
                    <section>
                        @include('modules.requisitions.maintenance.tabs.defects')
                    </section>
                    <h1>Parts Selection</h1>
                    <section>
                        @include('modules.requisitions.maintenance.tabs.partsSelection')
                    </section>

                </form>

                <input type="hidden" value="{{ route('user.search') }}" id="newUserSearchUrl"/>
                <input type="hidden" value="{{route('search.project')}}" id="projects_url"/>
                <input type="hidden" value="{{route('all.workshop.list')}}" id="workshopsUrl"/>
                <input type="hidden" value="{{route('fuels.levels')}}" id="fuelLevelsUrl"/>
                <input type="hidden" value="{{route('load.vehicle.systems')}}" id="systemsUrl"/>
                <input type="hidden" value="{{route('load.defects.category')}}" id="defectCategoryUrl"/>
                <input type="hidden" value="{{route('load.defects')}}" id="defectUrl"/>
                <input type="hidden" value="{{route('load.workshop.section')}}" id="workShopSectionsUrl"/>
                <input type="hidden" value="{{route('load.articles')}}" id="articlesUrl"/>
                <input type="hidden" value="{{route('load.article.details')}}" id="articleDetailsUrl"/>
                <input type="hidden" value="{{$details->job_card_no ?? ''}}" id="job_card_number"/>
                <input type="hidden" value="{{$details->veh_reg ?? ''}}" name="vehicle_registration"
                       id="vehicle_registration"/>
                <input type="hidden" value="{{$details->workshop_doc_no ?? ''}}" name="workshop_reference"
                       id="workshop_reference"/>
                <input type="hidden" value="{{route('delete.defect.record')}}" name="deleteDefectUrl"
                       id="deleteDefectUrl"/>
                <input type="hidden" value="{{route('delete.material.record')}}" name="deleteMaterialUrl"
                       id="deleteMaterialUrl"/>
            </div>
        </div>
        <input type="hidden" name="onboarding_status" id="onboarding_status" value="030">
    </section>
@endsection
@push('scripts')
    <script>
        window.selectedAccessories = {!! json_encode($accessories_checked_in) !!};
        window.defects = {!! json_encode($defects) !!};
        window.step_id = {!! $step !!};
    </script>
    <script src="{{asset('assets/plugins/select2/js/select2.min.js')}}"></script>
    <script src="{{asset("libs/steps/jquery.steps.js")}}"></script>
    <script>
        'use strict';

        function initArticleSelector(element) {
            const dataUrl = document.querySelector('#articlesUrl').value;

            // don't re-initialize
            let hasAttribute = element[0].hasAttribute('data-select2-id="1"');
            console.log(hasAttribute);
            if (hasAttribute) {
                return;
            }

            element.select2({
                selectOnClose: true,
                multiple: false,
                quietMillis: 100,
                id: function (project) {
                    return project['code_article'];
                },
                theme: 'bootstrap4',
                ajax: {
                    delay: 250,
                    beforeSend: function () {
                        window.showLoaderModal(false);
                        window.loaderVisible = false;
                    },
                    url: dataUrl,
                    dataType: 'json',
                    data: function (params) {
                        return {
                            search: params.term, // search term
                            type_article: document.querySelector('#itemType').value,
                            store_code: document.querySelector('#store_code').value,
                            page: params.page
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;

                        return {
                            results: formatResults(data.items),
                            pagination: {
                                more: (params.page * 30) < data['total_count']
                            }
                        };
                    },
                    cache: true
                },
                placeholder: 'Enter Article name or Code',
                minimumInputLength: 3,
                templateResult: formatRepo,
                templateSelection: formatRepoSelection
            }).off('select2:select').on('select2:select', function (e) {
                let article = e.params['data'];
                const row = $(e.currentTarget).closest('tr');

                if (article?.quantity_in_store === "0" || article?.quantity_in_store === 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'The Store '
                            + $("#store_name").val()
                            + ' does not have '
                            + article?.id
                            + ' - '+article['technical_specifications']+' in stock. ' +
                            'You may have to wait until the stock is received before your request can be processed'
                    });
                }

                //$(row).find('[name="quantity"]').attr('max', article['quantity_in_store']);
                $(row).find('[name="articleCode"]').val(article['id']);
                $(row).find('[name="unit_price"]').val(article['price_map']);
                $(row).find('[name="technical_specification"]').val(article['technical_specifications']);
                $(row).find('[name="unit_of_measure"]').val(article['unit_measure_name']);

                getArticleDetails(article['id'], )
            });
        }

        function formatRepo(project) {
            if (project.loading)
                return project.text;
            return $('<option value="' + project['id'] + '">' + project['text'] + '</option>');
        }

        function formatRepoSelection(project) {
            if (!project['id']) {
                return project['text'];
            }
            return project['description'];
        }

        function formatResults(items) {
            return $.map(items, function (obj) {
                return {
                    "id": obj['code_article'],
                    "text": obj['code_article'] + ':' + obj.description,
                    'code_article': obj?.code_article,
                    'description': obj?.description,
                    'price_map': obj?.price,
                    'technical_specifications': obj?.technical_specifications,
                    'unit_measure': obj?.unit_measure,
                    'unit_measure_code': obj?.unit_measure,
                    'unit_measure_name': obj?.unit_measure_name,
                    'quantity_in_store': obj?.quantity_in_store
                };
            });
        }


        function getArticleDetails(code_article, selectElem) {

            fetch(document.querySelector('#articleDetailsUrl').value + "?code_article=" + code_article)
                .then(response => response.json())
                .then(response => {
                    if (response.state === 'failure') {
                        //show errors
                        toastr.error('Connection error, no data found')
                        return;
                    }

                    let data = {
                        "id": response['code_article'],
                        "text": response['code_article'] + ':' + response.description,
                        'code_article': response?.code_article,
                        'description': response?.description,
                        'price_map': response?.price,
                        'technical_specifications': response?.technical_specifications,
                        'unit_measure': response?.unit_measure,
                        'unit_measure_name': response?.unit_measure_name
                    };

                    let option = new Option(data.text, data.id, true, true);
                    selectElem.append(option).trigger('change');

                    // manually trigger the `select2:select` event
                    selectElem.trigger({
                        type: 'select2:select',
                        params: {
                            data: data
                        }
                    });

                    /*let workshops = response['payload'];
                    tmsApp.populateDropDownList(selectElem, workshops, "code", ["name"]);

                    let location = selectElem.attr('data-value');
                    console.log(location);
                    if (location) {
                        selectElem.val(location);
                        selectElem.trigger('change');
                    }*/

                })
                .catch(function (error) {
                    // notify of error
                    toastr.error(
                        'Connection error. Could not retrieve data, some feature might not work.')
                });
        }

    </script>
    <script>
        $(document).ready(function () {

            initArticleSelector($('.articlesDropDownList'));

            Inputmask({
                "mask": "AAA 9{1,4}"
            }).mask('[name="vehicle_registration"]');

            $.fn.disableBtn = function () {
                return this.each(function () {
                    $(this).addClass("disabled").attr("disabled", true)
                })
            }

            $.fn.enableBtn = function () {
                return this.each(function () {
                    let $this = $(this);
                    $this.removeClass("disabled").attr("disabled", false)
                })
            }

        });

        (function (tmsApp, $) {
            function adjustIframeHeight() {
                /* var $body   = $('body'),
                     $iframe = $body.data('iframe.fv');
                 if ($iframe) {
                     // Adjust the height of iframe
                     $iframe.height($body.height());
                 }*/
            }

            let form = $('#jobCardForm').show();
            window.goToNext = false;
            let bodyTag = "section";

            $(document).ready(function () {

                setTimeout(function () {
                    let job_card_number = $('[name="job_card_number"]').val();

                    if (job_card_number) {
                        const elem = $("#repairTypeDropdownList");
                        let val = elem.attr('data-value');
                        if (val) {
                            elem.val(val);
                            elem.trigger('change');
                        }
                    }

                    if (window['selectedAccessories']) {
                        setSelectedAccessories();
                    }

                    if (window['defects']) {
                        dataFiler();
                    }

                    findDriver();

                    findVehicle();

                }, 600);
            });

            /*****************************Function Handlers************************************/
            function initializeFormWizard() {
                function postData(formElements, submitForm) {
                    window.loaderMessage = "Posting Data... please wait";
                    let $container = $(formElements);

                    let formSel = $(formElements);

                    let formData = {
                        modelName: formSel.data('modelName'),
                        submitForm: submitForm
                    };

                    let arr = [];
                    let obj = {};

                    if (formSel.data('modelName') === 'Defects' || formSel.data('modelName') === 'PartsHeader') {
                        $(formElements).find("tbody").children().map(function (index, row) {
                            let obj = {};
                            $(row).find('input[name], select[name]').each(function (i, item) {
                                let val = item.value.replace(/,/g, '');

                                if (item.name === 'endDate' || item.name === 'startDate' || item.name === 'invoiceDate') {
                                    let dateField = val;
                                    dateField = DateFormatter.format(new Date(moment(val, 'DD/MM/yyyy')), DateFormatter.ISO);

                                    obj[item.name] = dateField;
                                } else {
                                    obj[item.name] = item.value;
                                }
                            });

                            arr.push(obj);
                        });

                        if (formSel.data('modelName') === 'Defects') {
                            obj['workshop_reference'] = $('input[name="workshop_reference"]').val();
                            obj['job_card_no'] = $('input[name="job_card_voucher"]').val();
                            obj['vehicle_registration'] = $('input[name="vehicle_registration"]').val();
                            obj['remarks'] = $('#remarks').val();
                        } else if (formSel.data('modelName') === 'PartsHeader') {
                            obj['workshop_reference'] = $('input[name="workshop_reference"]').val();
                            obj['itemType'] = $('[name="itemType"]').val();
                            obj['job_card_no'] = $('[name="job_card_number"]').val();
                            obj['purchase_office'] = $('[name="purchase_office"]').val();
                            obj['workshop_code'] = $('[name="workshop_code"]').val();
                            obj['request_date'] = $('[name="request_date"]').val()?.trim();
                            obj['date_expected'] = $('[name="date_expected"]').val()?.trim();
                            obj['supplier'] = $('[name="supplier"]').val();
                            obj['store_code'] = $('[name="store_code"]').val();
                            obj['store_name'] = $('[name="store_name"]').val();
                            obj['remarks'] = $('#comments').val();
                            obj['total_amount'] = $('itemsTotal').text();
                            obj['vehicle_registration'] = $('input[name="vehicle_registration"]').val();
                        }
                    } else {
                        $($container).find('input[name], select[name]').each(function (i, item) {
                            let val = item.value.replace(/,/g, '');

                            if (item.type === 'radio') {
                                obj[item.name] = $('[name="' + item.name + '"]:checked').val();
                            } else {
                                obj[item.name] = item.value;
                            }
                        });
                    }

                    formData['items'] = arr;

                    formData = {
                        ...obj,
                        ...formData
                    }

                    $.ajax({
                        type: "POST",
                        url: formSel.data('formUrl'),
                        data: JSON.stringify(formData),
                        dataType: "json",
                        contentType: "application/json; charset=utf-8",
                    }).done(function (response) {
                        window.loaderMessage = "Loading... please wait";
                        if (response.hasOwnProperty("success") && response.success) {
                            const message = response.message > ""
                                ? response.message
                                : "Request submitted successfully, Click 'Ok' proceed to provide information for other sections";

                            tmsApp.showSystemMessage(
                                "Request Submission",
                                message,
                                function () {
                                    window.location.href = response['redirectUrl'];
                                    if (window.global_currentIndex === 2) {
                                        window.goToNext = true;
                                        form.steps("next");
                                    } else {
                                        window.location.href = response['redirectUrl'];
                                    }
                                },
                                "success"
                            );
                        } else {
                            if (!Util.isEmpty(response.errors)) {
                                if (response.errors) {
                                    tmsApp.printErrorMsg(response.errors);
                                }
                            } else if (!Util.isEmpty(response.message)) {
                                tmsApp.systemError("Request Submission", response.message);
                            }
                        }
                    }).fail(function (xhr) {
                        tmsApp.showErrorMessages(xhr, "Request Submission");
                    })
                }

                let stepId = window.step_id || 1;
                window.global_currentIndex = stepId - 1;
                form.steps({
                    showStepURLhash: true,
                    headerTag: "h1",
                    bodyTag: "section",
                    transitionEffect: "slideLeft",
                    autoFocus: true,
                    saveState: true,
                    startIndex: stepId - 1,
                    labels: {
                        finish: 'Submit'
                    },
                    onStepChanging: function (event, currentIndex, newIndex) {

                        if (currentIndex > newIndex) {
                            return true;
                        }

                        if (currentIndex < newIndex) {
                            // To remove error styles
                            form.find(".body:eq(" + newIndex + ") label.error").remove();
                            form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
                        }

                        form.validate().settings.ignore = ":disabled,:hidden";
                        window.global_currentIndex = currentIndex;
                        if (form.valid() && !window.goToNext) {
                            tmsApp.confirm('Confirm', 'Do you want to save the changes ?', 'Yes', 'No', function () {
                                postData(form.find('[data-model-name]').get(currentIndex), false);
                            }, function () {
                            });
                        }

                        let tmp = window.goToNext;
                        window.goToNext = false;
                        return tmp;

                    },
                    onStepChanged: function (event, currentIndex, priorIndex) {

                        if (currentIndex === 2 && priorIndex === 3) {
                            //form.steps("previous");
                        }
                        adjustIframeHeight();
                        $('ul[aria-label="Pagination"]').find('a[data-action="skip"]').removeClass('d-none');
                        window.global_currentIndex = currentIndex;
                        window.goToNext = false;

                    },
                    onFinishing: function (event, currentIndex) {
                        form.validate().settings.ignore = ":disabled,:hidden";
                        return form.valid();
                    },
                    onFinished: function () {
                        //postData.call(this);
                        //$('a[role="#finish"]').disableBtn();

                        if (form.valid()) {
                            tmsApp.confirm('Confirm', 'Do you want to save the changes ?', 'Yes', 'No', function () {
                                postData($(form.find(bodyTag).get(window.global_currentIndex)).find('[data-model-name]').get(0), true);
                            }, function () {
                            });
                        } else {
                            //$('a[role="#finish"]').enableBtn();
                            //swal("Error !", "You may have some missing data for the return, Kindly review your submission", "error");
                        }

                    },

                })
                    .validate({
                        errorClass: "error-class",
                        validClass: "valid-class",
                        errorElement: 'div',
                        errorPlacement: function (error, element) {
                            if (element.parent('.input-group').length) {
                                error.insertAfter(element.parent());
                            } else {
                                error.insertAfter(element);
                            }
                        },
                        onError: function () {
                            $('.input-group.error-class').find('.help-block.form-error').each(function () {
                                $(this).closest('.form-group').addClass('error-class').append($(this));
                            });
                        },
                        rules: {
                            vehicle_registration: {
                                required: true
                            },
                            workshop: {
                                required: true
                            }
                        },
                        messages: {
                            workshop: {
                                required: "Select the workshop vehicle is being checked-into"
                            },
                            vehicle_registration: {
                                required: "Vehicle Registration is required"
                            },

                            current_odometer: {
                                required: "Enter current odometer reading"
                            },
                            repairType: {
                                required: "Select type of repair"
                            },
                            driver_staff_number: {
                                required: "Driver details are required"
                            }
                        }
                    });
            }

            function getWorkshops() {
                fetch(document.querySelector('#workshopsUrl').value)
                    .then(response => response.json())
                    .then(response => {
                        let selectElem = $('select[name="workshop"]');
                        // Populate results
                        if (response.state === 'failure') {
                            //show errors
                            toastr.error('Connection error, no data found')
                            return;
                        }

                        let workshops = response['payload'];
                        tmsApp.populateDropDownList(selectElem, workshops, "workshop_code", ["workshop_name"], "");

                        let location = selectElem.attr('data-value');

                        if (location) {
                            selectElem.val(location);
                            selectElem.trigger('change');
                        }

                    })
                    .catch(function (error) {
                        // notify of error
                        toastr.error(
                            'Connection error. Could not retrieve data, some feature might not work.')
                    });
            }

            /*function getWorkshopSections() {
                fetch(document.querySelector('#workShopSectionsUrl').value)
                    .then(response => response.json())
                    .then(response => {
                        let selectElem = $('select[name="workshopSection"]');
                        // Populate results
                        if (response.state === 'failure') {
                            //show errors
                            toastr.error('Connection error, no data found')
                            return;
                        }

                        let workshops = response['payload'];
                        tmsApp.populateDropDownList(selectElem, workshops, "code", ["name"]);

                        let location = selectElem.attr('data-value');
                        console.log(location);
                        if (location) {
                            selectElem.val(location);
                            selectElem.trigger('change');
                        }

                    })
                    .catch(function (error) {
                        // notify of error
                        toastr.error(
                            'Connection error. Could not retrieve data, some feature might not work.')
                    });
            }*/

            function getFuelLevels() {
                fetch(document.querySelector('#fuelLevelsUrl').value)
                    .then(response => response.json())
                    .then(response => {
                        let selectElem = $('select[name="fuel_level"]');

                        if (response.state === 'failure') {
                            //show errors
                            toastr.error('Connection error, no data found')
                            return;
                        }

                        let fuelLevels = response['payload'];
                        tmsApp.populateDropDownList(selectElem, fuelLevels, "code", ["name"], "");

                        let location = selectElem.attr('data-value');

                        if (location) {
                            selectElem.val(location);
                            selectElem.trigger('change');
                        }
                    })
                    .catch(function (error) {
                        // notify of error
                        toastr.error(
                            'Connection error. Could not retrieve data, some feature might not work.')
                    });
            }

            function loadData(key, url, selectElem) {
                fetch(url)
                    .then(response => response.json())
                    .then(response => {

                        if (response.state === 'failure') {
                            toastr.error('Connection error, no data found')
                            return;
                        }

                        let fuelLevels = response['payload'];
                        tmsApp.populateDropDownList(selectElem, fuelLevels, "code", ["description"], "");

                        let location = selectElem.attr('data-value');

                        if (location) {
                            selectElem.val(location);
                            selectElem.trigger('change');
                        }
                    })
                    .catch(function (error) {
                        toastr.error(
                            'Connection error. Could not retrieve data, some feature might not work.')
                    });
            }

            function removeSubmissionAndDetailsOptions() {
                let elements = document.querySelectorAll('.when_valid');
                elements.forEach(function (element) {
                    element.setAttribute('disabled', 'disabled');
                });

                document.querySelector('#image_view').style.display = 'none';

                $('tbody#vehicleDetails').html('');
            }

            function enableWebUIControls() {

                let elements = document.querySelectorAll('.when_valid');

                elements.forEach(function (element) {
                    element.removeAttribute('disabled');
                });

                document.querySelector('#vehicleDetailsContainer').style.display = null;
                document.querySelector('#image_view').style.display = null;
            }

            function enableArticleSelectionWebUIControls() {
                let elements = document.querySelectorAll('.articlesDropDownList');
                elements.forEach(function (element) {
                    element.removeAttribute('disabled');
                });
            }

            function populateVehicleDetails(payload) {
                let vehicle = payload['vehicle'];
                //let article = payload['article'];
                let images = payload['images'];
                let vehicle_state = payload['vehicle_state'];

                if (!vehicle || !vehicle.brand_name) {
                    return;
                }

                /*if (typeof vehicle.fuel_allocation === 'undefined' || vehicle.fuel_allocation == null || vehicle.fuel_allocation === "0") {

                    tmsApp.showSystemMessage("Vehicle Details Incomplete",
                        'Vehicle has no Fuel Allocation, Request System Administrator to assign allocation', () => {
                        }, "error")

                    return;
                }*/

                // BAD 1010
                if (vehicle['on_boarding_status'] != document.querySelector('[name="onboarding_status"]').value) {
                    tmsApp.showSystemMessage("Incomplete Vehicle Details",
                        `The vehicle ${vehicle['registration_number']} is ${vehicle_state}. Please Contact Fleet Master
                            System Administrator on 3309,3350,3351,3306, fleetmaster@zesco.com`,
                        () => {
                        },
                        "error");
                    return;
                }

                let vLabel = vehicle['body_type_name'] + ' ' + vehicle['brand_name'] + ' ' + vehicle['model_name'] + ' ' + vehicle['model_code'];
                $("#vehicle_description").val(vLabel);
                let row = `<tr><th>Make</th><td id="make">${vehicle.brand_name}</td></tr>
                               <tr>
                                    <th>Model</th><td id="model">${vehicle.model_name} ${vehicle.model_code}</td>
                               </tr>
                               <tr style="">
                                     <th>Type</th><td id="registration">${vehicle['body_type_name']}</td>
                                </tr>`;

                $('tbody#vehicleDetails').html(row);

                /*if (vehicle.fuel_allocation) {
                    let perWeekAllocation = vehicle.fuel_allocation * 7;
                    document.querySelector('[name="fuel_allocation"]').value = perWeekAllocation ?? 0;
                    document.querySelector('[name="material_quantity"]').value = perWeekAllocation ?? 0;
                    document.querySelector('[name="material_quantity"]').setAttribute('max', perWeekAllocation);
                    $('#totalQty').text(tmsApp.numberFormat(perWeekAllocation));
                }*/

                enableWebUIControls();

                /*if (article) {

                    $("#material_description").text(article['name']);
                    $('input[name="material_description"]').val(article['name']);
                    $('input[name="material_article_code"]').val(article['code']);

                    $("#unit_of_measure").text(article['short_name']);
                    $('input[name="unit_of_measure"]').val(article['short_name']);

                    $("#material_price").text(tmsApp.formatMoney(article['price'], 2));
                    $('input[name="material_price"]').val(article['price']).change();
                }*/

                if (images && images.length > 0) {
                    let frontViewImages = images.filter((image) => {
                        return image['file_type'] === 'Front View';
                    })
                    let imagePath = frontViewImages[0]?.path;
                    document.querySelector(".imagePreview").style.backgroundImage = "url(/storage" + imagePath + ")";
                }

            }

            function findVehicle() {
                const numberPlate = document.querySelector('#vehicle_registration').value;
                if (!numberPlate) {
                    return;
                }

                let formData = new FormData();
                formData.append('vehicle_registration', numberPlate);

                tmsApp.asyncGetFormData(
                    $('#vehicle_registration').attr('data-action') + '?vehicle_registration=' + numberPlate,
                    formData,
                    function (response_data) {
                        if (response_data.success === 'true' || response_data.success === true) {
                            populateVehicleDetails(response_data.payload);
                        } else {
                            removeSubmissionAndDetailsOptions();
                            tmsApp.systemError(
                                'Vehicle',
                                'Vehicle with Registration No.' + numberPlate
                                + ' was not found, Check your input and try again',
                                function () {
                                });
                        }
                    },
                    function (xhr) {
                        tmsApp.systemError(
                            'System Message',
                            'We could not complete processing your request, please try again later',
                            function () {
                            });
                    }
                )
            }

            function findDriver() {
                const staff_number = document.querySelector('#driver_staff_number').value;
                if (!staff_number) {
                    return;
                }

                let formData = new FormData();
                formData.append('searchCriteria', staff_number);

                fetch(
                    document.querySelector("#driver_staff_number").getAttribute('data-action'),
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

                        if (!response.success || response.payload.length == 0) {
                            tmsApp.systemError('Driver Verification', response['message']);
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
                    .catch(function (xhr, settings, error) {
                        tmsApp.showErrorMessages(xhr, 'Driver Validation');
                    });
            }

            function eventHandler(element, e) {

                switch (element.name) {
                    case 'quantity':
                        let summaryTotalQty = 0;
                        $(element).closest("table").find("input[name=quantity]").each(function (i, it) {
                            summaryTotalQty += Util.getFloat(it.value);
                        });

                        // set value in footer
                        $('#quantityTotal').text(tmsApp.getRawNumber(summaryTotalQty));

                        let lineAmountTotal = tmsApp.getFloat(element.value) * tmsApp.getFloat($(element).closest("tr").find("input[name=unit_price]").val());
                        $(element).closest("tr").find("input[name=total_price]").val(lineAmountTotal).change();
                        $(element).closest("tr").find("#total_price").text(tmsApp.numberFormat(lineAmountTotal));
                        break;

                    case 'unit_price':
                        // line total = new material price multiplied by quantity value
                        let totalAmount = tmsApp.getFloat(element.value) * tmsApp.getFloat($(element).closest("tr").find("input[name=quantity]").val());
                        $(element).closest("tr").find("input[name=total_price]").val(totalAmount).change();
                        $(element).closest("tr").find("#total_price").text(tmsApp.numberFormat(totalAmount));
                        break;

                    case 'total_price':
                        // calculate new footer total
                        let summaryTotal = 0;
                        $(element).closest("table").find("input[name=total_price]").each(function (i, it) {
                            summaryTotal += tmsApp.getFloat(it.value);
                        });
                        $('#itemsTotal').text(tmsApp.numberFormat(summaryTotal, 2));
                    default:
                        break;
                }
            }

            function setSelectedAccessories() {

                $.each(selectedAccessories, function (index, element) {
                    $("input[name=field_" + element?.code + "][value=" + element?.is_present + "]").prop('checked', true);
                    $("input[name=comment_" + element.code + "]").val(element?.remarks);
                });
            }

            function autosave(form) {
                let time;
                window.onload = resetTimer;
                // DOM Events
                document.onchange = resetTimer;
                document.onkeyup = resetTimer;

                function work() {
                    //validateFormElements(form);
                }

                function resetTimer() {
                    clearTimeout(time);
                    time = setTimeout(work, 120000);
                }
            }

            function getVehicleDefectCategory(selectedValue, selectElem) {
                if (!selectedValue) return;
                loadData(
                    'WCT',
                    document.querySelector('#defectCategoryUrl').value + '?key=' + selectedValue,
                    selectElem
                );
            }

            function getVehicleDefects(selectedValue, selectElem) {
                if (!selectedValue) return;
                loadData(
                    'WDF',
                    document.querySelector('#defectUrl').value + '?key=' + selectedValue,
                    selectElem
                );
            }

            function showSupplierControls() {
                document.querySelector('#supplierContainer').style.display = null;
                document.querySelector('[name="supplier"]').setAttribute('required', 'required');

                document.querySelector('#storeContainer').style.display = 'none';
                document.querySelector('[name="store_code"]').removeAttribute('required');
            }

            function showStockItemControls() {
                document.querySelector('#supplierContainer').style.display = 'none';
                document.querySelector('[name="supplier"]').removeAttribute('required');

                document.querySelector('#storeContainer').style.display = null;
                document.querySelector('[name="store_code"]').setAttribute('required', 'required');
            }

            function tableHasItems() {
                let inputs = $("#material_table > tbody").find('.articleCode');
                for (const input of inputs) {
                    if (input.value > "") {
                        return true;
                    }
                }
                return false;
            }

            function changeRequestType(selectedItemType) {

                if (document.querySelector('[name="stockItemCode"]').value == selectedItemType) {
                    showStockItemControls();
                    $('.quantity').attr('readonly', false);
                } else if (selectedItemType == document.querySelector('[name="serviceItemCode"]').value) {
                    showSupplierControls();
                    $('.quantity').attr('readonly', 'readonly');
                    $('.quantity').val(1);
                } else {
                    showSupplierControls();
                    $('.quantity').attr('readonly', false);
                }

                if (selectedItemType) {
                    enableArticleSelectionWebUIControls();
                }
            }

            function initEventHandlers() {

                $("#itemType").on('change', function () {
                    const selectedItemType = this.value;

                    if (tableHasItems()) {
                        Swal.fire({
                            title: 'Change Requisition Item Type',
                            text: "Changing Item Type will clear the items you've selected already." +
                                " Would you like to proceed ?",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                changeRequestType(selectedItemType);
                            }
                        });
                        return;
                    }

                    changeRequestType(selectedItemType);
                });

                $(document).on('change', 'select[name="vehicleSystem"]', function () {
                    if (!this.value) return;
                    const tr = $(this).closest('tr');
                    let selectElem = tr.find('select[name="defectCategory"]');
                    getVehicleDefectCategory(this.value, selectElem);
                });

                $(document).on('change', 'select[name="defectCategory"]', function () {
                    if (!this.value) return;
                    const tr = $(this).closest('tr');
                    let selectElem = tr.find('select[name="defect"]');
                    getVehicleDefects(this.value, selectElem);
                })

                $(document).on('keyup paste', '[name="vehicle_registration"]', function () {
                    if (!this.value || this.value.replace('_', '').length < 4) {
                        return;
                    }

                    removeSubmissionAndDetailsOptions();
                    findVehicle();
                });

                $(document).on('click', '#vehicleSearchBtn', function () {
                    if (!document.querySelector('[name="vehicle_registration"]').value) {
                        return;
                    }
                    removeSubmissionAndDetailsOptions();
                    findVehicle();
                });

                setTimeout(function () {
                    $(document).on('keyup paste', '#driver_staff_number', function () {
                        if (!this.value) {
                            return;
                        }
                        if (this.value.length < 5) {
                            return;
                        }

                        findDriver();
                    });
                }, 300);

                setTimeout(function () {
                    $(document).on('click', '#employeeSearchBtn', function () {
                        if (!document.querySelector("#driver_staff_number").value
                            || document.querySelector("#driver_staff_number").value.length < 5) {
                            toastr.warning('Invalid Employee Id Number')
                            return;
                        }

                        findDriver();
                    });
                }, 300);

                /*****************************Event Handlers*****************************************/

                $(document).on('keypress', '.number_input', function (event) {
                    tmsApp.numberOnly(event);
                });

                $(document).on('keyup', '.comments', function (event) {
                    this.value = this.value.toUpperCase();
                });

                $(document).on('keyup', '.technical_specification', function (event) {
                    this.value = this.value.toUpperCase();
                });

                $(document).on('click', '#submitRequisitionBtn', function () {
                    let $form = document.forms['fuelRequisitionForm'];
                    if (!$($form).valid()) {
                        return;
                    }

                    $('.print-error-msg').css('display', 'none');
                    let formData = new FormData($form);
                    tmsApp.confirm(
                        'Fuel Requisition',
                        'Are you sure you want to submit this request ?',
                        'Yes',
                        'No',
                        function () {
                            window.top.tmsApp.asyncPostFormData(
                                $form.action,
                                formData,
                                function (asyncResponse) {

                                    if (asyncResponse.hasOwnProperty('success') && asyncResponse['success']) {
                                        setTimeout(function () {
                                            tmsApp.showSystemMessage(
                                                'Fuel Requisition',
                                                asyncResponse['message'],
                                                function () {
                                                    window.location.href = asyncResponse["redirectUrl"]
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
                                                'Fuel Requisition',
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
                                                    'Fuel Requisition',
                                                    xhr.responseJSON['message']
                                                );
                                            }
                                            return;
                                        }

                                        tmsApp.systemError(
                                            'Fuel Requisition',
                                            'We could not complete processing your request, please try again later');
                                    }, 300)
                                }
                            )
                        }
                    );
                })

                $(document).on('change', '#repairTypeDropdownList', function () {
                    if (this.value === '001') {
                        document.querySelector("#accidentRecordNo").classList.remove('d-none');
                    } else {
                        document.querySelector("#accidentRecordNo").classList.add('d-none');
                    }
                });

                $('#material_table').on('change', 'input', function (e) {
                    eventHandler(this, e);
                }).on('keyup', 'input,textarea', function (e) {
                    eventHandler(this, e);
                });

                $(document).off('click', 'button[value="addRow"][data-table-id]')
                    .on('click',
                        'button[value="addRow"][data-table-id]',
                        function () {
                            let tableId = $(this).data('tableId');

                            if (tableId === "part8") {
                                if ($('.select_2_control').data('select2')) {
                                    $('.select_2_control').select2('destroy');
                                }
                            }

                            Table.addRow($('table#' + tableId));
                            let lastRow = $('table#' + tableId).find('tbody tr').eq((0 + 1) * -1);

                            lastRow.find('button[value="deleteRow"]').attr('data-value', 0);
                            lastRow.find('[name="technical_specification"]').attr('readonly', false);
                            lastRow.find('[name="quantity"]').attr('readonly', false);

                            function reinitializeSelect2($_defect_sel) {
                                if ($_defect_sel) {
                                    $($_defect_sel).removeClass('select2-hidden-accessible');
                                    $($_defect_sel).select2({
                                        theme: "bootstrap4",
                                        width: "resolve",
                                    });
                                }
                            }

                            if (tableId === "part8") {
                                let row = lastRow[0];
                                $(row).find('.select2-container').remove();
                                let $_defect_sel = $(".select_2_control");
                                reinitializeSelect2($_defect_sel);
                            }

                            if (tableId === "material_table") {
                                let row = lastRow[0];
                                $(row).find('.select2-container').remove();
                                $(row).find('.articlesDropDownList').removeClass('select2-hidden-accessible');

                                let article = $(row).find('input.articleCode').val();
                                console.log('Article on line', article)
                                let $_defect_sel = $(row).find(".articlesDropDownList");
                                initArticleSelector($_defect_sel);
                                //getArticleDetails(article, $_defect_sel);
                            }
                        });

                $(document).on('click', 'button[value="deleteRow"]', function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    let btnEl = $(this);
                    let tableId = $(this).closest('table').attr('id');
                    let valueId = $(this).attr('data-value');
                    let tableRow = btnEl.closest('tr');
                    let table = btnEl.closest('table');
                    tmsApp.confirm(
                        "Are you sure ?",
                        "The data entered on this line will be cleared out, if not saved already, you will not be able to recover it",
                        "Yes",
                        "No",
                        function () {
                            Table.deleteRow(tableRow);
                            e.preventDefault();
                            e.stopPropagation();
                            if (!valueId || valueId == "0") {
                                return;
                            }
                            let dataUrl = "";
                            if (tableId === 'part8') {
                                dataUrl = document.querySelector('[name="deleteDefectUrl"]').value;
                            } else {
                                dataUrl = document.querySelector('[name="deleteMaterialUrl"]').value;
                            }

                            let formData = new FormData();
                            formData.append('record_id', valueId);

                            tmsApp.asyncPostFormData(
                                dataUrl,
                                formData,
                                function (asyncResponse) {
                                    if ('success' in asyncResponse && !asyncResponse.success) {
                                        if (asyncResponse.hasOwnProperty('errors')) {
                                            toastr.error(
                                                asyncResponse.message
                                            );
                                            tmsApp.printErrorMsg(asyncResponse.errors);
                                            return
                                        }

                                        setTimeout(function () {
                                                tmsApp.systemError(
                                                    'System Configuration',
                                                    asyncResponse['message'],
                                                    function () {
                                                    }, 'error');
                                            },
                                            300);
                                        return;
                                    }

                                    if (asyncResponse.success) {
                                        const entry = asyncResponse.payload;
                                        tmsApp.showSystemMessage(
                                            'System Configuration',
                                            asyncResponse['message'],
                                            function () {
                                                //window.location.reload();
                                            },
                                            'success'
                                        );
                                    }
                                },
                                function (xhr, settings, error) {
                                    setTimeout(
                                        function () {
                                            tmsApp.showErrorMessages(xhr, 'System Configuration');
                                        },
                                        300);
                                },
                                'POST',
                            )
                        });

                    return false;
                });
            }

            function getSuppliers() {
                fetch(document.querySelector('#suppliersList').value)
                    .then(response => response.json())
                    .then(function (response) {
                        let selectElem = $('select[name="supplier"]');

                        if (response.state === 'failure') {

                            toastr.error('Failed to retrieve Supplier Records', 'Connection Error');
                            return;
                        }

                        let suppliers = response['payload'];
                        tmsApp.populateDropDownList(selectElem, suppliers,
                            "code_supplier", ["code_supplier", "name_of_supplier"],
                            " ==> ", '--Select Supplier--');

                        let supplier = selectElem.attr('data-value');
                        if (supplier) {
                            selectElem.val(supplier);
                            selectElem.trigger('change');
                        }
                    }).catch(function (error) {
                    toastr.error('Could not Retrieve Data, some feature might not work.', 'Connection error');
                });
            }

            initializeFormWizard();

            getWorkshops();

            getFuelLevels();

            loadData('VEH_SYS', document.querySelector('#systemsUrl').value + '?key=VEH_SYS', $('select[name="vehicleSystem"]'));

            initEventHandlers();

            function dataFiler() {

                $(document).find('.vehicleSystem').map(function (index, item) {
                    const value = item.getAttribute('data-value');
                    if (!value) {
                        return;
                    }
                    $(item).val(value).trigger('change')
                });
            }

            getSuppliers();

        })(window.tmsApp || {}, jQuery)
    </script>
@endpush
