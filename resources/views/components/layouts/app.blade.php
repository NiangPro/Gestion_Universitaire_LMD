<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Page Title' }}</title>
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



    @yield('css')
    @livewireStyles
</head>

<body>

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
    @if (!request()->is("/"))


    <script src="{{asset('themes/vendor/global/global.min.js')}}"></script>
    <script src="{{asset('themes/js/quixnav-init.js')}}"></script>
    <script src="{{asset('themes/js/custom.min.js')}}"></script>


    <script src="{{asset('themes/vendor/moment/moment.min.js')}}"></script>
    <script src="{{asset('themes/vendor/pg-calendar/js/pignose.calendar.min.js')}}"></script>



    <!-- Jquery Validation -->
    <script src="{{asset('themes/vendor/jquery-validation/jquery.validate.min.js')}}"></script>
    <!-- Form validate init -->
    <script src="{{asset('themes/js/plugins-init/jquery.validate-init.js')}}"></script>
    <script src="{{ asset('themes/vendor/jquery-steps/build/jquery.steps.min.js')}}"></script>
    <script src="{{ asset('themes/vendor/jquery-validation/jquery.validate.min.js')}}"></script>
    <!-- Form validate init -->
    <!-- Datatable -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.colVis.min.js"></script>
    <!-- Form step init -->
    <script src="{{ asset('themes/js/plugins-init/jquery-steps-init.js')}}"></script>
    @elseif(request()->is("/"))
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @endif
    <script src="{{ asset('iziToast.min.js')}}"></script>
    <script src="{{ asset('themes/vendor/summernote/js/summernote.min.js')}}"></script>
    @stack('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            const modal = new bootstrap.Modal(document.getElementById('confirmationModal'));

            Livewire.on('show-confirmation-modal', () => {
                modal.show();
            });

            Livewire.on('hide-confirmation-modal', () => {
                modal.hide();
            });
        });
    </script>

    @yield('script')

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
                    text: 'Imprimer' // Renommer le bouton "Print" en "Imprimer"
                },
                //{extend:'colvis', text:"Visibilité"}
            ],
            lengthMenu: [10, 20, 50, 75, 100], // Options de sélection pour les éléments par page
        });
    </script>

    @livewireScripts
</body>

</html>