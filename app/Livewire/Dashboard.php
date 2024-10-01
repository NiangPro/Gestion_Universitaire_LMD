<?php

namespace App\Livewire;

use App\Models\Outils;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Tableau de bord")]
class Dashboard extends Component
{
    public $outils;

    #[Layout("components.layouts.app")]
    public function render()
    {
        return view('livewire.dashboard');
    }

    public function mount(){
        $this->outils = new Outils();
        $this->outils->isLogged();
    }
}
