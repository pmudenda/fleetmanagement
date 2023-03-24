let app = new Vue({
    computed: {
        username: function () {
            return this.userSelected?.email?.split('@')[0]
        }
    }, created() {

    },
    data() {
        return {
            form: null,
            modal: null,
            users: [
                {
                    name: 'Neil Owen',
                    email: 'owen.neil@gmail.com',
                    position: 'Software Developer',
                    last_login: new Date(),
                    created_at: new Date(),
                    two_fac_auth_status: 'Enabled'
                }
            ],
            searchedUsers: [],
            validator: null,
            userSelected: {},

            // dom elements
            suggestionsElement: null,
            resultsElement: null,
            wrapperElement: null,
            emptyElement: null,
            searchObject: null
        }
    },
    'el': '#kt_app_main',
    filters: {
        nameInitialAvatar(value) {
            if (!value) return;
            return value.toString().substring(0, 1).toUpperCase();
        },
        formatTimeAgo(value) {
            if (!value) value = new TimeAgo('en').format(new Date())
            return new TimeAgo('en').format(new Date(value))
        },
        formatToFriendlyDate(value) {
            if (!value) return;

            return moment(new Date(value)).format('DD MMM YYYY, h:mm a')
        }
    },
    methods: {
        discardAddUser: () => {
            const options = {
                text: "Are you sure you would like to cancel?",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Yes, cancel it!",
                cancelButtonText: "No, return",
                customClass: {
                    confirmButton: "btn btn-primary",
                    cancelButton: "btn btn-active-light"
                }
            };
            Swal.fire(options).then(function (result) {
                if (result.value) {
                    app.form.reset(); // Reset form
                    app.userSelected = {};
                } else if (result.dismiss === 'cancel') {

                }
            });
        },
        closeAddUserModal: () => {

            Swal.fire({
                text: "Are you sure you would like to cancel?",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Yes, cancel it!",
                cancelButtonText: "No, return",
                customClass: {
                    confirmButton: "btn btn-primary",
                    cancelButton: "btn btn-active-light"
                }
            }).then(function (result) {
                if (result.value) {
                    app.form.reset(); // Reset form
                    app.modal.hide();
                } else if (result.dismiss === 'cancel') {
                    Swal.fire({
                        text: "Your form has not been cancelled!.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary",
                        }
                    });
                }
            });
        },
        addNewUser: () => {
            const element = document.getElementById('tms_modal_add_user');
            const submitButton = element.querySelector('[data-kt-users-modal-action="submit"]');
            let validator = FormValidation.formValidation(this.form, {
                fields: {
                    'user_name': {
                        validators: {
                            notEmpty: {
                                message: 'Full name is required'
                            }
                        }
                    },
                    'user_email': {
                        validators: {
                            notEmpty: {
                                message: 'Valid email address is required'
                            }
                        }
                    },
                },

                plugins: {
                    trigger: new FormValidation.plugins.Trigger({}),
                }

                /* bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row', eleInvalidClass: '', eleValidClass: ''
                    })*/
            });

            // Validate form before submit
            if (validator) {
                validator.validate().then(function (status) {
                    console.log('validated!');

                    if (status == 'Valid') {
                        // Show loading indication
                        submitButton.setAttribute('data-kt-indicator', 'on');

                        // Disable button to avoid multiple click
                        submitButton.disabled = true;

                        setTimeout(function () {
                            // Remove loading indication
                            submitButton.removeAttribute('data-kt-indicator');

                            // Enable button
                            submitButton.disabled = false;

                            // Show popup confirmation
                            Swal.fire({
                                text: "Form has been successfully submitted!",
                                icon: "success",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            }).then(function (result) {
                                if (result.isConfirmed) {
                                    app.modal.hide();
                                }
                            });

                            //form.submit(); // Submit form
                        }, 2000);
                    } else {
                        Swal.fire({
                            text: "Sorry, looks like there are some errors detected, please try again.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        });
                    }
                });
            }
        },
        processAjax: function (search) {
            // Hide recently viewed
            axios.post(document.querySelector('#userSearchEndpoint').value, {
                query: app?.searchObject.getQuery()
            })
                .then(function (response) {

                    if (response.data.payload.state === 'failure') {
                        // Hide results
                        app?.resultsElement.classList.add('d-none');
                        // Show empty message
                        app?.emptyElement.classList.remove('d-none');

                        return;
                    }

                    // Populate results
                    app.searchedUsers = response.data.payload;

                    // Hide recently viewed
                    app?.suggestionsElement.classList.add('d-none');
                    app?.resultsElement.classList.remove('d-none');
                    // Hide empty message
                    app?.emptyElement.classList.add('d-none');

                    // Complete search
                    search.complete();

                })
                .catch(function (error) {
                    // Hide results
                    app?.resultsElement.classList.add('d-none');
                    // Show empty message
                    app?.emptyElement.classList.remove('d-none');

                    // Complete search
                    search.complete();
                });
        },
        clear: function (search) {
            // Show recently viewed
            app.suggestionsElement.classList.remove('d-none');
            // Hide results
            app.resultsElement.classList.add('d-none');
            // Hide empty message
            app.emptyElement.classList.add('d-none');
        },
    },
    mounted() {
        //const modal = document.getElementById('tms_modal_add_user');
        //this.form = modal.querySelector('#tms_modal_add_user_form');
        //this.modal = new bootstrap.Modal(modal);

        // Elements
        this.element = document.querySelector('#kt_modal_user_search_handler');

        if (!this.element) {
            return;
        }

        this.wrapperElement = this.element.querySelector('[data-kt-search-element="wrapper"]');
        this.suggestionsElement = this.element.querySelector('[data-kt-search-element="suggestions"]');
        this.resultsElement = this.element.querySelector('[data-kt-search-element="results"]');
        this.emptyElement = this.element.querySelector('[data-kt-search-element="empty"]');

        // Initialize search handler
        this.searchObject = new KTSearch(this.element);

        // Ajax search handler
        this.searchObject.on('kt.search.process', this.processAjax);

        // Clear handler
        this.searchObject.on('kt.search.clear', this.clear);

        // Handle select
        KTUtil.on(this.element, '[data-kt-search-element="user"]', 'click', function () {

            let selectedUser = $(this).attr('data-user');
            console.log(selectedUser);
            let filteredResultSet = app.searchedUsers.filter(function (user) {
                return user['staff_number'] === selectedUser;
            });

            if (filteredResultSet.length === 0) {
                return;
            }

            app.userSelected = filteredResultSet[0];
            app.resultsElement.classList.add('d-none');
            app.searchedUsers = []
        });
    }
});
