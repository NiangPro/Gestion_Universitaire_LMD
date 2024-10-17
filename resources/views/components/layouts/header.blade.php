<div class="header">
    <div class="header-content">
        <nav class="navbar navbar-expand">
            <div class="collapse navbar-collapse justify-content-between">
                <div class="header-left">
                    <div class="search_bar dropdown">
                        <span class="search_icon p-3 c-pointer" data-toggle="dropdown">
                            <i class="fa fa-lightbulb-o"></i>
                        </span>
                        
                    </div>
                </div>

                <ul class="navbar-nav header-right">
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
                        <a class="nav-link" href="#" role="button" data-toggle="dropdown">
                            <img src="{{asset('images/'.Auth()->user()->image)}}" alt="">{{ucfirst(Auth()->user()->prenom)}} {{ucfirst(Auth()->user()->nom)}} <i class="fa fa-chevron-down"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="./app-profile.html" class="dropdown-item">
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