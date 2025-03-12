<div class="quixnav">
    <div class="quixnav-scroll">
        <ul class="metismenu" id="menu">
            {{-- <li class="nav-label first">Main Menu</li> --}}
            <!-- <li><a href="index.html"><i class="icon icon-single-04"></i><span class="nav-text">Dashboard</span></a>
            </li> -->
            <li><a href="{{route('dashboard')}}" aria-expanded="false">
                    <i class="fa fa-dashboard"></i><span
                        class="nav-text">Tableau de bord</span>
                </a></li>
            @if(Auth()->user()->estSuperAdmin())
            <li class="nav-label">Gestion Etablissements</li>
            <li><a href="{{route('pack')}}" aria-expanded="false">
                    <i class="fa fa-server"></i><span
                        class="nav-text">Packs</span>
                </a></li>
            <li><a href="{{route('etablissement')}}" aria-expanded="false">
                    <i class="fa fa-institution"></i><span
                        class="nav-text">Campus</span>
                </a></li>
            <li><a href="{{route('activation')}}" aria-expanded="false">
                    <i class="fa fa-key"></i><span
                        class="nav-text">Activations</span>
                </a></li>
            <li><a href="{{route('corbeille')}}" aria-expanded="false">
                    <i class="fa fa-recycle"></i><span
                        class="nav-text">Corbeille</span>
                </a></li>
            @endif
            @if(Auth()->user()->estAdmin())
            <li><a href="{{route('academicyear')}}" aria-expanded="false">
                    <i class="fa fa-calendar"></i><span
                        class="nav-text">Années Académiques</span>
                </a></li>
            @if(Auth()->user()->campus->currentAcademicYear())
            <li><a href="{{route('configuration')}}" aria-expanded="false">
                    <i class="fa fa-cogs"></i><span
                        class="nav-text">Configurations</span>
                </a></li>
            <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i
                        class="fa fa-male"></i><span class="nav-text">Personnel</span></a>
                <ul aria-expanded="false">
                    <li><a href="{{route('professeur')}}">Professeurs</a></li>
                    <li><a href="{{route('surveillant')}}">Surveillants</a></li>
                    <li><a href="{{route('parent')}}">Parents</a></li>
                </ul>
            </li>
            <li><a href="{{route('departement')}}" aria-expanded="false">
                    <i class="fa fa-university"></i><span
                        class="nav-text">Départements</span>
                </a></li>

            <li><a href="{{route('classe')}}" aria-expanded="false">
                    <i class="fa fa-list-alt"></i><span
                        class="nav-text">Classes</span>
                </a></li>
            <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i
                        class="fa fa-graduation-cap"></i><span class="nav-text">Gestion Notes</span></a>
                <ul aria-expanded="false">
                    <li><a href="{{route('niveauetude')}}">Niveaux d'étude</a></li>
                    <li><a href="{{route('filiere')}}">Filières</a></li>
                    <li><a href="{{route('uniteenseignement')}}">U.E</a></li>
                    <li><a href="{{route('note')}}">Notes</a></li>
                    {{-- <li><a href="{{route('matiere')}}">Matières</a>
            </li> --}}
            <li><a href="{{route('parent')}}">Bulletins</a></li>

        </ul>
        </li>
        <li><a href="{{route('cours')}}" aria-expanded="false">
                <i class="fa fa-laptop"></i><span
                    class="nav-text">Cours</span>
            </a></li>
        <li><a href="{{route('emploisdutemps')}}" aria-expanded="false">
                <i class="fa fa-calendar"></i><span
                    class="nav-text">Emplois du temps</span>
            </a></li>
        <li><a href="{{route('etudiant')}}" aria-expanded="false">
                <i class="fa fa-users"></i><span
                    class="nav-text">Etudiants</span>
            </a></li>
        @endif
        <li><a href="{{route('message')}}" aria-expanded="false">
                <i class="fa fa-times-circle text-danger"></i><span
                    class="nav-text">Accès</span>
            </a></li>

        @endif
        @if(Auth()->user()->estSuperAdmin() || Auth()->user()->estAdmin())
        <li><a href="{{route('historique')}}" aria-expanded="false">
                <i class="fa fa-history"></i><span
                    class="nav-text">Historiques</span>
            </a></li>
        @endif
        <li><a href="{{route('message')}}" aria-expanded="false">
                <i class="fa fa-envelope"></i><span
                    class="nav-text">Messages</span>
            </a></li>

        <li class="nav-label">Apps</li>
        <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i
                    class="fa fa-cog"></i><span class="nav-text">Parametres</span></a>
            <ul aria-expanded="false">
                <li><a href="{{route('profil')}}">Profil</a></li>
                <li><a href="{{route('abonnement')}}">Abonnement</a></li>
                <li><a href="./app-calender.html">Général</a></li>
            </ul>
        </li>
        </ul>
    </div>
</div>