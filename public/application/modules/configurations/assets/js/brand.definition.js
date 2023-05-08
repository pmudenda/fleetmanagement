(function (tmsApp, $) {

    tmsApp.appFormValidator('form[name="addRecordForm"]',
        {
            'brand_name': {
                required: 'brand name is required',
            },
            "status": {
                required: 'Status is required'
            }
        },
        {
            'brand_name': {
                required: 'brand name is required',
                maxlength: 'brand name must contain 3 to 50 characters'
            },
            "status": {
                required: 'Status is required'
            }
        },
    );

    function addRecordToTable() {
        setTimeout(function (response) {

            Vue.set(app.$data.brands, app.$data.brands.length + 1, response.payload);

            //app.destroyDataTable();
            /* setTimeout(function () {
                 app.initDatatable();
             }, 300);*/
        }, 2000);
    }

    function submitVehicleBrand(currentTarget) {
        let $form = document.forms['addRecordForm'];

        if (!$($form).valid()) {
            toastr.warning(
                "Sorry, the data did not pass validation check, check the data and try again."
            );
            return;
        }

        tmsApp.asyncPostFormData(
            $form.action,
            new FormData($form),
            function (asyncResponse) {
                if ('state' in asyncResponse && asyncResponse.state != 'success') {
                    if (asyncResponse.hasOwnProperty('errors')) {
                        tmsApp.printErrorMsg(asyncResponse.errors);
                        return
                    }

                    setTimeout(function () {
                        tmsApp.systemError(
                            'Vehicle Make Record Creation',
                            asyncResponse['message'],
                            function () {
                            }, 'error');
                    }, 300);
                    toastr.error(
                        asyncResponse.message
                    );
                    return;
                }

                tmsApp.showSystemMessage(
                    'Record Creation',
                    asyncResponse.message,
                    function () {
                        setTimeout(
                            function () {
                                //app.$data.modal.hide();
                                //window.location.href = asyncResponse['redirectUrl'];
                                //addRecordToTable();
                            }, 500
                        );
                    }, 'success');
            },
            function (xhr, settings, errorThrown) {
                console.log(errorThrown)
                setTimeout(function () {
                    tmsApp.showErrorMessages(xhr, 'Vehicle Brand');
                }, 300)
            });
    }

    $('form[name="addRecordForm"]').on('submit', function (e) {
        e.preventDefault();
        e.stopPropagation();
        submitVehicleBrand(e.currentTarget);
    });

}(window.tmsApp || {}, jQuery));

