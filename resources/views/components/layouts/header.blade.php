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
                        <a class="nav-link" href="#" data-toggle="dropdown">
                            <i class="fa fa-bolt"></i> Actions <i class="fa fa-chevron-down"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            @if(Auth::user()->hasPermission('etudiants', 'create'))
                            <a href="{{ route('etudiant') }}?action=add" class="dropdown-item" data-toggle="dropdown">
                                <i class="fa fa-user-graduate"></i>
                                <span class="ml-2">Nouvel Étudiant</span>
                            </a>
                            @endif

                            @if(Auth::user()->hasPermission('paiements', 'create'))
                            <a href="{{ route('paiement') }}?action=add" class="dropdown-item" data-toggle="dropdown">
                                <i class="fa fa-money-bill"></i>
                                <span class="ml-2">Nouveau Paiement</span>
                            </a>
                            @endif

                            @if(Auth::user()->hasPermission('cours', 'create'))
                            <a href="{{ route('cours') }}?action=add" class="dropdown-item" data-toggle="dropdown">
                                <i class="fa fa-chalkboard-teacher"></i>
                                <span class="ml-2">Nouveau Cours</span>
                            </a>
                            @endif

                            @if(Auth::user()->hasPermission('notes', 'create'))
                            <a href="{{ route('note') }}?action=add" class="dropdown-item" data-toggle="dropdown">
                                <i class="fa fa-star"></i>
                                <span class="ml-2">Nouvelle Note</span>
                            </a>
                            @endif

                            @if(Auth::user()->hasPermission('absences', 'create'))
                            <a href="{{ route('absence') }}?action=add" class="dropdown-item" data-toggle="dropdown">
                                <i class="fa fa-user-clock"></i>
                                <span class="ml-2">Nouvelle Absence</span>
                            </a>
                            @endif

                            @if(Auth::user()->hasPermission('messages', 'create'))
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('message') }}?action=new" class="dropdown-item" data-toggle="dropdown">
                                <i class="fa fa-envelope"></i>
                                <span class="ml-2">Nouveau Message</span>
                            </a>
                            @endif

                            @if(Auth::user()->hasPermission('classes', 'view'))
                            <a href="{{ route('emploisdutemps') }}" class="dropdown-item" data-toggle="dropdown">
                                <i class="fa fa-calendar-alt"></i>
                                <span class="ml-2">Emploi du temps</span>
                            </a>
                            @endif
                        </div>
                    </li>

                    @if(count(Auth()->user()->notRead()) > 0)
                    <li class="nav-item dropdown notification_dropdown">
                        <a class="nav-link" href="#" role="button" data-toggle="dropdown">
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
                        <a class="nav-link" href="#" data-toggle="dropdown">
                            <img src="{{asset('storage/images/'.Auth()->user()->image)}}" alt="">
                            {{ucfirst(Auth()->user()->prenom)}} {{ucfirst(Auth()->user()->nom)}} 
                            <i class="fa fa-chevron-down"></i>
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
<!-- 
@section('css')
<style>
.dropdown-menu {
    padding: 0.5rem 0;
    min-width: 240px;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    border: none;
    border-radius: 0.5rem;
}

.dropdown-header {
    background-color: #f8f9fa;
    color: #6c757d;
    font-weight: 600;
    padding: 0.75rem 1.25rem;
    margin-bottom: 0.5rem;
}

.dropdown-item {
    padding: 0.75rem 1.25rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: #3a3b45;
    transition: all 0.2s;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
    color: #2e59d9;
}

.dropdown-item i {
    width: 20px;
    text-align: center;
}

.dropdown-divider {
    margin: 0.5rem 0;
}

.nav-link {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    color: #6c757d;
    transition: all 0.2s;
}

.nav-link:hover {
    color: #2e59d9;
}

.nav-link i {
    font-size: 1.1rem;
}

.dropdown-menu {
    display: none;
}

.dropdown-menu.show {
    display: block;
}

/* Amélioration de l'apparence des menus déroulants */
.nav-item.dropdown {
    position: relative;
}

.dropdown-menu {
    position: absolute;
    top: 100%;
    right: 0;
    z-index: 1000;
    min-width: 10rem;
    padding: 0.5rem 0;
    margin: 0.125rem 0 0;
    background-color: #fff;
    border: 1px solid rgba(0,0,0,.15);
    border-radius: 0.25rem;
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,.175);
}

.dropdown-toggle::after {
    display: none;
}
</style>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialiser les dropdowns
    $('.dropdown-toggle').dropdown();

    // Fermer le dropdown lors du clic sur un élément
    $('.dropdown-menu a.dropdown-item').click(function() {
        $(this).closest('.dropdown-menu').dropdown('hide');
    });
});
</script>
@endpush -->