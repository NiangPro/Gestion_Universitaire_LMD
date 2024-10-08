<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="profile">
                <div class="profile-head">
                    <div class="photo-content">
                        <div class="cover-photo"></div>
                        <div class="profile-photo">
                            <img src="{{asset("storage/images/".$image)}}" class="img-fluid rounded-circle" alt="">
                        </div>
                    </div>
                    <div class="profile-info">
                        <div class="row justify-content-center">
                            <div class="col-xl-8">
                                <div class="row ml-4">
                                    <div class="col-xl-5 col-sm-5 border-right-1 prf-col">
                                        <div class="profile-name">
                                            <h3 class="text-warning"><i class="fa fa-paperclip"></i> {{$nom}}</h3>
                                            @if($etat == 1)
                                            <p class="text-success h4"><i class="fa fa-unlock"></i> Ouvert</p>
                                            @else
                                            <p class="text-danger h4"><i class="fa fa-lock"></i> Fermé</p>
                                            @endif
                                        </div>
                                    </div>
                                    {{-- <div class="col-xl-4 col-sm-4 border-right-1 prf-col">
                                        <div class="profile-email">
                                            <h4 class="text-muted">{{$email}}</h4>
                                        </div>
                                    </div> --}}
                                    <div class="col-xl-5 col-sm-5 prf-col">
                                        <div class="profile-call">
                                            <h3 class="text-warning"><i class="fa fa-phone"></i> {{$telephone}}</h3>
                                            <p><i class="fa fa-envelope"></i> {{$email}}</p>
                                            <p><i class="fa fa-location-arrow"></i> {{$adresse}}</p>
                                        </div>
                                    </div> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="profile-statistics">
                        <div class="text-center mt-4 border-bottom-1 pb-3">
                            <div class="row">
                                <div class="col">
                                    <h3 class="m-b-0">{{count($camp->eleves)}}</h3><span>Elèves</span>
                                </div>
                                <div class="col">
                                    <h3 class="m-b-0">{{count($camp->professeurs)}}</h3><span>Professeurs</span>
                                </div>
                                <div class="col">
                                    <h3 class="m-b-0">{{count($camp->parents)}}</h3><span>Parents</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="profile-tab">
                        <div class="custom-tab-1">
                            <ul class="nav nav-tabs">
                                <li class="nav-item"><a href="#my-posts" data-toggle="tab" class="nav-link active show">Les administrateurs</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div id="my-posts" class="tab-pane fade active show">
                                    <div class="my-post-content pt-3">
                                        <div class="profile-uoloaded-post border-bottom-1 pb-5">
                                            <img src="images/profile/8.jpg" alt="" class="img-fluid">
                                            <a class="post-title" href="javascript:void()">
                                                <h4>Collection of textile samples lay spread</h4>
                                            </a>
                                            <p>A wonderful serenity has take possession of my entire soul like these sweet morning of spare which enjoy whole heart.A wonderful serenity has take possession of my entire soul like these sweet morning
                                                of spare which enjoy whole heart.</p>
                                            <button class="btn btn-primary mr-3"><span class="mr-3"><i
                                                        class="fa fa-heart"></i></span>Like</button>
                                            <button class="btn btn-secondary"><span class="mr-3"><i
                                                        class="fa fa-reply"></i></span>Reply</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>