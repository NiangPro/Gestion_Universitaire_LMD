<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? 'Page Title' }}</title>
        @if (!request()->is("/"))
        <link href="{{asset('themes/vendor/pg-calendar/css/pignose.calendar.min.css')}}" rel="stylesheet">
        <link href="{{asset('themes/vendor/chartist/css/chartist.min.css')}}" rel="stylesheet">
        <link href="{{asset('themes/css/style.css')}}" rel="stylesheet">
        <link href="{{asset('themes/vendor/sweetalert2/dist/sweetalert2.min.css')}}" rel="stylesheet">
        <link href="{{ asset('themes/vendor/jquery-steps/css/jquery.steps.css')}}" rel="stylesheet">

        <!-- Datatable -->
        <link href="{{asset('themes/vendor/datatables/css/jquery.dataTables.min.css')}}" rel="stylesheet">
        @elseif(request()->is("/")) 
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            /* Custom styles for pricing cards */
            .pricing-section {
              background-color: #f5f5f5;
              padding: 50px 0;
            }
            .card-pricing {
              background-color: #000;
              color: #fff;
              border: none;
              border-radius: 15px;
              box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
              margin-bottom: 30px;
              position: relative;
              overflow: hidden;
            }
            .card-header {
              padding: 20px;
              text-align: center;
              font-size: 1.5rem;
              font-weight: bold;
              border-radius: 10px 10px 0 0;
              color: #fff;
            }
            .bg-green {
              background-color: #6BCB77;
            }
            .bg-blue {
              background-color: #5bc0de;
            }
            .bg-pink {
              background-color: #f06292;
            }
            .pricing-price {
              font-size: 2.5rem;
              font-weight: bold;
              margin: 20px 0;
            }
            .pricing-subtext {
              font-size: 1rem;
              margin-bottom: 20px;
              color: #bdbdbd;
            }
            .pricing-list {
              list-style: none;
              padding: 0;
              margin-bottom: 30px;
              text-align: left;
            }
            .pricing-list li {
              margin-bottom: 15px;
              font-size: 1.1rem;
            }
            .btn-pricing {
              padding: 10px 30px;
              border-radius: 30px;
              font-size: 1.1rem;
              font-weight: bold;
            }
            .icon-check {
              width: 1.5rem;
              height: 1.5rem;
              margin-right: 10px;
              vertical-align: middle;
            }
            /* Adjustments for card styling */
            .medium-card {
              top: -20px;
              border-radius: 20px;
            }
            .card-body {
              padding: 20px 30px;
            }
        </style>
        @endif
        
        @yield('css')
        @livewireStyles
    </head>
    <body>

        @if (!request()->is("/") && !request()->is("inscription") &&  !request()->is("connexion"))
        <!--*******************
            Preloader start
        ********************-->
        {{-- <div id="preloader">
            <div class="sk-three-bounce">
                <div class="sk-child sk-bounce1"></div>
                <div class="sk-child sk-bounce2"></div>
                <div class="sk-child sk-bounce3"></div>
            </div>
        </div> --}}
        <!--*******************
            Preloader end
        ********************-->
         <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

        <!--**********************************
            Nav header start
        ***********************************-->
        <div class="nav-header">
            <a href="index.html" class="brand-logo">
                <img class="logo-abbr" src="themes/images/logo.png" alt="">
                <img class="logo-compact" src="themes/images/logo-text.png" alt="">
                <img class="brand-title" src="themes/images/logo-text.png" alt="">
            </a>

            <div class="nav-control">
                <div class="hamburger">
                    <span class="line"></span><span class="line"></span><span class="line"></span>
                </div>
            </div>
        </div>
        <!--**********************************
            Nav header end
        ***********************************-->

        <!--**********************************
            Header start
        ***********************************-->
        @include('components.layouts.header')
        <!--**********************************
            Header end ti-comment-alt
        ***********************************-->

        <!--**********************************
            Sidebar start
        ***********************************-->
        @include('components.layouts.sidebar')
        <!--**********************************
            Sidebar end
        ***********************************-->

        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <div class="container-fluid">
                <div class="row page-titles mx-0">
                    <div class="col-sm-6 p-md-0">
                        <div class="welcome-text">
                            <h4>{{ $title ?? 'Accueil' }}</h4>
                            <p class="mb-0">Your business dashboard template</p>
                        </div>
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Layout</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0)">Blank</a></li>
                        </ol>
                    </div>
                </div>
 
                @endif
                {{ $slot }}
        @if (!request()->is("/") && !request()->is("inscription") && !request()->is("connexion"))
                
            </div>
        </div>
        <!--**********************************
            Content body end
        ***********************************-->


        <!--**********************************
            Footer start
        ***********************************-->
        @include('components.layouts.footer')
        <!--**********************************
            Footer end
        ***********************************-->

        <!--**********************************
           Support ticket button start
        ***********************************-->

        <!--**********************************
           Support ticket button end
        ***********************************-->


    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->
        @endif
        @if (!request()->is("/"))

    
    <script src="{{asset('themes/vendor/global/global.min.js')}}"></script>
    <script src="{{asset('themes/js/quixnav-init.js')}}"></script>
    <script src="{{asset('themes/js/custom.min.js')}}"></script>
    
    <script src="{{asset('themes/vendor/chartist/js/chartist.min.js')}}"></script>
    
    <script src="{{asset('themes/vendor/moment/moment.min.js')}}"></script>
    <script src="{{asset('themes/vendor/pg-calendar/js/pignose.calendar.min.js')}}"></script>
    
    
    <script src="{{asset('themes/js/dashboard/dashboard-2.js')}}"></script>
    <script src="{{asset('themes/vendor/sweetalert2/dist/sweetalert2.min.js')}}"></script>
    <script src="{{asset('themes/js/plugins-init/sweetalert.init.js')}}"></script>

    <!-- Datatable -->
    <script src="{{asset('themes/vendor/datatables/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('themes/js/plugins-init/datatables.init.js')}}"></script>
    
    <!-- Jquery Validation -->
    <script src="{{asset('themes/vendor/jquery-validation/jquery.validate.min.js')}}"></script>
    <!-- Form validate init -->
    <script src="{{asset('themes/js/plugins-init/jquery.validate-init.js')}}"></script>
    <script src="{{ asset('themes/vendor/jquery-steps/build/jquery.steps.min.js')}}"></script>
    <script src="{{ asset('themes/vendor/jquery-validation/jquery.validate.min.js')}}"></script>
    <!-- Form validate init -->



    <!-- Form step init -->
    <script src="{{ asset('themes/js/plugins-init/jquery-steps-init.js')}}"></script>
    @elseif(request()->is("/"))  
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @endif
    @yield('script')
        
        @livewireScripts
    </body>
</html>
