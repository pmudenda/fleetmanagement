<div class="app-navbar-item ms-1 ms-md-3" id="kt_header_user_menu_toggle">

    <div class="cursor-pointer symbol symbol-30px symbol-md-40px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
         data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
        @if (!empty(Auth::user()->avatar))
            <img src="{{asset('storage/user_avatar/' . Auth::user()->avatar)}} "
                class="img-circle elevation-2" alt="User Profile Image">
        @else
            <img src="{{ asset('assets/media/avatars/profile.png') }}"
                class="img-circle elevation-2" alt="User Profile Image">
        @endif
    </div>

    <div
        class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px"
        data-kt-menu="true">

        <div class="menu-item px-3">
            <div class="menu-content d-flex align-items-center px-3">

                <div class="symbol symbol-50px me-5">
                    @if (!empty(Auth::user()->avatar))
                        <img src="{{asset('storage/user_avatar/' . Auth::user()->avatar)}} "
                             class="img-circle elevation-2" alt="User Profile Image">
                    @else
                        <img src="{{ asset('assets/media/avatars/profile.png') }}"
                             class="img-circle elevation-2" alt="User Profile Image">
                    @endif
                </div>

                <div class="d-flex flex-column">
                    <div class="fw-bold d-flex align-items-center fs-5">
                        {{ Auth::user()->name ?? 'Default' }}
                    </div>

                    <a href="#" class="fw-semibold text-muted text-hover-primary fs-7">
                        {{ Auth::user()->email ?? 'tms@zesco.co.zm' }}
                    </a>
                </div>

            </div>
        </div>

        <div class="separator my-2"></div>

        <div class="menu-item px-5">
            <a href="{{route('profile')}}" class="menu-link px-5">
                My Profile
            </a>
        </div>

        <div class="separator my-2"></div>

        <div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
            data-kt-menu-placement="left-start" data-kt-menu-offset="-15px, 0">
            <a href="#" class="menu-link px-5">
                <span class="menu-title position-relative">
                    Language

                    <span class="fs-8 rounded bg-light px-3 py-2 position-absolute translate-middle-y top-50 end-0">
                        English
                    </span>
                </span>
            </a>
        </div>

        <div class="menu-item px-5 my-1">
            <a href="{{ route('settings') }}" class="menu-link px-5">
                Account Settings
            </a>
        </div>

        <div class="menu-item px-5">

            @include('layouts.widgets.logout')
        </div>

    </div>
</div>
