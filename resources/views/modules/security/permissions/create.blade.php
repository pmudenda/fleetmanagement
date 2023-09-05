@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('dashboard/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet"
          href="{{ asset('dashboard/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('dashboard/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endpush


@section('content')
    <x-content-header :pageTitle="'Add System Permission'"
                      :activeCrumb="'New System Permission'"
                      :link="'home'"
                      :linkText="'Home'"/>

    <section class="content">
        <x-error-view/>
        <div class="card">
            <form name="device_from"
                  action="{{route('permissions.store')}}" method="post">
                @csrf
                <div class="card-body">
                    <div class="row">
                      <div class="col-6">
                          <div class="form-group mt-4">
                              <label for="description" class="field-required">
                                  Description:
                              </label>
                              {{--<input type="text" class="form-control" id="name" name="name" required
                                     maxlength="100"
                                     placeholder="create-status">--}}
                              <textarea type="text"
                                        style="min-height: 29px"
                                        class="form-control"
                                        id="description"
                                        name="description"
                                        required
                                        maxlength="255"
                              ></textarea>
                          </div>
                          <div class="form-group mt-4">
                              <label for="slug"
                                     class="field-required">
                                  Name:
                              </label>
                              <input type="text"
                                     class="form-control"
                                     id="name"
                                     name="name"
                                     required
                                     maxlength="100"
                                     placeholder="Enter Permission name">
                          </div>
                      </div>
                    </div>

                </div>
                <div class="card-footer">
                    <div class="row">
                        <div id="submit_button" class="col-12 text-center">
                            @can(config('rights.permission_create'))
                                <input class="btn btn-lg btn-success"
                                       type="submit"
                                       value="Submit">
                            @endcan
                            <input
                                class="btn btn-lg btn-secondary"
                                type="reset"
                                value="Clear"
                                name="reset_form">
                        </div>
                    </div>
                </div>
                <!-- /.card-footer-->
            </form>
        </div>
    </section>

@endsection


@push('scripts')
    <!-- page script -->
    <script>
        (function (appInstance) {
            appInstance.initDatatable("#example1", true);
        })(window.tmsApp || {});
    </script>

@endpush
