<?php

namespace App\Livewire;

use App\Models\Outils;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Connexion")]
#[Layout("components.layouts.home")]
class Login extends Component
{
    public $outils;
    public $remember = false;
    public $showPassword = false;

    #[Rule('required', message:"Email est obligatoire")]
    #[Rule('string')]
    public $login;

    #[Rule('required', message:"Le mot de passe est obligatoire")]
    #[Rule('string')]
    #[Rule('min:8', message:"Le mot de passe doit contenir au moins 8 caractères")]
    public $password;

    public function seconnecter()
    {
        $user = null;
        if ($this->login) {
            $user = User::where("email", $this->login)
                       ->orWhere("username", $this->login)
                       ->orWhere("tel", $this->login)
                       ->first();
        }

        if (!$user) {
            $this->addError('login', 'Ces identifiants ne correspondent pas à nos enregistrements.');
            return;
        }

        // Vérifier si le compte est verrouillé
        if ($user->is_locked) {
            if ($user->locked_at && now()->diffInMinutes($user->locked_at) < 30) {
                $remainingMinutes = 30 - now()->diffInMinutes($user->locked_at);
                $this->addError('login', "Ce compte est temporairement verrouillé. Réessayez dans {$remainingMinutes} minutes.");
                return;
            }
            // Déverrouiller le compte après 30 minutes
            $user->update([
                'is_locked' => false,
                'locked_at' => null,
                'failed_login_attempts' => 0
            ]);
        }

        // Vérifier le mot de passe
        if (!$user->password) {
            return redirect()->route('forget');
        }else if (!Hash::check($this->password, $user->password)) {
            $user->increment('failed_login_attempts');
            
            // Verrouiller le compte après 5 tentatives échouées
            if ($user->failed_login_attempts >= 5) {
                $user->update([
                    'is_locked' => true,
                    'locked_at' => now()
                ]);
                $this->addError('login', 'Ce compte a été temporairement verrouillé pour des raisons de sécurité.');
                return;
            }
            
            $this->addError('login', 'Ces identifiants ne correspondent pas à nos enregistrements.');
            return;
        }

        // Vérifier si l'email est vérifié
        if (!$user->email_verified_at && config('auth.verify_email')) {
            $this->addError('login', 'Veuillez vérifier votre adresse email avant de vous connecter.');
            return;
        }

        // Réinitialiser le compteur de tentatives échouées
        if ($user->failed_login_attempts > 0) {
            $user->update(['failed_login_attempts' => 0]);
        }

        // Connexion réussie
        Auth::login($user, $this->remember);

        // Vérifier si le mot de passe doit être changé
        if ($user->force_password_change) {
            return redirect()->route('password.change');
        }

        // Vérifier la 2FA
        if ($user->two_factor_secret && 
            $user->two_factor_confirmed_at && 
            !session()->get('auth.two_factor.authenticated')) {
            session()->put('auth.two_factor.intended_route', '/tableau_de_bord');
            return redirect()->route('two-factor.challenge');
        }

        return redirect("/tableau_de_bord");
    }

    public function togglePassword()
    {
        $this->showPassword = !$this->showPassword;
    }

    public function mount()
    {
        if (Auth::check()) {
            redirect("/tableau_de_bord");
        }
        $this->outils = new Outils;
        $this->outils->createSuperAdmin();
    }

    public function render()
    {
        return view('livewire.login');
    }
}
