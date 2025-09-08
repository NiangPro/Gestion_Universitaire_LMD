<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Emploi du temps')]
class EmploiDuTempsProfesseur extends Component
{
    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.emploi-du-temps-professeur');
    }
}
