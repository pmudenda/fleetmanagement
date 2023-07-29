@php use App\Services\Security\ParameterEncryption; @endphp
<nav class="main-header navbar navbar-expand navbar-white navbar-light bg-zesco-orange">

    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{route('home')}}" class="nav-link">System Dashboard</a>
        </li>
        {{--<li class="nav-item d-none d-sm-inline-block">
            <a href="#" class="nav-link">Document</a>
        </li>--}}
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#"
               id="navbarDropdown"
               role="button"
               data-toggle="dropdown"
               aria-haspopup="true" aria-expanded="false">
                Documents Check
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item"
                   data-toggle="modal"
                   data-target="#modal-auditTrail"
                   href="#">
                    Document Audit Trail
                </a>
                <a class="dropdown-item"
                   data-toggle="modal"
                   data-target="#modal-followUp"
                   href="#">
                    Document Follow Up
                </a>

                <div class="dropdown-divider"></div>
                <a class="dropdown-item"
                   data-toggle="modal"
                   data-target="#modal-taskFollowUp"
                   href="#">
                    Task Tracking
                </a>
            </div>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto">

        <li class="nav-item" style="display: none;">
            <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                <i class="fas fa-search"></i>
            </a>
            <div class="navbar-search-block">
                <form class="form-inline">
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control-navbar" type="search" placeholder="Search"
                               aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-navbar" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                            <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>

        {{-- <li class="nav-item dropdown">
             <a class="nav-link" data-toggle="dropdown" href="#">
                 <i class="far fa-comments"></i>
                 <span class="badge badge-danger navbar-badge">3</span>
             </a>
             <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                 <a href="#" class="dropdown-item">

                     <div class="media">
                         <div class="media-body">
                             <h3 class="dropdown-item-title">
                                 Brad Diesel
                                 <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                             </h3>
                             <p class="text-sm">Call me whenever you can...</p>
                             <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                         </div>
                     </div>

                 </a>
                 <div class="dropdown-divider"></div>
                 <a href="#" class="dropdown-item">

                     <div class="media">

                         <div class="media-body">
                             <h3 class="dropdown-item-title">
                                 John Pierce
                                 <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                             </h3>
                             <p class="text-sm">I got your message bro</p>
                             <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                         </div>
                     </div>

                 </a>
                 <div class="dropdown-divider"></div>
                 <a href="#" class="dropdown-item">

                     <div class="media">
                         <div class="media-body">
                             <h3 class="dropdown-item-title">
                                 Nora Silvester
                                 <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                             </h3>
                             <p class="text-sm">The subject goes here</p>
                             <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                         </div>
                     </div>

                 </a>
                 <div class="dropdown-divider"></div>
                 <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
             </div>
         </li>--}}

         <li class="nav-item dropdown">
             <a class="nav-link" data-toggle="dropdown" href="#">
                 <i class="far fa-bell" style="font-size: 1.5rem;"></i>
                 <span class="badge badge-warning navbar-badge">0</span>
             </a>
             <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                 <span class="dropdown-item dropdown-header">15 Notifications</span>
                 <div class="dropdown-divider"></div>
                 <a href="#" class="dropdown-item">
                     <i class="fas fa-envelope mr-2"></i> 4 new messages
                     <span class="float-right text-muted text-sm">3 mins</span>
                 </a>
                 <div class="dropdown-divider"></div>
                 <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
             </div>
         </li>
      {{--   <li class="nav-item">
             <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                 <i class="fas fa-expand-arrows-alt"></i>
             </a>
         </li>
         <li class="nav-item">
             <a class="nav-link" data-widget="control-sidebar" data-controlsidebar-slide="true" href="#"
                role="button">
                 <i class="fas fa-th-large"></i>
             </a>
         </li>--}}

        <li class="nav-item dropdown ">
            <div class="user-panel mt-1 pb-1 d-flex">
                <div class="image">
                    @if(isset(Auth::user()->avatar) && !empty(Auth::user()->avatar))
                        <img src="{{asset('storage/user_avatar/'.Auth::user()->avatar)}}" class="img-circle elevation-2"
                             alt="User Image">
                    @else
                        <img src="{{asset('assets/dist/img/avatar.png')}}" class="img-circle elevation-2"
                             alt="User Image">
                    @endif

                </div>
            </div>
        </li>
        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown ">
            <a class="nav-link" data-toggle="dropdown" href="#">
                {{Auth::user()->name}}</a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <a href="{{URL::signedRoute('profile',['key'=> ParameterEncryption::encrypt( Auth::user()->id) ]) }}"
                   class="dropdown-item">
                    <i class="fas fa-user-circle mr-2"></i>
                    My Profile
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{ route('logout') }}"
                   class="dropdown-item"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </li>
    </ul>
</nav>
