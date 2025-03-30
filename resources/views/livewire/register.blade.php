<div class="register-page py-4 mt-3">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-11">
                <div class="register-card">
                    <!-- En-tête -->
                    <div class="text-center mb-4">
                        <img src="{{asset('images/logo.png')}}" alt="Logo" class="register-logo mb-3">
                        <h4 class="gradient-text">Créer un nouveau campus</h4>
                        <p class="text-muted">Remplissez le formulaire ci-dessous pour commencer</p>
                    </div>

                    <form wire:submit='submit'>
                        <div class="row g-4">
                            <!-- Section Admin -->
                            <div class="col-md-7">
                                <div class="section-card h-100">
                                    <h5 class="section-title">
                                        <i class="fas fa-user-shield me-2"></i>
                                        Informations de l'administrateur
                                    </h5>
                                    
                                    <div class="row g-3">
                                        <!-- Nom et Prénom -->
                                        <div class="col-sm-6">
                                            <div class="form-floating">
                                                <input type="text" wire:model='prenom' class="form-control @error('prenom') is-invalid @enderror" id="prenom" placeholder=" ">
                                                <label for="prenom"><i class="fas fa-user me-2"></i>Prénom</label>
                                                @error('prenom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-floating">
                                                <input type="text" wire:model='nom' class="form-control @error('nom') is-invalid @enderror" id="nom" placeholder=" ">
                                                <label for="nom"><i class="fas fa-user me-2"></i>Nom</label>
                                                @error('nom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>
                                        </div>

                                        <!-- Username -->
                                        <div class="col-12">
                                            <div class="form-floating">
                                                <input type="text" wire:model='username' class="form-control @error('username') is-invalid @enderror" id="username" placeholder=" ">
                                                <label for="username"><i class="fas fa-at me-2"></i>Nom d'utilisateur</label>
                                                @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>
                                        </div>

                                        <!-- Contact -->
                                        <div class="col-sm-6">
                                            <div class="form-floating">
                                                <input type="tel" wire:model='tel' class="form-control @error('tel') is-invalid @enderror" id="tel" placeholder=" ">
                                                <label for="tel"><i class="fas fa-phone me-2"></i>Téléphone</label>
                                                @error('tel') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-floating">
                                                <select class="form-select @error('sexe') is-invalid @enderror" wire:model='sexe' id="sexe">
                                                    <option value="">Sélectionner</option>
                                                    <option value="Homme">Homme</option>
                                                    <option value="Femme">Femme</option>
                                                </select>
                                                <label for="sexe"><i class="fas fa-venus-mars me-2"></i>Sexe</label>
                                                @error('sexe') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>
                                        </div>

                                        <!-- Email et Adresse -->
                                        <div class="col-12">
                                            <div class="form-floating">
                                                <input type="email" wire:model='email' class="form-control @error('email') is-invalid @enderror" id="email" placeholder=" ">
                                                <label for="email"><i class="fas fa-envelope me-2"></i>Email</label>
                                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-floating">
                                                <input type="text" wire:model='adresse' class="form-control @error('adresse') is-invalid @enderror" id="adresse" placeholder=" ">
                                                <label for="adresse"><i class="fas fa-map-marker-alt me-2"></i>Adresse</label>
                                                @error('adresse') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>
                                        </div>

                                        <!-- Mot de passe -->
                                        <div class="col-12">
                                            <div class="form-floating password-field">
                                                <input type="{{ $showPassword ? 'text' : 'password' }}" wire:model='password' class="form-control @error('password') is-invalid @enderror" id="password" placeholder=" ">
                                                <label for="password"><i class="fas fa-lock me-2"></i>Mot de passe</label>
                                                <button type="button" class="btn password-toggle" wire:click="togglePassword">
                                                    <i class="far fa-{{ $showPassword ? 'eye-slash' : 'eye' }}"></i>
                                                </button>
                                                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-floating password-field">
                                                <input type="{{ $showPassword ? 'text' : 'password' }}" wire:model='password_confirmation' class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" placeholder=" ">
                                                <label for="password_confirmation"><i class="fas fa-lock me-2"></i>Confirmer</label>
                                                <button type="button" class="btn password-toggle" wire:click="togglePassword">
                                                    <i class="far fa-{{ $showPassword ? 'eye-slash' : 'eye' }}"></i>
                                                </button>
                                                @error('password_confirmation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section Campus -->
                            <div class="col-md-5">
                                <div class="section-card h-100">
                                    <h5 class="section-title">
                                        <i class="fas fa-university me-2"></i>
                                        Informations du campus
                                    </h5>
                                    
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <div class="form-floating">
                                                <input type="text" wire:model='nomc' class="form-control @error('nomc') is-invalid @enderror" id="nomc" placeholder=" ">
                                                <label for="nomc"><i class="fas fa-building me-2"></i>Nom du campus</label>
                                                @error('nomc') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-floating">
                                                <input type="email" wire:model='emailc' class="form-control @error('emailc') is-invalid @enderror" id="emailc" placeholder=" ">
                                                <label for="emailc"><i class="fas fa-envelope me-2"></i>Email du campus</label>
                                                @error('emailc') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-floating">
                                                <input type="tel" wire:model='telc' class="form-control @error('telc') is-invalid @enderror" id="telc" placeholder=" ">
                                                <label for="telc"><i class="fas fa-phone me-2"></i>Téléphone du campus</label>
                                                @error('telc') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-floating">
                                                <input type="text" wire:model='adressec' class="form-control @error('adressec') is-invalid @enderror" id="adressec" placeholder=" ">
                                                <label for="adressec"><i class="fas fa-map-marker-alt me-2"></i>Adresse du campus</label>
                                                @error('adressec') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <button type="submit" class="btn btn-primary w-100 register-btn">
                                            <i class="fas fa-plus-circle me-2"></i>Créer le campus
                                        </button>
                                        
                                        <div class="text-center mt-3">
                                            <p class="mb-0">Vous avez déjà un compte ? <br>
                                                <a href="{{ route('login')}}" class="text-primary text-decoration-none">
                                                    Connectez-vous
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                               
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@section("css")
<style>
.register-page {
    background: linear-gradient(135deg, rgba(26, 35, 126, 0.05) 0%, rgba(2, 136, 209, 0.05) 100%);
    min-height: 100vh;
}

.register-logo {
    height: 50px;
    width: auto;
}

.register-card {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.section-card {
    background: rgba(255, 255, 255, 0.5);
    border-radius: 15px;
    padding: 1.5rem;
    border: 1px solid rgba(0, 0, 0, 0.1);
}

.section-title {
    color: #FF8A00;
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid rgba(2, 136, 209, 0.1);
}

.gradient-text {
    background: linear-gradient(135deg, #FF8A00, #0288d1);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.form-floating {
    margin-bottom: 0;
}

.form-control, .form-select {
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    height: 48px;
    padding-left: 1rem;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #0288d1;
    box-shadow: 0 0 0 0.2rem rgba(2, 136, 209, 0.25);
    outline: none;
}

.password-field {
    position: relative;
}

.password-toggle {
    position: absolute;
    right: 10px;
    top: 8px;
    height: 30px;
    width: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: none !important;
    border: none !important;
    color: #757575 !important;
    padding: 0 !important;
    cursor: pointer;
    z-index: 5;
}

.register-btn {
    background: linear-gradient(135deg, #FF8A00, #0288d1);
    border: none;
    font-weight: 500;
    padding: 0.75rem;
    transition: all 0.3s ease;
}

.register-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(2, 136, 209, 0.3);
}

.invalid-feedback {
    font-size: 0.75rem;
    margin-top: 0.25rem;
}

@media (max-width: 768px) {
    .register-card {
        padding: 1.25rem;
        margin: 0.5rem;
    }

    .section-card {
        padding: 1.25rem;
        margin-bottom: 1.5rem;
    }

    .form-control, .form-select {
        height: 45px;
    }
}
</style>
@endsection