let app = new Vue({
    'el': '#app_main',
    data: {
        search: null,
        brands: [],
        table: null,
        datatable: null,
        modalEl: null,
        modal: null,
        validator: null,
        form: null,
        isEnabled: true,
        brand_name: null,
        status: null,
        statusList: [],
    },
    methods: {

        getBrands() {
            $.get(document.querySelector('#newBrandEndpoint').value)
                .done(function (response) {
                    // Populate results
                    if (response.state === 'failure') {
                        //show errors
                        toastr.error('Connection error, no data found')
                        return;
                    }

                    app.brands = response.payload;

                    app.$nextTick(function () {
                        app.initDatatable();
                    });
                })
                .fail(function (error) {
                    // notify of error
                    toastr.error(
                        'Connection error. Could not retrieve data, some feature might not work.')
                });
        },

        /*  postDeleteItem(parent, item) {
              $.post(document.querySelector('#newBrandEndpoint').value, {
                  headers: {
                      Authorization: 'Token'
                  }
                  , data: {
                      guid: item
                  }
              })
                  .then(function (response) {
                      if (response.data.state === 'failure') {
                          toastr.error(
                              'Connection error. Could not delete record');
                          return;
                      }

                      Swal.fire({
                          text: "You have deleted " + item.itemName +
                              "!."
                          , icon: "success"
                          , buttonsStyling: false
                          , confirmButtonText: "Ok, got it!"
                          , customClass: {
                              confirmButton: "btn fw-bold btn-primary"
                              ,
                          }
                      }).then(function () {
                          // Remove current row
                          app.datatable.row($(parent)).remove().draw();
                      });
                  })
                  .catch(function (error) {
                      toastr.error(
                          'Connection error. Could not retrieve data, some feature might not work.'
                      )
                  })
          },*/

        destroyDataTable() {
            this.datatable.destroy();
        },


        initDatatable: function () {
            const tableRows = this.table.querySelectorAll('tbody tr');

            tableRows.forEach(row => {
                const dateColumn = row.querySelectorAll('td');
                if (dateColumn.length < 4) {
                    return;
                }

                const realDate = moment(dateColumn[3].innerHTML, "DD MMM YYYY, LT").format();
                // select date from 4th column in table
                dateColumn[3].setAttribute('data-order', realDate);
            });

            // Disable ordering on column 0 (checkbox)
            // Disable ordering on column 6 (actions)
            this.datatable = $(this.table).DataTable({
                /*"info": false,*/
                'order': [],
                "pageLength": 10,
                "lengthChange": false,
                'columnDefs': [
                    {
                        orderable: false,
                        targets: 0
                    },
                    {
                        orderable: false,
                        targets: 4
                    }
                ]
            });

            this.datatable.on('draw', function () {
            });
        },

        add: function () {
            // Select modal buttons
            const closeButton = this.modalEl.querySelector('#kt_modal_add_close');
            const cancelButton = this.modalEl.querySelector('#kt_modal_add_cancel');
            //const submitButton = this.modalEl.querySelector('#kt_modal_add_submit');

            // Cancel button action
            cancelButton.addEventListener('click', function (e) {
                e.preventDefault();

                Swal.fire({
                    text: "Are you sure you would like to cancel?"
                    , icon: "warning"
                    , showCancelButton: true
                    , buttonsStyling: false
                    , confirmButtonText: "Yes, cancel it!"
                    , cancelButtonText: "No, return"
                    , customClass: {
                        confirmButton: "btn btn-primary"
                        , cancelButton: "btn btn-active-light"
                    }
                }).then(function (result) {
                    if (result.value) {
                        app.modal.hide();
                    } else if (result.dismiss === 'cancel') {

                    }
                });
            });
        },

        /*initDeleteButton: function () {
            KTUtil.on(this.table, '[data-kt-action="remove"]', 'click', function (e) {
                e.preventDefault();

                // Select parent row
                const parent = e.target.closest('tr');

                // Get customer name
                const itemName = parent.querySelectorAll('td')[1].innerText;
                const guid = parent.querySelector(
                    '[type="checkbox"]').value;
                Swal.fire({
                    text: "Are you sure you want to delete " + itemName +
                        "?"
                    , icon: "warning"
                    , showCancelButton: true
                    , buttonsStyling: false
                    , confirmButtonText: "Yes, delete!"
                    , cancelButtonText: "No, cancel"
                    , customClass: {
                        confirmButton: "btn fw-bold btn-danger"
                        , cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then(function (result) {
                    if (result.value) {
                        app.postDeleteItem(parent, guid);
                    } else if (result.dismiss === 'cancel') {

                    }
                });
            });
        },*/

        loadStatuses() {
            const status = [
                {"name": "Active ", "code": '01'},
                {"name": "Inactive", 'code': '02'}
            ];
            this.statusList = status.sort();
        }
    },

    filters: {
        formatToFriendlyDate(value) {
            if (!value) return value;
            return new Date(value).toDateString();
        }
    },

    created() {
        this.getBrands();
    },

    mounted() {

        this.modalEl = document.getElementById('kt_modal_add_brand');

        this.modal = new bootstrap.Modal(this.modalEl);

        this.table = document.querySelector('#kt_brands_table');

        this.add();

        this.loadStatuses();

        this.modalEl.addEventListener('hidden.bs.modal', function (event) {
            document.querySelector('#addRecordForm').reset();
        })
    }
})

