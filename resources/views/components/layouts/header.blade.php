@push('styles')
<link rel="stylesheet" href="{{ asset('css/header.css') }}">
@endpush

<div class="header">
    <div class="header-content">
        <nav class="navbar navbar-expand">
            <div class="collapse navbar-collapse justify-content-between">
                <div class="header-left">
                    <div class="search_bar dropdown">
                        <div class="theme-switch-wrapper">
                            @livewire('theme-switch')
                        </div>
                    </div>
                </div>

                <ul class="navbar-nav header-right">
                    <!-- Menu Actions Rapides -->
                    <li class="nav-item dropdown header-profile">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-bolt"></i> Actions
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            @if(Auth::user()->hasPermission('etudiants', 'create'))
                            <a href="{{ route('etudiant') }}?action=add" class="dropdown-item">
                                <i class="fa fa-user-graduate"></i>
                                <span class="ml-2">Nouvel Étudiant</span>
                            </a>
                            @endif

                            @if(Auth::user()->hasPermission('paiements', 'create'))
                            <a href="{{ route('paiement') }}?action=add" class="dropdown-item">
                                <i class="fa fa-money-bill"></i>
                                <span class="ml-2">Nouveau Paiement</span>
                            </a>
                            @endif

                            @if(Auth::user()->hasPermission('cours', 'create'))
                            <a href="{{ route('cours') }}?action=add" class="dropdown-item">
                                <i class="fa fa-chalkboard-teacher"></i>
                                <span class="ml-2">Nouveau Cours</span>
                            </a>
                            @endif

                            @if(Auth::user()->hasPermission('notes', 'create'))
                            <a href="{{ route('note') }}?action=add" class="dropdown-item">
                                <i class="fa fa-star"></i>
                                <span class="ml-2">Nouvelle Note</span>
                            </a>
                            @endif

                            @if(Auth::user()->hasPermission('absences', 'create'))
                            <a href="{{ route('absence') }}?action=add" class="dropdown-item">
                                <i class="fa fa-user-clock"></i>
                                <span class="ml-2">Nouvelle Absence</span>
                            </a>
                            @endif

                            @if(Auth::user()->hasPermission('messages', 'create'))
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('message') }}?action=new" class="dropdown-item">
                                <i class="fa fa-envelope"></i>
                                <span class="ml-2">Nouveau Message</span>
                            </a>
                            @endif

                            @if(Auth::user()->hasPermission('classes', 'view'))
                            <a href="{{ route('emploisdutemps') }}" class="dropdown-item">
                                <i class="fa fa-calendar-alt"></i>
                                <span class="ml-2">Emploi du temps</span>
                            </a>
                            @endif
                        </div>
                    </li>

                    @if(count(Auth()->user()->notRead()) > 0)
                    <li class="nav-item dropdown notification_dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="mdi mdi-bell"></i>
                            <div class="pulse-css"></div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <ul class="list-unstyled">
                                @foreach(Auth()->user()->notRead() as $m)
                                <li class="media dropdown-item">
                                    <span class="success"><img width="25" height="20" src="{{asset('storage/images/'.$m->sender->image)}}" alt=""></span>
                                    <div class="media-body">
                                        <a href="{{route('message')}}">
                                            <p><strong>{{$m->sender->prenom}}</strong> vous a envoyé un <strong>message</strong>
                                            </p>
                                        </a>
                                    </div>
                                    <span class="notify-time">{{ date("H\h:i\m\\n", strtotime($m->created_at))}}</span>
                                </li>
                                @endforeach
                            </ul>
                            <a class="all-notification" href="{{route('message')}}">Tout voir <i
                                    class="ti-arrow-right"></i></a>
                        </div>
                    </li>
                    @endif
                    <li class="nav-item dropdown header-profile">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{asset('storage/images/'.Auth()->user()->image)}}" alt="">
                            {{mb_ucfirst(Auth()->user()->prenom)}}
                            
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="{{route('profil')}}" class="dropdown-item">
                                <i class="icon-user"></i>
                                <span class="ml-2">Profil</span>
                            </a>
                            <a href="{{route('message')}}" class="dropdown-item">
                                <i class="icon-envelope-open"></i>
                                <span class="ml-2">Messages </span>
                            </a>
                            <livewire:logout />
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Initialiser les dropdowns Bootstrap 5
    var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
    var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
        return new bootstrap.Dropdown(dropdownToggleEl, {
            autoClose: 'outside'
        });
    });

    // Gérer la fermeture des dropdowns
    $(document).on('click', function(e) {
        var target = $(e.target);
        if (!target.hasClass('dropdown-toggle') && !target.closest('.dropdown-menu').length) {
            $('.dropdown-menu').removeClass('show');
            $('.dropdown-toggle').attr('aria-expanded', 'false');
        }
    });

    // Empêcher la propagation des clics dans le menu
    $('.dropdown-menu').on('click', function(e) {
        e.stopPropagation();
    });
});
</script>
@endpush