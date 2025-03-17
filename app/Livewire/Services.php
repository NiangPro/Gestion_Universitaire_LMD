<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Pack;

#[Title("Services")]
class Services extends Component
{
    public $packs;

    public function mount()
    {
        $this->packs = Pack::where('is_deleting', false)->get();
    }

    #[Layout("components.layouts.home")]
    public function render()
    {
        return view('livewire.services');
    }
}
