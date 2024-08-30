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
        @else 
            <link rel="stylesheet" href="{{asset('login.css')}}">
            <link rel="stylesheet" href="{{asset('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css')}}"/>
            
        @endif
        @livewireStyles
    </head>
    <body>

        @if (!request()->is("/"))
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
        @if (!request()->is("/"))
                
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
 
    
    <script src="{{asset('themes/vendor/global/global.min.js')}}"></script>
    <script src="{{asset('themes/js/quixnav-init.js')}}"></script>
    <script src="{{asset('themes/js/custom.min.js')}}"></script>
    
    <script src="{{asset('themes/vendor/chartist/js/chartist.min.js')}}"></script>
    
    <script src="{{asset('themes/vendor/moment/moment.min.js')}}"></script>
    <script src="{{asset('themes/vendor/pg-calendar/js/pignose.calendar.min.js')}}"></script>
    
    
    <script src="{{asset('themes/js/dashboard/dashboard-2.js')}}"></script>
    @else  
    <script src="{{asset('login.js')}}"></script>
    @endif
    @yield('script')
        
        @livewireScripts
    </body>
</html>
