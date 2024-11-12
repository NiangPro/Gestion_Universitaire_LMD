<div>
   <div class="row">
        <div class="col-lg-12">
            <div class="profile">
                <div class="profile-head">
                    <div class="photo-content">
                        <div class="cover-photo" style="background: url('{{ asset('storage/images/amphie.jpg') }}');
                            background-size: cover; 
                            background-position: center; 
                            min-height: 250px;
                            width: 100%;">
                        </div>
                        <div class="profile-photo">
                            @if ($profil)
                                <img src="{{$profil->temporaryUrl()}}" class="img-fluid rounded-circle border border-primary" alt="Image profil" style="height:150px">
                            @else
                                <img src="{{asset('storage/images/'.$user->image)}}" class="img-fluid rounded-circle border border-primary" alt="Image profil" style="height:150px">
                            @endif
                        </div>
                    </div>
                    <div class="profile-info">
                        <div class="row justify-content-center">
                            <div class="col-xl-8">
                                <div class="row">
                                    <div class="col-xl-4 col-sm-4 border-right-1 prf-col">
                                        <div class="profile-name">
                                            <h4 class="text-primary">{{$user->prenom}} {{$user->nom}}</h4>
                                            <p>Nom</p>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-sm-4 border-right-1 prf-col">
                                        <div class="profile-email">
                                            <h4 class="text-muted">{{$user->email}}</h4>
                                            <p>Email</p>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-sm-4 prf-col">
                                        <div class="profile-call">
                                            <h4 class="text-muted">{{$user->tel}}</h4>
                                            <p>Téléphone</p>
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
                    {{-- <div class="profile-statistics">
                        <div class="text-center mt-4 border-bottom-1 pb-3">
                            <div class="row">
                                <div class="col">
                                    <h3 class="m-b-0">150</h3><span>Follower</span>
                                </div>
                                <div class="col">
                                    <h3 class="m-b-0">140</h3><span>Place Stay</span>
                                </div>
                                <div class="col">
                                    <h3 class="m-b-0">45</h3><span>Reviews</span>
                                </div>
                            </div>
                            <div class="mt-4"><a href="javascript:void()" class="btn btn-primary pl-5 pr-5 mr-3 mb-4">Follow</a> <a href="javascript:void()" class="btn btn-dark pl-5 pr-5 mb-4">Send
                                    Message</a>
                            </div>
                        </div>
                    </div> --}}
                    <div class="profile-blog pt-3 border-bottom-1 pb-1">
                        <h5 class="text-primary d-inline">Changement d'image de profil</h5>
                        <div class="input-group mb-3 mt-2">
                            <div class="custom-file">
                            <input type="file" class="custom-file-input" wire:model="profil" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01">
                            <label class="custom-file-label" for="inputGroupFile01">Choisir</label>
                            <div wire:loading wire:target="image">Chargement...</div>
                            </div>
                        </div>
                        <button class="btn btn-icon icon-left btn-success m-auto" wire:click.prevent="editProfil">Changer</button>

                        {{-- <h5 class="text-primary d-inline">Today Highlights</h5><a href="javascript:void()" class="pull-right f-s-16">More</a>
                        <img src="images/profile/1.jpg" alt="" class="img-fluid mt-4 mb-4 w-100">
                        <h4>Darwin Creative Agency Theme</h4>
                        <p>A small river named Duden flows by their place and supplies it with the necessary regelialia. It is a paradisematic country, in which roasted parts of sentences fly into your mouth.</p> --}}
                    </div>
                    {{-- <div class="profile-interest mt-4 pb-2 border-bottom-1">
                        <h5 class="text-primary d-inline">Interest</h5>
                        <div class="row mt-4">
                            <div class="col-lg-4 col-xl-4 col-sm-4 col-6 int-col">
                                <a href="javascript:void()" class="interest-cat">
                                    <img src="images/profile/2.jpg" alt="" class="img-fluid">
                                    <p>Shopping Mall</p>
                                </a>
                            </div>
                            <div class="col-lg-4 col-xl-4 col-sm-4 col-6 int-col">
                                <a href="javascript:void()" class="interest-cat">
                                    <img src="images/profile/3.jpg" alt="" class="img-fluid">
                                    <p>Photography</p>
                                </a>
                            </div>
                            <div class="col-lg-4 col-xl-4 col-sm-4 col-6 int-col">
                                <a href="javascript:void()" class="interest-cat">
                                    <img src="images/profile/4.jpg" alt="" class="img-fluid">
                                    <p>Art &amp; Gallery</p>
                                </a>
                            </div>
                            <div class="col-lg-4 col-xl-4 col-sm-4 col-6 int-col">
                                <a href="javascript:void()" class="interest-cat">
                                    <img src="images/profile/2.jpg" alt="" class="img-fluid">
                                    <p>Visiting Place</p>
                                </a>
                            </div>
                            <div class="col-lg-4 col-xl-4 col-sm-4 col-6 int-col">
                                <a href="javascript:void()" class="interest-cat">
                                    <img src="images/profile/3.jpg" alt="" class="img-fluid">
                                    <p>Shopping</p>
                                </a>
                            </div>
                            <div class="col-lg-4 col-xl-4 col-sm-4 col-6 int-col">
                                <a href="javascript:void()" class="interest-cat">
                                    <img src="images/profile/4.jpg" alt="" class="img-fluid">
                                    <p>Biking</p>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="profile-news mt-4 pb-3">
                        <h5 class="text-primary d-inline">Our Latest News</h5>
                        <div class="media pt-3 pb-3">
                            <img src="images/profile/5.jpg" alt="image" class="mr-3">
                            <div class="media-body">
                                <h5 class="m-b-5">John Tomas</h5>
                                <p>I shared this on my fb wall a few months back, and I thought I'd share it here again because it's such a great read</p>
                            </div>
                        </div>
                        <div class="media pt-3 pb-3">
                            <img src="images/profile/6.jpg" alt="image" class="mr-3">
                            <div class="media-body">
                                <h5 class="m-b-5">John Tomas</h5>
                                <p>I shared this on my fb wall a few months back, and I thought I'd share it here again because it's such a great read</p>
                            </div>
                        </div>
                        <div class="media pt-3 pb-3">
                            <img src="images/profile/7.jpg" alt="image" class="mr-3">
                            <div class="media-body">
                                <h5 class="m-b-5">John Tomas</h5>
                                <p>I shared this on my fb wall a few months back, and I thought I'd share it here again because it's such a great read</p>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="profile-tab">
                        <div class="custom-tab-1">
                            <ul class="nav nav-tabs">
                                {{-- <li class="nav-item"><a href="#my-posts" data-toggle="tab" class="nav-link active show">Posts</a>
                                </li> --}}
                                <li class="nav-item"><a href="#about-me" data-toggle="tab" class="nav-link active show">Sur moi</a>
                                </li>
                                <li class="nav-item"><a href="#profile-settings" data-toggle="tab" class="nav-link">Paramétrage</a>
                                </li>
                                <li class="nav-item"><a href="#password-settings" data-toggle="tab" class="nav-link">Mot de passe</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                {{-- <div id="my-posts" class="tab-pane fade active show">
                                    <div class="my-post-content pt-3">
                                        <div class="post-input">
                                            <textarea name="textarea" id="textarea" cols="30" rows="5" class="form-control bg-transparent" placeholder="Please type what you want...."></textarea> <a href="javascript:void()"><i class="ti-clip"></i> </a>
                                            <a
                                                href="javascript:void()"><i class="ti-camera"></i> </a><a href="javascript:void()" class="btn btn-primary">Post</a>
                                        </div>
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
                                        <div class="profile-uoloaded-post border-bottom-1 pb-5">
                                            <img src="images/profile/9.jpg" alt="" class="img-fluid">
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
                                        <div class="profile-uoloaded-post pb-5">
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
                                        <div class="text-center mb-2"><a href="javascript:void()" class="btn btn-primary">Load More</a>
                                        </div>
                                    </div>
                                </div> --}}
                                <div id="about-me" class="tab-pane fade active show">
                                    <div class="profile-personal-info mt-5">
                                        <h4 class="text-primary mb-4">Renseignements personnels</h4>
                                        <div class="row mb-4">
                                            <div class="col-3">
                                                <h5 class="f-w-500">Nom <span class="pull-right">:</span>
                                                </h5>
                                            </div>
                                            <div class="col-9"><span>{{$user->prenom}} {{$user->nom}}</span>
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <div class="col-3">
                                                <h5 class="f-w-500">Email <span class="pull-right">:</span>
                                                </h5>
                                            </div>
                                            <div class="col-9"><span>{{$user->email}}</span>
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <div class="col-3">
                                                <h5 class="f-w-500">Téléphone <span class="pull-right">:</span></h5>
                                            </div>
                                            <div class="col-9"><span>{{$user->tel}}</span>
                                            </div>
                                        </div>
                                        {{-- <div class="row mb-4">
                                            <div class="col-3">
                                                <h5 class="f-w-500">Date de naissance <span class="pull-right">:</span>
                                                </h5>
                                            </div>
                                            <div class="col-9"><span>27</span>
                                            </div>
                                        </div> --}}
                                        <div class="row mb-4">
                                            <div class="col-3">
                                                <h5 class="f-w-500">Adresse <span class="pull-right">:</span></h5>
                                            </div>
                                            <div class="col-9"><span>{{$user->adresse}}</span>
                                            </div>
                                        </div>
                                        {{-- <div class="row mb-4">
                                            <div class="col-3">
                                                <h5 class="f-w-500">Année d'expérience <span class="pull-right">:</span></h5>
                                            </div>
                                            <div class="col-9"><span>07 Year Experiences</span>
                                            </div>
                                        </div> --}}
                                    </div>
                                </div>
                                <div id="profile-settings" class="tab-pane fade">
                                    <div class="pt-3">
                                        <div class="settings-form">
                                            <h4 class="text-primary">Paramétrage du compte</h4>
                                            <form wire:submit='store'>
                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label>Prenom</label>
                                                        <input type="text" wire:model='prenom' placeholder="Prénom" class="form-control @error('prenom') error @enderror">
                                                        @error('prenom') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label>Nom</label>
                                                        <input type="text" wire:model='nom' placeholder="Nom" class="form-control @error('nom') error @enderror">
                                                        @error('nom') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label>Non d'utilisateur</label>
                                                        <input type="text" wire:model='username' placeholder="Nom d'utilisateur" class="form-control @error('username') error @enderror">
                                                        @error('username') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label>Sexe</label>
                                                        <select class="form-control @error('sexe') error @enderror" wire:model='sexe'>
                                                            <option>Veuillez selectionner</option>
                                                            <option value="Homme">Homme</option>
                                                            <option value="Femme">Femme</option>
                                                        </select>
                                                        @error('sexe') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label>Téléphone</label>
                                                        <input type="tel" wire:model='tel' placeholder="Téléphone" class="form-control @error('tel') error @enderror ">
                                                        @error('tel') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label>Email</label>
                                                        <input type="email" wire:model='email' placeholder="Email" class="form-control @error('email') error @enderror">
                                                        @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Addresse</label>
                                                    <input type="text" wire:model='adresse' placeholder="Adresse" class="form-control @error('adresse') error @enderror">
                                                    @error('adresse') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                                {{-- <div class="form-group col-md-6">
                                                    <label>Adresse</label>
                                                    <input type="text" wire:model='adresse' placeholder="Adresse" class="form-control @error('adresse') error @enderror">
                                                    @error('adresse') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label>Address 2</label>
                                                    <input type="text" placeholder="Apartment, studio, or floor" class="form-control">
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label>City</label>
                                                        <input type="text" class="form-control">
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label>State</label>
                                                        <select class="form-control" id="inputState">
                                                            <option selected="">Choose...</option>
                                                            <option>Option 1</option>
                                                            <option>Option 2</option>
                                                            <option>Option 3</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-2">
                                                        <label>Zip</label>
                                                        <input type="text" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="gridCheck">
                                                        <label for="gridCheck" class="form-check-label">Check me out</label>
                                                    </div>
                                                </div> --}}
                                                <button class="btn btn-primary" type="submit">Modifier</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div id="password-settings" class="tab-pane fade">
                                    <div class="pt-3">
                                        <div class="settings-form">
                                            <h4 class="text-primary">Changement du mot de passe</h4>
                                            <form wire:submit.prevent="editPassword">
                                                <div class="form-row">
                                                    <div class="form-group col-md-8">
                                                        <label>Actuel</label>
                                                        <input type="password" wire:model='current_password' placeholder="Le mot de passe actuel" class="form-control @error('current_password') error @enderror">
                                                        @error('current_password') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>
                                                    <div class="form-group col-md-8">
                                                        <label>Nouveau</label>
                                                        <input type="password" wire:model='password' placeholder="Nouveau mot de passe" class="form-control @error('password') error @enderror">
                                                        @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>
                                                    <div class="form-group col-md-8">
                                                        <label>Confirmation</label>
                                                        <input type="password" wire:model='password_confirmation' placeholder="Mot de passe de confirmation" class="form-control @error('password_confirmation') error @enderror">
                                                        @error('password_confirmation') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                                <button class="btn btn-primary" type="submit">Modifier</button>
                                            </form>
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

@section('script')
    <script>
    window.addEventListener('updateSuccessful', event =>{
        iziToast.success({
        title: 'Profil',
        message: 'Mis à jour avec succes',
        position: 'topRight'
        });
    });

    window.addEventListener('profilEditSuccessful', event =>{
        iziToast.success({
        title: 'Image',
        message: 'Mis à jour avec succes',
        position: 'topRight'
        });
    });

     window.addEventListener('passwordNotFound', event =>{
        iziToast.error({
        title: 'Mot de passe actuel',
        message: 'Verifiez le mot de passe actuel',
        position: 'topRight'
        });
    });

    window.addEventListener('passwordEditSuccessful', event =>{
        iziToast.success({
        title: 'Mot de passe',
        message: 'Mis à jour avec succes',
        position: 'topRight'
        });
    });
    </script>
@endsection