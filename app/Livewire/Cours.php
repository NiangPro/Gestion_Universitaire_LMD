<?php

namespace App\Livewire;

use App\Models\AcademicYear;
use App\Models\Cour;
use App\Models\Outils;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Tableau de bord")]
class Cours extends Component
{
    public $status = "list";
    public $outil;
    public $academicYear = null;
    public $type = "";
    public $cours = null;

    public function changeStatus($status)
    {
        $this->status = $status;
        $this->init();
    }

    public function updatedAcademicYear($value)
    {
        $this->academicYear = $value;
        $this->reset(["type", "cours"]);
    }

    public function updatedType($value)
    {
        $this->type = $value;
        $this->reset(["cours"]);

        if ($this->type == "classe") {
            $this->cours = Cour::where("campus_id", Auth::user()->campus_id)->where("academic_year_id", $this->academicYear)->where("classe_id", $this->type)->get();
        } else {
            $this->cours = Cour::where("campus_id", Auth::user()->campus_id)->where("academic_year_id", $this->academicYear)->where("professeur_id", $this->type)->get();
        }
    }

    #[Layout("components.layouts.app")]
    public function render()
    {
        $this->outil = new Outils();
        return view('livewire.cours.cours', [
            "cours" => Cour::get(),
            "academicYears" => AcademicYear::where("campus_id", Auth::user()->campus_id)->orderBy("encours", "desc")->get()
        ]);
    }
}
