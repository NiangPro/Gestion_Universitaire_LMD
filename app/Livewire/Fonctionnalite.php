<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Fonctionnalités")]
class Fonctionnalite extends Component
{
    #[Layout("components.layouts.home")]
    public function render()
    {
        return view('livewire.fonctionnalite');
    }
}
