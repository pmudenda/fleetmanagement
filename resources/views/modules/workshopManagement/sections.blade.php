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
                                <h4>Manager Workshop Sections</h4>
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
                                @can(config('rights.user_create'))
                                    <a href="#"
                                       class="btn btn-sm btn-success float-right">
                                        <i class="fas fa-user-plus"></i>
                                        New Workshop
                                    </a>
                                @endcan
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
                                                @if($workshop->status == '01')
                                                    Active
                                                @else
                                                    Inactive
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
                    @if($type == 'nothing')
                        <h1 class="modal-title fs-5" id="createRecordModalLabel">Add <span
                                id="modalTitle">Add Record</span></h1>
                    @else
                        <h1 class="modal-title fs-5" id="createRecordModalLabel">Add <span
                                id="modalTitle">{{$type}}</span>
                        </h1>
                    @endif

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form name="configurationTableForm" method="post" action="{{route('save.data')}}">
                    @csrf
                    @if($type != 'nothing')
                        <input type="text" value="{{$typeStr}}" name="type" style="display: none" id="data_type"/>
                    @endif

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
                                <select name="status" class="form-control @error('status') is-invalid @enderror"
                                        id="data_status"
                                        required>
                                    <option>Select Status</option>
                                </select>

                        </div>


                    </div>
                    <div class="modal-footer">
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
                    @if($type == 'nothing')
                        <h1 class="modal-title fs-5" id="editRecordModalLabel">Add <span id="modalTitle">First</span>
                        </h1>
                    @else
                        <h1 class="modal-title fs-5" id="editRecordModalLabel">Edit <span
                                id="modalTitle">{{$type}}</span>
                        </h1>
                    @endif

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form name="configurationEditTableForm" method="post">
                    @csrf
                    @method('PUT')
                    @if($type != 'nothing')
                        <input type="text" value="{{$typeStr}}" name="type" style="display: none" id="data_type"/>
                    @endif
                    <div class="modal-body">

                        <div class="mb-3">
                            <label for="recipient-name" class="col-form-label">Name:</label>
                            <input type="text" class="form-control" name="name" id="data_edit_name">
                        </div>
                        <div class="mb-3">
                            <label for="message-text" class="col-form-label">Code:</label>
                            <input type="text" class="form-control" id="data_edit_code" name="code">
                        </div>

                        <div class="mb-3 d-none">
                            <label for="message-text" class="col-form-label">Status:</label>
                            <select name="status" class="form-control" id="data_edit_status">
                                <option value="01">Active</option>
                            </select>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button id="closeEditButton" type="button"
                                class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-success">Edit</button>
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
        (function (appInstance) {
            appInstance.initDatatable("#listTable", true);
        })(window.tmsApp ||{});
    </script>

@endpush
