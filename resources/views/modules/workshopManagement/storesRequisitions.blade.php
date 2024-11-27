@extends('layouts.app')
@push('styles')
    <link href="{{asset("assets/plugins/select2/css/select2.min.css")}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset("assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css")}}" rel="stylesheet"
          type="text/css"/>

@endpush
@section('content')

    <x-content-header :pageTitle="'Stores Requisition Management'"
                      :linkText="'Requisitions Listing'"
                      :activeCrumb="'Stores Requisition Management'"/>
    <section class="content">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <h4>Manage a Requisition</h4>
                </div>
                <div id="actionButtonsContainer"
                     class="card-toolbar justify-content-end">
                    <button type="button"
                            id="rejectRequisitionBtn"
                            class="btn btn-success btn-sm mr-3 when_odo_valid"
                            disabled>
                        <i class="fas fa-thumbs-down"></i>
                        Reject Requisition
                    </button>
                    <button type="button"
                            id="resetRequisitionBtn"
                            class="btn btn-danger btn-sm mr-3">
                        <i class="fas fa-undo"></i> Cancel
                    </button>
                </div>
            </div>

            <div class="card-body pb-4 min-h-600px pt-0 pl-2">

                <x-error-view/>

                <form name="requisitionRejectionForm"
                      id="requisitionRejectionForm"
                      action="#"
                      method="post">
                    @csrf
                    <div class="card-body user-data pl-0">
                        <label class="app-required-marker"></label>
                        @if(!empty($message))
                            <label class="text-danger">
                                {{$message}}
                            </label>
                        @endif

                        <div class="container-fluid mt-2">
                            <div class="row">
                                <div class="col-9">
                                    <!-- First Row: Stores Requisition and Vehicle Registration -->
                                    <div class="row">
                                        <div class="col-12 col-sm-6 col-md-6">
                                            <div class="form-group row">
                                                <label for="requisitionNumber"
                                                       class="col-12 col-sm-4 col-md-4 col-form-label field-required">
                                                    Stores Requisition #:
                                                </label>
                                                <div class="col-12 col-sm-8 col-md-8">
                                                    <div class="input-group">
                                                        <input type="text"
                                                               data-action="{{ route('stores.requisition.details') }}"
                                                               class="form-control form-control-sm"
                                                               autocapitalize="characters"
                                                               id="requisitionNumber"
                                                               placeholder="Requisition # e.g J01NR1234567"
                                                               name="requisitionNumber"
                                                               required>
                                                        <div class="input-group-append">
                                                            <button type="button"
                                                                    id="requisitionSearchBtn"
                                                                    name="requisitionSearchBtn"
                                                                    class="btn btn-success btn-sm border-radius-0">
                                                                <i class="fas fa-search"></i>
                                                            </button>
                                                        </div>
                                                        <div class="input-group-append">
                                                            <div class="spinner-border text-success" role="status"
                                                                 id="loader" style="display: none; text-align: center;">
                                                                <span class="visually-hidden">Loading...</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-sm-6 col-md-6">
                                            <div class="form-group row">
                                                <label for="veh_reg_no" class="col-12 col-sm-4 col-md-4 col-form-label">
                                                    Vehicle Registration #:
                                                </label>
                                                <div class="col-12 col-sm-8 col-md-8">
                                                    <input type="text"
                                                           class="form-control form-control-sm"
                                                           id="veh_reg_no"
                                                           name="veh_reg_no"
                                                           required
                                                           readonly/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Second Row: Form Order and Status -->
                                    <div class="row">

                                        <div class="col-12 col-sm-6 col-md-6">
                                            <div class="form-group row">
                                                <label for="status_name"
                                                       class="col-12 col-sm-4 col-md-4 col-form-label">
                                                    Status:
                                                </label>
                                                <div class="col-12 col-sm-8 col-md-8">
                                                    <input type="text"
                                                           class="form-control form-control-sm"
                                                           id="status_name"
                                                           readonly
                                                           value=""
                                                           name="status_name">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-sm-6 col-md-6" id="allocationContainer">
                                            <div class="form-group row">
                                                <label for="form_order" class="col-12 col-sm-4 col-md-4 col-form-label">
                                                    Comments.:
                                                </label>
                                                <div class="col-12 col-sm-8 col-md-8">
                                                    <div class="input-group input-group-sm">
                                                        <textarea type="text" class="form-control form-control-sm"
                                                                  id="comments" name="comments" readonly></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Third Row: Comments Section -->

                                    <div class="row">


                                    </div>


                                </div>
                            </div>
                        </div>

                    </div>
                </form>

            </div>
        </div>
        <!-- Reject Requisition Modal -->
        <div class="modal fade" id="rejectRequisitionModal" tabindex="-1" aria-labelledby="rejectRequisitionModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="rejectRequisitionModalLabel"><i class="fa fa-pencil-square-o"></i> Reject Requisition</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <span id="rejectSpanMessage" style="color: #f00;" class="errorMessage"></span>
                            <label for="rejectRemarks" class="app-label">Rejection Remarks</label>
                            <textarea id="rejectRemarks" name="rejectRemarks" class="form-control" cols="35" rows="4" maxlength="1000"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-end">
                        <button id="btnRejectRequisition" class="btn btn-sm btn-danger me-2">
                            <i class="fas fa-save"></i> Submit
                        </button>
                        <button class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-undo"></i> Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('requisitionSearchBtn').addEventListener('click', function () {
            const requisitionNumber = document.getElementById('requisitionNumber').value;
            const searchUrl = document.getElementById('requisitionNumber').dataset.action;
            const loader = document.getElementById('loader');

            if (!requisitionNumber) {
                Swal.fire({
                    title: 'Invalid Input',
                    text: 'Please enter a valid requisition number.',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return;
            }

            // Show the loader
            loader.style.display = 'block';

            fetch(searchUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({requisitionNumber: requisitionNumber})
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        document.getElementById('veh_reg_no').value = data.veh_reg_no || '';
                        document.getElementById('status_name').value = data.status_name || '';
                        document.getElementById('comments').value = data.comments || '';
                        document.getElementById('requisitionNumber').readOnly = true;

                        document.getElementById('rejectRequisitionBtn').disabled = false;

                    } else {
                        Swal.fire({
                            title: 'Not Found',
                            text: data.message || 'No requisition found with the provided number.',
                            icon: 'info',
                            confirmButtonText: 'OK'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching requisition details:', error);
                    console.error('Error details:', {
                        message: error.message,
                        stack: error.stack,
                        response: error.response,
                        status: error.response ? error.response.status : 'N/A',
                        statusText: error.response ? error.response.statusText : 'N/A'
                    });
                    Swal.fire({
                        title: 'Error',
                        text: 'The requisition has already been processed!',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                })
                .finally(() => {
                    // Hide the loader
                    loader.style.display = 'none';
                });
        });
    </script>
    <script>
        // Add the button click event listener
        $('#rejectRequisitionBtn').on('click', function () {
            // Show the Bootstrap modal
            $('#rejectRequisitionModal').modal('show');
        });

        // Handle the form submission inside the modal
        $('#btnRejectRequisition').on('click', function () {
            let justification = $('#rejectRemarks').val();
            if (!justification) {
                $('#rejectSpanMessage').text('Justification is required');
                return;
            }

            let requisitionId = $('#requisitionNumber').val();

            // Perform AJAX request
            $.ajax({
                url: '{{ route('stores.requisition.reject') }}',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'), // CSRF token
                    requisitionId: requisitionId,
                    justification: justification,
                    status: 3 // Update status to 3
                },
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Requisition Rejected',
                        text: response.message,
                        showConfirmButton: true,
                        timer: 3000
                    }).then(() => {
                        window.location.href = '{{ route('home') }}';
                    });
                    $('#rejectRequisitionModal').modal('hide');
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'There was an error rejecting this requisition.',
                        showConfirmButton: true
                    });
                }
            });
        });
    </script>

@endpush
