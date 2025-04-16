<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Notes")]
class NotesProfesseur extends Component
{
    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.note.notes-professeur');
    }
}
