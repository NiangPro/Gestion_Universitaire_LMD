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
use Livewire\Attributes\Reactive;
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
    public $coefficient_id = null;
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
        'coefficient_id' => 'required',
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
        $this->coefficient_id = $note->coefficient_id;
        $this->observation = $note->observation;
        $this->semestre_id = $note->semestre_id;
    }

    public function sauvegarderNote()
    {
        // Validation
        $this->validate([
            'classe_id' => 'required',
            'ue_id' => 'required',
            'matiere_id' => 'required',
            'semestre_id' => 'required',
        ]);

        // Récupérer les étudiants de la classe
        $etudiants = $this->getEtudiantsByCampus();

        $this->outil = new Outils();
        $this->outil->addHistorique("Enregistrement des notes pour la matière {$this->matiere_id} de la classe {$this->classe_id}", "add");

        foreach ($etudiants as $etudiant) {
            // Vérifier si une note a été saisie pour cet étudiant
            if (isset($this->notes[$etudiant->id]['note']) && !empty($this->notes[$etudiant->id]['note'])) {
                try {
            Note::create([
                        'etudiant_id' => $etudiant->id,
                        'matiere_id' => $this->matiere_id,
                        'note' => $this->notes[$etudiant->id]['note'],
                        'coefficient_id' => $this->notes[$etudiant->id]['coefficient_id'] ?? null,
                        'type_evaluation' => $this->notes[$etudiant->id]['type_evaluation'] ?? 'CC',
                        'observation' => $this->notes[$etudiant->id]['observation'] ?? null,
                        'semestre_id' => $this->semestre_id,
                        'academic_year_id' => Auth::user()->campus->currentAcademicYear()->id,
                        'campus_id' => Auth::user()->campus_id
                    ]);
                } catch (\Exception $e) {
                    session()->flash('error', 'Erreur lors de l\'enregistrement: ' . $e->getMessage());
                    return;
                }
            }
        }

        $this->reset(['notes', 'showModal', 'classe_id', 'ue_id', 'matiere_id', 'semestre_id']);
        session()->flash('message', 'Notes enregistrées avec succès');
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
            ->with(['etudiant', 'matiere', 'coefficient', 'semestre'])
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
            'coefficients' => Auth::user()->campus->coefficients
        ]);
    }

    public function showDetails($noteId)
    {
        $this->selectedNote = Note::with([
            'etudiant.inscriptions.classe',
            'matiere.uniteEnseignement',
            'academicYear',
            'semestre',
            'coefficient'
        ])->find($noteId);
        
        $this->showDetailsModal = true;
    }

    public function closeDetailsModal()
    {
        $this->showDetailsModal = false;
        $this->selectedNote = null;
    }
}
