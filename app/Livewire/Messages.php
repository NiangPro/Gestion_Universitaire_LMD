<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Messages")]
class Messages extends Component
{
    public $type = "list";
    public $receiver_id;

    public function changeType($type){
        $this->type = $type;
    }

    



    #[Layout("components.layouts.app")]
    public function render()
    {
        if ($this->receiver_id) {
             // Rechercher des utilisateurs en fonction du nom ou de l'email
        $users = User::where('prenom', 'LIKE', "%$this->receiver_id%")
        ->orWhere('email', 'LIKE', "%$this->receiver_id%")
        ->get();

        dd($users);
        }
        return view('livewire.message.messages');
    }
}
