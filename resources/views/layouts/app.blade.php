<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1">

     <!-- CSRF Token -->
     <meta name="csrf-token" content="{{ csrf_token() }}">
     <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('/assets/images/logo.png') }}">

     <title>{{ config('app.name') }} | @yield('page-title')</title>
     <!-- Fonts -->
     <link rel="dns-prefetch" href="//fonts.gstatic.com">
     <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

     <link rel="preconnect" href="https://fonts.googleapis.com">
     <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
     <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
     <!-- Bootstrap Css -->
     <link rel="stylesheet" href="https://res.cloudinary.com/djqqh07cr/raw/upload/v1669356791/Document-tracking/bootstrap.min_y0n0g5.css">
     <!-- Icons Css -->
     <link href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/7.0.96/css/materialdesignicons.min.css" rel="stylesheet" type="text/css" />
     <!-- App Css-->
     <link href="{{ asset('/assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
     {{-- datatables --}}
     <link href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
     <link href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
     <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
     <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
     <style>
          * {
               font-family: 'Inter', sans-serif;
          }

     </style>
     @stack('page-css')
</head>
<body data-layout="detached" data-topbar="colored">



     <!-- <body data-layout="horizontal" data-topbar="dark"> -->

     <div class="container-fluid">
          <!-- Begin page -->
          <div id="layout-wrapper">

               <header id="page-topbar">
                    <div class="navbar-header">
                         <div class="container-fluid">
                              <div class="float-end">

                                   <div class="dropdown d-none d-lg-inline-block ms-1">
                                        <button type="button" class="btn header-item noti-icon waves-effect" data-toggle="fullscreen">
                                             <i class="mdi mdi-fullscreen"></i>
                                        </button>
                                   </div>

                                   <div class="dropdown d-inline-block">
                                        <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                             <img class="rounded-circle header-profile-user" src="{{ !is_null(Auth::user()->profile_picture) ? '/document-tracking/storage/account/' . Auth::user()->profile_picture : 'https://res.cloudinary.com/dfm6cr1l9/image/upload/v1653217730/pngkey.com-avatar-png-1149878_lvpbsn.png' }}" alt="Header Avatar">
                                             <span class="d-none d-xl-inline-block ms-1">{{ Auth::user()->firstname . ' ' .Auth::user()->lastname }}</span>
                                             <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                             <!-- item-->
                                             <a class="dropdown-item" href="{{ route('user.account.settings') }}"><i class="bx bx-user font-size-16 align-middle me-1"></i>
                                                  Account Settings</a>
                                             {{-- <a class="dropdown-item" href="#"><i class="bx bx-wallet font-size-16 align-middle me-1"></i> My
                                        Wallet</a>
                                    <a class="dropdown-item d-block" href="#"><span class="badge bg-success float-end">11</span><i class="bx bx-wrench font-size-16 align-middle me-1"></i> Settings</a>
                                    <a class="dropdown-item" href="#"><i class="bx bx-lock-open font-size-16 align-middle me-1"></i>
                                        Lock screen</a> --}}
                                             <div class="dropdown-divider"></div>
                                             <a class="dropdown-item text-danger" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="bx bx-power-off font-size-16 align-middle me-1 text-danger"></i> Logout</a>
                                             <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                                  @csrf
                                             </form>
                                        </div>
                                   </div>


                              </div>
                              <div>
                                   <!-- LOGO -->
                                   <div class="navbar-brand-box">
                                        <a href="" class="logo logo-light">
                                            <span class="logo-sm">
                                                 <img src="{{ asset('/assets/images/logo2.png') }}" alt="" height="60">
                                            </span>
                                            <span class="logo-lg">
                                                 <img src="{{ asset('/assets/images/logo2.png') }}" alt="" height="60">
                                            </span>
                                       </a>
                                   </div>

                                   <button type="button" class="btn btn-sm px-3 font-size-16 header-item toggle-btn waves-effect" id="vertical-menu-btn">
                                        <i class="mdi mdi-menu-open"></i>
                                   </button>

                                   <!-- App Search-->

                              </div>

                         </div>
                    </div>
               </header> <!-- ========== Left Sidebar Start ========== -->
               <div class="vertical-menu">

                    <div class="h-100">

                         <div class="user-wid text-center py-4">
                              <div class="user-img">
                                   <img src="{{ !is_null(Auth::user()->profile_picture) ? '/document-tracking/storage/account/' . Auth::user()->profile_picture : 'https://res.cloudinary.com/dfm6cr1l9/image/upload/v1653217730/pngkey.com-avatar-png-1149878_lvpbsn.png' }}" alt="" class="avatar-md mx-auto rounded-circle">
                              </div>

                              <div class="mt-3">
                                   <a href="#" class="text-dark fw-medium font-size-16">{{ Auth::user()->firstname . ' ' . Auth::user()->lastname }}</a>
                                   <p class="text-body mt-1 mb-0 font-size-13">{{ Auth::user()->userPosition->position_name }}</p>
                              </div>
                         </div>

                         <!--- Sidemenu -->
                         <div id="sidebar-menu">
                              <!-- Left Menu Start -->
                              <ul class="metismenu list-unstyled" id="side-menu">


                                    @if ($userRole->role == 'checker')
                                    <li class="menu-title">Menu</li>
                                    <li>
                                        <a href="{{ route('service.incoming') }}" class="waves-effect">
                                            <i class="mdi mdi-file-download-outline"></i>
                                             <span>Incoming </span>
                                             <span class='mx-2 badge bg-primary'>{{ $incoming_documents }}</span>
                                        </a>
                                   </li>
                                   <li>
                                        <a href="{{ route('service.outgoing') }}" class="waves-effect">
                                             <i class="mdi mdi-file-export-outline"></i>
                                             <span>Out-going</span>
                                             <span class='mx-2 badge bg-primary'>{{ ($outgoing_documents - $for_release_documents ) < 0 ? 0 : ($outgoing_documents - $for_release_documents ) }}</span>
                                        </a>
                                   </li>
                                   <li>
                                    <a href="{{ route('service.for-release') }}" class="waves-effect">
                                        <i class="mdi mdi-file-export-outline"></i>
                                        <span>For Release</span>
                                        <span class='mx-2 badge bg-primary'>{{ $for_release_documents }}</span>
                                    </a>
                                </li>
                                   <li>
                                        <a href="{{ route('service.manage') }}" class=" waves-effect">
                                             <i class='mdi mdi-folder-multiple-outline'></i>
                                             <span>Manage</span>
                                             <span class='mx-2 badge bg-info'>{{ $manage_documents }}</span>
                                        </a>
                                   </li>
                                   <li class="menu-title">Logs</li>
                                   <li>
                                        <a href="{{ route('user.logs') }}" class=" waves-effect">
                                             <i class='mdi mdi-history'></i>
                                             <span>Transactions History</span>
                                        </a>
                                   </li>
                                    @else
                                    <li class="menu-title">Dashboard</li>
                                    <li>
                                        <a href="{{ route('service.track-my-document') }}" class=" waves-effect">
                                             <i class='mdi mdi-gesture'></i>
                                             <span>Track my Document
                                                  <span  class='mx-2 badge bg-primary'></span>
                                             </span>
                                        </a>
                                   </li>
                                    <li class="menu-title">Documents</li>
                                    <li>
                                         <a href="{{ route('user.documents') }}" class=" waves-effect">
                                              <i class='mdi mdi-folder-open-outline '></i>
                                              <span>My Documents
                                                   <span id="myDocsCount" class='mx-2 badge bg-primary'>{{ $processing_documents }}</span>
                                              </span>
                                         </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('service.return') }}" class=" waves-effect">
                                             <i class='mdi mdi-wrap'></i>
                                             <span>Returned</span>
                                             <span class='mx-2 badge bg-danger'>{{ $returned_documents }}</span>
                                        </a>
                                   </li>
                                    <li>
                                         <a href="{{ route('service.index') }}" class=" waves-effect">
                                              <i class="mdi mdi-equal-box"></i>
                                              <span>Services</span>
                                         </a>
                                    </li>
                                    <li class="menu-title">Logs</li>
                                    <li>
                                         <a href="{{ route('user.logs') }}" class=" waves-effect">
                                              <i class='mdi mdi-history'></i>
                                              <span>Transactions History</span>
                                         </a>
                                    </li>
                                    @endif






                              </ul>
                         </div>
                         <!-- Sidebar -->
                    </div>
               </div>
               <!-- Left Sidebar End -->

               <!-- ============================================================== -->
               <!-- Start right Content here -->
               <!-- ============================================================== -->
               <div class="main-content">

                    <div class="page-content">

                         <!-- start page title -->
                         <div class="row">
                              <div class="col-12">
                                   <div class="page-title-box d-flex align-items-center justify-content-between">
                                        <h4 class="page-title mb-0 font-size-18">@yield('page-title')</h4>

                                        <div class="page-title-right">
                                             <ol class="breadcrumb m-0">
                                                  {{-- <li class="breadcrumb-item"><a href="javascript: void(0);">Pages</a></li> --}}
                                                  {{-- <li class="breadcrumb-item active">Starter Page</li> --}}
                                             </ol>
                                        </div>
                                   </div>
                              </div>
                         </div>
                         <!-- end page title -->
                         @yield('content')
                    </div>
                    <!-- End Page-content -->

                    <footer class="footer">
                         <div class="container-fluid">
                              <div class="row">
                                   <div class="col-sm-6">
                                        <script>
                                             document.write(new Date().getFullYear())

                                        </script> © {{ config('app.name') }}.
                                   </div>
                                   {{-- <div class="col-sm-6">
                                        <div class="text-sm-end d-none d-sm-block">
                                             Design & Develop by Themesbrand
                                        </div>
                                   </div> --}}
                              </div>
                         </div>
                    </footer>
               </div>
               <!-- end main content-->

          </div>
          <!-- END layout-wrapper -->

     </div>
     <!-- end container-fluid -->

     <!-- Right Sidebar -->
     <div class="right-bar">
          <div data-simplebar class="h-100">
               <div class="rightbar-title px-3 py-4">
                    <a href="javascript:void(0);" class="right-bar-toggle float-end">
                         <i class="mdi mdi-close noti-icon"></i>
                    </a>
                    <h5 class="m-0">Settings</h5>
               </div>

          </div>
          <!-- end slimscroll-menu-->
     </div>
     <!-- /Right-bar -->

     <!-- Right bar overlay-->
     <div class="rightbar-overlay"></div>

     <!-- JAVASCRIPT -->
     <!-- JAVASCRIPT -->
     <script src="{{ asset('/assets/libs/jquery/jquery.min.js') }}"></script>
     <script src="{{ asset('/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
     <script src="{{ asset('/assets/libs/metismenu/metisMenu.min.js') }}"></script>
     <script src="{{ asset('/assets/libs/simplebar/simplebar.min.js') }}"></script>
     <script src="{{ asset('/assets/libs/node-waves/waves.min.js') }}"></script>
     <script src="{{ asset('/assets/libs/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
     <!-- Plugins js -->
     <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
     <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>
     <script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>
     <script src="https://cdn.datatables.net/responsive/2.4.0/js/responsive.bootstrap4.min.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

     <!-- App js -->
     <script src="{{ asset('/assets/js/app.js') }}"></script>
     <script>
          $(document).ready(function() {
               $(document).on('click', '.view-document', function() {
                    let filename = $(this).attr('data-file-name');
                    window.open(`/download/file/${filename}`);
               });
          });

     </script>
     @stack('page-scripts')
</body>
</html>
