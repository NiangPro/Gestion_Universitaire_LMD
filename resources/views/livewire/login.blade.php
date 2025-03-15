<div>
    <div class="container-fluid mt-5">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-lg">
                    <div class="card-body p-5">
                        <h4 class="text-center mb-4">Connexion</h4>
                        
                        <form wire:submit="seconnecter">
                            <div class="mb-3">
                                <label for="login" class="form-label">Identifiant</label>
                                <input type="text" wire:model="login" placeholder="Email, téléphone ou nom d'utilisateur" class="form-control @error('login') is-invalid @enderror" id="login">
                                @error('login')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe</label>
                                <div class="input-group">
                                    <input type="password" wire:model="password" class="form-control @error('password') is-invalid @enderror" id="password">
                                    <!-- <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">
                                        <i class="fa fa-eye"></i>
                                    </button> -->
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" wire:model="remember" class="form-check-input" id="remember">
                                <label class="form-check-label" for="remember">Se souvenir de moi</label>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <span wire:loading.remove wire:target="seconnecter">Se connecter</span>
                                    <span wire:loading wire:target="seconnecter">
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        Connexion...
                                    </span>
                                </button>
                            </div>

                            <div class="mt-3 text-center">
                                <a href="{{ route('password.request') }}" class="text-decoration-none">Mot de passe oublié ?</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const icon = event.currentTarget.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
    @endpush
</div>