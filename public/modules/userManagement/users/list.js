let app = new Vue({
    'el': '#kt_app_main', data() {
        return {
            users: [],
            userSelected: {},

            table: null,
            datatable: null,
            toolbarBase: null,
            toolbarSelected: null,
            selectedCount: null,
        }
    },
    filters: {
        formatTimeAgo(value) {
            if (!value) return new window.TimeAgo('en').format(new Date());
            return new window.TimeAgo('en').format(new Date(value));
        },
        nameInitialAvatar(value) {
            console.log(value)
            if (!value) return;
            let nameParts = value.toString().split(' ');
            return nameParts[0].substring(0, 1).toUpperCase() + ' ' + nameParts[1].substring(0, 1).toUpperCase();
        },
        formatToFriendlyDate(value) {
            if (!value) return;
            return window.moment(new Date(value)).format('DD MMM YYYY, h:mm a')
        }
    },
    methods: {
        getUsers: function () {
            // Hide recently viewed
            axios.get(document.querySelector('#usersEndpoint').value)
                .then(function (response) {

                    if (response.data?.payload?.state === 'failure') {
                        return;
                    }

                    // Populate results
                    app.users = response.data?.payload;

                    app.$nextTick(function () {
                        app?.initUserTable();
                        setTimeout(function () {
                            app?.handleEditRow();
                        }, 50);
                    });

                })
                .catch(function (error) {

                });
        },
        handleDeleteRows() {
            // Select all delete buttons
            const deleteButtons = this.table.querySelectorAll('[data-kt-users-table-filter="delete_row"]');

            deleteButtons.forEach(d => {
                // Delete button on click
                d.addEventListener('click', function (e) {
                    e.preventDefault();

                    // Select parent row
                    const parent = e.target.closest('tr');

                    const userName = parent.querySelectorAll('td')[1].querySelectorAll('a')[1].innerText;

                    Swal.fire({
                        text: "Are you sure you want to delete " + userName + "?",
                        icon: "warning",
                        showCancelButton: true,
                        buttonsStyling: false,
                        confirmButtonText: "Yes, delete!",
                        cancelButtonText: "No, cancel",
                        customClass: {
                            confirmButton: "btn fw-bold btn-danger",
                            cancelButton: "btn fw-bold btn-active-light-primary"
                        }
                    }).then(function (result) {
                        if (result.value) {
                            Swal.fire({
                                text: "You have deleted " + userName + "!.",
                                icon: "success",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn fw-bold btn-primary",
                                }
                            }).then(function () {
                                // Remove current row
                                app.datatable.row($(parent)).remove().draw();
                            }).then(function () {
                                // Detect checked checkboxes
                                app.toggleToolbars();
                            });
                        } else if (result.dismiss === 'cancel') {
                            /*Swal.fire({
                                text: customerName + " was not deleted.",
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn fw-bold btn-primary",
                                }
                            });*/
                        }
                    });
                })
            });
        },
        handleEditRow() {
            let editButtons = this.table.querySelectorAll('[data-tms-method="edit"]');
            editButtons.forEach(edit => {

                edit.addEventListener('click', function () {
                    let email = $(this).closest('tr').attr('data-identity');
                    let guid = $(this).closest('tr').attr('data-objectguid');
                    window.location.href = document.querySelector('[name="profileUrl"]').value + "?uuid=" + guid + "&email=" + email;
                });
            });
        },
        handleFilterDatatable() {
            // Select filter options
            const filterForm = document.querySelector('[data-kt-user-table-filter="form"]');
            const filterButton = filterForm.querySelector('[data-kt-user-table-filter="filter"]');
            const selectOptions = filterForm.querySelectorAll('select');

            // Filter datatable on submit
            filterButton.addEventListener('click', function () {
                let filterString = '';

                // Get filter values
                selectOptions.forEach((item, index) => {
                    if (item.value && item.value !== '') {
                        if (index !== 0) {
                            filterString += ' ';
                        }

                        // Build filter value options
                        filterString += item.value;
                    }
                });

                datatable.search(filterString).draw();
            });
        },
        handleResetForm() {
            // Select reset button
            const resetButton = document.querySelector('[data-kt-user-table-filter="reset"]');

            // Reset datatable
            resetButton.addEventListener('click', function () {
                // Select filter options
                const filterForm = document.querySelector('[data-kt-user-table-filter="form"]');
                const selectOptions = filterForm.querySelectorAll('select');

                // Reset select2 values -- more info: https://select2.org/programmatic-control/add-select-clear-items
                selectOptions.forEach(select => {
                    $(select).val('').trigger('change');
                });

                // Reset datatable --- official docs reference: https://datatables.net/reference/api/search()
                datatable.search('').draw();
            });
        },
        // Init toggle toolbar
        handleSearchDatatable() {
            const filterSearch = document.querySelector('[data-kt-user-table-filter="search"]');
            filterSearch.addEventListener('keyup', function (e) {
                app.datatable.search(e.target.value).draw();
            });
        },
        initToggleToolbar() {
            // Toggle selected action toolbar
            // Select all checkboxes
            const checkboxes = this.table.querySelectorAll('[type="checkbox"]');

            // Select elements
            this.toolbarBase = document.querySelector('[data-kt-user-table-toolbar="base"]');
            this.toolbarSelected = document.querySelector('[data-kt-user-table-toolbar="selected"]');
            this.selectedCount = document.querySelector('[data-kt-user-table-select="selected_count"]');
            const deleteSelected = document.querySelector('[data-kt-user-table-select="delete_selected"]');

            // Toggle delete selected toolbar
            checkboxes.forEach(c => {
                // Checkbox on click event
                c.addEventListener('click', function () {
                    setTimeout(function () {
                        app?.toggleToolbars();
                    }, 50);
                });
            });

            // Deleted selected rows
            deleteSelected.addEventListener('click', function () {
                Swal.fire({
                    text: "Are you sure you want to delete selected customers?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Yes, delete!",
                    cancelButtonText: "No, cancel",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then(function (result) {
                    if (result.value) {
                        Swal.fire({
                            text: "You have deleted all selected customers!.",
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            }
                        }).then(function () {
                            // Remove all selected customers
                            checkboxes.forEach(c => {
                                if (c.checked) {
                                    app?.datatable.row($(c.closest('tbody tr'))).remove().draw();
                                }
                            });

                            // Remove header checked box
                            const headerCheckbox = app?.table.querySelectorAll('[type="checkbox"]')[0];
                            headerCheckbox.checked = false;
                        }).then(function () {
                            app?.toggleToolbars();
                            app?.initToggleToolbar();
                        });
                    } else if (result.dismiss === 'cancel') {
                        /*Swal.fire({
                            text: "Selected customers was not deleted.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            }
                        });*/
                    }
                });
            });
        },
        initUserTable() {

            this.datatable = $(this.table).DataTable({
                "info": false,
                'order': [],
                "pageLength": 10,
                "lengthChange": false,
                'columnDefs': [
                    {'orderable': false, targets: 0}, // Disable ordering on column 0 (checkbox)
                    {'orderable': false, targets: 6}, // Disable ordering on column 6 (actions)
                ]
            });

            this.datatable.on('draw', function () {
                app?.initToggleToolbar();
                app?.handleDeleteRows();
                app?.toggleToolbars();
            });
        },
        toggleToolbars() {
            // Select refreshed checkbox DOM elements
            const allCheckboxes = this.table.querySelectorAll('tbody [type="checkbox"]');

            // Detect checkboxes state & count
            let checkedState = false;
            let count = 0;

            // Count checked boxes
            allCheckboxes.forEach(c => {
                if (c.checked) {
                    checkedState = true;
                    count++;
                }
            });

            // Toggle toolbars
            if (checkedState) {
                this.selectedCount.innerHTML = count.toString();
                this.toolbarBase.classList.add('d-none');
                this.toolbarSelected.classList.remove('d-none');
            } else {
                this.toolbarBase.classList.remove('d-none');
                this.toolbarSelected.classList.add('d-none');
            }
        }
    },

    created() {
        this.getUsers();
    },

    mounted() {
        this.table = document.getElementById('kt_table_users');
        this.initToggleToolbar();
        this.handleSearchDatatable();
        this.handleResetForm();
        this.handleDeleteRows();
        this.handleFilterDatatable();
    }
});


