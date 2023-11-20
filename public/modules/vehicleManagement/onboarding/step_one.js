Vue.component('v-select', VueSelect.VueSelect);
window.removeSpaces = function (value) {
    if (!value) return;
    return value.replace(/\s/g, '');
}

$(document).ready(function () {
    Inputmask({
        "mask": "AAA 9999"
    }).mask("#registrationNumber");
});

let app = new Vue({
    'el': '#kt_app_main',
    components: {},
    data() {
        return {
            vehicle_brand_placeholder: 'Select Vehicle Brand',
            vehicle_model_placeholder: 'Select Model',
            dataStatus: 0,
            vehicleHeaderId: null,
            isHeaderSaved: false,
            vehicleBrands: [],
            engineBrands: [],
            organizationalUnits: [],
            businessUnits: [],
            directorates: [],
            costCenters: [],
            businessAreas: [],
            fuelTypes: [],
            licenseTypes: [],
            registrationTypes: [],
            configuredModels: [],
            selectedBrandModels: [],
            bodyTypes: [],
            chassisDetails: {
                stickerRegistrationNumber: null,
                status: 'active'
            },
            transmissionTypes: [
                {
                    'name': 'Automatic',
                    'code': 'AT'
                },
                {
                    'name': 'Manual',
                    'code': 'MT'
                }
            ],
            vehicleHeader: {model: {}},
            engineDetails: {},
            otherDetails: {},
            costingAndValuation: {},
            bodyDetails: {
                numberOfSeats: 0,
                volumeOfBootTanker:
                    0,
                seatCapRear:
                    0
            },
            weightDetails: {
                trailerWeight2: 0
            },
            assignmentDetails: {},
            validators: [],
            selectedModelCodes: [],
            searchedEmployeesList: [],

            // forms
            vehicleHeaderForm: null,
            chassisDetailsForm: null,
            engineDetailsForm: null,
            costingDetailsForm: null,
            bodyDetailsForm: null,
            assignmentDetailsForm: null,
            // validators
            vehicleHeaderFormValidator: null,
            chassisDetailsFormValidator: null,
            engineDetailsFormValidator: null,
            document_validity: {
                state: null,
                message: null
            },
            regNumberValidity: {
                state: null,
                message: null
            }
        }
    },

    created() {
        //this.getVehicleBrands();
        //this.getConfiguredModels();
        //this.getBodyTypes();
        //this.getOrganizationalUnits();
        //this.loadRegistrationTypes()
        this.licenseTypes = [
            {'code': 'A', 'name': 'Class A'},
            {'code': 'B', 'name': 'Class B'},
            {'code': 'C', 'name': 'Class C'},
            {'code': 'E', 'name': 'Class E'},
        ];
    },

    mounted() {
        console.log("%c✔ Vehicle OnBoarding Running", "color: #148f32");
        //this.vehicleHeaderForm = document.querySelector('#tms_vehicle_header_form');

        //this.initDropzone();
        /*if (this.vehicleHeader && this.vehicleHeader.id) {
            this.isHeaderSaved = true;
        }*/

        /*$(document).on('click', '[data-select="file"]', function () {
            let fileInput = $(this).closest('p').find('input[type="file"]');
            $(fileInput).trigger('click');
        });

        let fileSelects = [].slice.call(document.querySelectorAll('.fileElem'));
        fileSelects.map(function (fileSelect) {
            fileSelect.addEventListener(
                "change",
                (e) => {
                    app.preview(e);
                },
                false
            );
        });

        $(document).on('click', '.clearImage', function (event) {
            let btn = this;
            Swal.fire({
                text: "Are you sure you would like to remove the image?"
                , icon: "warning"
                , showCancelButton: true
                , buttonsStyling: false
                , confirmButtonText: "Yes, remove it!"
                , cancelButtonText: "No, return"
                , customClass: {
                    confirmButton: "btn btn-primary"
                    , cancelButton: "btn btn-active-light"
                }
            }).then(function (result) {
                if (result.value) {
                    $(btn).parent().css({
                        "background-image": "",
                        'display': 'none'
                    });
                    // find the upload btn and make visible
                    $(btn).parent().parent().find('p').removeClass('d-none');
                }
            });

        });

        $(document).on('change', '[data-emp="staff_number"]', function (e) {
                let input = e.target;
                let value = input.value;

                let names = app['searchedEmployeesList'].filter(function (user) {
                    return user['staff_number'] === value;
                });

                if (names.length === 0) return;

                $(input).closest('tr').find('input[data-emp="name"]').val(names[0].name)

            }
        )*/
    },

    methods: {
    }
});

(function (tmsApp, $) {

    function getLocations() {
        fetch(document.querySelector('#locationUrl').value)
            .then(response => response.json())
            .then(response => {
                let selectElem = $('select[name="vehicleLocation"]');
                // Populate results
                if (response.state === 'failure') {
                    //show errors
                    toastr.error('Connection error, no data found')
                    return;
                }

                let locations = response['payload'];
                tmsApp.populateDropDownList(selectElem, locations, "location", ["location"], "")
            })
            .catch(function (error) {
                // notify of error
                toastr.error(
                    'Connection error. Could not retrieve data, some feature might not work.')
            });
    }

    new tmsApp.fileUploader().makeSingleFileUploader();

})(window.tmsApp || {}, jQuery);
