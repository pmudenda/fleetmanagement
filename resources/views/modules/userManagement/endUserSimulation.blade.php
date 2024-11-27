@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endpush


@section('content')
<section class="content">
    <x-error-view/>
    <x-content-header :pageTitle="'Stop User Simulation'"
                      :activeCrumb="'Stop User Simulation'"
                      :link="'home'"
                      :linkText="'Home'"/>
    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-4">
                <div class="col-lg-12">
                    <div class="card ">
                        <div class="card-header">
                            <div class="card-title">
                                Stop User Simulation
                            </div>
                            <div class="card-toolbar justify-content-end">

                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form action="{{route('user.simulation.end.by.admin')}}"
                                          method="POST"
                                          enctype="application/x-www-form-urlencoded"
                                          name="endUserSimulationForm"
                                          id="endUserSimulationForm">
                                        @csrf
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="userIdentifier" class="app-field-label">
                                                    Staff Number
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input type="text"
                                                       required
                                                       id="userIdentifier"
                                                       data-action="{{route('get.user')}}"
                                                       class="form-control form-control-sm"
                                                       name="userIdentifier"
                                                />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label class="app-field-label">
                                                    Name
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input type="text"
                                                       required
                                                       readonly
                                                       id="userNameIdentifier"
                                                       class="form-control form-control-sm"
                                                       name="userNameIdentifier"
                                                       list="simulationUsers"
                                                />

                                                <input type="hidden"
                                                       id="staffNumberIdentifier"
                                                       class="form-control form-control-sm"
                                                       name="staffNumberIdentifier"
                                                />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label class="app-field-label">
                                                    Justification
                                                </label>
                                                <textarea
                                                        id="simulationJustification"
                                                        style="height: 129px;"
                                                        required
                                                        minlength="20"
                                                        maxlength="255"
                                                        class="form-control comments form-control-sm"
                                                        name="simulationJustification"></textarea>
                                            </div>
                                        </div>
                                        <button type="submit"
                                                id="endSimulationBtn"
                                                name="endSimulationBtn"
                                                class="btn btn-sm btn-success">
                                            Submit
                                        </button>
                                    </form>

                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
    <!-- Add SweetAlert2 CSS and JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        (function (appInstance) {
            appInstance.initDatatable("#listTable", true, true, []);
        })(window.tmsApp || {});
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('endUserSimulationForm');
            const staffNumberInput = document.getElementById('userIdentifier');
            const justificationInput = document.getElementById('simulationJustification');
            const submitButton = document.getElementById('endSimulationBtn');

            form.addEventListener('submit', function (event) {
                event.preventDefault();

                const staffNumber = staffNumberInput.value.trim();
                const justification = justificationInput.value.trim();

                if (!staffNumber) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Staff number is required'
                    });
                    return;
                }

                if (justification.length < 20 || justification.length > 255) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Justification must be between 20 and 255 characters'
                    });
                    return;
                }

                submitButton.disabled = true;

                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: new URLSearchParams(new FormData(form))
                })
                    .then(response => response.json())
                    .then(data => {
                        submitButton.disabled = false;
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Simulation ended successfully'
                            });
                            form.reset();
                            window.location.href = "{{ route('home') }}";
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error: ' + data.message
                            });
                        }
                    })
                    .catch(error => {
                        submitButton.disabled = false;
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred while ending the simulation'
                        });
                    });
            });
        });    </script>

@endpush