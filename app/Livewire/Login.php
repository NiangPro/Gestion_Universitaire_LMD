<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Connexion")]
class Login extends Component
{
    public function dashboard(){
        $this->redirect("/tableau_de_bord", navigate: false);
    }
    
    #[Layout("components.layouts.app")]
    public function render()
    {
        return view('livewire.login');
    }
}
