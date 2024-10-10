<?php

namespace App\Livewire;

use App\Models\Activation;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Activations")]
class Activations extends Component
{
    public function changeStatus($id, $etat){
        $val = $etat == "actif" ? 1 : 0;

        $act = Activation::where("id", $id)->first();

        $act->status = $val;

        $act->save();

        $this->dispatch($etat);
    }

    #[Layout("components.layouts.app")]
    public function render()
    {
        return view('livewire.activations',[
            "tables" => Activation::orderBy("nom", "ASC")->get()
        ]);
    }
}
