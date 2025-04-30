<?php

namespace App\Livewire;

use App\Models\AcademicYear;
use App\Models\Note;
use App\Models\Semestre;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NoteEtudiant extends Component
{
    public $selectedSemestre = null;
    public $currentAcademicYear;
    public $semestres;
    public $notes;
    public $moyenneGenerale;
    public $creditsTotaux;
    public $creditsValides;

    public function mount()
    {
        $this->currentAcademicYear = Auth::user()->campus->currentAcademicYear();
        $this->semestres = Auth::user()->campus->semestres;
        
        if ($this->semestres->count() > 0) {
            $this->selectedSemestre = $this->semestres->first()->id;
            $this->loadNotes();
        }
    }

    public function loadNotes()
    {
        if (!$this->selectedSemestre || !$this->currentAcademicYear) {
            return;
        }

        $notes = Note::with(['matiere.uniteEnseignement', 'typeEvaluation'])
            ->where('etudiant_id', Auth::id())
            ->where('academic_year_id', $this->currentAcademicYear->id)
            ->where('semestre_id', $this->selectedSemestre)
            ->whereHas('typeEvaluation')
            ->get()
            ->filter(function ($note) {
                return $note->typeEvaluation !== null;
            });

        $this->notes = collect([]);
        foreach ($notes as $note) {
            if (!$this->notes->has($note->matiere_id)) {
                $this->notes[$note->matiere_id] = collect([]);
            }
            $this->notes[$note->matiere_id]->push($note);
        }

        $this->calculerStatistiques();
    }

    public function updatedSelectedSemestre()
    {
        $this->loadNotes();
    }

    private function calculerStatistiques()
    {
        $totalPoints = 0;
        $totalCoefficients = 0;
        $this->creditsTotaux = 0;
        $this->creditsValides = 0;

        foreach ($this->notes as $matiereId => $notesMatiere) {
            $matiere = $notesMatiere->first()->matiere;
            $this->creditsTotaux += $matiere->credit;

            $moyenneMatiere = $this->calculerMoyenneMatiere($notesMatiere);
            
            if ($moyenneMatiere >= 10) {
                $this->creditsValides += $matiere->credit;
            }

            $totalPoints += $moyenneMatiere * $matiere->coefficient;
            $totalCoefficients += $matiere->coefficient;
        }

        $this->moyenneGenerale = $totalCoefficients > 0 
            ? round($totalPoints / $totalCoefficients, 2) 
            : 0;
    }

    private function calculerMoyenneMatiere($notes)
    {
        $totalNotes = 0;
        $coefficientTotal = 0;

        foreach ($notes as $note) {
            $coefficient = match ($note->typeEvaluation->nom) {
                'Examen' => 0.7,
                'CC' => 0.2,
                'TP' => 0.1,
                default => 0
            };

            $totalNotes += $note->note * $coefficient;
            $coefficientTotal += $coefficient;
        }

        return $coefficientTotal > 0 ? round($totalNotes / $coefficientTotal, 2) : 0;
    }

    public function render()
    {
        return view('livewire.etudiant.note-etudiant');
    }
}
