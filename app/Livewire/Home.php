<?php

namespace App\Livewire;

use App\Models\Pack;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Accueil")]
class Home extends Component
{
    #[Layout("components.layouts.app")]
    public function render()
    {
        return view('livewire.home', [
            "packs" => Pack::orderBy("annuel", "ASC")->get()
        ]);
    }

    public function mount(){
        if (Auth::user()) {
            redirect(route("dashboard"));
        }
    }
}
