<?php

namespace App\Livewire;

use App\Models\Cour;
use Livewire\Component;
use App\Models\Note;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use App\Models\User;
use Livewire\Attributes\On;
use App\Models\Classe;
use App\Models\AcademicYear;
use App\Models\Semestre;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\UniteEnseignement;
use App\Models\Matiere;
use App\Models\Outils;

#[Title("Notes")]
class Notes extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $academic_year_id;
    public $classe_id;
    public $semestre_id;
    public $outil;

    public $etudiant_id = null;
    public $cours_id = null;
    public $note = null;
    public $matiere_id = null;
    public $observation;
    public $showModal = false;
    public $showDetailsModal = false;
    public $selectedNote;
    public $isEditing = false;
    public $noteId;
    public $searchTerm = '';
    public $filterType = '';
    public $filterSemestre = '';
    public $notes = [];
    public $ue_id = null;
    public $uniteEnseignements = [];
    public $matieres = [];
    public $type_evaluation_id = null;
    public $typeEvaluations = [];
    public $type_evaluation = null;
    public $currentNote = null;
    public $editNoteId;
    public $editNote = [
        'valeur' => '',
        'type_evaluation' => '',
        'semestre_id' => '',
        'observation' => ''
    ];
    public $showDeleteModal = false;
    public $noteToDelete = null;

    protected $rules = [
        'classe_id' => 'required',
        'etudiant_id' => 'required',
        'cours_id' => 'required',
        'note' => 'required|numeric|min:0|max:20',
        'matiere_id' => 'required',
        'semestre_id' => 'required'
    ];

    public function mount()
    {
        $currentAcademicYear = Auth::user()->campus->currentAcademicYear();
        if ($currentAcademicYear) {
            $this->academic_year_id = $currentAcademicYear->id;
        }
        $this->outil = new Outils();
        $this->typeEvaluations = Auth::user()->campus->typeEvaluations;
    }

    public function changeStatut($statut)
    {
        if($statut == 'list'){
            $this->showModal = false;
        }else{
            $this->showModal = true;
        }

         // Réinitialiser les variables
         $this->reset([
            'notes',
            'type_evaluation',
            'semestre_id',
            'matiere_id',
            'isEditing',
            'currentNote',
            'ue_id'
        ]);
    }

    public function updatedAcademicYearId()
    {
        $this->classe_id = null;
        $this->resetPage();
    }

    public function edit($noteId)
    {
        $this->changeStatut('edit');
        $this->isEditing = true;
        $this->editNoteId = $noteId;
        
        $note = Note::with(['etudiant', 'matiere'])->find($noteId);
        $this->currentNote = $note;
        
        $this->editNote = [
            'valeur' => $note->note,
            'type_evaluation' => $note->type_evaluation,
            'semestre_id' => $note->semestre_id,
            'observation' => $note->observation
        ];
        
        $this->classe_id = $note->etudiant->classe_id;
        $this->matiere_id = $note->matiere_id;
        $this->loadMatieres();
    }

    public function sauvegarderNote()
    {
        try {
            // Validation
            $this->validate([
                'classe_id' => 'required',
                'matiere_id' => 'required',
                'type_evaluation' => 'required',
                'semestre_id' => 'required',
            ]);

            foreach ($this->notes as $etudiantId => $noteData) {
                // Validation de chaque note
                if (!isset($noteData['note']) || $noteData['note'] < 0 || $noteData['note'] > 20) {
                    continue; // Sauter les notes invalides
                }

                Note::create([
                    'etudiant_id' => $etudiantId,
                    'matiere_id' => $this->matiere_id,
                    'academic_year_id' => Auth::user()->campus->currentAcademicYear()->id,
                    'type_evaluation' => $this->type_evaluation,
                    'note' => $noteData['note'],
                    'observation' => $noteData['observation'] ?? null,
                    'semestre_id' => $this->semestre_id,
                    'campus_id' => Auth::user()->campus_id
                ]);
            }

            // Ajouter à l'historique
            $this->outil = new Outils();
            $this->outil->addHistorique(
                "Ajout des notes en {$this->type_evaluation} pour la matière ID: {$this->matiere_id}, Semestre ID: {$this->semestre_id}",
                "create"
            );

            // Réinitialiser les variables
            $this->reset([
                'notes',
                'type_evaluation',
                'semestre_id',
                'matiere_id',
                'ue_id',
                'showModal'
            ]);

            // Message de succès
            session()->flash('success', 'Les notes ont été enregistrées avec succès.');
            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Les notes ont été enregistrées avec succès.'
            ]);

        } catch (\Exception $e) {
            // Gestion des erreurs
            session()->flash('error', 'Une erreur est survenue lors de l\'enregistrement des notes.');
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Une erreur est survenue lors de l\'enregistrement des notes.'
            ]);
        }
    }

    public function confirmDelete($noteId)
    {
        $this->noteToDelete = Note::with([
            'etudiant.inscriptions.classe', 
            'matiere', 
            'semestre'
        ])->find($noteId);
        
        $this->dispatch('showDeleteModal');
    }

    public function delete($noteId)
    {
        try {
            $note = Note::find($noteId);
            $etudiantInfo = "{$note->etudiant->prenom} {$note->etudiant->nom}";
            
            $note->delete();

            // Ajouter à l'historique
            $outil = new Outils();
            $outil->addHistorique("Suppression de la note de l'étudiant {$etudiantInfo}", "delete");

            session()->flash('success', 'La note a été supprimée avec succès.');
            
            $this->showDeleteModal = false;
            $this->currentNote = null;
            $this->dispatch('hide-delete-modal');

        } catch (\Exception $e) {
            session()->flash('error', 'Une erreur est survenue lors de la suppression.');
        }
    }

    public function resetFilters()
    {
        $this->reset(['searchTerm', 'filterType', 'filterSemestre']);
    }

    public function calculerMoyenne($etudiant_id, $semestre_id)
    {
        $notes = Note::where('etudiant_id', $etudiant_id)
                     ->where('semestre_id', $semestre_id)
                     ->get();

        $totalPoints = 0;
        $totalCoefficients = 0;

        foreach ($notes as $note) {
            $totalPoints += $note->note * $note->coefficient;
            $totalCoefficients += $note->coefficient;
        }

        return $totalCoefficients > 0 ? $totalPoints / $totalCoefficients : 0;
    }

    public function loadEtudiants()
    {
        $this->etudiant_id = null; // Réinitialiser la sélection de l'étudiant
    }

    public function getClassesProperty()
    {
        return Classe::where('campus_id', Auth::user()->campus_id)
                    ->where('is_deleting', 0)
                    ->get();
    }

    public function getEtudiantsByCampus()
    {
        if (!$this->classe_id) {
            return collect();
        }

        $currentAcademicYear = Auth::user()->campus->currentAcademicYear();

        if (!$currentAcademicYear) {
            return collect();
        }

        return User::whereHas('inscriptions', function($query) use ($currentAcademicYear) {
                $query->where('classe_id', $this->classe_id)
                      ->where('academic_year_id', $currentAcademicYear->id);
            })
            ->where('campus_id', Auth::user()->campus_id)
            ->where('role', 'etudiant')
            ->where('is_deleting', 0)
            ->orderBy('nom')
            ->get();
    }

    public function updatedClasseId($value)
    {
        $this->ue_id = null;
        $this->matiere_id = null;
        if ($value) {
            $classe = Classe::find($value);
            if ($classe) {
                $this->uniteEnseignements = UniteEnseignement::where('campus_id', Auth::user()->campus_id)
                    ->where('filiere_id', $classe->filiere_id)
                    ->where('is_deleting', 0)
                    ->get();
            }
        }
        $this->loadEtudiants();
        $this->resetPage();
    }

    public function loadMatieres()
    {
        $this->matiere_id = null;
        if ($this->ue_id) {
            $this->matieres = Matiere::where('campus_id', Auth::user()->campus_id)
                ->where('unite_enseignement_id', $this->ue_id)
                ->where('is_deleting', 0)
                ->get();
        } else {
            $this->matieres = collect();
        }
    }

    public function updatedUeId($value)
    {
        $this->matiere_id = null;
        if ($value) {
            $this->matieres = Matiere::where('campus_id', Auth::user()->campus_id)
                ->where('unite_enseignement_id', $value)
                ->where('is_deleting', 0)
                ->get();
        } else {
            $this->matieres = collect();
        }
    }

    public function updatedTypeEvaluation($value)
    {
        $this->semestre_id = null;
        $this->dispatch('refresh-component');
    }

    public function updatedSemestreId()
    {
        if ($this->semestre_id) {
            $this->loadEtudiants();
            $this->showModal = true;
        }
        $this->resetPage();
    }

    public function updateNote()
    {
        $this->validate([
            'editNote.valeur' => 'required|numeric|between:0,20',
            'editNote.type_evaluation' => 'required',
            'editNote.semestre_id' => 'required'
        ]);

        try {
            $note = Note::find($this->editNoteId);
            $note->update([
                'note' => $this->editNote['valeur'],
                'type_evaluation' => $this->editNote['type_evaluation'],
                'semestre_id' => $this->editNote['semestre_id'],
                'observation' => $this->editNote['observation'] ?? null
            ]);

            // Ajouter à l'historique
            $outil = new Outils();
            $outil->addHistorique(
                "Modification de la note de l'étudiant {$note->etudiant->prenom} {$note->etudiant->nom}",
                "update"
            );

            session()->flash('success', 'Note modifiée avec succès');
            $this->resetEdit();

        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors de la modification de la note');
        }
    }

    public function resetEdit()
    {
        $this->isEditing = false;
        $this->editNoteId = null;
        $this->editNote = [
            'valeur' => '',
            'type_evaluation' => '',
            'semestre_id' => '',
            'observation' => ''
        ];
        $this->showModal = false;
    }

    #[Layout("components.layouts.app")]
    public function render()
    {
        if ($this->classe_id && empty($this->uniteEnseignements)) {
            $classe = Classe::find($this->classe_id);
            if ($classe) {
                $this->uniteEnseignements = UniteEnseignement::where('campus_id', Auth::user()->campus_id)
                    ->where('filiere_id', $classe->filiere_id)
                    ->where('is_deleting', 0)
                    ->get();
            }
        }

        $notesQuery = Note::query()
            ->with(['etudiant', 'matiere', 'semestre'])
            ->where('campus_id', Auth::user()->campus_id);

        if ($this->academic_year_id) {
            $notesQuery->where('academic_year_id', $this->academic_year_id);
        }

        if ($this->classe_id) {
            $notesQuery->whereHas('etudiant.inscriptions', function($query) {
                $query->where('classe_id', $this->classe_id)
                    ->where('academic_year_id', $this->academic_year_id);
            });
        }

        if ($this->semestre_id) {
            $notesQuery->where('semestre_id', $this->semestre_id);
        }

        $classes = Classe::where('campus_id', Auth::user()->campus_id)
            ->where('is_deleting', 0)
            ->where('academic_year_id', $this->academic_year_id)
            ->with('filiere')
            ->get();

        return view('livewire.note.notes', [
            'notesList' => $notesQuery->orderBy('created_at', 'desc')->paginate(10),
            'academic_years' => AcademicYear::where('campus_id', Auth::user()->campus_id)
                              ->orderBy('created_at', 'desc')
                              ->get(),
            'classes' => Auth::user()->campus->classes,
            'semestres' => Semestre::where('campus_id', Auth::user()->campus_id)
                          ->where('is_active', true)
                          ->get(),
            'etudiants' => $this->getEtudiantsByCampus(),
            'uniteEnseignements' => $this->uniteEnseignements,
            'matieres' => $this->matieres,
            'cours' => Cour::where('campus_id', Auth::user()->campus_id)
                          ->where('is_deleting', 0)
                          ->get(),
        ]);
    }

    public function showDetails($noteId)
    {
        $this->selectedNote = Note::with([
            'etudiant.inscriptions.classe',
            'matiere.uniteEnseignement',
            'academicYear',
            'semestre',
        ])->find($noteId);
        
        $this->showDetailsModal = true;
    }

    public function closeDetailsModal()
    {
        $this->showDetailsModal = false;
        $this->selectedNote = null;
    }

    #[On('resetModal')]
    public function resetDeleteData()
    {
        $this->noteToDelete = null;
    }
}
