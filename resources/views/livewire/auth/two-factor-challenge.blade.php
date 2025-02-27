<div class="authincation mt-5 mb-5 pt-5">
    <div class="container-fluid h-100 mt-4">
        <div class="row justify-content-center h-100 align-items-center">
            <div class="col-md-6">
                <div class="authincation-content">
                    <div class="row no-gutters">
                        <div class="col-xl-12">
                            <div class="auth-form">
                                <h4 class="text-center mb-4">Vérification en Deux Étapes</h4>
                                
                                <form wire:submit.prevent="confirmTwoFactorAuth">
                                    <div x-data="{ recovery: false }">
                                        <div class="mb-4" x-show="! recovery">
                                            <div class="form-group">
                                                <label><strong>Code d'Authentification</strong></label>
                                                <input type="text" 
                                                       wire:model="code" 
                                                       class="form-control @error('code') error @enderror" 
                                                       placeholder="Entrez le code à 6 chiffres"
                                                       autofocus>
                                                @error('code') 
                                                    <span class="text-danger">{{ $message }}</span> 
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="mb-4" x-show="recovery">
                                            <div class="form-group">
                                                <label><strong>Code de Récupération</strong></label>
                                                <input type="text" 
                                                       wire:model="recovery_code" 
                                                       class="form-control @error('recovery_code') error @enderror" 
                                                       placeholder="Entrez un code de récupération"
                                                       autofocus>
                                                @error('recovery_code') 
                                                    <span class="text-danger">{{ $message }}</span> 
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between mt-4">
                                            <button type="button" 
                                                    class="btn btn-link px-0" 
                                                    x-show="! recovery"
                                                    x-on:click="recovery = true">
                                                Utiliser un code de récupération
                                            </button>

                                            <button type="button" 
                                                    class="btn btn-link px-0" 
                                                    x-show="recovery"
                                                    x-on:click="recovery = false">
                                                Utiliser un code d'authentification
                                            </button>
                                        </div>

                                        <div class="mt-4">
                                            <button type="submit" class="btn btn-primary btn-block">
                                                Vérifier
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
