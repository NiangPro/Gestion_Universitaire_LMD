<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Title("Retards")]
class Retards extends Component
{
    #[Layout("components.layouts.app")]
    public function render()
    {
        return view('livewire.retards');
    }
}
