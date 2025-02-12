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
    public $classe= "";
    public $matricule= "";
    public function updatedClasse($value)
    {
        $this->classe = $value;
    }

    public function updatedPaiement($value)
    {
        $this->etat = $value;
    }

    public function updatedMatricule($value)
    {
        $this->matricule = $value;
    }

    #[Layout("components.layouts.app")]
    public function render()
    {
        
        return view('livewire.etudiant.etudiant');
    }
}
