@php use Carbon\Carbon; @endphp
@extends('layouts.app')


@push('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endpush


@section('content')

    <x-content-header :pageTitle="'Driver List'"
                      :activeCrumb="'Drivers'"
                      :link="'home'"
                      :linkText="'Drivers'"/>

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
                                <h4>Driver Management</h4>
                            </div>
                            <div class="card-toolbar justify-content-end">
                                <!--begin::Filter-->
                                <button type="button" class="btn btn-sm btn-primary me-3" data-menu-trigger="click"
                                        data-menu-placement="bottom-end">
                                <span class="svg-icon svg-icon-2">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M19.0759 3H4.72777C3.95892 3
                                            3.47768 3.83148 3.86067 4.49814L8.56967
                                            12.6949C9.17923 13.7559 9.5 14.9582 9.5
                                            16.1819V19.5072C9.5 20.2189 10.2223 20.7028
                                            10.8805 20.432L13.8805 19.1977C14.2553 19.0435
                                            14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089
                                            14.8171 11.8056 15.408 10.964L19.8943
                                            4.57465C20.3596 3.912 19.8856 3
                                            19.0759 3Z"
                                            fill="currentColor"></path>
                                    </svg>
                                </span>
                                    Filter
                                </button>
                                @can(config('rights.user_create'))
                                    <a href="{{route('driver.create')}}" class="btn btn-sm btn-success float-right">
                                        <i class="fas fa-user-plus"></i>
                                        Onboard Driver
                                    </a>
                                @endcan
                            </div>
                        </div>
                        <div class="card-body p-2">
                            <div class="table-responsive mt-10 ">
                                <table id="driverList"
                                       role="table"
                                       aria-label="Driver List"
                                       class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th scope="row">#</th>
                                        <th scope="row">Image</th>
                                        <th scope="row">Driver Name</th>
                                        <th scope="row">Staff Number</th>
                                        <th scope="row">License No</th>
                                        <th scope="row">License Expiry Date</th>
                                        <th scope="row">Permit No</th>
                                        <th scope="row">Permit Expiry</th>
                                        <th scope="row">Status</th>
                                        <th scope="row"></th>
                                        {{--@can(config('rights.user_show'))--}}
                                        {{--<th>Action</th>--}}
                                        {{--@endcan--}}
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($users as $key => $user)
                                        <tr>
                                            <td>
                                                {{++$key}}
                                            </td>
                                            <td>
                                                @if(!empty($user->avatar))
                                                    <img class="profile-user-img img-fluid img-circle border-0"
                                                         width="100%"
                                                         src="{{ asset('storage/user_avatar/' . $user->avatar) }}"
                                                         alt="Image not found"
                                                         style="width: 60px; height: 54px;"
                                                    />
                                                @else
                                                    <img class="profile-user-img img-fluid img-circle border-0"
                                                         width="100%"
                                                         src="{{ asset('assets/media/avatars/avatar.png') }}"
                                                         alt="Image not found"
                                                         style="width: 60px; height: 54px;"
                                                    />
                                                @endif
                                            </td>
                                            <td>
                                                {{$user->name}}
                                            </td>
                                            <td>
                                                {{$user->staff_number ?? '--'}}
                                            </td>

                                            <td>
                                                {{$user->license_number ?? '--'}}
                                            </td>
                                            <td>
                                                {{Carbon::parse($user->license_date_expiry)->format('d/m/Y') ?? '--'}}
                                            </td>
                                            <td>
                                                {{$user->permit_number ?? '--'}}
                                            </td>
                                            <td>
                                                {{Carbon::parse($user->license_date_expiry)->format('d/m/Y') ?? '--'}}
                                            </td>
                                            <td>
                                                @if($user->status == '01')
                                                    <span class="badge badge-success">
                                                    Active
                                                </span>
                                                @else
                                                    <span class="badge badge-success">
                                                    Inactive
                                                </span>
                                                @endif
                                            </td>
                                            <td style="display: none;">
                                                <div class="dropdown">
                                                    <button class="btn btn-light btn-active-light-primary btn-sm dropdown-toggle show" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="true">
                                                        Actions
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1" data-popper-placement="bottom-start" data-popper-reference-hidden="" data-popper-escaped="" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(0px, 2px);">
                                                        <li>
                                                            <a href="{{route('driver.show',$user)}}" class="dropdown-item">
                                                                Details
                                                                <i class="fas fa-eye pull-right"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
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
    <script>
        (function (appInstance) {
            appInstance.initDatatable("#driverList", true, true, [
                {
                    "targets": [0],
                    "orderable": false
                }
            ]);
        })(window.tmsApp || {});
    </script>

@endpush
