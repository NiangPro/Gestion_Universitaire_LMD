<div class="quixnav">
    <div class="quixnav-scroll">
        <ul class="metismenu" id="menu">
            {{-- <li class="nav-label first">Main Menu</li> --}}
            <!-- <li><a href="index.html"><i class="icon icon-single-04"></i><span class="nav-text">Dashboard</span></a>
            </li> -->
            <li>
                <a href="{{route('dashboard')}}" aria-expanded="false">
                    <i class="fa fa-dashboard"></i>
                    <span class="nav-text">Tableau de bord</span>
                </a>
            </li>

            @if(Auth()->user()->estSuperAdmin())
            <!-- <li class="nav-label text-white">Gestion Etablissements</li> -->
            <li>
                <a href="{{route('pack')}}" aria-expanded="false">
                    <i class="fa fa-server"></i>
                    <span class="nav-text">Packs</span>
                </a>
            </li>
            <li>
                <a href="{{route('etablissement')}}" aria-expanded="false">
                    <i class="fa fa-institution"></i>
                    <span class="nav-text">Campus</span>
                </a>
            </li>
            <li>
                <a href="{{route('activation')}}" aria-expanded="false">
                    <i class="fa fa-key"></i>
                    <span class="nav-text">Activations</span>
                </a>
            </li>
            <li>
                <a href="{{route('corbeille')}}" aria-expanded="false">
                    <i class="fa fa-recycle"></i>
                    <span class="nav-text">Corbeille</span>
                </a>
            </li>
            @endif

            @if(Auth()->user()->estAdmin())
                @if(Auth::user()->hasPermission('academic_years', 'view'))
                <li>
                    <a href="{{route('academicyear')}}" aria-expanded="false">
                        <i class="fa fa-calendar"></i>
                        <span class="nav-text">Années Académiques</span>
                    </a>
                </li>
                @endif

                @if(Auth()->user()->campus->currentAcademicYear())
                    @if(Auth::user()->hasPermission('configuration', 'view'))
                    <li>
                        <a href="{{route('configuration')}}" aria-expanded="false">
                            <i class="fa fa-cogs"></i>
                            <span class="nav-text">Configurations</span>
                        </a>
                    </li>
                    @endif

                    @if(Auth::user()->hasPermission('personnel', 'view'))
                    <li>
                        <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                            <i class="fa fa-male"></i>
                            <span class="nav-text">Personnel</span>
                        </a>
                        <ul aria-expanded="false">
                            @if(Auth::user()->hasPermission('professeurs', 'view'))
                            <li><a href="{{route('professeur')}}">Professeurs</a></li>
                            @endif
                            @if(Auth::user()->hasPermission('surveillants', 'view'))
                            <li><a href="{{route('surveillant')}}">Surveillants</a></li>
                            @endif
                            @if(Auth::user()->hasPermission('parents', 'view'))
                            <li><a href="{{route('parent')}}">Parents</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    <li>
                        <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                            <i class="fa fa-sitemap"></i>
                            <span class="nav-text">Org. Académique</span>
                        </a>
                        <ul aria-expanded="false">
                            @if(Auth::user()->hasPermission('departements', 'view'))
                            <li><a href="{{route('departement')}}">Départements</a></li>
                            @endif
                            @if(Auth::user()->hasPermission('filieres', 'view'))
                            <li><a href="{{route('filiere')}}">Filières</a></li>
                            @endif
                            @if(Auth::user()->hasPermission('ue', 'view'))
                            <li><a href="{{route('uniteenseignement')}}">U.E</a></li>
                            @endif
                            @if(Auth::user()->hasPermission('classes', 'view'))
                            <li><a href="{{route('classe')}}">Classes</a></li>
                            @endif
                        </ul>
                    </li>

                    <li>
                        <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                            <i class="fa fa-chalkboard-teacher"></i>
                            <span class="nav-text">Ens. & Suivi</span>
                        </a>
                        <ul aria-expanded="false">
                            @if(Auth::user()->hasPermission('cours', 'view'))
                            <li><a href="{{route('cours')}}">Cours</a></li>
                            @endif
                            @if(Auth::user()->hasPermission('notes', 'view'))
                            <li><a href="{{route('note')}}">Notes</a></li>
                            @endif
                            @if(Auth::user()->hasPermission('evaluations', 'view'))
                            <li><a href="{{route('evaluation')}}">Evaluations</a></li>
                            @endif
                            @if(Auth::user()->hasPermission('absences', 'view'))
                            <li><a href="{{route('absence')}}">Absences</a></li>
                            @endif
                            @if(Auth::user()->hasPermission('retards', 'view'))
                            <li><a href="{{route('retard')}}">Retards</a></li>
                            @endif
                            @if(Auth::user()->hasPermission('emploisdutemps', 'view'))
                            <li><a href="{{route('emploisdutemps')}}">Emplois du temps</a></li>
                            @endif
                        </ul>
                    </li>

                    @if(Auth::user()->hasPermission('etudiants', 'view'))
                    <li>
                        <a href="{{route('etudiant')}}" aria-expanded="false">
                            <i class="fa fa-users"></i>
                            <span class="nav-text">Etudiants</span>
                        </a>
                    </li>
                    @endif

                    @if(Auth::user()->hasPermission('comptabilite', 'view'))
                    <li>
                        <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                            <i class="fa fa-money-bills"></i>
                            <span class="nav-text">Comptabilité</span>
                        </a>
                        <ul aria-expanded="false">
                            @if(Auth::user()->hasPermission('paiements', 'view'))
                            <li><a href="{{route('paiement')}}">Paiements</a></li>
                            @endif
                            @if(Auth::user()->hasPermission('rapports', 'view'))
                            <li><a href="{{route('rapport')}}">Rapports</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif
                @endif

                <li>
                    <a href="{{route('acces')}}" aria-expanded="false">
                        <i class="fa fa-key text-danger"></i>
                        <span class="nav-text">Permissions</span>
                    </a>
                </li>
            @endif

            @if(Auth()->user()->estProfesseur())
                @if(Auth::user()->hasPermission('notes', 'view'))
                <li>
                    <a href="{{route('noteprofesseur')}}" aria-expanded="false">
                        <i class="fa fa-file"></i>
                        <span class="nav-text">Notes</span>
                    </a>
                </li>
                @endif
                @if(Auth::user()->hasPermission('absences', 'view'))
                <li>
                    <a href="{{route('absenceprofesseur')}}" aria-expanded="false">
                        <i class="fa fa-calendar-times"></i>
                        <span class="nav-text">Absences</span>
                    </a>
                </li>
                @endif
                @if(Auth::user()->hasPermission('retards', 'view'))
                <li>
                    <a href="{{route('retardprofesseur')}}" aria-expanded="false">
                        <i class="fa fa-clock"></i>
                        <span class="nav-text">Retards</span>
                    </a>
                </li>
                @endif
            @endif
            @if(Auth()->user()->estEtudiant())
                @if(Auth::user()->hasPermission('notes', 'view'))
                <li>
                    <a href="{{route('noteetudiant')}}" aria-expanded="false">
                        <i class="fa fa-file"></i>
                        <span class="nav-text">Notes</span>
                    </a>
                </li>
                @endif
                @if(Auth::user()->hasPermission('absences', 'view'))
                <li>
                    <a href="{{route('absence')}}" aria-expanded="false">
                        <i class="fa fa-calendar-times"></i>
                        <span class="nav-text">Absences</span>
                    </a>
                </li>
                @endif
                @if(Auth::user()->hasPermission('retards', 'view'))
                <li>
                    <a href="{{route('retard')}}" aria-expanded="false">
                        <i class="fa fa-clock"></i>
                        <span class="nav-text">Retards</span>
                    </a>
                </li>
                @endif
            @endif

            @if(Auth()->user()->estSuperAdmin())
                <li>
                    <a href="{{route('historique')}}" aria-expanded="false">
                        <i class="fa fa-history"></i>
                        <span class="nav-text">Historique Système</span>
                        <!-- <span class="badge badge-info">Abonnements & Campus</span> -->
                    </a>
                </li>
            @elseif(Auth()->user()->role === 'admin')
                <li>
                    <a href="{{route('historique')}}" aria-expanded="false">
                        <i class="fa fa-history"></i>
                        <span class="nav-text">Historique Campus</span>
                        <!-- <span class="badge badge-primary">{{ Auth()->user()->campus->nom }}</span> -->
                    </a>
                </li>
            @endif

            @if(Auth::user()->hasPermission('messages', 'view'))
            <li>
                <a href="{{route('message')}}" aria-expanded="false">
                    <i class="fa fa-envelope"></i>
                    <span class="nav-text">Messages</span>
                </a>
            </li>
            @endif

            <!-- <li class="nav-label">Apps</li> -->
            <li>
                <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                    <i class="fa fa-cog"></i>
                    <span class="nav-text">Parametres</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{route('profil')}}">Profil</a></li>
                    <li><a href="{{route('abonnement')}}">Abonnement</a></li>
                    <li><a href="./app-calender.html">Général</a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>