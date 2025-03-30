<?php

namespace App\Livewire;

use App\Models\AcademicYear;
use App\Models\Outils;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Années Académiques")]
class AcademicYears extends Component
{
    public $title = "Liste des années académiques";
    public $status = "list";
    public $outil;

    public $debut, $fin,$id;

    protected $rules =[
        "debut" => "required|date",
        "fin" => "required|date|after:debut",
    ];

    protected $messages = [
        "debut.required" => "La date d'ouverture est obligatoire",
        "debut.date" => "Veuillez entrer une date valide",
        "fin.required" => "La date de fermeture est obligatoire",
        "fin.date" => "Veuillez entrer une date valide",
        "fin.after" => "La date de fermeture est être supérieur à la date d'ouverture",
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

        $this->outil->addHistorique("Suppression d'une année scolaire", "delete");

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
        foreach (AcademicYear::get() as $ay) {
            $ay->encours = false;
            $ay->save();
        }

        $ac = AcademicYear::where("id", $id)->first();
        $ac->encours = true;
        $ac->save();

        $this->dispatch("actif");
        return redirect()->route('academicyear');
    }

    public function desactiver($id){
        $ac = AcademicYear::where("id", $id)->first();
        $ac->encours = false;
        $ac->save();

        $this->dispatch("desactif");
        return redirect()->route('academicyear');
    }

    public function store(){
        $this->validate();

         // Vérifier que la date de fin est au moins 6 mois après la date de début

         $debut = \Carbon\Carbon::parse($this->debut);
         $fin = \Carbon\Carbon::parse($this->fin);

            if (-$fin->diffInMonths($debut) < 6) {
                $this->dispatch("lessdate");
                return;
            }

        if ($this->id) {
            $a = AcademicYear::where("id", $this->id)->first();

            $a->debut = $this->debut;
            $a->fin = $this->fin;

            $a->save();
            $this->outil->addHistorique("Mis à jour des données d'une année scolaire", "edit");

            $this->dispatch("update");
        }else{
            AcademicYear::create([
                "debut" => $this->debut,
                "fin" => $this->fin,
                "campus_id" => Auth::user()->campus_id,
                "encours" => false
            ]);

            $this->outil->addHistorique("Ajout d'une année scolaire", "add");
    
            $this->dispatch("added");
        }


        $this->changeStatus("list");
    }

    #[Layout("components.layouts.app")]
    public function render()
    {
        $this->outil = new Outils();
        return view('livewire.anneeacademique.academic-years', [
            "acs" => AcademicYear::where("campus_id", Auth::user()->campus_id)->where("is_deleting", false)->orderBy("fin", "DESC")->get()
        ]);
    }
}
