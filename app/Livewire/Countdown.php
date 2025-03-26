<?php

namespace App\Livewire;

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
        $this->dateFermeture = Auth::user()->campus->activeSubscription() ? Auth::user()->campus->activeSubscription()->end_date:null;
        return view('livewire.countdown');
    }
}
