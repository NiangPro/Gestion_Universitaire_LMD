<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Note;
use App\Models\Etudiant;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

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

    protected $rules = [
        'etudiant_id' => 'required',
        'cours_id' => 'required',
        'note' => 'required|numeric|min:0|max:20',
        'type_evaluation' => 'required',
        'coefficient' => 'required|numeric|min:1',
        'date_evaluation' => 'required|date',
        'semestre' => 'required'
    ];

    public function sauvegarderNote()
    {
        $this->validate();

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

        $this->reset();
        session()->flash('message', 'Note enregistrée avec succès.');
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

    public function render()
    {
        return view('livewire.notes', [
            'notes' => Note::with(['etudiant', 'cours'])->paginate(10),
            'etudiants' => Auth::user()->campus->eleves
        ]);
    }
}
