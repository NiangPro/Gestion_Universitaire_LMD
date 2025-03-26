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

#[Title("Notes")]
class Notes extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $etudiant_id;
    public $cours_id;
    public $note;
    public $coefficient_id;
    public $matiere_id;
    public $observation;
    public $semestre_id;
    public $showModal = false;
    public $isEditing = false;
    public $noteId;
    public $searchTerm = '';
    public $filterType = '';
    public $filterSemestre = '';
    public $classe_id;
    public $notes = [];

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
        foreach ($this->notes as $etudiantId => $noteData) {
            if (!empty($noteData['note'])) {
                Note::create([
                    'etudiant_id' => $etudiantId,
                    'note' => $noteData['note'],
                    'coefficient_id' => $noteData['coefficient_id'],
                    'type_evaluation' => $noteData['type_evaluation'],
                    'observation' => $noteData['observation'] ?? null,
                    'semestre_id' => $this->semestre_id,
                    'academic_year_id' => Auth::user()->campus->currentAcademicYear()->id,
                    'campus_id' => Auth::user()->campus_id
                ]);
            }
        }

        $this->reset(['notes', 'showModal']);
        session()->flash('message', 'Notes enregistrées avec succès');
    }

    public function confirmDelete($noteId)
    {
        $this->dispatchBrowserEvent('swal:confirm', [
            'type' => 'warning',
            'title' => 'Êtes-vous sûr?',
            'text' => 'Cette note sera supprimée définitivement.',
            'id' => $noteId
        ]);
    }

    #[On('deleteNote')]
    public function delete($noteId)
    {
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
        $this->etudiant_id = ''; // Réinitialiser la sélection de l'étudiant
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

    #[Layout("components.layouts.app")]
    public function render()
    {
        $notesQuery = Note::query()
            ->with(['etudiant', 'cours'])
            ->where('campus_id', Auth::user()->campus_id);

        if ($this->searchTerm) {
            $notesQuery->whereHas('etudiant', function($query) {
                $query->where('nom', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('prenom', 'like', '%' . $this->searchTerm . '%');
            });
        }

        if ($this->filterType) {
            $notesQuery->where('type_evaluation', $this->filterType);
        }

        if ($this->filterSemestre) {
            $notesQuery->where('semestre_id', $this->filterSemestre);
        }

        $notes = $notesQuery->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.note.notes', [
            'notesList' => $notes,
            'etudiants' => $this->getEtudiantsByCampus(),
            'classes' => $this->classes,
            'semestres' => Semestre::where('campus_id', Auth::user()->campus_id)
                          ->where('is_active', true)
                          ->get(),
            'cours' => Cour::where('campus_id', Auth::user()->campus_id)
                          ->where('is_deleting', 0)
                          ->get(),
            'coefficients' => Auth::user()->campus->coefficients
        ]);
    }
}
