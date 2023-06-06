@if(!empty($user->roles))
    <div class="table-responsive mt-10 ">
        <table id="groupsTable"
               class="table table-bordered table-hover">
            <thead>
            <tr>
                <th>Description</th>
                @can(config('rights.role_detach'))
                    <th>Name</th>
                    @if(auth()->user()->id != $user->id )
                        <th>Action</th>
                    @endif
                @endcan
            </tr>
            </thead>
            <tbody>

            @foreach($user->roles as $item)
                <tr>
                    <td>
                        {{$item->description}}
                    </td>
                    @can(config('rights.role_detach'))
                        <td>
                            {{$item->name}}
                        </td>
                        @if(auth()->user()->id != $user->id )
                            <td>
                                <div class="row">
                                    <div class="col-06">

                                        <button
                                            class="btn btn-sm btn-danger m-1"
                                            data-sent_data="{{$item}}"
                                            title="Revoke Role"
                                            data-toggle="modal"
                                            data-target="#removeFromGroup{{$item->id}}">
                                            <i class="fas fa-trash"></i>
                                        </button>

                                    </div>
                                </div>
                            </td>
                        @endif
                    @endcan
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endif
 <div class="card-tools">
     @can(config('rights.role_attach'))
         <button type="button"
                 data-bs-toggle="modal"
                 data-bs-target="#addUserToGroup"
                 title="Add User To Groups"
                 class="btn btn-warning btn-sm">
             <i class="fas fa-user-lock"></i>
             Profiles
         </button>
     @endcan
 </div>

<div class="post">

    <div class="user-block">
        <span class="username ml-1"> <a href="#">PROFILES</a> </span>
    </div>

    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12">
            <form role="form-remove" method="post"
                  action="#">
                @csrf
                <div class="form-group">
                    <input hidden value="{{ $user->id }}"
                           class="form-control select2" id="owner_id"
                           name="owner_id"
                           required style="width: 100%;">
                </div>
                <div class="col-sm-12 col-lg-12">
                    <div class="form-group">
                        <label class="text-green">ACTIVE PROFILES</label>
                        <table class="table m-0">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody id="profiles">
                            {{--@foreach($user->user_profile as $item)
                                <tr>
                                    <td>
                                        <div
                                            class="icheck-warning d-inline">
                                            <input type="checkbox"
                                                   value='{"profiles": "{{$item->profile}}" ,"form":{{$item->form->id}}}'
                                                   id="remove_profiles[]"
                                                   name="remove_profiles[]">
                                        </div>
                                    </td>
                                    <td> {{$item->profiles->code}}  </td>
                                    <td>  {{$item->profiles->name}} </td>
                                    <td>  {{$item->form->name}} </td>
                                    <td>
                                        --}}{{--<a class="btn btn-link"
                                           href="{{route('main.user.profile.sync', ['user'=>$user ?? 0, 'profile'=>$item->profiles ?? 0])}}">
                                            <i class="fa fa-sync"></i>
                                            Sync
                                        </a>--}}{{--
                                    </td>
                                <tr>
                            @endforeach--}}
                            </tbody>
                        </table>
                    </div>
                    {{--@if (\App\Helpers\Authorise::hasDeveloperUserType(Auth::user()))
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="fa fa-trash-alt"></i>
                            Remove
                        </button>
                    @endif--}}
                </div>
            </form>
        </div>

        <div class="col-sm-12 col-lg-6">
            <form role="form-remove-delegate" method="post"
                  action="#">
                @csrf
                <div class="form-group">
                    <input hidden value="{{ $user->id }} : {{ $user->name }}"
                           class="form-control select2" id="owner_id"
                           name="owner_id"
                           required style="width: 100%;">
                </div>
                <div class="col-lg-12 col-md-12">
                    <div class="form-group">
                        <label class="text-green">DELEGATED PROFILES</label>
                        <table class="table m-0">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Owner</th>
                                <th>Delegating</th>
                            </tr>
                            </thead>
                            <tbody id="profiles">
                            {{--@foreach ($delegated_profiles as $item_p)
                                <tr>
                                    <td>
                                        <div class="icheck-warning d-inline">
                                            <input type="checkbox"
                                                   value='{{ $item_p->id }}'
                                                   id="delegated_profiles[]"
                                                   name="delegated_profiles[]">
                                        </div>
                                    </td>
                                    <td> {{ $item_p->form->code ?? '' }} </td>
                                    <td> {{ $item_p->profile->name ?? '' }} </td>
                                    <td> {{ $item_p->status->name ?? '' }} </td>
                                    <td> {{ $item_p->me->name ?? '' }} </td>
                                    <td> {{ $item_p->delegation->name ?? '' }}</td>
                                <tr>
                            @endforeach--}}
                            </tbody>
                        </table>
                    </div>
                    @{{--if (Auth::user()->type_id == config('constants.user_types.developer'))
                                                                <button type="submit" class="btn btn-sm btn-danger">
                                                                    <i class="fa fa-trash-alt"></i>
                                                                    Remove Delegation
                                                                </button>
                                                            @endif--}}
                </div>
            </form>
        </div>

        <div class="col-md-12 col-sm-12">
            <hr>
            <label>Delegate Profiles</label>
            <div>
                <a class="btn btn-sm bg-gradient-gray float-left "
                   style="margin: 1px"
                   title="Edit" data-toggle="modal"
                   data-target="#modal-profile-delegate">
                    Delegate
                </a>
            </div>
        </div>

    </div>

</div>
