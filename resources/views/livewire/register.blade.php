<div class="container h-100 mt-3 authincation-content">
    <div class="row pt-3">
        <h3 class="col-md-10">Enregistrement d'un nouveau campus</h3>
        <div class="col-md-2 text-md-right">
            <a href="{{ route('home')}}" class="btn btn-primary"><i class="fa fa-home"></i> Accueil</a>
        </div>
    </div>
    <form wire:submit='submit'>
        <div class="row justify-content-center h-100 align-items-center">
            <div class="col-md-7">
                <div class="">
                    <div class="row no-gutters">
                        <div class="col-xl-12">
                            <div class="auth-form">
                                <h4 class="text-center mb-4">Les coordonnées de l'administrateur</h4>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label><strong>Prénom</strong></label>
                                            <input type="text" wire:model='prenom' class="form-control @error('prenom') error @enderror" placeholder="Veuillez entrer votre prénom">
                                            @error('prenom') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label><strong>Nom</strong></label>
                                            <input type="text" wire:model='nom' class="form-control @error('nom') error @enderror" placeholder="Veuillez entrer votre nom">
                                            @error('nom') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label><strong>Nom d'utilisateur</strong></label>
                                            <input type="text" wire:model='username' class="form-control @error('username') error @enderror" placeholder="Veuillez entrer votre username">
                                            @error('username') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label><strong>Adresse</strong></label>
                                            <input type="text" wire:model='adresse' class="form-control @error('adresse') error @enderror" placeholder="Veuillez entrer votre adresse">
                                            @error('adresse') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label><strong>Téléphone</strong></label>
                                            <input type="text" wire:model='tel' class="form-control @error('tel') error @enderror" placeholder="Veuillez entrer votre numéro de téléphone">
                                            @error('tel') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label><strong>Sexe</strong></label>
                                            <select class="form-control @error('sexe') error @enderror" wire:model='sexe'>
                                                <option >Veuillez selectionner</option>
                                                <option value="Homme">Homme</option>
                                                <option value="Femme">Femme</option>
                                            </select>
                                            @error('sexe') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label><strong>Email</strong></label>
                                            <input type="email" wire:model='email' class="form-control @error('email') error @enderror" placeholder="Veuillez entrer votre email">
                                            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label><strong>Mot de passe</strong></label>
                                            <input type="password" wire:model='password' class="form-control @error('password') error @enderror" value="Veuillez entrer votre mot de passe">
                                            @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label><strong>Confirmer votre mot de passe</strong></label>
                                            <input type="password" wire:model='password_confirmation' class="form-control @error('password_confirmation') error @enderror" value="Confirmer">
                                            @error('password_confirmation') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5 border-left">
                <div class="">
                    <div class="row no-gutters">
                        <div class="col-xl-12">
                            <div class="auth-form">
                                <h4 class="text-center mb-4">Les informations du campus</h4>
                                <form action="index.html">
                                    <div class="form-group">
                                        <label><strong>Nom du campus</strong></label>
                                        <input type="text" wire:model='nomc'  class="form-control @error('nomc') error @enderror" placeholder="Entrer le nom du campus">
                                        @error('nomc') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label><strong>Email</strong></label>
                                        <input type="email" wire:model='emailc'  class="form-control @error('emailc') error @enderror" placeholder="Entrer l'adresse mail du campus">
                                        @error('emailc') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label><strong>N° téléphone du campus</strong></label>
                                        <input type="text" wire:model='telc'  class="form-control @error('telc') error @enderror" placeholder="Entrer le numéro de téléphone du campus">
                                        @error('telc') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label><strong>L'adresse du campus</strong></label>
                                        <input type="text" wire:model='adressec'  class="form-control @error('adressec') error @enderror" placeholder="Entrer l'adresse du campus">
                                        @error('adressec') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="text-center mt-4">
                                        <button type="submit" class="btn btn-primary btn-block">Créer</button>
                                    </div>
                                    <div class="new-account mt-3">
                                        <p>Vous avez déjà un compte ? <a class="text-primary" href="{{ route('login')}}">Connexion</a></p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>
