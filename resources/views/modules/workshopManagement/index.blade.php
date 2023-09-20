@extends('layouts.app')


@push('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endpush


@section('content')

    <x-content-header :pageTitle="'Workshop List'" :activeCrumb="'Works'" :link="'home'"
                      :linkText="'Workshops'"/>

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
                                <h4>Manager Workshops</h4>
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
                                {{--@can(config('rights.create_workshop'))@endcan--}}
                                <a href="#"
                                   data-bs-toggle="modal"
                                   data-bs-target="#createRecordModal"
                                   class="btn btn-sm btn-success float-right">
                                    <i class="fas fa-user-plus"></i>
                                    New Workshop
                                </a>

                            </div>
                        </div>
                        <div class="card-body p-2">
                            <div class="table-responsive mt-10 ">
                                <table id="pendingJobCardTable" class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Workshop Code</th>
                                        <th>Area Code</th>
                                        <th>User Unit</th>
                                        <th>Store</th>
                                        <th>Status</th>
                                        {{--@can(config('rights.user_show'))--}}
                                        <th>Action</th>
                                        {{--@endcan--}}
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($workshopsList as $key => $workshop)
                                        <tr>
                                            <td>
                                                {{++$key}}
                                            </td>
                                            <td>
                                                {{$workshop->workshop_name}}
                                            </td>
                                            <td>
                                                {{$workshop->workshop_code}}
                                            </td>

                                            <td>
                                                {{$workshop->area_code ?? '--'}}
                                            </td>
                                            <td>
                                                {{$workshop->user_unit ?? '--'}}
                                            </td>
                                            <td>
                                                {{$workshop->store_code ?? '--'}}
                                            </td>
                                            <td>
                                                @if($workshop->status == '01')
                                                    Active
                                                @else
                                                    Inactive
                                                @endif
                                            </td>
                                            {{--@can(config('rights.user_show'))--}}
                                            <td>
                                                <div class="dropdown">
                                                    <button
                                                        class="btn btn-light btn-active-light-primary
                                                        btn-sm dropdown-toggle"
                                                        type="button"
                                                        id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        Actions
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                        {{--@can(config('rights.edit_vehicle'))--}}
                                                        <li>
                                                            <a class="dropdown-item"
                                                               data-model="{{json_encode($workshop)}}"
                                                               data-kt-action="edit"
                                                               href="#">
                                                                Edit
                                                            </a>
                                                        </li>
                                                        {{--@endcan--}}
                                                    </ul>
                                                </div>
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="createRecordModalLabel">
                        <span id="modalTitle">Add Workshop</span>
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form name="addRecordForm" method="post" action="{{route('save.data')}}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="recordId">
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="data_name" class="col-form-label">Name:</label>
                                    <input type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           name="name"
                                           id="data_name" required>
                                    @error('name')
                                    <p class=" errorText">{{$message}}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label for="data_code" class="col-form-label">Code:</label>
                                    <input type="text"
                                           class="form-control @error('code') is-invalid @enderror"
                                           id="data_code"
                                           name="code" required>
                                    @error('code')
                                    <p class=" errorText">{{$message}}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="area" class="col-form-label">Area:</label>
                                    <select type="text"
                                            class="form-select @error('code') is-invalid @enderror"
                                            id="area"
                                            name="area" required>
                                        @foreach($businessAreas as $businessArea)
                                            <option value="{{$businessArea->area}}">{{$businessArea->description}}
                                                => {{$businessArea->area}}</option>
                                        @endforeach
                                    </select>
                                    @error('code')
                                    <p class=" errorText">{{$message}}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col"></div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="message-text" class="col-form-label">Cost Center:</label>
                                    <select type="text"
                                            class="form-select @error('code') is-invalid @enderror"
                                            id="data_code"
                                            name="code" required>
                                        @foreach($costCenters as $costCenter) @endforeach
                                        <option value="{{$costCenter->code_cost_center}}">
                                            {{$costCenter->description}}=>{{$costCenter->code_cost_center}}
                                        </option>
                                    </select>
                                    @error('code')
                                    <p class=" errorText">{{$message}}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="justify-content-end">
                            <button id="closeButton" type="button"
                                    class="btn btn-sm btn-danger mr-3" data-bs-dismiss="modal">
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

    {{--Edit modal--}}
    <div class="modal fade" id="editRecordModal" tabindex="-1" aria-labelledby="editRecordModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editRecordModalLabel">
                        <span id="modalTitle">Edit Workshop</span>
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form name="configurationEditTableForm" method="post">
                    @csrf
                    @method('PUT')

                    <div class="modal-body">
                        UNDER CONSTRUCTION
                    </div>
                    <div class="modal-footer">
                        <button id="closeEditButton" type="button"
                                class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        {{--<button type="submit" class="btn btn-success">Edit</button>--}}
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')

    <script>
        (function (tmsApp) {
            let editRecordModalEl = document.querySelector('#editRecordModal')
            let addRecordModalEl = document.querySelector('#createRecordModal')

            tmsApp.initDatatable("#pendingJobCardTable", true, true, []);

            addRecordModalEl.addEventListener('hidden.bs.modal', function (event) {
                document.querySelector('[name="addRecordForm"]').reset();
            });

            $(document).on('click', '[data-kt-action="edit"]', function () {
                $('#modalTitle').text('Edit Workshop');
                addRecordModalEl.show();
                console.log(JSON.parse(this.getAttribute('data-model')));
            });

            $('input[name="name"]').on('paste keyup', function () {
                this.value = this.value.toLocaleUpperCase();
            })

        })(window.tmsApp || {});
    </script>

@endpush
