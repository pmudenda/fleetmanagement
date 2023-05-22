@extends('layouts.app')


@push('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endpush


@section('content')

    <x-content-header :pageTitle="'Workshop Sections'" :activeCrumb="'Workshop Sections'" :link="'home'"
                      :linkText="'Sections'"/>

    <!-- Main content -->
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
                                <h4>Workshop Sections</h4>
                            </div>
                            <div class="card-toolbar justify-content-end">
                                <!--begin::Filter-->
                                <button type="button" class="btn btn-sm btn-primary me-3" data-menu-trigger="click"
                                        data-menu-placement="bottom-end">
                                    <span class="svg-icon svg-icon-2">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z"
                                                fill="currentColor"></path>
                                        </svg>
                                    </span>
                                    Filter
                                </button>
                                {{--@can(config('rights.add_work_shop_section'))--}}
                                <a href="#"
                                   data-bs-target="#createRecordModal"
                                   data-bs-toggle="modal"
                                   class="btn btn-sm btn-success float-right">
                                    <i class="fas fa-user-plus"></i>
                                    New Section
                                </a>
                                {{--@endcan--}}
                            </div>
                        </div>
                        <div class="card-body p-2">
                            <div class="table-responsive mt-10 ">
                                <table id="listTable" class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Section Code</th>
                                        <th>Status</th>
                                        {{--@can(config('rights.user_show'))--}}
                                        <th>Action</th>
                                        {{--@endcan--}}
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($workshop_sections as $key => $workshop)
                                        <tr>
                                            <td>
                                                {{++$key}}
                                            </td>
                                            <td>
                                                {{$workshop->name}}
                                            </td>
                                            <td>
                                                {{$workshop->code}}
                                            </td>

                                            <td>
                                                @if($workshop->active == '1')
                                                    <span class="badge badge-success p2">
                                                        Active
                                                    </span>
                                                @else
                                                    <span class="badge badge-danger p2">
                                                    Inactive
                                                    </span>
                                                @endif
                                            </td>
                                            {{--@can(config('rights.user_show'))--}}
                                            <td>
                                                <a href="#"
                                                   class="btn btn-sm btn-success m-1">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                            </td>
                                            {{-- @endcan--}}
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </section>

    <div class="modal fade" id="createRecordModal" tabindex="-1" aria-labelledby="createRecordModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="createRecordModalLabel">
                        <span id="modalTitle">Add Workshop Section Record</span>
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form name="addRecordForm" method="post" action="{{route('save.data')}}">
                    @csrf

                    <input type="hidden" value="{{$typeStr}}" name="type" style="display: none" id="data_type"/>
                    <input type="hidden" value="" name="recordId"/>

                    <div class="modal-body">

                        <div class="mb-3">
                            <label for="recipient-name" class="col-form-label">Name:</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                                   id="data_name" required>

                            @error('name')
                            <p class=" errorText">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="message-text" class="col-form-label">Code:</label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" id="data_code"
                                   name="code" required>
                            @error('code')
                            <p class=" errorText">{{$message}}</p>
                            @enderror
                        </div>

                        <div class="mb-3 d-none">
                            <label for="message-text" class="col-form-label">Status:</label>
                            <input name="status" class="form-control @error('status') is-invalid @enderror"
                                   value="1"
                                   id="data_status"
                                   required/>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="justify-content-end">
                            <button id="closeButton" type="button"
                                    class="btn btn-sm btn-danger" data-bs-dismiss="modal">
                                <i class="fas fa-times"></i>
                                Close
                            </button>
                            <button type="submit" class="btn btn-sm btn-success">
                                <i class="fas fa-save"></i>
                                Save
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editRecordModal" tabindex="-1" aria-labelledby="createRecordModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="createRecordModalLabel">
                        <span id="modalTitle">Add Workshop Section Record</span>
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form name="addRecordForm" method="post" action="{{route('save.data')}}">
                    @csrf

                    <input type="hidden" value="{{$typeStr}}" name="type" style="display: none" id="data_type"/>
                    <input type="hidden" value="" name="recordId"/>

                    <div class="modal-body">

                        <div class="mb-3">
                            <label for="recipient-name" class="col-form-label">Name:</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                                   id="data_name" required>

                            @error('name')
                            <p class=" errorText">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="message-text" class="col-form-label">Code:</label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" id="data_code"
                                   name="code" required>
                            @error('code')
                            <p class=" errorText">{{$message}}</p>
                            @enderror
                        </div>

                        <div class="mb-3 d-none">
                            <label for="message-text" class="col-form-label">Status:</label>
                            <input name="status" class="form-control @error('status') is-invalid @enderror"
                                   value="1"
                                   id="data_status"
                                   required/>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="justify-content-end">
                            <button id="closeButton" type="button"
                                    class="btn btn-sm btn-danger" data-bs-dismiss="modal">
                                <i class="fas fa-times"></i>
                                Close
                            </button>
                            <button type="submit" class="btn btn-sm btn-success">
                                <i class="fas fa-save"></i>
                                Save
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')

    <!-- DataTables  & Plugins -->
    @include('layouts.partials.dataTableScripts')
    <!-- page script -->
    <script>
        (function (tmsApp, $) {
            let editRecordModalEl = document.querySelector('#editRecordModal')
            let addRecordModalEl = document.querySelector('#createRecordModal')

            tmsApp.initDatatable("#listTable", true);

            let editModal = bootstrap.Modal.getOrCreateInstance(editRecordModalEl, {
                 'backdrop': true,
                 'keyboard': false
             });

            let createRecordModal = bootstrap.Modal.getOrCreateInstance(addRecordModalEl, {
                'backdrop': true,
                'keyboard': false
            });

            addRecordModalEl.addEventListener('hidden.bs.modal', function (event) {
                document.querySelector('[name="addRecordForm"]').reset();
            });

            $(document).on('click', '[data-kt-action="edit"]', function () {
                $('#modalTitle').text('Edit Workshop');
                //editModal.show();
                console.log(JSON.parse(this.getAttribute('data-model')));
            });

            $('input[name="name"]').on('paste keyup', function () {
                this.value = this.value.toLocaleUpperCase();
            });

            $('input[name="code"]').on('paste keyup', function () {
                this.value = this.value.toLocaleUpperCase();
            });

            $('form[name="addRecordForm"]').on('submit', function (e) {
                e.preventDefault();
                e.stopPropagation();
                postRecordCreation();
            });

            tmsApp.appFormValidator('form[name="addRecordForm"]',
                {
                    'name': {
                        required: 'Section name is required',
                        maxlength: 50
                    }
                },
                {
                    'code': {
                        required: 'Section code is required',
                        maxlength: 'Brand can not be more than 50 characters'
                    }
                },
            );

            function postRecordCreation() {

                let $form = document.querySelector('form[name="addRecordForm"]')

                if (!$($form).valid()) {
                    toastr.warning(
                        "Sorry, the data did not pass validation check, Brand name is required, check the data and try again.",
                        "Validation Failure"
                    );
                    return;
                }
                tmsApp.confirm('Workshop Section',
                    'Are you sure you want to submit the request ?',
                    'Yes',
                    'No, Cancel',
                    function () {
                        setTimeout(function () {
                            tmsApp.asyncPostFormData(
                                $form.action,
                                new FormData($form),
                                function (asyncResponse) {
                                    if (asyncResponse.hasOwnProperty('success') && !asyncResponse.success) {
                                        if (asyncResponse.hasOwnProperty('errors')) {
                                            tmsApp.printErrorMsg(asyncResponse.errors);
                                            return
                                        }

                                        setTimeout(function () {
                                            tmsApp.systemError(
                                                'Section Record Creation',
                                                asyncResponse['message'],
                                                function () {
                                                }, 'error');
                                        }, 300);
                                        toastr.error(
                                            asyncResponse.message
                                        );
                                        return;
                                    }
                                    createRecordModal.hide();
                                    let message = 'Record Created Successfully';
                                    tmsApp.showSystemMessage(
                                        'Record Creation',
                                        message,
                                        function () {
                                            setTimeout(
                                                function () {
                                                    window.location.reload();
                                                }, 500
                                            );
                                        }, 'success');
                                },
                                function (xhr, settings, errorThrown) {
                                    console.log(errorThrown)
                                    setTimeout(function () {
                                        tmsApp.showErrorMessages(xhr, 'Record Creation');
                                    }, 300)
                                }
                            );
                        }, 300)

                    }, function () {
                    });
            }

        })(window.tmsApp || {}, jQuery);
    </script>

@endpush
