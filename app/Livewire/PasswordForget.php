<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Mot de passe oublié")]
class PasswordForget extends Component
{

    #[Layout("components.layouts.app")]
    public function render()
    {
        return view('livewire.password-forget');
    }
}
