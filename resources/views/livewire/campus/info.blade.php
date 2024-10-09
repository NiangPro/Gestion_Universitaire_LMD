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
                                        <div class="row">
                                            @foreach($camp->admins as $a)
                                                <div class="card mb-3 col-md-6">
                                                    <img class="card-img-top img-fluid" src="{{asset('storage/images/'.$a->image)}}" alt="Card image cap">
                                                    <div class="card-header">
                                                        <h3 class="card-title">{{$a->prenom}} {{$a->nom}}</h3>
                                                    </div>
                                                    <div class="card-body">
                                                        <button class="btn btn-info btn-rounded" data-toggle="modal"
                                                        data-target="#modalId{{$a->id}}"><i class="fa fa-eye"></i></button>
                                                        <!-- Button trigger modal -->
                                                        
                                                        <!-- Modal -->
                                                        <div
                                                            class="modal fade"
                                                            id="modalId{{$a->id}}"
                                                            tabindex="-1"
                                                            role="dialog"
                                                            aria-labelledby="modalTitleId"
                                                            aria-hidden="true"
                                                        >
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="modalTitleId">
                                                                            Information de {{$a->prenom}} {{$a->nom}}
                                                                        </h5>
                                                                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="container-fluid">
                                                                            <div class="profile-personal-info">
                                                                                <div class="row mb-4">
                                                                                    <div class="col-4">
                                                                                        <h5 class="f-w-500">N° téléphone <span class="pull-right">:</span>
                                                                                        </h5>
                                                                                    </div>
                                                                                    <div class="col-8"><span>{{$a->tel}}</span>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row mb-4">
                                                                                    <div class="col-4">
                                                                                        <h5 class="f-w-500">Email <span class="pull-right">:</span>
                                                                                        </h5>
                                                                                    </div>
                                                                                    <div class="col-8"><span>{{$a->email}}</span>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row mb-4">
                                                                                    <div class="col-4">
                                                                                        <h5 class="f-w-500">Adresse <span class="pull-right">:</span></h5>
                                                                                    </div>
                                                                                    <div class="col-8"><span>{{$a->adresse}}</span>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row mb-4">
                                                                                    <div class="col-4">
                                                                                        <h5 class="f-w-500">Username <span class="pull-right">:</span>
                                                                                        </h5>
                                                                                    </div>
                                                                                    <div class="col-8"><span>{{$a->username}}</span>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row mb-4">
                                                                                    <div class="col-4">
                                                                                        <h5 class="f-w-500">Sexe <span class="pull-right">:</span></h5>
                                                                                    </div>
                                                                                    <div class="col-8"><span>{{$a->sexe}}</span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button
                                                                            type="button"
                                                                            class="btn btn-danger"
                                                                            data-dismiss="modal"
                                                                        >
                                                                        <span class="btn-icon-left text-danger"><i class="fa fa-times-circle"></i></span>
                                                                            Fermer
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        
                                                    </div>
                                                </div>
                                            @endforeach
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