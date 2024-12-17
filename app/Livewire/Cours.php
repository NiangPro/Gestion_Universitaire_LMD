<?php

namespace App\Livewire;

use App\Models\Cour;
use App\Models\Outils;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Tableau de bord")]
class Cours extends Component
{
    public $status = "list";
    public $outil;

    public function changeStatus($status)
    {
        $this->status = $status;
        $this->init();
    }
    
    #[Layout("components.layouts.app")]
    public function render()
    {
        $this->outil = new Outils();
        return view('livewire.cours.cours',[
            "cours" => Cour::get()
        ]);
    }
}
