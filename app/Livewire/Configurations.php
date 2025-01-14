<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Configurations")]
class Configurations extends Component
{
    #[Layout("components.layouts.app")]
    public function render()
    {
        return view('livewire.configurations');
    }
}
