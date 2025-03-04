<?php

namespace App\Livewire;

use App\Models\AcademicYear;
use App\Models\Cour;
use App\Models\Outils;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\User;
use App\Models\Matiere;
use App\Models\Classe;
use App\Models\Salle;
use App\Models\Semaine;

#[Title("Cours")]
class Cours extends Component
{
    public $status = "list";
    public $outil;
    public $academicYear = null;
    public $type = "";
    public $cours = null;
    public $classrooms = [];
    public $teachers = [];
    public $matieres = null;
    public $courses = [];
    public $title = "Liste des cours";
    public $modalTitle = "Ajouter un cours";

    // Propriétés pour le formulaire
    public $id = null;
    public $professeur_id;
    public $matiere_id;
    public $classe_id;
    public $salle_id;
    public $semaine_id;
    public $heure_debut;
    public $heure_fin;
    public $statut = 'en attente';

    // Propriété pour gérer le modal
    public $isOpen = false;

    // Règles de validation
    protected $rules = [
        'professeur_id' => 'required|exists:users,id',
        'matiere_id' => 'required|exists:matieres,id',
        'classe_id' => 'required|exists:classes,id',
        'salle_id' => 'required|exists:salles,id',
        'semaine_id' => 'required|exists:semaines,id',
        'heure_debut' => 'required',
        'heure_fin' => 'required',
        'statut' => 'required'
    ];

    protected $messages = [
        'professeur_id.required' => 'Veuillez sélectionner un professeur',
        'matiere_id.required' => 'Veuillez sélectionner une matière',
        'classe_id.required' => 'Veuillez sélectionner une classe',
        'semaine_id.required' => 'Veuillez sélectionner le jour',
        'salle_id.required' => 'Veuillez sélectionner une salle',
        'heure_debut.required' => 'L\'heure de début est requise',
        'heure_fin.after' => 'L\'heure de fin doit être après l\'heure de début',
        'statut.required' => 'Le statut est obligatoire'
    ];

    public function changeStatus($status)
    {
        $this->status = $status;
        $this->init();
    }

    public function updatedAcademicYear($value)
    {
        $this->academicYear = $value;
        $this->reset(["type", "cours", "classrooms", "teachers", "courses"]);
    }

    public function updatedType($value)
    {
        $this->type = $value;
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
        if ($value) {
            if (count($this->classrooms) > 0) {
                $this->courses = Cour::where("classe_id", $value)->get();
            } elseif (count($this->teachers) > 0) {
                $this->courses = Cour::where("professeur_id", $value)->get();
            }
        }
        
    }

    public function updatedClasseId($value)
    {
        $this->classe_id = $value;
        $this->matiere_id = null;
        $this->matieres = [];
        if ($value) {
            $classe = Classe::find($value);
            foreach ($classe->filiere->uniteEnseignements as $unite) {
                foreach ($unite->matieres as $matiere) {
                    $this->matieres[] = $matiere;
                }
            }
        }
    }

    public function edit($id)
    {
        $course = Cour::find($id);

        $this->id = $course->id;
        $this->professeur_id = $course->professeur_id;
        $this->matiere_id = $course->matiere_id;
        $this->classe_id = $course->classe_id;
        $this->salle_id = $course->salle_id;
        $this->semaine_id = $course->semaine_id;
        $this->heure_debut = $course->heure_debut;
        $this->heure_fin = $course->heure_fin;
        $this->statut = $course->statut;
        $this->matieres = [];

        $classe = Classe::find($this->classe_id);
            foreach ($classe->filiere->uniteEnseignements as $unite) {
                foreach ($unite->matieres as $matiere) {
                    $this->matieres[] = $matiere;
                }
            }

        $this->modalTitle = "Modification cours";
        $this->isOpen = true;
    }

    #[Layout("components.layouts.app")]
    public function render()
    {
        $this->outil = new Outils();
        return view('livewire.cours.cours', [
            "cours" => Cour::get(),
            "academicYears" => AcademicYear::where("campus_id", Auth::user()->campus_id)->orderBy("encours", "desc")->get(),
            'professeurs' => User::where('role', 'professeur')->get(),
            'classes' => Classe::all(),
            'cours' => Cour::with(['professeur', 'matiere', 'classe'])->get(),
            'semaines' => Semaine::all(),
            'salles' => Salle::all()
        ]);
    }

    public function openModal()
    {
        // $this->resetValidation();
        // $this->reset();
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->reset(["professeur_id", "matiere_id", "classe_id", "heure_debut", "heure_fin", "statut", "id", "semaine_id"]);
    }

    public function save()
    {
        $this->validate();

        try {
            $isSalleOccupee = Cour::where('salle_id', $this->salle_id)
                ->where('semaine_id', $this->semaine_id)
                ->where(function($query) {
                    $query->whereBetween('heure_debut', [$this->heure_debut, $this->heure_fin])
                        ->orWhereBetween('heure_fin', [$this->heure_debut, $this->heure_fin]);
                })
                ->where('id', '!=', $this->id ?? 0)
                ->exists();

            $isClasseOccupee = Cour::where('classe_id', $this->classe_id)
                ->where('semaine_id', $this->semaine_id)
                ->where(function($query) {
                    $query->whereBetween('heure_debut', [$this->heure_debut, $this->heure_fin])
                        ->orWhereBetween('heure_fin', [$this->heure_debut, $this->heure_fin]);
                })
                ->where('id', '!=', $this->id ?? 0)
                ->exists();

            $isProfesseurOccupe = Cour::where('professeur_id', $this->professeur_id)
                ->where('semaine_id', $this->semaine_id)
                ->where(function($query) {
                    $query->whereBetween('heure_debut', [$this->heure_debut, $this->heure_fin])
                        ->orWhereBetween('heure_fin', [$this->heure_debut, $this->heure_fin]);
                })
                ->where('id', '!=', $this->id ?? 0)
                ->exists();

            if ($isSalleOccupee || $isClasseOccupee || $isProfesseurOccupe) {
                if($isSalleOccupee){
                    $this->dispatch('alertSalle');
                }elseif($isClasseOccupee){
                    $this->dispatch('alertClasse');
                }elseif($isProfesseurOccupe){
                    $this->dispatch('alertProfesseur');
                }
            }else {
                if($this->id){
                    $cour = Cour::find($this->id);
                    $cour->update([
                        'professeur_id' => $this->professeur_id,
                        'matiere_id' => $this->matiere_id,
                        'classe_id' => $this->classe_id,
                        'salle_id' => $this->salle_id,
                        'semaine_id' => $this->semaine_id,
                        'heure_debut' => $this->heure_debut,
                        'heure_fin' => $this->heure_fin,
                        'statut' => $this->statut,
                    ]);
                    $this->dispatch('updated');
                } else {
                    Cour::create([
                        'professeur_id' => $this->professeur_id,
                        'matiere_id' => $this->matiere_id,
                        'classe_id' => $this->classe_id,
                        'salle_id' => $this->salle_id,
                        'semaine_id' => $this->semaine_id,
                        'heure_debut' => $this->heure_debut,
                        'campus_id' => Auth::user()->campus_id,
                        'academic_year_id' => Auth::user()->campus->currentAcademicYear()->id,
                        'heure_fin' => $this->heure_fin,
                        'statut' => $this->statut,
                    ]);
                    $this->dispatch('added');
                }
                $this->closeModal();
            }
            
        } catch (\Exception $e) {
            $this->dispatch('alert');
        }
    }
}
