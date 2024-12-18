<div class="authincation h-100 mt-5 pt-5 mb-5">
    <div class="container-fluid h-100">
        <div class="row justify-content-center h-100 align-items-center">
            <div class="col-md-6">
                <div class="authincation-content">
                    <div class="row no-gutters">
                        <div class="col-xl-12">
                            <div class="auth-form">
                            @if(!$trouve)
                                @if(!$trouveCode)
                                    <h4 class="text-center mb-4">Vos informations pour recevoir un code par email</h4>
                                    <form wire:submit.prevent="sendWelcomeEmail">
                                        <div class="form-group">
                                            <label><strong>email</strong></label>
                                            <input wire:model="form.email" type="email" class="form-control @error('email') error @enderror" placeholder="Adresse email">
                                            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Téléphone</strong></label>
                                            <input wire:model="form.tel" type="tel" class="form-control" placeholder="Téléphone">
                                            @error('tel') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="mt-3">
                                            <button type="submit" class="btn btn-primary btn-block">Recevoir code</button>
                                        </div>
                                    </form>
                                    <div class="new-account mt-3">
                                        <p>Retour à la page de <a class="text-primary" href="{{ route('login') }}">Connexion</a></p>
                                    </div>
                                @else
                                    <h4 class="text-center mb-4">Entrer le code envoyer par email</h4>
                                    <form wire:submit.prevent="isExact">
                                        <div class="form-group">
                                            <input wire:model="form.code" type="text" class="form-control @error('code') error @enderror" placeholder="Le code">
                                            @error('code') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="mt-3">
                                            <button type="submit" class="btn btn-primary btn-block">Vérifier</button>
                                        </div>
                                    </form>
                                    <div class="new-account mt-3">
                                        <p>Retour à la page de <a class="text-primary" href="{{ route('login') }}">Connexion</a></p>
                                    </div>
                                @endif
                            @else
                                <h4 class="text-center mb-4">Changement mot de passe</h4>
                                <form wire:submit.prevent="editPassword">
                                    <div class="form-group">
                                        <label><strong>Nouveau mot de passe</strong></label>
                                        <input type="password" wire:model="form2.password" class="form-control @error('password') error @enderror" placeholder="Nouveau mot de passe">
                                        @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label><strong>Confimer mot de passe</strong></label>
                                        <input type="password" wire:model="form2.password_confirmation" class="form-control" placeholder="Confirmer mot de passe">
                                        @error('password_confirmation') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mt-3">
                                        <button type="submit" class="btn btn-primary btn-block">Changer</button>
                                    </div>
                                </form>
                                <div class="new-account mt-3">
                                    <p>Retour à la page de <a class="text-primary" href="{{ route('login') }}">Connexion</a></p>
                                </div>
                            @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@section('script')
    <script>
       window.addEventListener('errorMail', event =>{
          iziToast.error({
          title: 'Verification',
          message: 'Email incorrecte',
          position: 'topRight'
          });
      });

       window.addEventListener('errorTel', event =>{
          iziToast.error({
          title: 'Verification',
          message: 'Téléphone incorrecte',
          position: 'topRight'
          });
      });

      window.addEventListener('passwordEditSuccessful', event =>{
        iziToast.success({
        title: 'Mot de passe',
        message: 'Mis à jour avec succes',
        position: 'topRight'
        });
    });

    window.addEventListener('accessCode', event =>{
        iziToast.success({
        title: 'Code',
        message: 'verification exacte',
        position: 'topRight'
        });
    });

    window.addEventListener('sendCode', event =>{
        iziToast.success({
        title: 'Code ',
        message: 'envoyé par email avec succes',
        position: 'topRight'
        });
    });

    window.addEventListener('errorCode', event =>{
          iziToast.error({
          title: 'Verification',
          message: 'code incorrecte',
          position: 'topRight'
          });
      });

    </script>
@endsection