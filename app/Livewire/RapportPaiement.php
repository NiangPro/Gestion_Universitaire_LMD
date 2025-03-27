<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Title('Rapport Paiement')]
class RapportPaiement extends Component
{
    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.paiement.rapport-paiement');
    }
}
