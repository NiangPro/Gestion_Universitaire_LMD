<?php

namespace App\Livewire;

use App\Models\Classe;
use App\Models\Departement;
use App\Models\Filiere;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Configurations")]
class Configurations extends Component
{
    public $classe = [
        "nom" => "",
        "idclasse" => null
    ];

    public $filiere = [
        "nom" => "",
        "idfiliere" => null
    ];

    public $departement = [
        "nom" => "",
        "iddepartement" => null
    ];

    protected $messages = [
        "classe.nom.required" => "Le nom est requis",
        "filiere.nom.required" => "Le nom est requis",
        "departement.nom.required" => "Le nom est requis",
    ];

    public function initialiser($champ){
        $this->reset([$champ]);
    }

    public function supprimerDepartement($id){
        $departement = Departement::where("id", $id)->first();

        $departement->is_deleting = true;

        $departement->save();

        $this->dispatch("deleted");
    }

    public function getDepartement($id){
        $departement = Departement::where("id", $id)->first();

        $this->departement["iddepartement"] = $departement->id;
        $this->departement["nom"] = $departement->nom;
    }

    public function storeDepartement(){
        $this->validate(["departement.nom" => "required"]);

        if ($this->departement["iddepartement"]) {
            $departement = Departement::where("id", $this->departement["iddepartement"])->first();

            $departement->nom = strtoupper($this->departement["nom"]);

            $departement->save();

            $this->dispatch("updated");
        }else{
            Departement::create(["nom" => strtoupper($this->departement["nom"])]);
            $this->dispatch("added");
        }
        
        $this->reset(["departement"]);
    }

    public function supprimerFiliere($id){
        $filiere = Filiere::where("id", $id)->first();

        $filiere->is_deleting = true;

        $filiere->save();

        $this->dispatch("deleted");
    }

    public function getFiliere($id){
        $filiere = Filiere::where("id", $id)->first();

        $this->filiere["idfiliere"] = $filiere->id;
        $this->filiere["nom"] = $filiere->nom;
    }

    public function storeFiliere(){
        $this->validate(["filiere.nom" => "required"]);

        if ($this->filiere["idfiliere"]) {
            $filiere = Filiere::where("id", $this->filiere["idfiliere"])->first();

            $filiere->nom = strtoupper($this->filiere["nom"]);

            $filiere->save();

            $this->dispatch("updated");
        }else{
            Filiere::create(["nom" => strtoupper($this->filiere["nom"])]);
            $this->dispatch("added");
        }
        
        $this->reset(["filiere"]);
    }

    public function supprimerClasse($id){
        $classe = Classe::where("id", $id)->first();

        $classe->is_deleting = true;

        $classe->save();

        $this->dispatch("deleted");
    }

    public function getClasse($id){
        $classe = Classe::where("id", $id)->first();

        $this->classe["idclasse"] = $classe->id;
        $this->classe["nom"] = $classe->nom;
    }

    public function storeClasse(){
        $this->validate(["classe.nom" => "required"]);

        if ($this->classe["idclasse"]) {
            $classe = Classe::where("id", $this->classe["idclasse"])->first();

            $classe->nom = strtoupper($this->classe["nom"]);

            $classe->save();

            $this->dispatch("updated");
        }else{
            Classe::create(["nom" => strtoupper($this->classe["nom"])]);
            $this->dispatch("added");
        }
        
        $this->reset(["classe"]);
    }

    #[Layout("components.layouts.app")]
    public function render()
    {
        return view('livewire.configuration.configurations', [
            "classes" => Classe::where("is_deleting", false)->orderBy("nom", "ASC")->get(),
            "filieres" => Filiere::where("is_deleting", false)->orderBy("nom", "ASC")->get(),
            "departements" => Departement::where("is_deleting", false)->orderBy("nom", "ASC")->get(),
        ]);
    }
}
