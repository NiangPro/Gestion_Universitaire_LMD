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

    // Propriétés pour le formulaire
    public $titre;
    public $description;
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
        $this->resetValidation();
        $this->reset();
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function save()
    {
        $this->validate();

        try {

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

            $this->closeModal();
            $this->dispatch('added');
            $this->reset(["professeur_id", "matiere_id", "classe_id", "heure_debut", "heure_fin", "statut"]);
        } catch (\Exception $e) {
            // Ajout du message d'erreur
            dd($e->getMessage());
            $this->dispatch('alert');
        }
    }
}
