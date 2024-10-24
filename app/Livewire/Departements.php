<?php

namespace App\Livewire;

use App\Models\Departement;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Departement")]
class Departements extends Component
{
    public $status = "list";
    public $title= "Liste des départements";

    public $id, $nom, $description;

    protected $rules =[
        "nom" => "required",
        "description" => "required",
    ];

    protected $messages = [
        "nom.required" => "Le nom est obligatoire",
        "description.required" => "La description est obligatoire",
    ];

    public function changeStatus($status){
        $this->status = $status;

        if ($status == "add") {
            $this->title = "Formulaire d'ajout département";
        }elseif($status == "edit"){
            $this->title = "Formulaire d'édition département";
        }else{
            $this->title = "Liste des département";
        }

        $this->reset(["nom", "description", "id"]);
    }

    public function getInfo($id){
        $this->changeStatus("info");

        $d = Departement::where("id", $id)->first();

        $this->nom = $d->nom;
        $this->description = $d->description;
        $this->id = $d->id;
    }

    public function supprimer($id){
        $ac = Departement::where("id", $id)->first();

        $ac->is_deleting = true;

        $ac->save();

        $this->dispatch("delete");
    }

    public function store(){
        $this->validate();

        if ($this->id) {
            $a = Departement::where("id", $this->id)->first();

            $a->nom = $this->nom;
            $a->description = $this->description;

            $a->save();

            $this->dispatch("update");
        }else{
            Departement::create([
                "nom" => $this->nom,
                "description" => $this->description,
                "campus_id" => Auth::user()->campus_id
            ]);
    
            $this->dispatch("added");
        }


        $this->changeStatus("list");
    }

    #[Layout("components.layouts.app")]
    public function render()
    {
        return view('livewire.departement.departements', [
            "depts" => Departement::where("is_deleting", false)->orderBy("nom", "ASC")->get()
        ]);
    }
}
