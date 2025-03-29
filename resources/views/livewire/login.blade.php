<div class="login-page mt-5">
    <div class="container-fluid">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 col-lg-5">
                <div class="login-card">
                    <div class="text-center mb-4">
                        <img src="{{asset('images/logo.png')}}" alt="Logo" class="login-logo mb-3">
                        <div class="welcome-text">
                            <h4 class="mb-1">Bienvenue</h4>
                            <p class="text-muted">Connectez-vous pour accéder à votre espace</p>
                        </div>
                    </div>

                    <form wire:submit="seconnecter">
                        <div class="form-floating mb-3">
                            <input type="text" 
                                wire:model="login" 
                                class="form-control @error('login') is-invalid @enderror" 
                                id="login" 
                                placeholder=" ">
                            <label for="login">
                                <i class="fas fa-user me-2"></i>Email, téléphone ou nom d'utilisateur
                            </label>
                            @error('login')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-floating mb-3">
                            <input type="password" 
                                wire:model="password" 
                                class="form-control @error('password') is-invalid @enderror" 
                                id="password" 
                                placeholder=" ">
                            <label for="password">
                                <i class="fas fa-lock me-2"></i>Mot de passe
                            </label>
                            <button type="button" class="btn password-toggle" id="togglePasswordBtn">
                                <i class="far fa-eye"></i>
                            </button>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input type="checkbox" wire:model="remember" class="form-check-input" id="remember">
                                <label class="form-check-label" for="remember">Se souvenir de moi</label>
                            </div>
                            <a href="{{ route('forget') }}" class="forgot-password">
                                Mot de passe oublié ?
                            </a>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 login-btn">
                            <span wire:loading.remove wire:target="seconnecter">
                                <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                            </span>
                            <span wire:loading wire:target="seconnecter">
                                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                Connexion en cours...
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
    .login-page {
        background: linear-gradient(135deg, rgba(26, 35, 126, 0.05) 0%, rgba(2, 136, 209, 0.05) 100%);
    }

    .login-card {
        background: white;
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .login-card:hover {
        transform: translateY(-5px);
    }

    .login-logo {
        height: 60px;
        width: auto;
        margin-bottom: 1rem;
    }

    .welcome-text h4 {
        color: #1a237e;
        font-weight: 600;
    }

    .form-floating {
        position: relative;
    }

    .form-control {
        height: 55px;
        border-radius: 10px;
        border: 2px solid #e0e0e0;
        padding-left: 45px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #0288d1;
        box-shadow: 0 0 0 0.2rem rgba(2, 136, 209, 0.25);
    }

    .form-floating label {
        padding-left: 45px;
    }

    .form-floating label i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #757575;
    }

    .password-toggle {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #757575;
        padding: 5px;
        cursor: pointer;
    }

    .password-toggle:hover {
        color: #0288d1;
    }

    .form-check-input:checked {
        background-color: #0288d1;
        border-color: #0288d1;
    }

    .forgot-password {
        color: #0288d1;
        text-decoration: none;
        font-size: 0.9rem;
        transition: color 0.3s ease;
    }

    .forgot-password:hover {
        color: #1a237e;
        text-decoration: underline;
    }

    .login-btn {
        height: 50px;
        border-radius: 10px;
        background: linear-gradient(135deg, #1a237e, #0288d1);
        border: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .login-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(2, 136, 209, 0.3);
    }

    .invalid-feedback {
        font-size: 0.85rem;
        margin-top: 0.5rem;
    }

    @media (max-width: 768px) {
        .login-card {
            padding: 2rem;
            margin: 1rem;
        }
    }
    </style>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('togglePasswordBtn');
            const passwordInput = document.getElementById('password');
            
            toggleBtn.addEventListener('click', function() {
                const icon = this.querySelector('i');
                
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });
    </script>
    @endpush
</div>