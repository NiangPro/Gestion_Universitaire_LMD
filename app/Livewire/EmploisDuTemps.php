<?php

namespace App\Livewire;

use App\Models\AcademicYear;
use App\Models\Classe;
use App\Models\Cour;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Emplois du temps")]
class EmploisDuTemps extends Component
{
    public $classrooms = [];
    public $teachers = [];
    public $courses = [];
    public $title = "Emplois du temps";
    public $academicYear = null;
    public $type = "";
    public $cours = null;
    public $trouve = false;

    public function updatedAcademicYear($value)
    {
        $this->trouve = false;
        $this->academicYear = $value;
        $this->reset(["type", "cours", "classrooms", "teachers", "courses"]);
    }

    public function updatedType($value)
    {
        $this->type = $value;
        $this->trouve = false;
        $this->reset(["cours", "classrooms", "teachers", "courses"]);


        if ($this->type == "classe") {
            $this->classrooms = Classe::where("campus_id", Auth::user()->campus_id)->whereHas("cours", function($query) {
                $query->where("academic_year_id", $this->academicYear);
            })->get();

        } else {
            $this->teachers = User::whereHas("cours", function($query) {
                $query->where("campus_id", Auth::user()->campus_id)->where("academic_year_id", $this->academicYear);
            })->get();
        }
    }

    public function updatedCours($value)
    {
        $this->trouve = true;
        
        if ($this->type == "classe") {
            $this->courses = Cour::where("classe_id", $value)->where("academic_year_id", $this->academicYear)->where("is_deleting", false)->get();
        } else {
            $this->courses = Cour::where("professeur_id", $value)->where("academic_year_id", $this->academicYear)->where("is_deleting", false)->get();
        }
    
        // dd($this->courses); 
    }

    #[Layout("components.layouts.app")]
    public function render()
    {
        return view('livewire.schedule.emplois-du-temps',[
            "academicYears" => AcademicYear::where("campus_id", Auth::user()->campus_id)->orderBy("encours", "desc")->get(),
        ]);
    }
}
