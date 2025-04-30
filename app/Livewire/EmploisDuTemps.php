<?php

namespace App\Livewire;

use App\Models\AcademicYear;
use App\Models\Classe;
use App\Models\Cour;
use App\Models\User;
use App\Models\Salle;
use App\Models\Semaine;
use App\Models\TypeEvaluation;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Emplois du temps")]
class EmploisDuTemps extends Component
{
    public $title = "Emplois du temps";
    public $academicYear = null;
    public $type = "";
    public $cours = null;
    public $classrooms = [];
    public $teachers = [];
    public $courses = [];
    public $trouve = false;

    public function checkTimeSlotConflict($cours)
    {
        $conflits = [];
        
        // Vérifier les conflits de salle
        $salleConflits = Cour::where('salle_id', $cours->salle_id)
            ->where('semaine_id', $cours->semaine_id)
            ->where('id', '!=', $cours->id)
            ->where(function($query) use ($cours) {
                $query->whereBetween('heure_debut', [$cours->heure_debut, $cours->heure_fin])
                    ->orWhereBetween('heure_fin', [$cours->heure_debut, $cours->heure_fin]);
            })
            ->get();
            
        if($salleConflits->count() > 0) {
            $conflits[] = 'Salle déjà occupée';
        }
        
        // Vérifier les conflits de classe
        $classeConflits = Cour::where('classe_id', $cours->classe_id)
            ->where('semaine_id', $cours->semaine_id)
            ->where('id', '!=', $cours->id)
            ->where(function($query) use ($cours) {
                $query->whereBetween('heure_debut', [$cours->heure_debut, $cours->heure_fin])
                    ->orWhereBetween('heure_fin', [$cours->heure_debut, $cours->heure_fin]);
            })
            ->get();
            
        if($classeConflits->count() > 0) {
            $conflits[] = 'La classe a déjà un cours';
        }
        
        // Vérifier les conflits de professeur
        $profConflits = Cour::where('professeur_id', $cours->professeur_id)
            ->where('semaine_id', $cours->semaine_id)
            ->where('id', '!=', $cours->id)
            ->where(function($query) use ($cours) {
                $query->whereBetween('heure_debut', [$cours->heure_debut, $cours->heure_fin])
                    ->orWhereBetween('heure_fin', [$cours->heure_debut, $cours->heure_fin]);
            })
            ->get();
            
        if($profConflits->count() > 0) {
            $conflits[] = 'Le professeur a déjà un cours';
        }
        
        return $conflits;
    }

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
        return view('livewire.schedule.emplois-du-temps', [
            "academicYears" => AcademicYear::where("campus_id", Auth::user()->campus_id)
                ->orderByDesc(function($query) {
                    return $query->id == Auth::user()->campus->currentAcademicYear()->id;
                })
                ->get(),
            'semaines' => Semaine::all()
        ]);
    }
}
