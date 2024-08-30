<div class="container" id="container">
        
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
        <form wire:submit.prevent='dashboard'>
            <h1>Connexion</h1>
            <div class="social-icons">
                <a href="#"><i class="fa-brands fa-facebook"></i></a>
                <a href="#"><i class="fa-brands fa-google-plus-g"></i></a>
                <a href="#"><i class="fa-brands fa-github"></i></a>
                <a href="#"><i class="fa-brands fa-linkedin"></i></a>
            </div>
            <span>Ou</span>
            <input type="email" placeholder="Email" >
            <input type="password" placeholder="Mot de passe" >
            <button type="submit">Se connecter</button>
        </form>
    </div>

    <div class="toggle-container">
        <div class="toggle">
            <div class="toggle-panel toggle-left">
                <h1>Bienvenue</h1>
                <p>Entrez vos coordonnées pour accéder aux fonctionnalités de ce site</p>
                <button class="hidden" id="login">Connexion</button>
            </div>
            <div class="toggle-panel toggle-right">
                <h1>Bienvenue</h1>
                <p>Saisissez vos coordonnées et commencez à travailler !</p>
                <button class="hidden" id="register">Mot de passe oublié</button>
            </div>
        </div>
    </div>
</div>