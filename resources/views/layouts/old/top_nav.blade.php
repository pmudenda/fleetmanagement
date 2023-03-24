@php use Illuminate\Support\Facades\Auth; @endphp
<nav class="navbar-custom-menu navbar navbar-expand-lg m-0">
    <div class="sidebar-toggle-icon" id="sidebarCollapse">
        sidebar toggle<span></span>
    </div>

    <div class="d-flex flex-grow-1">
        <ul class="navbar-nav flex-row align-items-center ml-auto">
            <li class="nav-item dropdown user-menu">
                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                    <i class="typcn typcn-user-add-outline"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <div class="dropdown-header d-sm-none">
                        <a href="" class="header-arrow"><i class="icon ion-md-arrow-back"></i></a>
                    </div>
                    <div class="user-header">
                        <div class="img-user">
                            <img src="{{asset('assets/dist/img/avatar.png')}}" alt="">
                        </div>
                        {{Auth::user()->name}}
                    </div>
                    <a href="https://vmsdemo.bdtask-demo.com/dashboard/home/profile"
                       class="dropdown-item"><i class="typcn typcn-user-outline"></i> My Profile</a>
                    <a href="https://vmsdemo.bdtask-demo.com/dashboard/home/setting"
                       class="dropdown-item"><i class="typcn typcn-edit"></i> Edit Profile</a>
                    <a href="https://vmsdemo.bdtask-demo.com/dashboard/setting" class="dropdown-item"><i
                            class="typcn typcn-cog-outline"></i> Application Settings</a>
                    <a href="https://vmsdemo.bdtask-demo.com/dashboard/auth/logout" class="dropdown-item"><i
                            class="typcn typcn-key-outline"></i> Sign Out</a>
                </div>
            </li>
        </ul>

        <div class="nav-clock">
            <div class="time">
                <span class="time-hours"></span>
                <span class="time-min"></span>
                <span class="time-sec"></span>
            </div>
        </div>
    </div>

</nav>
