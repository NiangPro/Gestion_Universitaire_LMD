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
        $this->validate();

        if ($this->isEditing) {
            Note::find($this->noteId)->update([
                'etudiant_id' => $this->etudiant_id,
                'cours_id' => $this->cours_id,
                'note' => $this->note,
                'coefficient_id' => $this->coefficient_id,
                'observation' => $this->observation,
                'matiere_id' => $this->matiere_id,
                'semestre_id' => $this->semestre_id
            ]);
            $this->dispatch('updatedNote');
        } else {
            Note::create([
                'etudiant_id' => $this->etudiant_id,
                'cours_id' => $this->cours_id,
                'note' => $this->note,
                'matiere_id' => $this->matiere_id,
                'academic_year_id' => Auth::user()->campus->currentAcademicYear()->id,
                'coefficient_id' => $this->coefficient_id,
                'observation' => $this->observation,
                'campus_id' => Auth::user()->campus_id,
                'semestre_id' => $this->semestre_id
            ]);
            $this->dispatch('addedNote');
        }

        $this->reset(['showModal', 'isEditing', 'noteId', 'etudiant_id', 'cours_id', 
                     'note', 'coefficient_id', 'observation', 
                     'semestre_id']);
        $this->showModal = false;
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
        $notes = Note::with(['etudiant', 'cours'])
            ->whereHas('etudiant', function($query) {
                $query->where('campus_id', Auth::user()->campus_id);
                if ($this->searchTerm) {
                    $query->where(function($q) {
                        $q->where('nom', 'like', '%' . $this->searchTerm . '%')
                          ->orWhere('prenom', 'like', '%' . $this->searchTerm . '%');
                    });
                }
            });

        if ($this->filterType) {
            $notes->where('type_evaluation', $this->filterType);
        }

        if ($this->filterSemestre) {
            $notes->where('semestre_id', $this->filterSemestre);
        }

        return view('livewire.note.notes', [
            'notes' => $notes->orderBy('date_evaluation', 'desc')->paginate(10),
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
