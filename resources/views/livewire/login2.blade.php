<div class="authincation h-100">
    <div class="container-fluid h-100 mt-4">
        <div class="row justify-content-center h-100 align-items-center">
            <div class="col-md-6">
                <div class="authincation-content">
                    <div class="row no-gutters">
                        <div class="col-xl-12">
                            <div class="auth-form">
                                <h4 class="text-center mb-4">Connexion</h4>
                                <form wire:submit.prevent='seconnecter'>
                                    <div class="form-group">
                                        <label><strong>Identifiant</strong></label>
                                        <input type="text" wire:model='login' class="form-control @error('login') error @enderror" placeholder="Email, telephone ou nom d'utilisateur">
                                        @error('login') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label><strong>Mot de passe</strong></label>
                                        <input type="password" wire:model='password' class="form-control @error('password') error @enderror" placeholder="Mot de passe">
                                        @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-row d-flex justify-content-between mt-4 mb-2">
                                        <div class="form-group">
                                            <div class="form-check ml-2">
                                                <input class="form-check-input" type="checkbox" id="basic_checkbox_1">
                                                <label class="form-check-label" for="basic_checkbox_1">Remember me</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <a href="page-forgot-password.html">Mot de passe oublié ?</a>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary btn-block">Se connecter</button>
                                    </div>
                                </form>
                                <div class="new-account mt-3">
                                    <p>Pas encore inscrit ? <a class="text-primary" href="{{ route('register') }}">Créer un compte</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>