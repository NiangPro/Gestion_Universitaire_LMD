<div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Configuration de la Double Authentification</h4>
                    </div>
                    <div class="card-body">
                        @if(!auth()->user()->two_factor_secret)
                            {{-- Activer 2FA --}}
                            <form wire:submit.prevent="enableTwoFactorAuth">
                                <div class="mb-4">
                                    <p>La double authentification n'est pas activée. Activez-la pour plus de sécurité.</p>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    Activer la Double Authentification
                                </button>
                            </form>
                        @else
                            {{-- QR Code et Codes de récupération --}}
                            <div class="mb-4">
                                <p>Scannez le QR code suivant avec votre application d'authentification (Google Authenticator, Authy, etc.)</p>
                                
                                @if($qrCodeUrl)
                                    <div class="mb-4">
                                        <img src="data:image/svg+xml;base64,{{ base64_encode(QrCode::size(200)->generate($qrCodeUrl)) }}" alt="QR Code">
                                    </div>
                                @endif

                                <div class="mb-4">
                                    <p><strong>Codes de récupération</strong></p>
                                    <p class="text-muted">Conservez ces codes dans un endroit sûr. Ils vous permettront de récupérer l'accès à votre compte si vous perdez l'accès à votre application d'authentification.</p>
                                    
                                    @foreach($recoveryCodes as $code)
                                        <div class="mb-1">
                                            <code>{{ $code }}</code>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- État de la confirmation --}}
                                @if(!auth()->user()->two_factor_confirmed_at)
                                    <div class="alert alert-warning">
                                        La double authentification n'est pas encore confirmée. Veuillez scanner le QR code et entrer un code de vérification lors de votre prochaine connexion.
                                    </div>
                                @else
                                    <div class="alert alert-success">
                                        La double authentification est active et confirmée.
                                    </div>
                                @endif

                                {{-- Désactiver 2FA --}}
                                <form wire:submit.prevent="disableTwoFactorAuth" class="mt-4">
                                    <button type="submit" class="btn btn-danger">
                                        Désactiver la Double Authentification
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
