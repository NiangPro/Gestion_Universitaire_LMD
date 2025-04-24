<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Page non trouvée")]
class NotFound extends Component
{
    public function render()
    {
        return view('livewire.notfound');
    }
}