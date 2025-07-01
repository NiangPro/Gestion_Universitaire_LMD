<?php

namespace App\Livewire;

use App\Models\Note;
use App\Models\Cour;
use App\Models\Classe;
use App\Models\Matiere;
use App\Models\Semestre;
use App\Models\AcademicYear;
use App\Models\UniteEnseignement;
use App\Models\User;
use App\Models\Outils;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;

#[Title("Notes")]
class Notes extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'classe_id' => 'required',
        'matiere_id' => 'required',
        'type_evaluation_id' => 'required',
        'note' => 'nullable|numeric|min:0|max:20',
        'observation' => 'nullable|string'
    ];

    public $academic_year_id;
    public $classe_id;
    public $ue_id;
    public $matiere_id;
    public $type_evaluation_id;
    public $type_evaluation;
    public $semestre_id;
    public $notes = [];
    public $outil;
    public $typeEvaluations;
    public $showModal = false;
    public $isEditing = false;
    public $editNoteId;
    public $editNote = [
        'valeur' => '',
        'type_evaluation_id' => '',
        'semestre_id' => '',
        'observation' => ''
    ];
    public $showDetailsModal = false;
    public $selectedNote = null;
    public $noteToDelete = null;
    public $showDeleteModal = false;
    public $currentNote = null;
    public $uniteEnseignements = [];
    public $matieres = [];

    public function updatedMatiereId($value)
    {
        $this->type_evaluation_id = null;
        $this->type_evaluation = null;
        $this->notes = [];
        if ($value) {
            $this->loadEtudiants();
            $this->dispatch('refresh-component');
        } else {
            $this->reset(['notes', 'type_evaluation_id', 'type_evaluation']);
            $this->dispatch('refresh-component');
        }
    }

    public function updatedTypeEvaluationId($value)
    {
        if ($value) {
            $typeEvaluation = $this->typeEvaluations->where('id', $value)->first();
            if ($typeEvaluation) {
                $this->type_evaluation = $typeEvaluation->nom;
                $this->notes = [];
                $this->dispatch('refresh-component');
            }
        } else {
            $this->type_evaluation = null;
            $this->notes = [];
            $this->dispatch('refresh-component');
        }
    }

    public function mount()
    {
        $currentAcademicYear = Auth::user()->campus->currentAcademicYear();
        if ($currentAcademicYear) {
            $this->academic_year_id = $currentAcademicYear->id;
        }
        $this->semestre_id = Semestre::where('campus_id', Auth::user()->campus_id)
            ->where('is_active', true)
            ->where('is_deleting', false)
            ->first()?->id;
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

        $this->reset([
            'notes',
            'type_evaluation',
            'type_evaluation_id',
            'matiere_id',
            'isEditing',
            'currentNote',
            'ue_id',
            'classe_id'
        ]);
    }

    public function loadEtudiants()
    {
        if ($this->classe_id && $this->academic_year_id) {
            $etudiants = User::whereHas('inscriptions', function ($query) {
                $query->where('classe_id', $this->classe_id)
                    ->where('academic_year_id', $this->academic_year_id);
            })
            ->with(['inscriptions' => function ($query) {
                $query->where('classe_id', $this->classe_id)
                    ->where('academic_year_id', $this->academic_year_id);
            }])
            ->where('campus_id', Auth::user()->campus_id)
            ->where('role', 'etudiant')
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();

            foreach ($etudiants as $etudiant) {
                if (!isset($this->notes[$etudiant->id])) {
                    $this->notes[$etudiant->id] = [
                        'note' => '',
                        'observation' => ''
                    ];
                }
            }

            return $etudiants;
        }

        return collect();
    }

    public function updatedClasseId($value)
    {
        if ($value) {
            $this->loadEtudiants();
            $this->resetPage();
            $classe = Classe::find($value);
            if ($classe) {
                $this->uniteEnseignements = UniteEnseignement::where('campus_id', Auth::user()->campus_id)
                    ->where('filiere_id', $classe->filiere_id)
                    ->where('is_deleting', false)
                    ->get();
            }
        } else {
            $this->uniteEnseignements = [];
            $this->matieres = [];
            $this->reset(['ue_id', 'matiere_id', 'type_evaluation_id', 'type_evaluation', 'notes']);
        }
    }

    public function updatedUeId($value)
    {
        if ($value) {
            $this->matieres = Matiere::where('unite_enseignement_id', $value)
                ->where('campus_id', Auth::user()->campus_id)
                ->where('is_deleting', false)
                ->get();
        } else {
            $this->matieres = [];
            $this->reset(['matiere_id', 'type_evaluation_id', 'type_evaluation', 'notes']);
        }
    }

    public function loadMatieres()
    {
        if ($this->ue_id) {
            $this->matieres = Matiere::where('unite_enseignement_id', $this->ue_id)
                ->where('campus_id', Auth::user()->campus_id)
                ->where('is_deleting', false)
                ->get();
        } else {
            $this->matieres = [];
            $this->reset(['matiere_id', 'type_evaluation_id', 'type_evaluation', 'notes']);
        }
    }

    public function sauvegarderNote()
    {
        try {
            $this->validate([
                'classe_id' => 'required',
                'matiere_id' => 'required',
                'type_evaluation_id' => 'required',
            ]);

            DB::beginTransaction();

            foreach ($this->notes as $etudiantId => $noteData) {
                if (!isset($noteData['note']) || $noteData['note'] < 0 || $noteData['note'] > 20) {
                    continue;
                }

                $existingNote = Note::where([
                    'etudiant_id' => $etudiantId,
                    'matiere_id' => $this->matiere_id,
                    'type_evaluation_id' => $this->type_evaluation_id,
                    'semestre_id' => $this->semestre_id,
                    'academic_year_id' => $this->academic_year_id,
                    'campus_id' => Auth::user()->campus_id,
                ])->first();

                if ($existingNote) {
                    DB::rollBack();
                    $this->dispatch('notify', [
                        'type' => 'error',
                        'message' => 'Une note existe déjà pour cet étudiant dans cette évaluation.'
                    ]);
                    return;
                }

                Note::create([
                    'etudiant_id' => $etudiantId,
                    'matiere_id' => $this->matiere_id,
                    'academic_year_id' => $this->academic_year_id,
                    'type_evaluation_id' => $this->type_evaluation_id,
                    'note' => $noteData['note'],
                    'observation' => $noteData['observation'] ?? null,
                    'semestre_id' => $this->semestre_id,
                    'campus_id' => Auth::user()->campus_id,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id
                ]);
            }

            DB::commit();

            $this->outil->addHistorique(
                "Ajout des notes pour la matière ID: {$this->matiere_id}, Semestre ID: {$this->semestre_id}",
                "create"
            );

            $this->reset([
                'notes',
                'type_evaluation_id',
                'matiere_id',
                'ue_id',
                'showModal'
            ]);

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Les notes ont été enregistrées avec succès.'
            ]);

            $this->dispatch('refresh-component');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Une erreur est survenue lors de l\'enregistrement des notes.'
            ]);
        }
    }

    public function edit($noteId)
    {
        $this->isEditing = true;
        $this->editNoteId = $noteId;
        
        $note = Note::with(['etudiant', 'matiere'])->find($noteId);
        $this->currentNote = $note;
        
        $this->editNote = [
            'valeur' => $note->note,
            'type_evaluation_id' => $note->type_evaluation_id,
            'semestre_id' => $this->semestre_id,
            'observation' => $note->observation
        ];
        
        $this->classe_id = $note->etudiant->classe_id;
        $this->matiere_id = $note->matiere_id;
        $this->showModal = true;
    }

    public function updateNote()
    {
        $this->validate([
            'editNote.valeur' => 'required|numeric|between:0,20',
            'editNote.type_evaluation_id' => 'required',
            'editNote.semestre_id' => 'required'
        ]);

        try {
            DB::beginTransaction();
            
            $note = Note::find($this->editNoteId);
            $existingNote = Note::where([
                'etudiant_id' => $note->etudiant_id,
                'matiere_id' => $note->matiere_id,
                'type_evaluation_id' => $this->editNote['type_evaluation_id'],
                'semestre_id' => $this->editNote['semestre_id'],
                'academic_year_id' => $note->academic_year_id,
                'campus_id' => Auth::user()->campus_id,
            ])
            ->where('id', '!=', $note->id)
            ->first();

            if ($existingNote) {
                DB::rollBack();
                $this->dispatch('notify', [
                    'type' => 'error',
                    'message' => 'Une note existe déjà pour cet étudiant dans cette évaluation.'
                ]);
                return;
            }

            $note->update([
                'note' => $this->editNote['valeur'],
                'type_evaluation_id' => $this->editNote['type_evaluation_id'],
                'semestre_id' => $this->editNote['semestre_id'],
                'observation' => $this->editNote['observation'] ?? null,
                'updated_by' => Auth::id()
            ]);

            DB::commit();

            $this->outil->addHistorique(
                "Modification de la note de l'étudiant {$note->etudiant->prenom} {$note->etudiant->nom}",
                "update"
            );

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Note modifiée avec succès'
            ]);
            $this->resetEdit();

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Erreur lors de la modification de la note'
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

            $this->outil->addHistorique("Suppression de la note de l'étudiant {$etudiantInfo}", "delete");

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'La note a été supprimée avec succès.'
            ]);
            
            $this->showDeleteModal = false;
            $this->currentNote = null;
            $this->dispatch('hide-delete-modal');

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Une erreur est survenue lors de la suppression.'
            ]);
        }
    }

    public function resetEdit()
    {
        $this->isEditing = false;
        $this->editNoteId = null;
        $this->editNote = [
            'valeur' => '',
            'type_evaluation_id' => '',
            'semestre_id' => '',
            'observation' => ''
        ];
        $this->showModal = false;
    }

    public function showDetails($noteId)
    {
        $this->selectedNote = Note::with([
            'etudiant.inscriptions.classe',
            'matiere.uniteEnseignement',
            'academicYear',
            'semestre',
            'typeEvaluation'
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
        $this->showDeleteModal = false;
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        $query = Note::query()
            ->with([
                'etudiant.inscriptions.classe',
                'matiere.uniteEnseignement',
                'academicYear',
                'semestre',
                'typeEvaluation'
            ])
            ->where('campus_id', Auth::user()->campus_id)
            ->where('academic_year_id', $this->academic_year_id)
            ;

        if ($this->classe_id) {
            $query->whereHas('etudiant.inscriptions', function ($q) {
                $q->where('classe_id', $this->classe_id);
            });
        }

        if ($this->matiere_id) {
            $query->where('matiere_id', $this->matiere_id);
        }

        if ($this->type_evaluation_id) {
            $query->where('type_evaluation_id', $this->type_evaluation_id);
        }

        $notes = $query->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.note.notes', [
            'notesList' => $notes,
            'classes' => Classe::where('campus_id', Auth::user()->campus_id)
                ->where('is_deleting', false)
                ->get(),
            'uniteEnseignements' => $this->uniteEnseignements,
            'matieres' => $this->matieres,
            'typeEvaluations' => $this->typeEvaluations,
            'semestres' => Semestre::where('campus_id', Auth::user()->campus_id)
                ->where('is_active', true)
                ->where('is_deleting', false)
                ->get(),
            'academic_years' => AcademicYear::where('campus_id', Auth::user()->campus_id)
                ->where('is_deleting', false)
                ->get()
        ]);
    }
}
