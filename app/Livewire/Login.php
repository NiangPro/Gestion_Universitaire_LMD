<?php

namespace App\Livewire;

use App\Models\Outils;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Connexion")]
class Login extends Component
{
    public $outils;
    

    #[Rule('required', message:"Email est obligatoire")]
    #[Rule('string')]
    public $login;
    #[Rule('required', message:"Le mot de passe est obligatoire")]
    #[Rule('string')]
    public $password;

    public function seconnecter()
    {
        $user = null;
        if ($this->login) {
            $user = User::where("email", $this->login)->first();
        }
        if ($user && $user->password == null) {
           redirect(route("forget"));
        }else{
            $this->validate();

            // Déterminer le champ utilisé pour la connexion
            $field = filter_var($this->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
    
            if (is_numeric($this->login)) {
                $field = 'tel';
            }
    
            // Tenter la connexion avec les informations fournies
            if (Auth::attempt([$field => $this->login, 'password' => $this->password])) {
                $user = Auth::user();
                
                // Vérifier si l'utilisateur a activé la 2FA
                if ($user->two_factor_secret && 
                    $user->two_factor_confirmed_at && 
                    !session()->get('auth.two_factor.authenticated')) {
                    
                    // Stocker l'intention de connexion
                    session()->put('auth.two_factor.intended_route', '/tableau_de_bord');
                    
                    // Rediriger vers la page de challenge 2FA
                    return redirect()->route('two-factor.challenge');
                }
                
                // Si pas de 2FA ou déjà authentifié, rediriger vers le tableau de bord
                return redirect("/tableau_de_bord");
            } else {
                // Afficher un message d'erreur si les informations sont incorrectes
                $this->addError('login', 'Ces identifiants ne correspondent pas à nos enregistrements.');
            }
        }
    }

    public function mount()
    {
        if (Auth::check()) {
            redirect("/tableau_de_bord");
        }
        $this->outils = new Outils;
        $this->outils->createSuperAdmin();
        $this->outils->initActivation();
    }

    #[Layout("components.layouts.home")]
    public function render()
    {
        return view('livewire.login');
    }
}
