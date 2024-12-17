<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\NiveauEtude;
use App\Models\Outils;
use Livewire\Component;


#[Title("Niveau d'étude")]
class NiveauEtudes extends Component
{
    public $status = "list";
    public $outil;
    public $nom;
    public $id;

    protected $rules = [
        'nom' => 'required|string|max:255',
    ];

    public function messages(){
        return [
            'nom.required' => 'Le nom est obligatoire.',
        ];
    }

    public function delete($id){
        $n = NiveauEtude::where("id", $id)->first();
        $n->is_deleting = true;

        $n->save();

        $this->outil->addHistorique("Suppression d'un niveau d'étude", "delete");

        $this->dispatch("deleteLevel");
    }

    public function changeStatus($status)
    {
        $this->status = $status;
        $this->init();
    }

    public function getLevel($id){
        $n = NiveauEtude::where("id", $id)->first();

        $this->id = $n->id;
        $this->nom = $n->nom;
        $this->status="edit";
    }

    public function store()
    {
       
        if ($this->id) {
            $this->validate([
                'nom' => 'required|string|max:255',
            ]);
            
            $n = NiveauEtude::where("id", $this->id)->first();
            
            if ($n) {
                $n->nom = $this->nom;
                $n->campus_id = Auth::user()->campus_id;

                $n->save();
                $this->outil->addHistorique("Mis à jour des données d'un niveau d'étude", "edit");

                $this->dispatch("updateSuccessful");
            }
        }else{
            $this->validate();
            NiveauEtude::create([
                'nom' => $this->nom,
                'campus_id' => Auth::user()->campus_id,
            ]);

            $this->outil->addHistorique("Ajout d'un niveau d'étude", "add");

            $this->dispatch("addSuccessful");
        }

        // Réinitialiser les champs du formulaire
        $this->init();
        $this->status = "list";
    }

    #[Layout("components.layouts.app")]
    public function render()
    {
        $this->outil = new Outils();
        return view('livewire.niveau.niveau-etudes',[
            "level" => NiveauEtude::where("campus_id", Auth::user()->campus_id)->where("is_deleting", false)->get(),
        ]);
    }

    public function init(){
        $this->id=null;
        $this->reset(['nom']);
    }

}
