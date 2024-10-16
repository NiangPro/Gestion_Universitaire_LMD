<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Années Académiques")]
class AcademicYears extends Component
{

    #[Layout("components.layouts.app")]
    public function render()
    {
        return view('livewire.anneeacademique.academic-years');
    }
}
