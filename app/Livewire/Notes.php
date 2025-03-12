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

class Notes extends Component
{
    use WithPagination;

    public $etudiant_id;
    public $cours_id;
    public $note;
    public $type_evaluation;
    public $coefficient;
    public $observation;
    public $date_evaluation;
    public $semestre;
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
        'type_evaluation' => 'required',
        'coefficient' => 'required|numeric|min:1',
        'date_evaluation' => 'required|date',
        'semestre' => 'required'
    ];

    public function mount()
    {
        $this->date_evaluation = date('Y-m-d');
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
        $this->type_evaluation = $note->type_evaluation;
        $this->coefficient = $note->coefficient;
        $this->observation = $note->observation;
        $this->date_evaluation = $note->date_evaluation;
        $this->semestre = $note->semestre;
    }

    public function sauvegarderNote()
    {
        $this->validate();

        if ($this->isEditing) {
            Note::find($this->noteId)->update([
                'etudiant_id' => $this->etudiant_id,
                'cours_id' => $this->cours_id,
                'note' => $this->note,
                'type_evaluation' => $this->type_evaluation,
                'coefficient' => $this->coefficient,
                'observation' => $this->observation,
                'date_evaluation' => $this->date_evaluation,
                'semestre' => $this->semestre
            ]);
            $message = 'Note modifiée avec succès.';
        } else {
            Note::create([
                'etudiant_id' => $this->etudiant_id,
                'cours_id' => $this->cours_id,
                'note' => $this->note,
                'type_evaluation' => $this->type_evaluation,
                'coefficient' => $this->coefficient,
                'observation' => $this->observation,
                'date_evaluation' => $this->date_evaluation,
                'semestre' => $this->semestre
            ]);
            $message = 'Note enregistrée avec succès.';
        }

        $this->reset(['showModal', 'isEditing', 'noteId', 'etudiant_id', 'cours_id', 
                     'note', 'type_evaluation', 'coefficient', 'observation', 
                     'date_evaluation', 'semestre']);
        session()->flash('message', $message);
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

    public function calculerMoyenne($etudiant_id, $semestre)
    {
        $notes = Note::where('etudiant_id', $etudiant_id)
                     ->where('semestre', $semestre)
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
            $notes->where('semestre', $this->filterSemestre);
        }

        return view('livewire.notes', [
            'notes' => $notes->orderBy('date_evaluation', 'desc')->paginate(10),
            'etudiants' => $this->getEtudiantsByCampus(),
            'classes' => $this->classes,
            'cours' => Cour::where('campus_id', Auth::user()->campus_id)
                          ->where('is_deleting', 0)
                          ->get()
        ]);
    }
}
