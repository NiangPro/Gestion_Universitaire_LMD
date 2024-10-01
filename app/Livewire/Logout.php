<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Logout extends Component
{
    public function deconnecter(){
        Auth::logout();  // Déconnexion de l'utilisateur
        session()->invalidate();  // Invalide la session
        session()->regenerateToken();  // Regénère le token CSRF pour plus de sécurité
        redirect(route("login"));
    }
    public function render()
    {
        return view('livewire.logout');
    }
}
