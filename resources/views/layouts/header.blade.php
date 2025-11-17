 <!-- Navigation Bar-->
        <header id="topnav">
            <div class="topbar-main">
                <div class="container-fluid">

                    <!-- Logo container-->
                    <div class="logo">
                        <!-- Text Logo -->
                        <!--<a href="index.html" class="logo">-->
                        <!--Annex-->
                        <!--</a>-->

                        <a href="/dashboard" class="logo">
                            <img src="/assets/images/logo-sm.png" alt="" height="22" class="logo-small">
                            <img src="/assets/images/logo.png" alt="" height="35" class="logo-large">
                        </a>

                    </div>



                    <div class="menu-extras topbar-custom">

                        <ul class="list-inline float-right mb-0">
                            <li class="list-inline-item" style="margin-right:35px;font-size:13px;color:green">
                                 Auto Refresh (5s)
                            </li>

                            <li class="list-inline-item" style="margin-right:35px;font-size:13px;color:green">
                                 {{ Auth::user()->roles->first()->name }}
                            </li>

                            <li class="list-inline-item" style="margin-right:35px;font-size:22px;color:#ec1c24">
                                <i class="fa fa-calendar" aria-hidden="true"></i>
                                <a >
                                    <?php
                                echo date("d-m-Y"); ?>
                                </a>

                            </li>

                            <!-- notification-->
                            <li class="list-inline-item dropdown notification-list">
                                <a class="nav-link dropdown-toggle arrow-none waves-effect" data-toggle="dropdown" href="#" role="button"
                                   aria-haspopup="false" aria-expanded="false">
                                    <i class="mdi mdi-bell-outline noti-icon"></i>
                                    <span class="badge badge-success noti-icon-badge">{{ $pendingCount }}</span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-arrow dropdown-menu-lg">
                                    <!-- item-->
                                    <div class="dropdown-item noti-title">
                                        <h5>Notification ({{ $pendingCount }})</h5>
                                    </div>
                                    @if($taskPendingDetails)
                                        @foreach ($taskPendingDetails as $taskPendingDetail)
                                            <a href="javascript:void(0);" class="dropdown-item notify-item"><div class="notify-icon bg-warning"><i class="mdi mdi-message" style="margin-top:12px"></i></div><p class="notify-details"><b>Allocated Task Pending</b><small class="text-muted">{{ $taskPendingDetail->task_details }}</small></p></a>
                                        @endforeach
                                    @endif

                                    <!-- All-->

                                </div>
                            </li>
                            <!-- User-->
                            <li class="list-inline-item dropdown notification-list">
                                <a class="nav-link dropdown-toggle arrow-none waves-effect nav-user" data-toggle="dropdown" href="#" role="button"
                                   aria-haspopup="false" aria-expanded="false">
                                    <img src="/assets/images/users/avatar-1.png" alt="user" class="rounded-circle">
                                </a>
                                <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                                    <!-- item-->
                                    <div class="dropdown-item noti-title">
                                        <h5>Welcome</h5>
                                    </div>
                                    <a class="dropdown-item" href="#"><i class="mdi mdi-account-circle m-r-5 text-muted"></i> Profile</a>
                                    {{--  <a class="dropdown-item" href="#"><i class="mdi mdi-wallet m-r-5 text-muted"></i> My Wallet</a>
                                    <a class="dropdown-item" href="#"><span class="badge badge-success float-right">5</span><i class="mdi mdi-settings m-r-5 text-muted"></i> Settings</a>
                                    <a class="dropdown-item" href="#"><i class="mdi mdi-lock-open-outline m-r-5 text-muted"></i> Lock screen</a>  --}}
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="/logout"><i class="mdi mdi-logout m-r-5 text-muted"></i> Logout</a>
                                </div>
                            </li>
                            <li class="menu-item list-inline-item">
                                <!-- Mobile menu toggle-->
                                <a class="navbar-toggle nav-link">
                                    <div class="lines">
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                    </div>
                                </a>
                                <!-- End mobile menu toggle-->
                            </li>

                        </ul>
                    </div>
                    <!-- end menu-extras -->

                    <div class="clearfix"></div>

                </div> <!-- end container -->
            </div>
            <!-- end topbar-main -->

            <!-- MENU Start -->
            <div class="navbar-custom">
                <div class="container-fluid">
                    <div id="navigation">
                        <!-- Navigation Menu-->
                        <ul class="navigation-menu">

                            @can('dashboard-view-table')
                            <li class="has-submenu">
                                <a href="/dashboard"><i class="mdi mdi-airplay"></i>Dashboard</a>
                            </li>
                            @endcan

                            @can('masters-view-table')
                            <li class="has-submenu">
                                <a href="#"><i class="mdi mdi-layers"></i>Masters</a>
                                <ul class="submenu">
                                    <li><a href="/categories">Categories</a></li>
                                    <li><a href="/products">Products</a></li>
                                    <li><a href="/components">Components</a></li>
                                    <li><a href="/customers">Customers</a></li>
                                    {{--  <li><a href="">Vendors</a></li>  --}}
                                    <li><a href="/employees">Employees</a></li>
                                    <li><a href="/process-team">Teams</a></li>
                                    {{--  <li><a href="/production-stages">Team Process</a></li>  --}}
                                </ul>
                            </li>
                            @endcan

                            @can('authentication-view-table')
                            <li class="has-submenu">
                                <a href="#"><i class="mdi mdi-layers"></i>Authentication</a>
                                <ul class="submenu">
                                    <li><a href="/roles">Roles</a></li>
                                    <li><a href="/permissions">Permissions</a></li>
                                    <li><a href="/users">Users</a></li>
                                </ul>
                            </li>
                            @endcan

                            {{--  <li class="has-submenu">
                                <a href="#"><i class="mdi mdi-layers"></i>BOM</a>
                                <ul class="submenu">
                                    <li><a href="/manage-bom">Manage</a></li>
                                </ul>
                            </li>  --}}

                            @can('production-view-table')
                             <li class="has-submenu">
                                <a href="/productions"><i class="mdi mdi-airplay"></i>Production</a>
                            </li>
                            @endcan

                            @can('quotations-view-table')
                            <li class="has-submenu">
                                <a href="#"><i class="mdi mdi-layers"></i>Quotations</a>
                                <ul class="submenu">
                                    <li><a href="/quotations">Manage Quotations</a></li>
                                </ul>
                            </li>
                            @endcan

                            @can('tasks-view-table')
                            <li class="has-submenu">
                                <a href="#"><i class="mdi mdi-layers"></i>Tasks</a>
                                <ul class="submenu">
                                    <li><a href="{{ route('tasks.create') }}">Create Tasks</a></li>
                                    <li><a href="/tasks">Manage Tasks</a></li>
                                </ul>
                            </li>
                            @endcan


                            <li class="has-submenu">
                                <a href="#"><i class="mdi mdi-layers"></i>Payments</a>
                                <ul class="submenu">
                                    <li><a href="/payments">Manage Payments</a></li>
                                </ul>
                            </li>

                            <li class="has-submenu">
                                <a href="#"><i class="mdi mdi-layers"></i>LR Documents</a>
                                <ul class="submenu">
                                    <li><a href="/lr-documents">Manage LR Documents</a></li>
                                </ul>
                            </li>

                            @can('reports-view-table')
                             <li class="has-submenu">
                                <a href="/reports"><i class="mdi mdi-airplay"></i>Reports</a>
                            </li>
                            @endcan


                        </ul>
                        <!-- End navigation menu -->
                    </div> <!-- end #navigation -->
                </div> <!-- end container -->
            </div> <!-- end navbar-custom -->
        </header>
        <!-- End Navigation Bar-->
