<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Cours')]
class CoursEtudiant extends Component
{
    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.etudiant.cours-etudiant');
    }
}
