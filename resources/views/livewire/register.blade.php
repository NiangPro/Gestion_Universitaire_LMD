<form wire:submit.prevent='seconnecter'>
    <div class="container" id="container" wire:ignore>
        <div class="form-container sign-in" style="padding: 20px!important;">
                <h2>Administrateur</h2>
                <p>Les coordonnées de l'administrateur</p>
                <input type="text" wire:model='prenom' name="prenom" id="prenom" class="@error('prenom') error @enderror" placeholder="Entrer votre prénom" >
                @error('prenom') <span class="text-danger">{{ $message }}</span> @enderror
                <input type="text" wire:model='nom' name="nom" id="nom" class="@error('nom') error @enderror" placeholder="Entrer votre nom" >
                @error('nom') <span class="text-danger">{{ $message }}</span> @enderror
                <input type="tel" wire:model='tel'  class="@error('tel') error @enderror" placeholder="Entrer votre numéro de téléphone" >
                @error('tel') <span class="text-danger">{{ $message }}</span> @enderror
                <input type="text" wire:model='adresse' class="@error('adresse') error @enderror" placeholder="Entrer votre adresse" >
                @error('adresse') <span class="text-danger">{{ $message }}</span> @enderror
                <input type="text" wire:model='email' class="@error('email') error @enderror" placeholder="Entrer votre email" >
                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                <input type="password" wire:model='password'  class="@error('password') error @enderror" placeholder="Entrer le mot de passe" >
                @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                <input type="password" wire:model='password_confirmation' class="@error('password_confirmation') error @enderror" placeholder="Confirmer votre mot de passe" >
                @error('password_confirmation') <span class="text-danger">{{ $message }}</span> @enderror
                
        </div>

        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-right">
                    <h2>Création de campus</h2>
                    <p>Les informations du campus</p>
                    <input type="text" wire:model='nomc'  class="@error('nomc') error @enderror" placeholder="Entrer le nom du campus" >
                    @error('nomc') <span class="text-danger">{{ $message }}</span> @enderror
                    <input type="email" wire:model='emailc'  class="@error('emailc') error @enderror" placeholder="Entrer l'adresse mail du campus" >
                    @error('emailc') <span class="text-danger">{{ $message }}</span> @enderror
                    <input type="tel" wire:model='telc' class="@error('telc') error @enderror" placeholder="Entrer le numéro de téléphone du campus" >
                    @error('telc') <span class="text-danger">{{ $message }}</span> @enderror
                    <input type="text" wire:model='adressec' class="@error('adressec') error @enderror" placeholder="Entrer l'adresse du campus" >
                    @error('adressec') <span class="text-danger">{{ $message }}</span> @enderror
                    <div>
                        <button type="submit">Créer</button>
                        <a href="{{route("login")}}" style="border: 1px solid white; color:white; padding:7px; border-radius:5px;" class="hidden" >Connexion</a>
                    </div>
                    <a href="{{route("home")}}" style="color: white; text-decoration:underline;">Page d'accueil</a>
                </div>
            </div>
        </div>
        
    </div>
</form>