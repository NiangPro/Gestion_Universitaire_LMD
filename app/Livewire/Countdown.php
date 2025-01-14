<?php

namespace App\Livewire;

use App\Models\Campus;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Countdown extends Component
{
    public $dateFermeture;

    // public function mount($campusId)
    // {
    //     $campus = Campus::find($campusId);
    //     $this->dateFermeture = $campus->date_fermeture; // Assurez-vous que ce champ est au format 'Y-m-d H:i:s'
    // }
    
    public function render()
    {
        $this->dateFermeture = Auth::user()->campus->date_fermeture;
        return view('livewire.countdown');
    }
}
