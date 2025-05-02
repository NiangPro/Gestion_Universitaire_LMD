<?php

namespace App\Livewire\Dashboard;

use App\Models\AcademicYear;
use App\Models\Note;
use App\Models\Semestre;
use App\Models\Absence;
use App\Models\Retard;
use App\Models\EmploiDuTemps;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DashboardEtudiant extends Component
{
    public $user;
    public $selectedSemestre = null;
    public $currentAcademicYear;
    public $semestres;
    public $moyenneGenerale = 0;
    public $creditsTotaux = 0;
    public $creditsValides = 0;
    public $progressionCredits = 0;
    public $totalAbsences = 0;
    public $totalRetards = 0;
    public $moyennesParUE = [];
    public $moyennesParMatiere = [];
    public $recentActivities = [];
    public $emploiDuTemps = [];

    public function mount()
    {
        $this->user = Auth::user();
        $this->currentAcademicYear = $this->user->campus->currentAcademicYear();
        $this->semestres = $this->user->campus->semestres;
        
        if ($this->semestres->count() > 0) {
            $this->selectedSemestre = $this->semestres->first()->id;
            $this->loadData();
        }
    }

    public function updatedSelectedSemestre()
    {
        $this->loadData();
    }

    private function loadData()
    {
        $this->loadNotes();
        $this->loadAbsencesRetards();
        $this->loadEmploiDuTemps();
    }

    private function loadNotes()
    {
        $notes = Note::with(['matiere.uniteEnseignement', 'typeEvaluation'])
            ->where('etudiant_id', $this->user->id)
            ->where('academic_year_id', $this->currentAcademicYear->id)
            ->where('semestre_id', $this->selectedSemestre)
            ->get();

        $this->calculerStatistiques($notes);
        $this->recentActivities = $notes->sortByDesc('created_at')->take(5);
    }

    private function calculerStatistiques($notes)
    {
        $ueStats = [];
        $matiereStats = [];
        $totalPoints = 0;
        $totalCoefficientsGeneral = 0;
        $this->creditsTotaux = 0;
        $this->creditsValides = 0;

        foreach ($notes->groupBy('matiere.uniteEnseignement.nom') as $ue => $notesUE) {
            $moyenneUE = 0;
            $totalCoefficientsUE = 0;
            $creditsUE = 0;

            foreach ($notesUE->groupBy('matiere_id') as $matiereId => $notesMatiere) {
                $matiere = $notesMatiere->first()->matiere;
                $moyenneMatiere = $this->calculerMoyenneMatiere($notesMatiere);
                
                $matiereStats[$matiere->nom] = [
                    'moyenne' => $moyenneMatiere,
                    'notes' => $notesMatiere,
                    'coefficient' => $matiere->coefficient
                ];

                $moyenneUE += $moyenneMatiere * $matiere->coefficient;
                $totalCoefficientsUE += $matiere->coefficient;
                $creditsUE += $matiere->credit;
                $this->creditsTotaux += $matiere->credit;

                if ($moyenneMatiere >= 10) {
                    $this->creditsValides += $matiere->credit;
                }
            }

            if ($totalCoefficientsUE > 0) {
                $moyenneUE = $moyenneUE / $totalCoefficientsUE;
                $totalPoints += $moyenneUE * $totalCoefficientsUE;
                $totalCoefficientsGeneral += $totalCoefficientsUE;
            }

            $ueStats[$ue] = [
                'moyenne' => $moyenneUE,
                'credits' => $creditsUE
            ];
        }

        $this->moyennesParUE = $ueStats;
        $this->moyennesParMatiere = $matiereStats;
        $this->moyenneGenerale = $totalCoefficientsGeneral > 0 ? $totalPoints / $totalCoefficientsGeneral : 0;
        $this->progressionCredits = $this->creditsTotaux > 0 ? ($this->creditsValides / $this->creditsTotaux) * 100 : 0;
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

        return $coefficientTotal > 0 ? $totalNotes / $coefficientTotal : 0;
    }

    private function loadAbsencesRetards()
    {
        $this->totalAbsences = Absence::where('etudiant_id', $this->user->id)
            ->where('academic_year_id', $this->currentAcademicYear->id)
            ->where('semestre_id', $this->selectedSemestre)
            ->count();

        $this->totalRetards = Retard::where('etudiant_id', $this->user->id)
            ->where('academic_year_id', $this->currentAcademicYear->id)
            ->where('semestre_id', $this->selectedSemestre)
            ->count();
    }

    private function loadEmploiDuTemps()
    {
        $this->emploiDuTemps = EmploiDuTemps::with(['matiere', 'professeur', 'salle'])
            ->where('semestre_id', $this->selectedSemestre)
            ->where('academic_year_id', $this->currentAcademicYear->id)
            ->orderBy('jour')
            ->orderBy('heure_debut')
            ->get()
            ->groupBy('jour');
    }

    public function toggleNotesModal()
    {
        $this->dispatch('toggle-notes-modal');
    }

    public function toggleEmploiModal()
    {
        $this->dispatch('toggle-emploi-modal');
    }

    public function render()
    {
        return view('livewire.dashboard.dashboard-etudiant');
    }
}