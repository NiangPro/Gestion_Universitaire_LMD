<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Tableau de bord")]
class Dashboard extends Component
{
    #[Layout("components.layouts.app")]
    public function render()
    {
        return view('livewire.dashboard');
    }
}
