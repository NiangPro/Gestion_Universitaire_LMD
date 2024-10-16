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
            @endif
            @if(Auth()->user()->estAdmin())
            <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i
                class="fa fa-male"></i><span class="nav-text">Personnel</span></a>
                <ul aria-expanded="false">
                    <li><a href="{{route('professeur')}}">Professeurs</a></li>
                    <li><a href="{{route('surveillant')}}">Surveillants</a></li>
                    <li><a href="{{route('etudiant')}}">Etudiants</a></li>
                    <li><a href="{{route('parent')}}">Parents</a></li>
                    
                </ul>
            </li>
            <li><a href="{{route('academicyear')}}" aria-expanded="false">
                <i class="fa fa-calendar"></i><span
                class="nav-text">Années Académiques</span>
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
                    <li><a href="./app-profile.html">Profile</a></li>
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">Email</a>
                        <ul aria-expanded="false">
                            <li><a href="./email-compose.html">Compose</a></li>
                            <li><a href="./email-inbox.html">Inbox</a></li>
                            <li><a href="./email-read.html">Read</a></li>
                        </ul>
                    </li>
                    <li><a href="./app-calender.html">Calendar</a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>