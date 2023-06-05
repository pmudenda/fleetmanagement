<div class="post">
    <div class="user-block">
        <div class="username ml-1">
            <a href="javascropt:void(0);">COMPANY</a>
        </div>
    </div>
    <!-- /. user-block -->
    <div class="row">
        <div class="col-6">
            <p class="text-muted">
                <b>Directorate:</b> {{ $user->directorate ?? '--' }}
            </p>
            {{-- <p class="text-muted">
                 <b>PayPoint:</b> {{ $user->pay_point->name ?? '' }}
             </p>--}}
            <p class="text-muted"><b>Location:</b> {{ $user->functional_section ?? '' }}</p>
            <p class="text-muted"><b>Area:</b> {{ $user->area_code ?? '' }}</p>
        </div>
        <div class="col-6">
            <p class="text-muted">
                <b>
                    User Unit:
                </b>
                @if (Auth::user()->type_id == config('constants.user_types.developer') ||
                    Auth::user()->type_id == config('constants.user_types.mgt'))
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
                {{$user->user_unit ?? '' }}
            @endif
            </p>
            <p class="text-muted"><b class="text-dark">Business Unit Code:</b> {{ $user->bu_code ?? '' }}</p>
            {{--<p class="text-muted"><b>Business Unit Code:</b> {{ $user->bu_code ?? '' }}</p>--}}
            <p class="text-muted"><b>Cost Center:</b> {{ $user->cc_code ?? '' }} </p>
        </div>
    </div>
</div>
<div class="post">
    <div class="user-block">
        <span class="username ml-1"><a href="#">POSITION AND PROFILES</a> </span>
    </div>
    <div class="row">

        <div class="col-lg-6 col-sm-12">
            <p class="text-muted">
                <strong>Contract Type:</strong>
                {{ $user->contract_type ?? '' }}
            </p>
            <p class="text-muted">
                <strong>Grade:</strong>
                {{ $user->grade ?? '' }}
            </p>
            {{--<p class="text-muted">
                <strong>Category:</strong>
                {{ $user->grade->category->name ?? '' }}
            </p>--}}
            <p class="text-muted">
                <strong>User Position:</strong>
                {{ $user->job_title ?? '' }}
            </p>
            {{-- <p class="text-muted ">
                 <strong class="text-orange ">
                     Job Code:
                 </strong>
                 {{ $user->job_code ?? '' }}
             </p>--}}
        </div>

        @if(!empty($user_acting->acting_date_from))
            <div class="col-lg-6 col-sm-12">
                <p class="text-muted">
                    <strong>Acting Period :</strong>
                    {{ Carbon\Carbon::parse($user_acting->acting_date_from ?? '0')->format('d-M-Y') ?? '' }}
                    To
                    {{ Carbon\Carbon::parse($user_acting->acting_date_to ?? '0')->format('d-M-Y') ?? ('' ?? '') }}
                </p>
                <p class="text-muted"><b>Acting Grade:</b>
                    {{ $user_acting->grade->name ?? '' }}
                </p>
                <p class="text-muted">
                    <b>Acting Category:</b>
                    {{ $user_acting->grade->category->name ?? '' }}
                </p>
                <p class="text-muted"><b>
                        Acting
                        Position:</b> {{ $user_acting->acting_position ?? '' }}
                </p>
            </div>
        @endif
    </div>
</div>

<div class="post">
    <div class="user-block">
        <span class="username ml-1"><a href="#">LINE SUPERVISOR</a> </span>
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
            {{--<p class="text-muted">
                <strong>Category:</strong>
                {{ $user->grade->category->name ?? '' }}
            </p>--}}
            {{--     <p class="text-muted">
                     <strong>User Position:</strong>
                     {{ $user->job_title ?? '' }}
                 </p>--}}
            {{-- <p class="text-muted ">
                 <strong class="text-orange ">
                     Job Code:
                 </strong>
                 {{ $user->job_code ?? '' }}
             </p>--}}
        </div>

        @if(!empty($user_acting->acting_date_from))
            <div class="col-lg-6 col-sm-12">
                <p class="text-muted">
                    <strong>Acting Period :</strong>
                    {{ Carbon\Carbon::parse($user_acting->acting_date_from ?? '0')->format('d-M-Y') ?? '' }}
                    To
                    {{ Carbon\Carbon::parse($user_acting->acting_date_to ?? '0')->format('d-M-Y') ?? ('' ?? '') }}
                </p>
                <p class="text-muted"><b>Acting Grade:</b>
                    {{ $user_acting->grade->name ?? '' }}
                </p>
                <p class="text-muted">
                    <b>Acting Category:</b>
                    {{ $user_acting->grade->category->name ?? '' }}
                </p>
                <p class="text-muted"><b>
                        Acting
                        Position:</b> {{ $user_acting->acting_position ?? '' }}
                </p>
            </div>
        @endif
    </div>
</div>

{{--@include('UserManagement/userProfileTabs/details')--}}
