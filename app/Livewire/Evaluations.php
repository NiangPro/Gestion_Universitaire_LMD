<?php

namespace App\Livewire;

use App\Models\Evaluation;
use App\Models\TypeEvaluation;
use App\Models\Matiere;
use App\Models\Classe;
use App\Models\User;
use App\Models\Outils;
use App\Models\AcademicYear;
use App\Models\Semestre;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Evaluations extends Component
{
    use WithPagination;

    public $titre;
    public $description;
    public $date_evaluation;
    public $heure_debut;
    public $duree;
    public $type_evaluation_id;
    public $matiere_id;
    public $classes = [];
    public $evaluation_id;
    public $statut = 'planifié';
    public $mode = 'create';
    public $search = '';
    public $annee_academique_id;
    public $semestre_id;
    
    protected $rules = [
        'titre' => 'required|string|max:255',
        'description' => 'nullable|string',
        'date_evaluation' => 'required|date|after_or_equal:today',
        'heure_debut' => 'required',
        'duree' => 'required|integer|min:1',
        'type_evaluation_id' => 'required|exists:type_evaluations,id',
        'matiere_id' => 'required|exists:matieres,id',
        'classes' => 'required|array|min:1',
        'classes.*' => 'exists:classes,id',
        'statut' => 'required|in:planifié,en_cours,terminé,annulé'
    ];

    protected $messages = [
        'titre.required' => 'Le titre est requis',
        'titre.max' => 'Le titre ne doit pas dépasser 255 caractères',
        'date_evaluation.required' => 'La date est requise',
        'date_evaluation.after_or_equal' => 'La date doit être égale ou postérieure à aujourd\'hui',
        'heure_debut.required' => 'L\'heure de début est requise',
        'duree.required' => 'La durée est requise',
        'duree.integer' => 'La durée doit être un nombre entier',
        'duree.min' => 'La durée minimum est de 1 minute',
        'type_evaluation_id.required' => 'Le type d\'évaluation est requis',
        'type_evaluation_id.exists' => 'Le type d\'évaluation sélectionné n\'existe pas',
        'matiere_id.required' => 'La matière est requise',
        'matiere_id.exists' => 'La matière sélectionnée n\'existe pas',
        'classes.required' => 'Au moins une classe doit être sélectionnée',
        'classes.min' => 'Au moins une classe doit être sélectionnée',
        'classes.*.exists' => 'Une des classes sélectionnées n\'existe pas'
    ];

    public function mount()
    {
        $this->resetForm();
        $this->annee_academique_id = Auth::user()->campus->currentAcademicYear()?->id;
        $this->semestre_id = Auth::user()->campus->currentSemestre()?->id;
    }

    public function resetForm()
    {
        $this->reset(['titre', 'description', 'date_evaluation', 'heure_debut', 'duree', 
                      'type_evaluation_id', 'matiere_id', 'classes', 'evaluation_id', 
                      'statut', 'mode']);
    }

    public function showModal()
    {
        $this->resetForm();
        $this->mode = 'create';
        $this->dispatch('showModal');
    }

    public function render()
    {
        $query = Evaluation::with(['typeEvaluation', 'matiere', 'classes'])
            ->where('campus_id', Auth::user()->campus_id);

        if ($this->search) {
            $query->where('titre', 'like', '%' . $this->search . '%');
        }

        if ($this->annee_academique_id) {
            $query->where('academic_year_id', $this->annee_academique_id);
        }

        if ($this->semestre_id) {
            $query->where('semestre_id', $this->semestre_id);
        }

        $evaluations = $query->latest()->paginate(10);
        
        return view('livewire.evaluation.evaluations', [
            'evaluations' => $evaluations,
            'anneeAcademiques' => AcademicYear::where('campus_id', Auth::user()->campus_id)
                ->orderBy('debut', 'desc')
                ->get(),
            'semestres' => Semestre::where('campus_id', Auth::user()->campus_id)->get(),
            'typeEvaluations' => TypeEvaluation::where('campus_id', Auth::user()->campus_id)->get(),
            'matieres' => Matiere::where('campus_id', Auth::user()->campus_id)->get(),
            'allClasses' => Classe::where('campus_id', Auth::user()->campus_id)->get()
        ]);
    }

    public function save()
    {
        $this->validate();

  
            $currentYear = Auth::user()->campus->currentAcademicYear();
            $currentSemestre = Auth::user()->campus->currentSemestre();

            if (!$currentYear || !$currentSemestre) {
                $this->dispatch('showToast', [
                    'type' => 'error',
                    'message' => 'Impossible de créer une évaluation sans année académique ou semestre actif'
                ]);
                return;
            }

            $data = [
                'titre' => $this->titre,
                'description' => $this->description,
                'date_evaluation' => $this->date_evaluation,
                'heure_debut' => $this->heure_debut,
                'duree' => $this->duree,
                'type_evaluation_id' => $this->type_evaluation_id,
                'matiere_id' => $this->matiere_id,
                'academic_year_id' => $currentYear->id,
                'semestre_id' => $currentSemestre->id,
                'campus_id' => Auth::user()->campus_id,
                'statut' => $this->statut
            ];


            if ($this->mode === 'edit' && $this->evaluation_id) {
                $evaluation = Evaluation::findOrFail($this->evaluation_id);
                $evaluation->update($data);
                $evaluation->classes()->sync($this->classes);
                $message = 'Évaluation mise à jour avec succès';
                $outils = new Outils();
                $outils->addHistorique('Modification de l\'évaluation : ' . $evaluation->titre, 'modification');
            } else {
                $evaluation = Evaluation::create($data);
                $evaluation->classes()->attach($this->classes);
                $message = 'Évaluation créée avec succès';
                $outils = new Outils();
                $outils->addHistorique('Création d\'une nouvelle évaluation : ' . $evaluation->titre, 'creation');
            }

            $this->dispatch('showToast', [
                'type' => 'success',
                'message' => $message
            ]);
            $this->resetForm();
            $this->dispatch('evaluation-saved');
            $this->dispatch('closeModal');

    }

    public function edit(Evaluation $evaluation)
    {
        $this->mode = 'edit';
        $this->evaluation_id = $evaluation->id;
        $this->titre = $evaluation->titre;
        $this->description = $evaluation->description;
        $this->date_evaluation = $evaluation->date_evaluation;
        $this->heure_debut = $evaluation->heure_debut;
        $this->duree = $evaluation->duree;
        $this->type_evaluation_id = $evaluation->type_evaluation_id;
        $this->matiere_id = $evaluation->matiere_id;
        $this->statut = $evaluation->statut;
        $this->classes = $evaluation->classes->pluck('id')->toArray();
    }

    public function delete(Evaluation $evaluation)
    {
        try {
            $titre = $evaluation->titre;
            $evaluation->delete();
            $outils = new Outils();
            $outils->addHistorique('Suppression de l\'évaluation : ' . $titre, 'suppression');
            $this->dispatch('showToast', [
                'type' => 'success',
                'message' => 'Évaluation supprimée avec succès'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToast', [
                'type' => 'error',
                'message' => 'Une erreur est survenue lors de la suppression'
            ]);
        }
    }
}
