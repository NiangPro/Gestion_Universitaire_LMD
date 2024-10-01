<div class="container" id="container" wire:ignore>
        
    <div class="form-container sign-up">
        <form>
            <h2>Mot de passe oublié</h2>
            <div class="social-icons">
               <h4>Vous avez oublié votre mot de passe</h4>
            </div>
            <span>pour réinitiliser votre mot de passe, saisissez l'adresse email que vous utilisez pour vous connecter 
                à votre compte. Il peut s'agit de votre adresse Gmail ou d'une autre adresse que vous avez associé à votre compte.
            </span>
            <input type="email" placeholder="Votre adresse email" required>
            <button type="submit">Envoyer</button>
        </form>
    </div>

    <div class="form-container sign-in">
        <form wire:submit.prevent='seconnecter'>
            <h1>Connexion</h1>
            <div class="social-icons">
                <a href="#"><i style="color: blue" class="fa-brands fa-facebook"></i></a>
                <a href="#"><i style="color: red" class="fa-brands fa-google-plus-g"></i></a>
                <a href="#"><i class="fa-brands fa-github"></i></a>
                <a href="#"><i style="color: rgb(34, 151, 246)" class="fa-brands fa-linkedin"></i></a>
            </div>
            <span>Ou</span>
            <input type="text" wire:model='login' name="login" id="login" class="@error('form.login') error @enderror" placeholder="Email, telephone ou nom d'utilisateur" >
            @error('login') <span class="text-danger">{{ $message }}</span> @enderror
            <input type="password" wire:model='password' class="@error('form.password') error @enderror" placeholder="Mot de passe" >
            @error('password') <span class="text-danger">{{ $message }}</span> @enderror
            <p class="hidden" id="register" style="color: rgb(34, 151, 246);cursor: pointer;">Vous avez oublié votre mot de passe</p>
            <button type="submit">Se connecter</button>
            <a href="{{route("home")}}" style="color: rgb(34, 151, 246)">Page d'accueil</a>
        </form>
    </div>

    <div class="toggle-container">
        <div class="toggle">
            <div class="toggle-panel toggle-left">
                <h1>Bienvenue</h1>
                <p>Entrez vos coordonnées pour accéder aux fonctionnalités de ce site</p>
                <a href="{{route('login')}}" style="border: 1px solid #fff;padding:7px;color:white;border-radius:5px;" class="hidden" id="login">Connexion</a>
            </div>
            <div class="toggle-panel toggle-right">
                <h1>Bienvenue</h1>
                <p>Saisissez vos coordonnées et commencez à travailler !</p>
                <a href="{{route('register')}}" style="border: 1px solid #fff; color:white; padding:7px; border-radius:5px;" class="hidden" >Créer votre campus</a>
            </div>
        </div>
    </div>
</div>