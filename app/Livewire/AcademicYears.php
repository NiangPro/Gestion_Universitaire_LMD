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

    public $debut, $fin;

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


    public function changeStatus($status){
        $this->status = $status;

        if ($status == "add") {
            $this->title = "Formulaire d'ajout année académique";
        }elseif($status == "edit"){
            $this->title = "Formulaire d'édition année académique";
        }else{
            $this->title = "Liste des années académiques";
        }
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

        AcademicYear::create([
            "debut" => $this->debut,
            "fin" => $this->fin,
            "campus_id" => Auth::user()->campus_id,
            "encours" => false
        ]);

        $this->dispatch("added");

        $this->reset(["debut", "fin"]);

        $this->changeStatus("list");
    }

    #[Layout("components.layouts.app")]
    public function render()
    {
        return view('livewire.anneeacademique.academic-years', [
            "acs" => AcademicYear::where("campus_id", Auth::user()->campus_id)->orderBy("fin", "DESC")->get()
        ]);
    }
}
