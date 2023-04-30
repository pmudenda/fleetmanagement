@extends('layouts.app')


@push('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endpush


@section('content')

    <x-content-header :pageTitle="'System Users List'" :activeCrumb="'User Details'" :link="'home'"
                      :linkText="'System Users'"/>

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
                            @can(config('rights.user_create'))
                                <a href="{{route('user.create')}}"
                                   class="btn btn-sm bg-success float-right">
                                    <i class="fas fa-user-plus"></i>
                                    Create User
                                </a>
                            @endcan
                        </div>
                        <div class="card-body p-2">
                            <div class="table-responsive mt-10 ">
                                <table id="listTable" class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email Address</th>
                                        <th>Staff Number</th>
                                        <th>JobTitle</th>
                                        @can(config('rights.user_show'))
                                            <th>Action</th>
                                        @endcan
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>
                                                {{$user->id}}
                                            </td>
                                            <td>
                                                {{$user->name}}
                                            </td>
                                            <td>
                                                {{$user->email}}
                                            </td>
                                            <td>
                                                {{$user->staff_no ?? '--'}}
                                            </td>
                                            <td>
                                                {{$user->job_title ?? '--'}}
                                            </td>
                                            @can(config('rights.user_show'))
                                                <td>
                                                    <a href="{{route('user.show', $user->id)}}"
                                                       class="btn btn-sm btn-success m-1">
                                                        <i class="fas fa-eye">Details</i>
                                                    </a>
                                                </td>
                                            @endcan
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

@endsection

@push('scripts')

    <!-- DataTables  & Plugins -->
    @include('layouts.partials.dataTableScripts')
    <!-- page script -->
    <script>
        $(function () {
            tmsApp.initDatatable("#listTable", true);
        });
    </script>

@endpush
