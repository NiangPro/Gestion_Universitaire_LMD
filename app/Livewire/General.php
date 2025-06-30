<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title("Abonnements")]
class General extends Component
{
    #[Layout("components.layouts.app")]
    public function render()
    {
        return view('livewire.general');
    }
}
