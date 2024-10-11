<?php

namespace App\Livewire;

use App\Models\Outils;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use phpDocumentor\Reflection\Types\This;

#[Title("Messages")]
class Messages extends Component
{
    public $type = "list";
    public $receiver_id;
    public $receiver;
    public $users = [];
    public $outils;

    public function changeType($type){
        $this->type = $type;
    }

    public function getReceiver($id){
        $this->receiver = User::where("id", $id)->first();

        if ($this->receiver) {
            $this->receiver_id = $this->receiver->email;

            $this->users = [];
        }
        
    }



    #[Layout("components.layouts.app")]
    public function render()
    {
        $this->outils = new Outils();
        if ($this->receiver_id && !$this->receiver) {
             // Rechercher des utilisateurs en fonction du nom ou de l'email
            $this->users = $this->outils->searchUser($this->receiver_id);
        }else{
            $this->users = [];
            $this->receiver = null;
        }
        return view('livewire.message.messages');
    }
}
