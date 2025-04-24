<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="apple-touch-icon"  href="assets{{asset('images/logo.png')}}">
    <link rel="icon" type="image/png"  href="assets{{asset('images/logo.png')}}">
    <link rel="shortcut icon" type="image/x-icon" href="assets{{asset('images/logo.png')}}">
    <title>{{ isset($title) ? $title : 'EduLink' }}</title>
    @if (!request()->is("/"))
    <link href="{{asset('themes/vendor/pg-calendar/css/pignose.calendar.min.css')}}" rel="stylesheet">
    <link href="{{asset('themes/css/style.css')}}" rel="stylesheet">
    <link href="{{ asset('themes/vendor/jquery-steps/css/jquery.steps.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Datatable -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
    @elseif(request()->is("/"))
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    @endif
    <link href="{{asset('iziToast.min.css')}}" rel="stylesheet">
    <link href="{{asset('themes/vendor/summernote/summernote.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dark-mode.css') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles pour le mode sombre -->
    <style>
        body.dark-mode {
            background-color: #333;
            color: #fff;
        }

        body.dark-mode .card {
            background-color: #444;
            border-color: #555;
            color: #fff;
        }

        body.dark-mode .navbar {
            background-color: #222 !important;
        }

        body.dark-mode .navbar-light .navbar-nav .nav-link {
            color: #fff !important;
        }

        body.dark-mode .table {
            color: #fff;
            background-color: #444;
        }

        body.dark-mode .table td,
        body.dark-mode .table th {
            border-color: #555;
        }

        body.dark-mode .modal-content {
            background-color: #444;
            color: #fff;
        }

        body.dark-mode .form-control {
            background-color: #555;
            border-color: #666;
            color: #fff;
        }

        body.dark-mode .form-control:focus {
            background-color: #666;
            color: #fff;
        }

        body.dark-mode .list-group-item {
            background-color: #444;
            border-color: #555;
            color: #fff;
        }

        body.dark-mode .dropdown-menu {
            background-color: #444;
            border-color: #555;
        }

        body.dark-mode .dropdown-item {
            color: #fff;
        }

        body.dark-mode .dropdown-item:hover {
            background-color: #555;
        }

        /* Ajoutez ici d'autres styles spécifiques à votre application */
    </style>

    @yield('css')
    @livewireStyles
</head>

<body class="{{ session()->get('darkMode') ? 'dark-mode' : '' }}">

    @if (Auth()->user())
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
                <img class="logo-abbr" src="{{ asset('themes/images/logo.png')}}" alt="">
                <img class="logo-compact" src="{{ asset('themes/images/logo-text.png')}}" alt="">
                <img class="brand-title" src="{{ asset('themes/images/logo-text.png')}}" alt="">
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
                {{-- <div class="row page-titles mx-0">
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
    </div> --}}

    @endif
    {{ $slot }}
    @if (Auth()->user())

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
    <!-- 1. jQuery (requis pour Bootstrap et autres plugins) -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <!-- 2. Scripts Livewire -->
    @livewireScripts

    <!-- 3. Bootstrap et autres dépendances -->
    @if (!request()->is("/"))
        <script src="{{asset('themes/vendor/global/global.min.js')}}"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="{{asset('themes/vendor/moment/moment.min.js')}}"></script>
        <script src="{{asset('themes/vendor/pg-calendar/js/pignose.calendar.min.js')}}"></script>
        <script src="{{asset('themes/vendor/jquery-validation/jquery.validate.min.js')}}"></script>
        <script src="{{asset('themes/js/plugins-init/jquery.validate-init.js')}}"></script>
        <script src="{{asset('themes/vendor/jquery-steps/build/jquery.steps.min.js')}}"></script>
        <script src="{{asset('themes/js/plugins-init/jquery-steps-init.js')}}"></script>
        <!-- Datatable -->
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.colVis.min.js"></script>
        <script src="{{asset('themes/js/quixnav-init.js')}}"></script>
        <script src="{{asset('themes/js/custom.min.js')}}"></script>
    @else
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @endif

    <!-- 4. Plugins et scripts spécifiques -->
    <script src="{{ asset('iziToast.min.js')}}"></script>
    <script src="{{ asset('themes/vendor/summernote/js/summernote.min.js')}}"></script>
    <script src="{{ asset('js/dark-mode.js')}}"></script>

    <!-- 5. Configuration DataTables -->
    <script>
        let table = $('#myTable').DataTable({
            language: {
                info: 'Affichage de _PAGE_ sur _PAGES_',
                infoEmpty: 'Aucun enregistrement disponible',
                infoFiltered: '(filtré de _MAX_ enregistrements totaux)',
                lengthMenu: 'Affichage de _MENU_  &nbsp;',
                zeroRecords: 'Aucun résultat trouvé',
                search: "Recherche :",
                paginate: {
                    next: "<h3>&raquo;</h3>",
                    previous: "<h3>&laquo;</h3>",
                },
            },
            dom: 'lBfrtip',
            buttons: ['excel', 'pdf', {
                    extend: 'print',
                    text: 'Imprimer'
                }],
            lengthMenu: [10, 20, 50, 75, 100]
        });
    </script>

    <!-- 6. Gestion du thème sombre et des modales -->
    <script>
        function applyTheme(darkMode) {
            if (darkMode) {
                document.body.classList.add('dark-mode');
                document.documentElement.setAttribute('data-theme', 'dark');
            } else {
                document.body.classList.remove('dark-mode');
                document.documentElement.setAttribute('data-theme', 'light');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('darkMode');
            if (savedTheme !== null) {
                applyTheme(savedTheme === 'true');
            }
        });

        document.addEventListener('livewire:init', () => {
            const modal = new bootstrap.Modal(document.getElementById('confirmationModal'));

            Livewire.on('show-confirmation-modal', () => modal.show());
            Livewire.on('hide-confirmation-modal', () => modal.hide());

            Livewire.on('theme-changed', (event) => {
                const darkMode = event.darkMode;
                localStorage.setItem('darkMode', darkMode);
                applyTheme(darkMode);
            });
        });
    </script>

    @stack('scripts')
    @yield('script')
</body>

</html>