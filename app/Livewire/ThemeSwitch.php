<?php

namespace App\Livewire;

use Livewire\Component;

class ThemeSwitch extends Component
{
    public $darkMode = false;

    public function mount()
    {
        $this->darkMode = session()->get('darkMode', false);
    }

    public function switching()
    {
        $this->darkMode = !$this->darkMode;
        session()->put('darkMode', $this->darkMode);
        $this->dispatch('theme-changed', darkMode: $this->darkMode);
    }

    public function render()
    {
        return view('livewire.theme-switch');
    }
} 