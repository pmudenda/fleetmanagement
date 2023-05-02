@php use Illuminate\Support\Facades\Auth; @endphp
<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <a href="{{ route('home') }}" class="brand-link" style="background: var(--bs-zesco-secondary)">
        {{--  <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo"
               class="brand-image img-circle elevation-3"
               style="opacity: .8">--}}
        <img class="h-45px app-sidebar-logo-default brand-image img-circle elevation-3"
             style="opacity: .8"
             src="{{ asset('assets/dist/img/icons/zesco_logo.png') }}"
             alt="">

        {{--<img class="h-20px app-sidebar-logo-minimize"
             src="{{ asset('assets/dist/img/icons/zesco_logo.png') }}"
             alt="">--}}
        <span class="brand-text font-weight-light">Fleet Master</span>
    </a>

    <div class="sidebar">

        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            {{--<div class="image">
                <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>--}}
            <div class="info">
                <a href="#" class="d-block">{{Auth::user()->name}}</a>
            </div>
        </div>

        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                       aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">

                {{-- <li class="nav-item menu-open">
                     <a href="#" class="nav-link active">
                         <i class="nav-icon fas fa-tachometer-alt"></i>
                         <p>
                             Dashboard
                             <i class="right fas fa-angle-left"></i>
                         </p>
                     </a>
                     <ul class="nav nav-treeview">
                         <li class="nav-item">
                             <a href="index-2.html" class="nav-link active">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>Dashboard v1</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="index2.html" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>Dashboard v2</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="index3.html" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>Dashboard v3</p>
                             </a>
                         </li>
                     </ul>
                 </li>
                 <li class="nav-item">
                     <a href="pages/widgets.html" class="nav-link">
                         <i class="nav-icon fas fa-th"></i>
                         <p>
                             Widgets
                             <span class="right badge badge-danger">New</span>
                         </p>
                     </a>
                 </li>
                 <li class="nav-item">
                     <a href="#" class="nav-link">
                         <i class="nav-icon fas fa-copy"></i>
                         <p>
                             Layout Options
                             <i class="fas fa-angle-left right"></i>
                             <span class="badge badge-info right">6</span>
                         </p>
                     </a>
                     <ul class="nav nav-treeview">
                         <li class="nav-item">
                             <a href="pages/layout/top-nav.html" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>Top Navigation</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="pages/layout/top-nav-sidebar.html" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>Top Navigation + Sidebar</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="pages/layout/boxed.html" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>Boxed</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="pages/layout/fixed-sidebar.html" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>Fixed Sidebar</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="pages/layout/fixed-sidebar-custom.html" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>Fixed Sidebar <small>+ Custom Area</small></p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="pages/layout/fixed-topnav.html" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>Fixed Navbar</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="pages/layout/fixed-footer.html" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>Fixed Footer</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="pages/layout/collapsed-sidebar.html" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>Collapsed Sidebar</p>
                             </a>
                         </li>
                     </ul>
                 </li>
                 <li class="nav-item">
                     <a href="#" class="nav-link">
                         <i class="nav-icon fas fa-chart-pie"></i>
                         <p>
                             Charts
                             <i class="right fas fa-angle-left"></i>
                         </p>
                     </a>
                     <ul class="nav nav-treeview">
                         <li class="nav-item">
                             <a href="pages/charts/chartjs.html" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>ChartJS</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="pages/charts/flot.html" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>Flot</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="pages/charts/inline.html" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>Inline</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="pages/charts/uplot.html" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>uPlot</p>
                             </a>
                         </li>
                     </ul>
                 </li>--}}
                {{-- <li class="nav-item">
                     <a href="#" class="nav-link">
                         <i class="nav-icon fas fa-tree"></i>
                         <p>
                             UI Elements
                             <i class="fas fa-angle-left right"></i>
                         </p>
                     </a>
                     <ul class="nav nav-treeview">
                         <li class="nav-item">
                             <a href="pages/UI/general.html" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>General</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="pages/UI/icons.html" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>Icons</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="pages/UI/buttons.html" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>Buttons</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="pages/UI/sliders.html" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>Sliders</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="pages/UI/modals.html" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>Modals & Alerts</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="pages/UI/navbar.html" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>Navbar & Tabs</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="pages/UI/timeline.html" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>Timeline</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="pages/UI/ribbons.html" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>Ribbons</p>
                             </a>
                         </li>
                     </ul>
                 </li>--}}
                {{--   <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-edit"></i>
                        <p>
                            Forms
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                 <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="pages/forms/general.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>General Elements</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/forms/advanced.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Advanced Elements</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/forms/editors.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Editors</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/forms/validation.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Validation</p>
                            </a>
                        </li>
                    </ul>
                </li>--}}
                {{--      <li class="nav-item">
                          <a href="#" class="nav-link">
                              <i class="nav-icon fas fa-table"></i>
                              <p>
                                  Tables
                                  <i class="fas fa-angle-left right"></i>
                              </p>
                          </a>
                          <ul class="nav nav-treeview">
                              <li class="nav-item">
                                  <a href="pages/tables/simple.html" class="nav-link">
                                      <i class="far fa-circle nav-icon"></i>
                                      <p>Simple Tables</p>
                                  </a>
                              </li>
                              <li class="nav-item">
                                  <a href="pages/tables/data.html" class="nav-link">
                                      <i class="far fa-circle nav-icon"></i>
                                      <p>DataTables</p>
                                  </a>
                              </li>
                              <li class="nav-item">
                                  <a href="pages/tables/jsgrid.html" class="nav-link">
                                      <i class="far fa-circle nav-icon"></i>
                                      <p>jsGrid</p>
                                  </a>
                              </li>
                          </ul>
                      </li>--}}
                {{--         <li class="nav-header">EXAMPLES</li>
                         <li class="nav-item">
                             <a href="pages/calendar.html" class="nav-link">
                                 <i class="nav-icon far fa-calendar-alt"></i>
                                 <p>
                                     Calendar
                                     <span class="badge badge-info right">2</span>
                                 </p>
                             </a>
                         </li>--}}
                {{--     <li class="nav-item">
                         <a href="pages/gallery.html" class="nav-link">
                             <i class="nav-icon far fa-image"></i>
                             <p>
                                 Gallery
                             </p>
                         </a>
                     </li>--}}
                {{--           <li class="nav-item">
                               <a href="pages/kanban.html" class="nav-link">
                                   <i class="nav-icon fas fa-columns"></i>
                                   <p>
                                       Kanban Board
                                   </p>
                               </a>
                           </li>--}}
                {{-- <li class="nav-item">
                     <a href="#" class="nav-link">
                         <i class="nav-icon far fa-envelope"></i>
                         <p>
                             Mailbox
                             <i class="fas fa-angle-left right"></i>
                         </p>
                     </a>
                     <ul class="nav nav-treeview">
                         <li class="nav-item">
                             <a href="pages/mailbox/mailbox.html" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>Inbox</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="pages/mailbox/compose.html" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>Compose</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="pages/mailbox/read-mail.html" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>Read</p>
                             </a>
                         </li>
                     </ul>
                 </li>--}}
                {{--    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-book"></i>
                            <p>
                                Pages
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="pages/examples/invoice.html" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Invoice</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="pages/examples/profile.html" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Profile</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="pages/examples/e-commerce.html" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>E-commerce</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="pages/examples/projects.html" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Projects</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="pages/examples/project-add.html" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Project Add</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="pages/examples/project-edit.html" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Project Edit</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="pages/examples/project-detail.html" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Project Detail</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="pages/examples/contacts.html" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Contacts</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="pages/examples/faq.html" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>FAQ</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="pages/examples/contact-us.html" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Contact us</p>
                                </a>
                            </li>
                        </ul>
                    </li>--}}
                {{-- <li class="nav-item">
                     <a href="#" class="nav-link">
                         <i class="nav-icon far fa-plus-square"></i>
                         <p>
                             Extras
                             <i class="fas fa-angle-left right"></i>
                         </p>
                     </a>
                     <ul class="nav nav-treeview">
                         <li class="nav-item">
                             <a href="#" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>
                                     Login & Register v1
                                     <i class="fas fa-angle-left right"></i>
                                 </p>
                             </a>
                             <ul class="nav nav-treeview">
                                 <li class="nav-item">
                                     <a href="pages/examples/login.html" class="nav-link">
                                         <i class="far fa-circle nav-icon"></i>
                                         <p>Login v1</p>
                                     </a>
                                 </li>
                                 <li class="nav-item">
                                     <a href="pages/examples/register.html" class="nav-link">
                                         <i class="far fa-circle nav-icon"></i>
                                         <p>Register v1</p>
                                     </a>
                                 </li>
                                 <li class="nav-item">
                                     <a href="pages/examples/forgot-password.html" class="nav-link">
                                         <i class="far fa-circle nav-icon"></i>
                                         <p>Forgot Password v1</p>
                                     </a>
                                 </li>
                                 <li class="nav-item">
                                     <a href="pages/examples/recover-password.html" class="nav-link">
                                         <i class="far fa-circle nav-icon"></i>
                                         <p>Recover Password v1</p>
                                     </a>
                                 </li>
                             </ul>
                         </li>
                         <li class="nav-item">
                             <a href="#" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>
                                     Login & Register v2
                                     <i class="fas fa-angle-left right"></i>
                                 </p>
                             </a>
                             <ul class="nav nav-treeview">
                                 <li class="nav-item">
                                     <a href="pages/examples/login-v2.html" class="nav-link">
                                         <i class="far fa-circle nav-icon"></i>
                                         <p>Login v2</p>
                                     </a>
                                 </li>
                                 <li class="nav-item">
                                     <a href="pages/examples/register-v2.html" class="nav-link">
                                         <i class="far fa-circle nav-icon"></i>
                                         <p>Register v2</p>
                                     </a>
                                 </li>
                                 <li class="nav-item">
                                     <a href="pages/examples/forgot-password-v2.html" class="nav-link">
                                         <i class="far fa-circle nav-icon"></i>
                                         <p>Forgot Password v2</p>
                                     </a>
                                 </li>
                                 <li class="nav-item">
                                     <a href="pages/examples/recover-password-v2.html" class="nav-link">
                                         <i class="far fa-circle nav-icon"></i>
                                         <p>Recover Password v2</p>
                                     </a>
                                 </li>
                             </ul>
                         </li>
                         <li class="nav-item">
                             <a href="pages/examples/lockscreen.html" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>Lockscreen</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="pages/examples/legacy-user-menu.html" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>Legacy User Menu</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="pages/examples/language-menu.html" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>Language Menu</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="pages/examples/404.html" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>Error 404</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="pages/examples/500.html" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>Error 500</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="pages/examples/pace.html" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>Pace</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="pages/examples/blank.html" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>Blank Page</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="starter.html" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>Starter Page</p>
                             </a>
                         </li>
                     </ul>
                 </li>--}}
                {{--<li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-search"></i>
                        <p>
                            Search
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="pages/search/simple.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Simple Search</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/search/enhanced.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Enhanced</p>
                            </a>
                        </li>
                    </ul>
                </li>--}}
                {{--    <li class="nav-header">MISCELLANEOUS</li>
                    <li class="nav-item">
                        <a href="iframe.html" class="nav-link">
                            <i class="nav-icon fas fa-ellipsis-h"></i>
                            <p>Tabbed IFrame Plugin</p>
                        </a>
                    </li>--}}
                {{--     <li class="nav-item">
                         <a href="https://adminlte.io/docs/3.1/" class="nav-link">
                             <i class="nav-icon fas fa-file"></i>
                             <p>Documentation</p>
                         </a>
                     </li>--}}
                {{--     <li class="nav-header">MULTI LEVEL EXAMPLE</li>
                     <li class="nav-item">
                         <a href="#" class="nav-link">
                             <i class="fas fa-circle nav-icon"></i>
                             <p>Level 1</p>
                         </a>
                     </li>
                     <li class="nav-item">
                         <a href="#" class="nav-link">
                             <i class="nav-icon fas fa-circle"></i>
                             <p>
                                 Level 1
                                 <i class="right fas fa-angle-left"></i>
                             </p>
                         </a>
                         <ul class="nav nav-treeview">
                             <li class="nav-item">
                                 <a href="#" class="nav-link">
                                     <i class="far fa-circle nav-icon"></i>
                                     <p>Level 2</p>
                                 </a>
                             </li>
                             <li class="nav-item">
                                 <a href="#" class="nav-link">
                                     <i class="far fa-circle nav-icon"></i>
                                     <p>
                                         Level 2
                                         <i class="right fas fa-angle-left"></i>
                                     </p>
                                 </a>
                                 <ul class="nav nav-treeview">
                                     <li class="nav-item">
                                         <a href="#" class="nav-link">
                                             <i class="far fa-dot-circle nav-icon"></i>
                                             <p>Level 3</p>
                                         </a>
                                     </li>
                                     <li class="nav-item">
                                         <a href="#" class="nav-link">
                                             <i class="far fa-dot-circle nav-icon"></i>
                                             <p>Level 3</p>
                                         </a>
                                     </li>
                                     <li class="nav-item">
                                         <a href="#" class="nav-link">
                                             <i class="far fa-dot-circle nav-icon"></i>
                                             <p>Level 3</p>
                                         </a>
                                     </li>
                                 </ul>
                             </li>
                             <li class="nav-item">
                                 <a href="#" class="nav-link">
                                     <i class="far fa-circle nav-icon"></i>
                                     <p>Level 2</p>
                                 </a>
                             </li>
                         </ul>
                     </li>--}}
                {{--   <li class="nav-item">
                       <a href="#" class="nav-link">
                           <i class="fas fa-circle nav-icon"></i>
                           <p>Level 1</p>
                       </a>
                   </li>--}}
                {{--<li class="nav-header">LABELS</li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon far fa-circle text-danger"></i>
                        <p class="text">Important</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon far fa-circle text-warning"></i>
                        <p>Warning</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon far fa-circle text-info"></i>
                        <p>Informational</p>
                    </a>
                </li>--}}

                {{--      <li class="nav-header">MULTI LEVEL EXAMPLE</li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-circle nav-icon"></i>
                            <p>Level 1</p>
                        </a>
                    </li>--}}


                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-truck-pickup"></i>
                        <p>
                            Vehicle Management
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('new.vehicle') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>New Vehicle</p>
                            </a>
                        </li>

                        <li class="nav-item d-none">
                            <a href="{{ route('permissions.list') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Vehicle Details</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('vehicles.list') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Vehicle List</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-circle"></i>
                        <p>
                            Requisitions
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ route('new.vehicle.requisition') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Vehicle Requisition</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            {{-- {{ route('new.parts.requisition') }}--}}
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Maintenance Requisition</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Fuel Requisition
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview pl-4">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('new.fuel.requisition') }}">
                                        <i class="fas fa-plus nav-icon"></i>
                                        <p>New</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('list.fuel.requisition') }}">
                                        <i class="fas fa-list nav-icon"></i>
                                        <p>List</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>


                <li class="nav-item">
                    <a href="#" class="nav-link">
                         <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <svg width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M20 14H18V10H20C20.6 10 21 10.4 21 11V13C21 13.6 20.6 14 20 14ZM21 19V17C21 16.4 20.6 16 20 16H18V20H20C20.6 20 21 19.6 21 19ZM21 7V5C21 4.4 20.6 4 20 4H18V8H20C20.6 8 21 7.6 21 7Z"
                                        fill="currentColor"></path>
                                    <path opacity="0.3"
                                          d="M17 22H3C2.4 22 2 21.6 2 21V3C2 2.4 2.4 2 3 2H17C17.6 2 18 2.4 18 3V21C18 21.6 17.6 22 17 22ZM10 7C8.9 7 8 7.9 8 9C8 10.1 8.9 11 10 11C11.1 11 12 10.1 12 9C12 7.9 11.1 7 10 7ZM13.3 16C14 16 14.5 15.3 14.3 14.7C13.7 13.2 12 12 10.1 12C8.10001 12 6.49999 13.1 5.89999 14.7C5.59999 15.3 6.19999 16 7.39999 16H13.3Z"
                                          fill="currentColor"></path>
                                </svg>
                            </span>
                        </span>
                        {{--<i class="nav-icon bullet bullet-dot"></i>--}}
                        <p>
                            User Management
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('users.new') }}">
                                <i class="fas fa-user-plus nav-icon"></i>
                                <p class="menu-title">Add User</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('users.list') }}">
                                <i class="fas fa-users nav-icon"></i>
                                <p>
                                    Users List
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                         <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <svg width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M20 14H18V10H20C20.6 10 21 10.4 21 11V13C21 13.6 20.6 14 20 14ZM21 19V17C21 16.4 20.6 16 20 16H18V20H20C20.6 20 21 19.6 21 19ZM21 7V5C21 4.4 20.6 4 20 4H18V8H20C20.6 8 21 7.6 21 7Z"
                                        fill="currentColor"></path>
                                    <path opacity="0.3"
                                          d="M17 22H3C2.4 22 2 21.6 2 21V3C2 2.4 2.4 2 3 2H17C17.6 2 18 2.4 18 3V21C18 21.6 17.6 22 17 22ZM10 7C8.9 7 8 7.9 8 9C8 10.1 8.9 11 10 11C11.1 11 12 10.1 12 9C12 7.9 11.1 7 10 7ZM13.3 16C14 16 14.5 15.3 14.3 14.7C13.7 13.2 12 12 10.1 12C8.10001 12 6.49999 13.1 5.89999 14.7C5.59999 15.3 6.19999 16 7.39999 16H13.3Z"
                                          fill="currentColor"></path>
                                </svg>
                            </span>
                        </span>
                        <p>
                            Security
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fas fa-user-shield nav-icon"></i>
                                <p>
                                    Profiles
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview pl-4">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('roles.list') }}">
                                        <i class="fas fa-list nav-icon"></i>
                                        <p> Roles List</p>
                                    </a>
                                </li>
                              {{--  <li class="nav-item">
                                    <a class="nav-link" href="{{ route('roles.view') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">View Role</span>
                                    </a>
                                </li>--}}
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fas fa-user-secret nav-icon"></i>
                                <p>
                                    Permissions
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview pl-4">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('roles.list') }}">
                                        <i class="fa fa-list nav-icon"></i>
                                        <p class="menu-title">
                                            Permission List
                                        </p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>


                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M17.5 11H6.5C4 11 2 9 2 6.5C2 4 4 2 6.5 2H17.5C20 2 22 4 22 6.5C22 9 20 11 17.5 11ZM15 6.5C15 7.9 16.1 9 17.5 9C18.9 9 20 7.9 20 6.5C20 5.1 18.9 4 17.5 4C16.1 4 15 5.1 15 6.5Z"
                                        fill="currentColor"></path>
                                    <path opacity="0.3"
                                          d="M17.5 22H6.5C4 22 2 20 2 17.5C2 15 4 13 6.5 13H17.5C20 13 22 15 22 17.5C22 20 20 22 17.5 22ZM4 17.5C4 18.9 5.1 20 6.5 20C7.9 20 9 18.9 9 17.5C9 16.1 7.9 15 6.5 15C5.1 15 4 16.1 4 17.5Z"
                                          fill="currentColor"></path>
                                </svg>
                            </span>
                        </span>
                        {{--<i class="nav-icon bullet bullet-dot"></i>--}}
                        <p>
                            Configurations
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Vehicle
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('vehicle.make') }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot">
                                                </span>
                                            </span>
                                        <span class="menu-title">
                                                Make (Brand)
                                            </span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('vehicle.models') }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot">
                                                </span>
                                            </span>
                                        <span class="menu-title">
                                                Models
                                            </span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('vehicle.body.types') }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot">
                                                </span></span>
                                        <span class="menu-title">
                                                Body Types
                                            </span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    General Tables
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('accident.types') }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot">
                                                </span></span>
                                        <span class="menu-title">
                                                Accident Types
                                            </span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('accident.nature') }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot">
                                                </span>
                                            </span>
                                        <span class="menu-title">
                                                Accident Natures
                                            </span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('insurance.types') }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot">
                                                </span></span>
                                        <span class="menu-title">
                                                Insurance Types
                                            </span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('insurance.companies') }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot">
                                                </span>
                                            </span>
                                        <span class="menu-title">
                                                Insurance Company
                                            </span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('insurance.types') }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot">
                                                </span></span>
                                        <span class="menu-title">
                                                Statuses
                                            </span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('insurance.companies') }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot">
                                                </span>
                                            </span>
                                        <span class="menu-title">
                                                Fuel Types
                                            </span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                    </ul>
                </li>
            </ul>
        </nav>

    </div>

</aside>
