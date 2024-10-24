<?php

namespace App\Livewire;

use App\Models\Activation;
use App\Models\AcademicYear;
use App\Models\Campus;
use App\Models\Departement;
use App\Models\Pack;
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
        }
        return $table::where("is_deleting", true)->orderBy("id", "DESC")->get();
    }

    #[Layout("components.layouts.app")]
    public function render()
    {

        return view('livewire.corbeille', [
            "tables" => Activation::orderBy("nom", "ASC")->get()
        ]);
    }
}
