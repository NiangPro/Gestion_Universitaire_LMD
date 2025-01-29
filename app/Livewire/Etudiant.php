<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Etudiants")]
class Etudiant extends Component
{
    public $status = "";
    public $etat= "";

    public function updateStatus($value){
        $this->etat=$value;
        \Log::info('Valeur sélectionnée : ' . $this->etat); 
    }
    #[Layout("components.layouts.app")]
    public function render()
    {
        
        return view('livewire.etudiant.etudiant');
    }
}
