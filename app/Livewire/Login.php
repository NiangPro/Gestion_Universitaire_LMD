<?php

namespace App\Livewire;

use App\Models\Outils;
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
        $this->validate();

        // Déterminer le champ utilisé pour la connexion
        $field = filter_var($this->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (is_numeric($this->login)) {
            $field = 'tel';
        }

        // Tenter la connexion avec les informations fournies
        if (Auth::attempt([$field => $this->login, 'password' => $this->password])) {
            // Rediriger vers la page d'accueil ou la page souhaitée après la connexion
            $this->redirect("/tableau_de_bord", navigate: false);
        } else {
            // Afficher un message d'erreur si les informations sont incorrectes
            $this->addError('login', 'Ces identifiants ne correspondent pas à nos enregistrements.');
        }
    }

    
    #[Layout("components.layouts.app")]
    public function render()
    {
        return view('livewire.login2');
    }

    public function mount(){
        $this->outils = new Outils();
        $this->outils->createSuperAdmin();
    }
}
