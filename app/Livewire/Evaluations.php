<?php

namespace App\Livewire;

use App\Models\Evaluation;
use App\Models\TypeEvaluation;
use App\Models\Matiere;
use App\Models\Classe;
use App\Models\User;
use App\Models\Outils;
use App\Models\AcademicYear;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Evaluations extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // Propriétés pour le formulaire d'évaluation
    public $evaluation_id, $titre, $description, $date_evaluation, $heure_debut, $duree;
    public $coefficient, $type_evaluation_id, $matiere_id, $classe_id, $professeur_id, $statut;
    public $selectedClasses = []; // Pour stocker les classes sélectionnées
    public $academic_year_id; // Ajout de l'année académique

    // Propriétés pour le formulaire de type d'évaluation
    public $type_id, $type_nom, $type_description, $coefficient_defaut;

    // Propriétés de filtrage et recherche
    public $search = '';
    public $type_filter = '';
    public $date_filter = '';
    public $year_filter = ''; // Nouveau filtre pour l'année académique
    public $showTypeModal = false;
    public $showEvalModal = false;
    public $showModal = false;
    public $isEditing = false;

    protected $rules = [
        'titre' => 'required|string|max:255',
        'description' => 'nullable|string',
        'date_evaluation' => 'required|date|after_or_equal:today',
        'heure_debut' => 'required',
        'duree' => 'required|integer|min:15',
        'coefficient' => 'required|numeric|min:0.1',
        'type_evaluation_id' => 'required|exists:type_evaluations,id',
        'matiere_id' => 'required|exists:matieres,id',
        'classe_id' => 'required|exists:classes,id',
        'professeur_id' => 'required|exists:users,id',
        'selectedClasses' => 'required|array|min:1',
        'statut' => 'required|in:planifié,en_cours,terminé,annulé',
        'academic_year_id' => 'required|exists:academic_years,id', // Validation année académique
    ];

    protected $messages = [
        'selectedClasses.required' => 'Veuillez sélectionner au moins une classe',
        'selectedClasses.min' => 'Veuillez sélectionner au moins une classe',
    ];

    protected $listeners = ['closeModal'];

    public function mount()
    {
        if (!Auth::user()->hasPermission('evaluations', 'view')) {
            abort(403);
        }
        // Définir l'année académique actuelle par défaut
        $this->academic_year_id = Auth::user()->campus->currentAcademicYear()?->id;
    }

    public function render()
    {
        $types = TypeEvaluation::where('campus_id', Auth::user()->campus_id)->get();
        $matieres = Matiere::where('campus_id', Auth::user()->campus_id)->get();
        $classes = Classe::where('campus_id', Auth::user()->campus_id)->get();
        $professeurs = User::where('campus_id', Auth::user()->campus_id)
                          ->where('role', 'professeur')
                          ->get();
        $academic_years = AcademicYear::where('campus_id', Auth::user()->campus_id)->get();
        $current_year = Auth::user()->campus->currentAcademicYear();

        $evaluations = Evaluation::where('campus_id', Auth::user()->campus_id)
            ->when($this->search, function ($query) {
                $query->where('titre', 'like', '%' . $this->search . '%');
            })
            ->when($this->type_filter, function ($query) {
                $query->where('type_evaluation_id', $this->type_filter);
            })
            ->when($this->date_filter, function ($query) {
                $query->whereDate('date_evaluation', $this->date_filter);
            })
            ->when($this->year_filter, function ($query) {
                $query->where('academic_year_id', $this->year_filter);
            }, function ($query) use ($current_year) {
                // Par défaut, afficher les évaluations de l'année en cours
                $query->where('academic_year_id', $current_year->id);
            })
            ->orderBy('date_evaluation', 'desc')
            ->paginate(10);

        return view('livewire.evaluation.evaluations', compact(
            'evaluations', 'types', 'matieres', 'classes', 'professeurs', 'academic_years', 'current_year'
        ));
    }

    public function saveType()
    {
        if (!Auth::user()->hasPermission('evaluations', 'create')) {
            $this->dispatch('error', ['message' => 'Permission non accordée']);
            return;
        }

        $this->validate([
            'type_nom' => 'required|string|max:255',
            'type_description' => 'nullable|string',
            'coefficient_defaut' => 'required|numeric|min:0.1',
        ]);

        $type = TypeEvaluation::create([
            'nom' => $this->type_nom,
            'description' => $this->type_description,
            'coefficient_defaut' => $this->coefficient_defaut,
            'campus_id' => Auth::user()->campus_id,
        ]);

        $outils = new Outils();
        $outils->addHistorique("Création du type d'évaluation : {$type->nom}", 'create');

        $this->resetType();
        $this->dispatch('success', ['message' => 'Type d\'évaluation créé avec succès']);
    }

    public function saveEvaluation()
    {
        if (!Auth::user()->hasPermission('evaluations', $this->isEditing ? 'edit' : 'create')) {
            $this->dispatch('error', ['message' => 'Permission non accordée']);
            return;
        }

        $this->validate();

        $data = [
            'titre' => $this->titre,
            'description' => $this->description,
            'date_evaluation' => $this->date_evaluation,
            'heure_debut' => $this->heure_debut,
            'duree' => $this->duree,
            'type_evaluation_id' => $this->type_evaluation_id,
            'matiere_id' => $this->matiere_id,
            'professeur_id' => $this->professeur_id,
            'statut' => $this->statut,
            'campus_id' => Auth::user()->campus_id,
            'academic_year_id' => $this->academic_year_id,
        ];

        if ($this->isEditing) {
            $evaluation = Evaluation::find($this->evaluation_id);
            $evaluation->update($data);
            $evaluation->classes()->sync($this->selectedClasses);
            $message = "Modification de l'évaluation : {$evaluation->titre}";
        } else {
            $evaluation = Evaluation::create($data);
            $evaluation->classes()->attach($this->selectedClasses);
            $message = "Création de l'évaluation : {$evaluation->titre}";
        }

        $outils = new Outils();
        $outils->addHistorique($message, $this->isEditing ? 'edit' : 'create');

        $this->resetEvaluationFields();
        $this->dispatch('hideModal');
        $this->dispatch('success', ['message' => 'Évaluation ' . ($this->isEditing ? 'modifiée' : 'créée') . ' avec succès']);
    }

    public function delete($id)
    {
        if (!Auth::user()->hasPermission('evaluations', 'delete')) {
            $this->dispatch('error', ['message' => 'Permission non accordée']);
            return;
        }

        $evaluation = Evaluation::find($id);
        $outils = new Outils();
        $outils->addHistorique("Suppression de l'évaluation : {$evaluation->titre}", 'delete');
        
        $evaluation->delete();
        $this->dispatch('success', ['message' => 'Évaluation supprimée avec succès']);
    }

    public function edit($id)
    {
        $this->isEditing = true;
        $evaluation = Evaluation::with('classes')->find($id);
        
        $this->evaluation_id = $evaluation->id;
        $this->titre = $evaluation->titre;
        $this->description = $evaluation->description;
        $this->date_evaluation = $evaluation->date_evaluation->format('Y-m-d');
        $this->heure_debut = $evaluation->heure_debut->format('H:i');
        $this->duree = $evaluation->duree;
        $this->type_evaluation_id = $evaluation->type_evaluation_id;
        $this->matiere_id = $evaluation->matiere_id;
        $this->professeur_id = $evaluation->professeur_id;
        $this->statut = $evaluation->statut;
        $this->selectedClasses = $evaluation->classes->pluck('id')->toArray();
        $this->academic_year_id = $evaluation->academic_year_id;
        
        $this->showEvalModal = true;
    }

    private function resetType()
    {
        $this->type_id = null;
        $this->type_nom = '';
        $this->type_description = '';
        $this->coefficient_defaut = 1.00;
        $this->showTypeModal = false;
    }

    private function resetEvaluationFields()
    {
        $this->evaluation_id = null;
        $this->titre = '';
        $this->description = '';
        $this->date_evaluation = '';
        $this->heure_debut = '';
        $this->duree = '';
        $this->type_evaluation_id = '';
        $this->matiere_id = '';
        $this->professeur_id = '';
        $this->selectedClasses = [];
        $this->statut = 'planifié';
        $this->academic_year_id = '';
        $this->showModal = false;
        $this->isEditing = false;
    }

    public function openModal()
    {
        // Réinitialiser et définir l'année académique actuelle
        $this->resetEvaluationFields();
        $this->academic_year_id = Auth::user()->campus->currentAcademicYear()?->id;
        $this->showModal = true;
        $this->dispatch('showModal');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetEvaluationFields();
    }
}
