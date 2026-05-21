<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Favicon icon -->

        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('admin/assets/images/favicon.ico') }}">
        <title>@yield('title') | {{ config('app.name', 'Balanceboat') }}</title>
        <!-- Bootstrap Core CSS -->
        <link href="{{ asset('admin/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
        <!-- toast CSS -->
        <link href="{{ asset('admin/plugins/toast-master/css/jquery.toast.css') }}" rel="stylesheet">
        <!-- Morries chart CSS -->
        <link href="{{ asset('admin/plugins/morrisjs/morris.css') }}" rel="stylesheet">
        <link href="{{ asset('admin/plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="{{ asset('admin/plugins/html5-editor/bootstrap-wysihtml5.css') }}" />
        <!--alerts CSS -->
        <link href="{{ asset('admin/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css" />
        <!-- Bootstrap Datepicker plugin -->
        <link href="{{ asset('admin/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />

        @yield('head')

        <!-- Custom CSS -->
        <link href="{{ asset('admin/css/style.css') }}" rel="stylesheet">
        <!-- You can change the theme colors from here -->
        <link href="{{ asset('admin/css/colors/red.css') }}" id="theme" rel="stylesheet">
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    </head>

    <body class="fix-header fix-sidebar card-no-border">
        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->
        <div class="preloader">
            <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
        </div>
        <!-- ============================================================== -->
        <!-- Main wrapper - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <div id="main-wrapper">
            <!-- ============================================================== -->
            <!-- Topbar header - style you can find in pages.scss -->
            <!-- ============================================================== -->
            <header class="topbar">
                <nav class="navbar top-navbar navbar-expand-md navbar-light">
                    <!-- ============================================================== -->
                    <!-- Logo -->
                    <!-- ============================================================== -->
                    <div class="navbar-header">
                        <a class="navbar-brand" href="{{ url('bbadmin') }}">
                            <!-- Logo icon -->
                            <?php /* <b>
                              <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                              <!-- Dark Logo icon -->
                              <img src="{{ asset('admin/images/logo-icon.png') }}" alt="homepage" class="dark-logo" />
                              <!-- Light Logo icon -->
                              <img src="{{ asset('admin/assets/images/logo-icon.png') }}" alt="homepage" class="light-logo" />
                              </b> */ ?>
                            <!--End Logo icon -->
                            <!-- Logo text -->
                            <span>
                                <!-- dark Logo text -->
                                <img src="{{ asset('admin/images/logo.png') }}" alt="{{ config('app.name', 'Balanceboat') }}" class="dark-logo" width="175" />
                                <!-- Light Logo text -->    
                                <img src="{{ asset('admin/images/logo.png') }}" class="light-logo" alt="{{ config('app.name', 'Balanceboat') }}" width="175" />
                            </span>
                        </a>
                    </div>
                    <!-- ============================================================== -->
                    <!-- End Logo -->
                    <!-- ============================================================== -->
                    <div class="navbar-collapse">
                        <!-- ============================================================== -->
                        <!-- toggle and nav items -->
                        <!-- ============================================================== -->
                        <ul class="navbar-nav mr-auto mt-md-0">
                            <!-- This is  -->
                            <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="mdi mdi-menu"></i></a> </li>
                            <li class="nav-item m-l-10"> <a class="nav-link sidebartoggler hidden-sm-down text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
                        </ul>
                        <!-- ============================================================== -->
                        <!-- User profile and search -->
                        <!-- ============================================================== -->
                        <ul class="navbar-nav my-lg-0">
                            <!-- ============================================================== -->
                            <!-- Search -->
                            <!-- ============================================================== -->
                            <li class="nav-item hidden-sm-down search-box"> <a class="nav-link hidden-sm-down text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="ti-search"></i></a>
                                <form class="app-search">
                                    <input type="text" class="form-control" placeholder="Search & enter"> <a class="srh-btn"><i class="ti-close"></i></a> </form>
                            </li>

                            <!-- ============================================================== -->
                            <!-- Profile -->
                            <!-- ============================================================== -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="{{ asset('admin/images/users/profile.png') }}" alt="user" class="profile-pic" /></a>
                                <div class="dropdown-menu dropdown-menu-right scale-up">
                                    <ul class="dropdown-user">
                                        <li>
                                            <div class="dw-user-box">
                                                <div class="u-img"><img src="{{ asset('admin/images/users/1.jpg') }}" alt="user"></div>
                                                <div class="u-text">
                                                    <h4>{{ Auth::user()->first_name." ".Auth::user()->last_name }}</h4>
                                                    <p class="text-muted">{{ Auth::user()->email }}</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li role="separator" class="divider"></li>
                                        <li><a href="#"><i class="ti-user"></i> My Profile</a></li>
                                        <li role="separator" class="divider"></li>
                                        <li><a href="#"><i class="ti-settings"></i> Account Setting</a></li>
                                        <li role="separator" class="divider"></li>
                                        <li><a href="{{ url("bbadmin/logout") }}"><i class="fa fa-power-off"></i> Logout</a></li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
            <!-- ============================================================== -->
            <!-- End Topbar header -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Left Sidebar - style you can find in sidebar.scss  -->
            <!-- ============================================================== -->
            <aside class="left-sidebar">
                <!-- Sidebar scroll-->
                <div class="scroll-sidebar">
                    <!-- Sidebar navigation-->
                    <nav class="sidebar-nav">
                        <ul id="sidebarnav">
                            <li class="nav-devider" style="margin:0 0 10px;"></li>
                            <li> <a class="waves-effect waves-dark" href="#" ><i class="mdi mdi-gauge"></i><span class="hide-menu">Dashboard </span></a></li>
                            <li> <a class="waves-effect waves-dark" href="{{ url("bbadmin/bookings") }}"><i class="mdi mdi-receipt"></i><span class="hide-menu">Bookings </span></a></li>
                            <li> <a class="waves-effect waves-dark" href="{{ url("bbadmin/category") }}"><i class="mdi mdi-format-list-bulleted-type"></i><span class="hide-menu">Categories </span></a></li>
                            <li> <a class="waves-effect waves-dark" href="{{ url("bbadmin/deals") }}"><i class="mdi mdi-format-list-bulleted-type"></i><span class="hide-menu">Deal </span></a></li>
                            <li> <a class="waves-effect waves-dark" href="{{ url("bbadmin/certificates") }}"><i class="mdi mdi-certificate"></i><span class="hide-menu">Certificates </span></a></li>
                            <li> <a class="waves-effect waves-dark" href="{{ url("bbadmin/teachers") }}"><i class="mdi mdi-account-multiple"></i><span class="hide-menu">Teachers </span></a></li>
                            <li> <a class="waves-effect waves-dark" href="{{ url("bbadmin/accomodations") }}"><i class="mdi mdi-hotel"></i><span class="hide-menu">Accomodations </span></a></li>
                            <li> 
                                <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-map-marker-multiple"></i><span class="hide-menu">Centres</span></a>
                                <ul aria-expanded="false" class="collapse">
                                    <li><a href="{{ url("bbadmin/centers") }}">Centres</a></li>
                                    <li><a href="{{ url("bbadmin/centers/upload") }}">Upload</a></li>
                                </ul>
                            </li>
                            <li> <a class="waves-effect waves-dark" href="{{ url("bbadmin/commissions") }}"><i class="mdi mdi-creation"></i><span class="hide-menu">Commission </span></a></li>
                            <li> 
                                <a class="has-arrow waves-effect waves-dark" href="#"><i class="mdi mdi-creation"></i><span class="hide-menu">Experiences </span></a>
                                <ul aria-expanded="false" class="collapse">
                                    <li><a href="{{ url("bbadmin/experiences") }}">Experiences</a></li>
                                    <li><a href="{{ url("bbadmin/experiences/upload") }}">Upload</a></li>
                                </ul>
                            </li>
                            <li> <a class="waves-effect waves-dark" href="{{ url("bbadmin/leads") }}"><i class="mdi mdi-format-list-bulleted-type"></i><span class="hide-menu">Leads </span></a></li>
                            <li> <a class="waves-effect waves-dark" href="{{ url("bbadmin/blogs") }}"><i class="mdi mdi-blogger"></i><span class="hide-menu">Blog </span></a></li>
                            <li> <a class="waves-effect waves-dark" href="{{ url("bbadmin/adverts") }}"><i class="mdi mdi-file-image"></i><span class="hide-menu">Adverts </span></a></li>
                            <li> <a class="waves-effect waves-dark" href="{{ url("bbadmin/marquee") }}"><i class="mdi mdi-file-image"></i><span class="hide-menu">Marquee </span></a></li>
                            <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-account-multiple"></i><span class="hide-menu">Users</span></a>
                                <ul aria-expanded="false" class="collapse">
                                    <li><a href="{{ url("bbadmin/users") }}">Users</a></li>
                                    <li><a href="#">Permissions</a></li>
                                    <li><a href="#">Roles</a></li>
                                </ul>
                            </li>
                            <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-file-chart"></i><span class="hide-menu">Reports</span></a>
                                <ul aria-expanded="false" class="collapse">
                                    <li><a href="{{ url("bbadmin/report/experiences") }}">Experiences</a></li>
                                    <li><a href="{{ url("bbadmin/report/teachers") }}">Teachers</a></li>
                                    <li><a href="{{ url("bbadmin/export") }}">Export Packages</a></li>
                                </ul>
                            </li>
                            <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-upload"></i><span class="hide-menu">Uploads</span></a>
                                <ul aria-expanded="false" class="collapse">
                                    <li><a href="{{ url("bbadmin/upload") }}">Sitemap</a></li>
                                    <li><a href="{{ url("bbadmin/centre-onboard") }}">Centre Onboard</a></li>
                                </ul>
                            </li>
                            <li> <a class="waves-effect waves-dark" href="{{ url("sync-bb-images") }}" target="_blank"><i class="mdi mdi-sync"></i><span class="hide-menu">Sync Images </span></a></li>
                        </ul>
                    </nav>
                    <!-- End Sidebar navigation -->
                </div>
                <!-- End Sidebar scroll-->
            </aside>
            <!-- ============================================================== -->
            <!-- End Left Sidebar - style you can find in sidebar.scss  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Page wrapper  -->
            <!-- ============================================================== -->
            <div class="page-wrapper">

                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                    <div class="col-md-5 align-self-center">
                        @yield('page-heading')
                    </div>
                    <div class="col-md-7 align-self-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                            @yield('page-breadcrumb')
                        </ol>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->

                @yield('content')
                <!-- ============================================================== -->
                <!-- End Page wrapper  -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- footer -->
                <!-- ============================================================== -->
                <footer class="footer">
                    © {{date('Y')}} Balanceboat
                </footer>
                <!-- ============================================================== -->
                <!-- End footer -->
                <!-- ============================================================== -->
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- End Wrapper -->
        <!-- ============================================================== -->

        <!-- ============================================================== -->
        <!-- All Jquery -->
        <!-- ============================================================== -->
        <script src="{{ asset('admin/plugins/jquery/jquery.min.js') }}"></script>
        <!-- Bootstrap tether Core JavaScript -->
        <script src="{{ asset('admin/plugins/bootstrap/js/popper.min.js') }}"></script>
        <script src="{{ asset('admin/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
        <!-- slimscrollbar scrollbar JavaScript -->
        <script src="{{ asset('admin/js/jquery.slimscroll.js') }}"></script>
        <!--Wave Effects -->
        <script src="{{ asset('admin/js/waves.js') }}"></script>
        <!--Menu sidebar -->
        <script src="{{ asset('admin/js/sidebarmenu.js') }}"></script>
        <!--stickey kit -->
        <script src="{{ asset('admin/plugins/sticky-kit-master/dist/sticky-kit.min.js') }}"></script>
        <script src="{{ asset('admin/plugins/sparkline/jquery.sparkline.min.js') }}"></script>
        <script src="{{ asset('admin/plugins/select2/dist/js/select2.full.min.js') }}" type="text/javascript"></script>
        <!-- Sweet-Alert  -->
        <script src="{{ asset('admin/plugins/sweetalert/sweetalert.min.js') }}"></script>
        <!--Custom JavaScript -->
        <script src="{{ asset('admin/plugins/toast-master/js/jquery.toast.js') }}"></script>
        <script src="{{ asset('admin/js/validation.js') }}"></script>
        <!--morris JavaScript -->
        <script src="{{ asset('admin/plugins/raphael/raphael-min.js') }}"></script>
        <script src="{{ asset('admin/plugins/morrisjs/morris.min.js') }}"></script>
        <!-- sparkline chart -->
        <script src="{{ asset('admin/plugins/sparkline/jquery.sparkline.min.js') }}"></script>
        <!-- This is data table -->
        <script src="{{ asset('admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
        <!-- Bootstrap Datepicker plugin -->
        <script src="{{ asset('admin/plugins/moment/min/moment.min.js') }}"></script>
        <script src="{{ asset('admin/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>
        <!-- wysuhtml5 Plugin JavaScript -->
        <script src="{{ asset('admin/plugins/tinymce/tinymce.min.js') }}"></script>
        <script src="{{ asset('admin/js/custom.min.js') }}"></script>
        <!-- Scripts -->
        <script type="text/javascript">ADMIN_URL = '<?php echo url("/bbadmin/"); ?>';</script>
        @yield('footer')
    </body>
</html>