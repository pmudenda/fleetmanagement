@extends('layouts.app')
@push('styles')
    <link href="{{asset("assets/plugins/select2/css/select2.min.css")}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset("assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css")}}" rel="stylesheet"
          type="text/css"/>

@endpush
@section('content')

    <x-content-header :pageTitle="'Job Card Delinking'"
                      :linkText="'Job Card'"
                      :activeCrumb="'Job Card Delinking'"/>
    <section class="content">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <h4>De-Link a Job Card</h4>
                </div>
                <div id="actionButtonsContainer" class="card-toolbar justify-content-end">
                    <button type="button" id="delinkRequisitionBtn" class="btn btn-success btn-sm mr-3 when_odo_valid"
                            disabled>
                        <i class="fas fa-thumbs-down"></i> De-Link Job Card
                    </button>
                    <button type="button" id="resetRequisitionBtn" class="btn btn-danger btn-sm mr-3">
                        <i class="fas fa-undo"></i> Cancel
                    </button>
                </div>
            </div>

            <div class="card-body pb-4 min-h-600px pt-0 pl-2">

                <x-error-view/>

                <form name="requisitionSearchForm" id="requisitionSearchForm">
                    <div class="card-body user-data pl-0">
                        @if(!empty($message))
                            <div class="error">{{ $message }}</div>
                        @endif

                        <div class="container-fluid mt-2">
                            <div class="row">
                                <div class="col-9">
                                    <!-- First Row: st_pur and proc_ref (Filter Inputs) -->
                                    <div class="row">
                                        <div class="col-12 col-sm-6 col-md-6">
                                            <div class="form-group row">
                                                <label for="st_pur"
                                                       class="col-12 col-sm-4 col-md-4 col-form-label field-required">
                                                    Supplier/PO Number:
                                                </label>
                                                <div class="col-12 col-sm-8 col-md-8">
                                                    <div class="input-group">
                                                        <input type="text"
                                                               class="form-control form-control-sm"
                                                               id="st_pur"
                                                               name="st_pur"
                                                               value="{{ request('st_pur') }}"
                                                               placeholder="e.g., C03LR1069976"
                                                               autocapitalize="characters"
                                                               data-action="{{ route('job.card.delinkPRSearch') }}">
                                                        <div class="input-group-append">
                                                            <button type="button"
                                                                    id="requisitionSearchBtn"
                                                                    class="btn btn-success btn-sm">
                                                                <i class="fas fa-search"></i>
                                                            </button>
                                                        </div>
                                                        <div class="input-group-append">
                                                            <div class="spinner-border text-success" id="loader"
                                                                 style="display: none;">
                                                                <span class="visually-hidden">Loading...</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <span class="hint">Enter Requisition Number (e.g., C03LR1069976).</span>
                                                    @error('st_pur')
                                                    <span class="error">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-sm-6 col-md-6">
                                            <div class="form-group row">
                                                <label for="job_card_no"
                                                       class="col-12 col-sm-4 col-md-4 col-form-label">
                                                    Job Card Number:
                                                </label>
                                                <div class="col-12 col-sm-8 col-md-8">
                                                    <input type="text"
                                                           class="form-control form-control-sm"
                                                           id="job_card_no"
                                                           name="job_card_no"
                                                           value="{{ $result->job_card_no ?? '' }}"
                                                           readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Second Row: Readonly Result Fields -->
                                    <div class="row">
                                        <div class="col-12 col-sm-6 col-md-6">
                                            <div class="form-group row">
                                                <label for="driver_in" class="col-12 col-sm-4 col-md-4 col-form-label">
                                                    Driver:
                                                </label>
                                                <div class="col-12 col-sm-8 col-md-8">
                                                    <input type="text"
                                                           class="form-control form-control-sm"
                                                           id="driver_in"
                                                           name="driver_in"
                                                           value="{{ $result->DRIVER_IN ?? '' }}"
                                                           readonly>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-12 col-sm-6 col-md-6">
                                            <div class="form-group row">
                                                <label for="status" class="col-12 col-sm-4 col-md-4 col-form-label">
                                                    Status:
                                                </label>
                                                <div class="col-12 col-sm-8 col-md-8">
                                                    <input type="text"
                                                           class="form-control form-control-sm"
                                                           id="status"
                                                           name="status"
                                                           value="{{ $result->status ?? '' }}"
                                                           readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Third Row: Date Created and Valid To -->
                                    <div class="row">
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
                                                           value="{{ $result->VEH_REG_NO ?? '' }}"
                                                           readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-sm-6 col-md-6">
                                            <div class="form-group row">
                                                <label for="req_no" class="col-12 col-sm-4 col-md-4 col-form-label">
                                                    Requisition Number:
                                                </label>
                                                <div class="col-12 col-sm-8 col-md-8">
                                                    <input type="text"
                                                           class="form-control form-control-sm"
                                                           id="req_no"
                                                           name="req_no"
                                                           value="{{ $result->req_no ?? '' }}"
                                                           readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Fifth Row: Date In and Date Out -->
                                    <div class="row">
                                        <div class="col-12 col-sm-6 col-md-6">
                                            <div class="form-group row">
                                                <label for="date_in" class="col-12 col-sm-4 col-md-4 col-form-label">
                                                    Date In:
                                                </label>
                                                <div class="col-12 col-sm-8 col-md-8">
                                                    <input type="text"
                                                           class="form-control form-control-sm"
                                                           id="date_in"
                                                           name="date_in"
                                                           value="{{ $result->DATE_IN ?? '' }}"
                                                           readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-sm-6 col-md-6">
                                            <div class="form-group row">
                                                <label for="date_out" class="col-12 col-sm-4 col-md-4 col-form-label">
                                                    Date Out:
                                                </label>
                                                <div class="col-12 col-sm-8 col-md-8">
                                                    <input type="text"
                                                           class="form-control form-control-sm"
                                                           id="date_out"
                                                           name="date_out"
                                                           value="{{ $result->DATE_OUT ?? '' }}"
                                                           readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
        <!-- Reject Requisition Modal -->
        {{--        <div class="modal fade" id="rejectRequisitionModal" tabindex="-1" aria-labelledby="rejectRequisitionModalLabel" aria-hidden="true">--}}
        {{--            <div class="modal-dialog modal-dialog modal-dialog-centered">--}}
        {{--                <div class="modal-content">--}}
        {{--                    <div class="modal-header">--}}
        {{--                        <h5 class="modal-title" id="rejectRequisitionModalLabel"><i class="fa fa-pencil-square-o"></i> Reject Requisition</h5>--}}
        {{--                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>--}}
        {{--                    </div>--}}
        {{--                    <div class="modal-body">--}}
        {{--                        <div>--}}
        {{--                            <span id="rejectSpanMessage" style="color: #f00;" class="errorMessage"></span>--}}
        {{--                            <label for="rejectRemarks" class="app-label">Rejection Remarks</label>--}}
        {{--                            <textarea id="rejectRemarks" name="rejectRemarks" class="form-control" cols="35" rows="4" maxlength="1000"></textarea>--}}
        {{--                        </div>--}}
        {{--                    </div>--}}
        {{--                    <div class="modal-footer justify-content-end">--}}
        {{--                        <button id="btnRejectRequisition" class="btn btn-sm btn-danger me-2">--}}
        {{--                            <i class="fas fa-save"></i> Submit--}}
        {{--                        </button>--}}
        {{--                        <button class="btn btn-sm btn-secondary" data-bs-dismiss="modal">--}}
        {{--                            <i class="fas fa-undo"></i> Cancel--}}
        {{--                        </button>--}}
        {{--                    </div>--}}
        {{--                </div>--}}
        {{--            </div>--}}
        {{--        </div>--}}

        <div class="modal fade" id="delinkRequisitionModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">De-Link Job Card</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="delinkJustification">Justification:</label>
                            <textarea class="form-control" id="delinkJustification" rows="3"
                                      placeholder="Enter reason for delinking"></textarea>
                            <span id="delinkSpanMessage" class="error"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" id="btnDelinkRequisition" class="btn btn-danger">Confirm Delink</button>
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
            // Get input values
            const stPur = document.getElementById('st_pur').value;
            const searchUrl = document.getElementById('st_pur').dataset.action; // Assumes this is set in the form
            const loader = document.getElementById('loader');

            // Basic validation: at least one field should be filled (optional, adjust as needed)
            if (!stPur) {
                Swal.fire({
                    title: 'Invalid Input',
                    text: 'Please enter a Supplier/Purchase Order Number.',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return;
            }

            // Show the loader
            loader.style.display = 'block';

            // Make AJAX request
            fetch(searchUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    st_pur: stPur,
                })
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {

                        document.getElementById('status').value = data.status || '';
                        document.getElementById('veh_reg_no').value = data.VEH_REG_NO || '';
                        document.getElementById('req_no').value = data.req_no || '';
                        document.getElementById('job_card_no').value = data.job_card_no || '';
                        document.getElementById('driver_in').value = data.DRIVER_IN || '';
                        document.getElementById('date_in').value = data.DATE_IN || '';
                        document.getElementById('date_out').value = data.DATE_OUT || '';

                        // Optional: Disable inputs Fields after successful search of details
                        document.getElementById('st_pur').readOnly = true;

                        // Define status rules
                        const activeStatuses = ['01', '04', '59'];
                        const inactiveStatuses = ['03', '08', '34'];

                        if (data.job_card_no && activeStatuses.includes(data.status_code)) {
                            document.getElementById('delinkRequisitionBtn').disabled = false;
                        } else if (!data.job_card_no || inactiveStatuses.includes(data.status_code)) {
                            document.getElementById('delinkRequisitionBtn').disabled = true;
                            Swal.fire({
                                title: 'Inactive PO',
                                text: `The PO is in Status "${data.status_code}" and cannot be processed. Contact Fleet-Master on 3306, 3315, 3350, 3309 for further assistance.`,
                                icon: 'warning',
                                confirmButtonText: 'OK'
                            });
                        } else {
                            document.getElementById('delinkRequisitionBtn').disabled = true;
                            // No message for null job_card_no or other statuses
                        }
                    } else {
                        Swal.fire({
                            title: 'Not Found',
                            text: data.message || 'No requisition found with the provided filters.',
                            icon: 'info',
                            confirmButtonText: 'OK'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching requisition details:', error);
                    Swal.fire({
                        title: 'Error',
                        text: 'An error occurred while searching. Please try again.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                })
                .finally(() => {
                    loader.style.display = 'none';
                });
        });
    </script>
    <script>
        // Delink button click event listener
        $('#delinkRequisitionBtn').on('click', function () {
            // Show the Bootstrap modal
            $('#delinkRequisitionModal').modal('show');
        });

        // Handle the delink submission inside the modal
        $('#btnDelinkRequisition').on('click', function () {
            let justification = $('#delinkJustification').val();
            if (!justification) {
                $('#delinkSpanMessage').text('Justification is required');
                return;
            }

            // Assuming st_pur is available in the form (e.g., from a previous search)
            let stPur = $('#st_pur').val();

            // Perform AJAX request to delink
            $.ajax({
                url: '{{ route("requisitions.delink") }}',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'), // CSRF token
                    st_pur: stPur,
                    justification: justification
                },
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Job Card Delinked',
                        text: response.message,
                        showConfirmButton: true,
                        timer: 3000
                    }).then(() => {
                        const stPur = document.getElementById('st_pur').value;
                        window.location.href = `{{ route('delinked.job.card.details') }}?st_pur=${encodeURIComponent(stPur)}`; // Redirect to the new page
                    });
                    $('#delinkRequisitionModal').modal('hide');
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'There was an error delinking the job card.',
                        showConfirmButton: true
                    });
                }
            });
        });
    </script>
@endpush
