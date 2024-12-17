<?php

namespace App\Livewire;

use App\Models\Activation;
use App\Models\AcademicYear;
use App\Models\Campus;
use App\Models\Departement;
use App\Models\Filiere;
use App\Models\Historique;
use App\Models\Matiere;
use App\Models\NiveauEtude;
use App\Models\Pack;
use App\Models\UniteEnseignement;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Poubelle")]
class Corbeille extends Component
{
    public function restaurer($model, $id){
        if ($model == "AcademicYear") {
            $ac = AcademicYear::where("id", $id)->first();

            $ac->is_deleting = false;
            $ac->save();

        }elseif($model == "Campus"){
            $c = Campus::where("id", $id)->first();
            
            $c->is_deleting = false;

            $c->save();
        }elseif($model == "Pack"){
            $c = Pack::where("id", $id)->first();
            
            $c->is_deleting = false;

            $c->save();
        }elseif($model == "Departement"){
            $c = Departement::where("id", $id)->first();
            
            $c->is_deleting = false;

            $c->save();
        }
        $this->dispatch("restore");
    }

    public function getDeletedItems($table){
        if ($table == "AcademicYear") {
            return AcademicYear::where("is_deleting", true)->orderBy("id", "DESC")->get();
        }elseif ($table == "Campus") {
            return Campus::where("is_deleting", true)->orderBy("id", "DESC")->get();
        }elseif ($table == "Departement") {
            return Departement::where("is_deleting", true)->orderBy("id", "DESC")->get();
        }elseif ($table == "Pack") {
            return Pack::where("is_deleting", true)->orderBy("id", "DESC")->get();
        }elseif ($table == "User") {
            return User::where("is_deleting", true)->orderBy("id", "DESC")->get();
        }elseif ($table == "Filiere") {
            return Filiere::where("is_deleting", true)->orderBy("id", "DESC")->get();
        }elseif ($table == "Matiere") {
            return Matiere::where("is_deleting", true)->orderBy("id", "DESC")->get();
        }elseif ($table == "NiveauEtude") {
            return NiveauEtude::where("is_deleting", true)->orderBy("id", "DESC")->get();
        }elseif ($table == "UniteEnseignement") {
            return UniteEnseignement::where("is_deleting", true)->orderBy("id", "DESC")->get();
        }elseif ($table == "Historique") {
            return [];
        }
        return $table::where("is_deleting", true)->orderBy("id", "DESC")->get();
    }

    public function supprimer($model, $id){
        if ($model == "AcademicYear") {
            $ac = AcademicYear::where("id", $id)->first();
            $ac->delete();
        }elseif($model == "Campus"){
            $c = Campus::where("id", $id)->first();
            $c->delete();
        }elseif($model == "Pack"){
            $c = Pack::where("id", $id)->first();
            $c->delete();
        }elseif($model == "Departement"){
            $c = Departement::where("id", $id)->first();
            $c->delete();
        }elseif($model == "Filiere"){
            $c = Filiere::where("id", $id)->first();
            $c->delete();
        }elseif($model == "Matiere"){
            $c = Matiere::where("id", $id)->first();
            $c->delete();
        }elseif($model == "NiveauEtude"){
            $c = NiveauEtude::where("id", $id)->first();
            $c->delete();
        }elseif($model == "UniteEnseignement"){
            $c = UniteEnseignement::where("id", $id)->first();
            $c->delete();
        }
        $this->dispatch("delete");
    }

    #[Layout("components.layouts.app")]
    public function render()
    {

        return view('livewire.corbeille', [
            "tables" => Activation::orderBy("nom", "ASC")->get()
        ]);
    }
}
