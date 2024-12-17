<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use App\Models\NiveauEtude;
use App\Models\Filiere;
use App\Models\UniteEnseignement;
use App\Models\Outils;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Unité d'enseignement")]
class UniteEnseignements extends Component
{
    public $status = "list";
    public $title= "Liste des unités d'enseignement";
    public $outil;
    public $nom ,$filiere_id, $niveau_etude_id, $credit; //les inputs
    public $id;

    protected $rules = [
        'nom' => 'required|string|max:255',
        'filiere_id' => 'required',
        'niveau_etude_id' => 'required',
        'credit' => 'required',
    ];

    public function messages(){
        return [
            'nom.required' => 'Le nom est obligatoire.',
            'filiere_id.required' => 'Le filière est obligatoire.',
            'niveau_etude_id.required' => 'Le niveu d\'étude est obligatoire.',
            'credit.required' => 'Le crédit est obligatoire.',
        ];
    }

    public function getInfo($id){
        $this->changeStatus("info");

        $u = UniteEnseignement::where("id", $id)->first();

        $this->nom = $u->nom;
        $this->filiere_id = $u->filiere_id;
        $this->niveau_etude_id = $u->niveau_etude_id;
        $this->credit = $u->credit;
        $this->id = $u->id;
    }

    public function supprimer($id){
        $ue = UniteEnseignement::where("id", $id)->first();

        $ue->is_deleting = true;

        $ue->save();

        $this->outil->addHistorique("Suppression d'une unité d'enseignement ", "edit");

        $this->dispatch("delete");
        $this->init();
    }


    public function store(){
        $this->validate();

        if ($this->id) {
            $u = UniteEnseignement::where("id", $this->id)->first();

            $u->nom = $this->nom;
            $u->filiere_id = $this->filiere_id;
            $u->niveau_etude_id = $this->niveau_etude_id;
            $u->credit = $this->credit;

            $u->save();
            $this->outil->addHistorique("Mis à jour des données d'une unité d'enseignement", "edit");

            $this->dispatch("update");
        }else{
            UniteEnseignement::create([
                "nom" => $this->nom,
                "filiere_id" => $this->filiere_id,
                "niveau_etude_id" => $this->niveau_etude_id,
                "credit" => $this->credit,
                'campus_id' => Auth::user()->campus_id,
            ]);
    
            $this->outil->addHistorique("Ajout d'une unité d'enseignement", "add");

            $this->dispatch("added");
        }

        $this->init();
        $this->changeStatus("list");
    }

    public function changeStatus($status){
        $this->status = $status;

        if ($status == "add") {
            $this->title = "Formulaire d'ajout unité d'enseignement";
        }elseif($status == "edit"){
            $this->title = "Formulaire d'édition unité d'enseignement";
        }else{
            $this->title = "Liste des unités d'enseignement";
        }

    }

    #[Layout("components.layouts.app")]
    public function render()
    {
        $this->outil = new Outils();
        return view('livewire.ue.unite-enseignements',[
            "uniteEnseignement" => UniteEnseignement::where("campus_id", Auth::user()->campus_id)->where("is_deleting", false)->get(),
            "filieres" => Filiere::where("campus_id", Auth::user()->campus_id)->where("is_deleting", false)->get(),
            "niveauEtude" => NiveauEtude::where("campus_id", Auth::user()->campus_id)->where("is_deleting", false)->get(),
        ]);
    }

    public function init(){
        $this->id=null;
        $this->reset(['nom', 'filiere_id', 'niveau_etude_id', 'credit']);
    }
}
