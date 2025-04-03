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
    }

    public function updatedAcademicYearId()
    {
        $this->classe_id = null;
        $this->resetPage();
    }

    public function edit($noteId)
    {
        $this->isEditing = true;
        $this->noteId = $noteId;
        $this->showModal = true;
        
        $note = Note::find($noteId);
        $this->etudiant_id = $note->etudiant_id;
        $this->cours_id = $note->cours_id;
        $this->note = $note->note;
        $this->observation = $note->observation;
        $this->semestre_id = $note->semestre_id;
    }

    public function sauvegarderNote()
    {
        foreach ($this->notes as $etudiantId => $noteData) {
            Note::create([
                'etudiant_id' => $etudiantId,
                'matiere_id' => $this->matiere_id,
                'academic_year_id' => auth()->user()->campus->currentAcademicYear()->id,
                'type_evaluation' => $noteData['type_evaluation'],
                'note' => $noteData['note'],
                'observation' => $noteData['observation'] ?? null,
                'semestre_id' => $this->semestre_id,
                'campus_id' => auth()->user()->campus_id
            ]);
        }
    }

    public function confirmDelete($noteId)
    {
        $this->dispatch('swal:confirm', [
            'type' => 'warning',
            'title' => 'Êtes-vous sûr?',
            'text' => 'Cette note sera supprimée définitivement.',
            'id' => $noteId
        ]);
    }

    #[On('deleteNote')]
    public function delete($noteId)
    {
        $this->outil = new Outils();
        $this->outil->addHistorique("Suppression d'une note de l'étudiant {$this->selectedNote->etudiant->prenom} {$this->selectedNote->etudiant->nom}", "delete");
        Note::find($noteId)->delete();
        session()->flash('message', 'Note supprimée avec succès.');
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

        $currentAcademicYear = AcademicYear::where('campus_id', Auth::user()->campus_id)
                                         ->where('encours', true)
                                         ->first();

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
}
