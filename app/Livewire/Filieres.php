<?php

namespace App\Livewire;

use App\Models\Departement;
use App\Models\Filiere;
use App\Models\Outils;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Filières")]
class Filieres extends Component
{
    public $status = "list";
    public $title= "Liste des filières";
    public $outil;
    public $id, $nom, $departement_id;

    protected $rules =[
        "nom" => "required",
        "departement_id" => "required",
    ];

    protected $messages = [
        "nom.required" => "Le nom est obligatoire",
        "departement_id.required" => "Le département est obligatoire",
    ];

    public function changeStatus($status){
        $this->status = $status;

        if ($status == "add") {
            $this->title = "Formulaire d'ajout filière";
        }elseif($status == "edit"){
            $this->title = "Formulaire d'édition filière";
        }else{
            $this->title = "Liste des filière";
        }

        $this->reset(["nom", "departement_id", "id"]);
    }

    public function getInfo($id){
        $this->changeStatus("info");

        $d = Filiere::where("id", $id)->first();

        $this->nom = $d->nom;
        $this->departement_id = $d->departement_id;
        $this->id = $d->id;
    }

    public function supprimer($id){
        $ac = Filiere::where("id", $id)->first();

        $ac->is_deleting = true;

        $ac->save();

        $this->outil->addHistorique("Suppression d'un filière", "edit");


        $this->dispatch("delete");
    }

    public function store(){
        $this->validate();

        if ($this->id) {
            $a = Filiere::where("id", $this->id)->first();

            $a->nom = $this->nom;
            $a->departement_id = $this->departement_id;

            $a->save();
            $this->outil->addHistorique("Mis à jour des données d'un filière", "edit");

            $this->dispatch("update");
        }else{
            Filiere::create([
                "nom" => $this->nom,
                "departement_id" => $this->departement_id,
                "campus_id" => Auth::user()->campus_id
            ]);
    
            $this->outil->addHistorique("Ajout d'un filière", "add");

            $this->dispatch("added");
        }


        $this->changeStatus("list");
    }

    #[Layout("components.layouts.app")]
    public function render()
    {
        $this->outil = new Outils();
        return view('livewire.filiere.filieres',[
            "filieres" => Filiere::where("campus_id", Auth::user()->campus_id)->where("is_deleting", false)->get(),
            "depts" => Departement::where("campus_id", Auth::user()->campus_id)->where("is_deleting", false)->get(),
        ]);
    }
}
