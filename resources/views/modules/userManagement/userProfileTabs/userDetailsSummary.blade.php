@php use App\Models\Reference\Area; @endphp
<div class="post">
    <div class="user-block">
        <div class="username ml-1">
            <a href="javascript:void(0);">COMPANY</a>
        </div>
    </div>
    <!-- /. user-block -->
    <div class="row">
        <div class="col-6">
            <p class="text-muted">
                <b>Directorate:</b> {{ $user->directorate ?? '--' }}
            </p>
            <p class="text-muted"><b>Location:</b> {{ $user->functional_section ?? '' }}</p>
            <p class="text-muted"><b>Area:</b>
                @foreach(Area::get() as $area)
                    @if($area->area == $user->area_code)
                        <b data-value="{{$area->area}}">
                            {{$area->description}}
                        </b>
                    @endif
                @endforeach
            </p>
        </div>
        <div class="col-6">
            <p class="text-muted">
                <b>
                    User Unit:
                </b>
                {{$user->user_unit ?? '' }}
                {{--@if (Auth::user()->type_id == config('constants.user_types.developer')
                    || Auth::user()->type_id == config('constants.user_types.mgt'))
                    <a href="{{ route('logout') }}" class="text-dark"
                       onclick="event.preventDefault(); document.getElementById('search-form12').submit();">
                        {{ $user->user_unit ?? ''}}
                    </a>
            <form id="search-form12"
                  action="#"
                  method="post" class="d-none">
                @csrf
            </form>
            @else
            @endif--}}
            </p>
            <p class="text-muted">
                <b class="text-dark">
                    Business Unit Code:
                </b>
                {{ $user->bu_code ?? '' }}
            </p>
            <p class="text-muted">
                <b>Cost Center:</b>
                {{ $user->cc_code ?? '' }}
            </p>
        </div>
    </div>
</div>
<div class="post">

    <div class="row">

        <div class="col-lg-6 col-sm-12">
            <div class="user-block">
                <span class="username ml-1"><a href="#">POSITION AND PROFILES</a> </span>
            </div>
            <p class="text-muted">
                <strong>Contract Type:</strong>
                {{ $user->contract_type ?? '' }}
            </p>
            <p class="text-muted">
                <strong>Grade:</strong>
                {{ $user->grade ?? '' }}
            </p>
            <p class="text-muted">
                <strong>User Position:</strong>
                {{ $user->job_title ?? '' }}
            </p>
            <p class="text-muted">
                <strong>System Profile:</strong>
                @foreach ($roles as $groupName)
                    @if(!empty($user->roles()->first()))
                        @if($groupName->id == $user->roles()->first()->id)
                            {{strtoupper($groupName->description)}}
                        @endif
                    @endif

                @endforeach
            </p>
        </div>

        @if(!empty($userDelegating) && Carbon\Carbon::now()->isBefore($userDelegating->period_to))
            <div class="col-lg-6 col-sm-12">
                <div class="user-block">
                    <span class="username ml-1"><a href="#">DELEGATED PROFILE</a> </span>
                </div>
                <p class="text-muted">
                    <strong>Delegation Period :</strong>
                    {{ Carbon\Carbon::parse($userDelegating->period_from ?? '0')->format('d-M-Y') ?? '' }}
                    To
                    {{ Carbon\Carbon::parse($userDelegating->period_to ?? '0')->format('d-M-Y') ??  '' }}
                </p>
                <p class="text-muted">
                    <b>Delegation Profile:</b>
                    {{ $userDelegating->delegatedRole()->name ?? '' }}
                </p>

                <p class="text-muted">
                    <b>Profile Owner:</b>
                    {{ $userDelegating->profileOwner()->name ?? '' }}
                </p>
            </div>
        @endif
    </div>
</div>

<div class="post">
    <div class="user-block">
        <span class="username ml-1"><a href="#">LINE SUPERVISOR</a></span>
    </div>
    <div class="row">

        <div class="col-lg-6 col-sm-12">
            <p class="text-muted">
                <strong>Name:</strong>
                {{ $user->supervisor_name ?? '' }}
            </p>
            <p class="text-muted">
                <strong>Staff No.:</strong>
                {{ $user->supervisor_code ?? '' }}
            </p>
        </div>
    </div>
</div>

{{--@include('modules/userManagement/userProfileTabs/profiles')--}}
