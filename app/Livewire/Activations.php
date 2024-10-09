<?php

namespace App\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Activations")]
class Activations extends Component
{
    #[Layout("components.layouts.app")]
    public function render()
    {
        return view('livewire.activations',[
            "tables" => DB::select('SHOW TABLES')
        ]);
    }
}
