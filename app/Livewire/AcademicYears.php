<?php

namespace App\Livewire;

use App\Models\AcademicYear;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Années Académiques")]
class AcademicYears extends Component
{
    public $title = "Liste des années académiques";
    public $status = "list";

    public $debut, $fin,$id;

    protected $rules =[
        "debut" => "required|date",
        "fin" => "required|date",
    ];

    protected $messages = [
        "debut.required" => "La date d'ouverture est obligatoire",
        "debut.date" => "Veuillez entrer une date valide",
        "fin.required" => "La date de fermeture est obligatoire",
        "fin.date" => "Veuillez entrer une date valide",
    ];

    public function getInfo($id){
        $ac = AcademicYear::where("id", $id)->first();

        $this->changeStatus("edit");

        $this->debut = $ac->debut;
        $this->fin = $ac->fin;
        $this->id = $ac->id;

    }

    public function supprimer($id){
        $ac = AcademicYear::where("id", $id)->first();

        $ac->is_deleting = true;

        $ac->save();

        $this->dispatch("delete");
    }

    public function changeStatus($status){
        $this->status = $status;

        if ($status == "add") {
            $this->title = "Formulaire d'ajout année académique";
        }elseif($status == "edit"){
            $this->title = "Formulaire d'édition année académique";
        }else{
            $this->title = "Liste des années académiques";
        }

        $this->reset(["debut", "fin", "id"]);

    }

    public function activer($id){

        foreach ( AcademicYear::get() as $ay) {
            $ay->encours = false;
            $ay->save();
        }

        $ac = AcademicYear::where("id", $id)->first();

        $ac->encours = true;

        $ac->save();

        $this->dispatch("actif");
    }

    public function desactiver($id){
        $ac = AcademicYear::where("id", $id)->first();

        $ac->encours = false;

        $ac->save();

        $this->dispatch("desactif");
    }

    public function store(){
        $this->validate();

        if ($this->id) {
            $a = AcademicYear::where("id", $this->id)->first();

            $a->debut = $this->debut;
            $a->fin = $this->fin;

            $a->save();

            $this->dispatch("update");
        }else{
            AcademicYear::create([
                "debut" => $this->debut,
                "fin" => $this->fin,
                "campus_id" => Auth::user()->campus_id,
                "encours" => false
            ]);
    
            $this->dispatch("added");
        }


        $this->changeStatus("list");
    }

    #[Layout("components.layouts.app")]
    public function render()
    {
        return view('livewire.anneeacademique.academic-years', [
            "acs" => AcademicYear::where("campus_id", Auth::user()->campus_id)->where("is_deleting", false)->orderBy("fin", "DESC")->get()
        ]);
    }
}
