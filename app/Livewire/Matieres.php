<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use App\Models\Filiere;
use App\Models\Matiere;
use App\Models\Outils;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;


#[Title("Matières")]
class Matieres extends Component
{
    public $status = "list";
    public $title= "Liste des matières";
    public $nom, $coef,$credit, $filiere_id;
    public $id;
    public $outil;

    protected $rules = [
        "nom" => "required|string|max: 255",
        "coef" => "required",
        "credit" => "required",
        "filiere_id" => "required",
    ];

    public function messages(){
        return [
            'nom.required' => 'Le nom est obligatoire.',
            'coef.required' => 'Le coefficient est obligatoire.',
            'crédit.required' => 'Le crédit est obligatoire.',
            'filiere_id.required' => 'La filière est obligatoire.',
        ];
    }

    public function getInfo($id){
        $this->changeStatus("info");

        $m = Matiere::where("id", $id)->first();

        $this->nom = $m->nom;
        $this->coef = $m->coef;
        $this->credit = $m->credit;
        $this->filiere_id = $m->filiere_id;

        $this->id = $m->id;
    }

    public function supprimer($id){
        $m = Matiere::where("id", $id)->first();

        $m->is_deleting = true;

        $m->save();

        $this->outil->addHistorique("Suppression d'une unité d'enseignement ", "edit");

        $this->dispatch("delete");
        $this->init();
    }

    public function changeStatus($status){
        $this->status = $status;

        if ($status == "add") {
            $this->title = "Formulaire d'ajout matière";
        }elseif($status == "edit"){
            $this->title = "Formulaire d'édition matière";
        }else{
            $this->title = "Liste des matières";
        }
        $this->init();
    }

    
    public function store(){
        $this->validate();

        if ($this->id) {
            $m = Matiere::where("id", $this->id)->first();

            $m->nom = $this->nom;
            $m->coef = $this->coef;
            $m->credit = $this->credit;
            $m->filiere_id = $this->filiere_id;

            $m->save();
            $this->outil->addHistorique("Mis à jour des données d'une matière", "edit");

            $this->dispatch("update");
        }else{
            Matiere::create([
                "nom" => $this->nom,
                "coef" => $this->coef,
                "credit" => $this->credit,
                "filiere_id" => $this->filiere_id,
                'campus_id' => Auth::user()->campus_id,
            ]);
    
            $this->outil->addHistorique("Ajout d'une matière", "add");

            $this->dispatch("added");
        }

        $this->changeStatus("list");
    }

    #[Layout("components.layouts.app")]
    public function render()
    {
        $this->outil = new Outils();
        return view('livewire.matiere.matieres',[
            "matieres" => Matiere::where("campus_id", Auth::user()->campus_id)->where("is_deleting", false)->get(),
            "filieres" => Filiere::where("campus_id", Auth::user()->campus_id)->where("is_deleting", false)->get()
        ]);
    }

    public function init(){
        $this->id=null;
        $this->reset(['nom', 'filiere_id', 'coef']);
    }
}
