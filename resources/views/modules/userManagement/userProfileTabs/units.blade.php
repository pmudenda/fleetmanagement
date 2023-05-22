<div class="post">
    <div class="row">
        <div class="col-6">
            <form role="form-units" method="post"
                  action="#">
                @csrf
                <div class="form-group">
                    <input hidden value="{{ $user->id }}"
                           class="form-control select2" id="owner_id"
                           name="owner_id"
                           required style="width: 100%;">
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <label class="text-green">ACTIVE UNITS</label>
                        <table class="table m-0">
                            {{--                                    @endif --}}
                            <thead>
                            <tr>
                                <th></th>
                                <th>Name</th>
                                <th>Code</th>
                                <th>BU</th>
                                <th>CC</th>
                            </tr>
                            </thead>
                            <tbody id="units">

                            {{--@foreach ($responsible_units as $item)
                                <tr>
                                    <td>
                                        <div class="icheck-warning d-inline">
                                            <input type="checkbox"
                                                   value='{"code": "{{ $item->user_unit_code }}" ,"id":{{ $item->id }}}'
                                                   id="transfer_units[]"
                                                   name="transfer_units[]">
                                        </div>
                                    </td>
                                    <td> {{ $item->user_unit_description }} </td>
                                    <td> {{ $item->user_unit_code }} </td>
                                    <td> {{ $item->user_unit_bc_code }} </td>
                                    <td> {{ $item->user_unit_cc_code }} </td>
                                <tr>
                            @endforeach--}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-6">
            <form role="form-assign_units" method="post"
                  action="">
                @csrf
                <div class="form-group">
                    <input hidden value="{{ $user->id }}"
                           class="form-control select2" id="owner_id"
                           name="owner_id"
                           required style="width: 100%;">
                </div>
                @if (Auth::user()->type_id == config('constants.user_types.developer'))
                    <div class="col-12">
                        <div class="form-group">
                            <label class="text-orange">ASSIGN UNITS</label>
                            <div class="col-12">
                                <input class="form-control" id="myInput"
                                       type="text" placeholder="Search..">
                            </div>
                        </div>
                        <table id="userUnits" class="table table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Code</th>
                                <th>Name</th>
                                <th>BU</th>
                                <th>CC</th>
                            </tr>
                            </thead>
                            <tbody id="myTable">
                            {{--  @foreach ($user_unit_new as $item)
                                  <tr>
                                      <td>
                                          <div class="form-group clearfix">
                                              <div class="icheck-warning d-inline">
                                                  <input type="checkbox"
                                                         value="{{ $item->id }}"
                                                         id="units[]" name="units[]">

                                              </div>
                                          </div>
                                      </td>
                                      <td><span for="accounts"> <span
                                                  class="text-gray">{{ $item->user_unit_code }}</span>
                                          </span>
                                      </td>
                                      <td><span for="accounts"> <span
                                                  class="text-gray">{{ $item->user_unit_description }}</span>
                                          </span>
                                      </td>
                                      <td><span for="accounts"> <span
                                                  class="text-gray">{{ $item->user_unit_bc_code }}</span>
                                          </span>
                                      </td>
                                      <td><span for="accounts"> <span
                                                  class="text-gray">{{ $item->user_unit_cc_code }}</span>
                                          </span>
                                      </td>
                                  </tr>
                              @endforeach--}}
                            </tbody>
                        </table>

                        <button type="submit" class="btn btn-sm btn-info">
                            Assign
                        </button>
                    </div>
                @endif
            </form>
        </div>
    </div>

</div>
