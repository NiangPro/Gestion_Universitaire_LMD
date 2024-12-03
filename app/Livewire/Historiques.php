<?php

namespace App\Livewire;

use App\Models\Historique;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Historiques")]
class Historiques extends Component
{

    #[Layout("components.layouts.app")]
    public function render()
    {
        return view('livewire.historiques', [
            "histos" => Auth::user()->estSuperAdmin() ? Historique::orderBy("id", "DESC")->get() : Historique::where("campus_id", Auth::user()->campus_id)->orderBy("created_at", "DESC")->get()
        ]);
    }
}
