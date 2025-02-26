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

#[Title("Tableau de bord")]
class Cours extends Component
{
    public $status = "list";
    public $outil;
    public $academicYear = null;
    public $type = "";
    public $cours = null;
    public $matieres = null;
    public $title = "Liste des cours";

    // Propriétés pour le formulaire
    public $titre;
    public $description;
    public $professeur_id;
    public $matiere_id;
    public $classe_id;
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
        'heure_debut' => 'required',
        'heure_fin' => 'required',
        'statut' => 'required|in:en attente,encours,terminé'
    ];

    protected $messages = [
        'professeur_id.required' => 'Veuillez sélectionner un professeur',
        'matiere_id.required' => 'Veuillez sélectionner une matière',
        'classe_id.required' => 'Veuillez sélectionner une classe',
        'heure_debut.required' => 'L\'heure de début est requise',
        'heure_fin.after' => 'L\'heure de fin doit être après l\'heure de début'
    ];

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
                'heure_debut' => $this->heure_debut,
                'campus_id' => Auth::user()->campus_id,
                'academic_year_id' => Auth::user()->campus->academic_year_id,
                'heure_fin' => $this->heure_fin,
                'statut' => $this->statut,
            ]);

            $this->closeModal();
            $this->dispatch('added');
            $this->reset(["professeur_id", "matiere_id", "classe_id", "heure_debut", "heure_fin", "statut"]);
        } catch (\Exception $e) {
            $this->dispatch('alert');
        }
    }
}
