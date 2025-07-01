<?php

namespace App\Livewire;

use App\Models\AcademicYear;
use App\Models\Classe;
use App\Models\Note;
use App\Models\Semestre;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Bulletins")]
class Bulletin extends Component
{
    public $selectedYear = null;
    public $selectedClasse = null;
    public $selectedStudent = null;
    
    public $academicYears;
    public $classes = [];
    public $students = [];

    public function mount()
    {
        $this->academicYears = AcademicYear::where('campus_id', Auth::user()->campus_id)
            ->orderBy('debut', 'desc')
            ->get();
    }

    public function updatedSelectedYear($value)
    {
        $this->selectedClasse = null;
        $this->selectedStudent = null;
        $this->classes = [];
        $this->students = [];
        
        if ($value) {
            $this->classes = Classe::where('academic_year_id', $value)
                ->where('campus_id', Auth::user()->campus_id)
                ->get();
        }
    }

    public function updatedSelectedClasse($value)
    {
        $this->selectedStudent = null;
        $this->students = [];
        
        if ($value) {
            $this->students = User::where('role', 'etudiant')
                ->whereHas('inscriptions', function($query) {
                    $query->where('classe_id', $this->selectedClasse)
                          ->where('academic_year_id', $this->selectedYear);
                })
                ->get();
        }
    }

    #[Layout("components.layouts.app")]
    public function calculerMoyenneMatiere($notes)
    {
        $totalCoef = 0;
        $totalPoints = 0;

        foreach ($notes as $note) {
            $coef = $note->typeEvaluation->coefficient ?? 1;
            $totalCoef += $coef;
            $totalPoints += $note->note * $coef;
        }

        return $totalCoef > 0 ? $totalPoints / $totalCoef : 0;
    }

    public function calculerMoyenneUE($matieres)
    {
        $totalCredits = 0;
        $totalPoints = 0;

        foreach ($matieres as $matiere) {
            $moyenneMatiere = $this->calculerMoyenneMatiere($matiere['notes']);
            $totalCredits += $matiere['credit'];
            $totalPoints += $moyenneMatiere * $matiere['credit'];
        }

        return $totalCredits > 0 ? $totalPoints / $totalCredits : 0;
    }

    public function render()
    {
        $bulletin = null;
        if ($this->selectedStudent) {
            $notes = Note::with(['matiere.uniteEnseignement', 'etudiant', 'typeEvaluation', 'semestre'])
                ->where('etudiant_id', $this->selectedStudent)
                ->where('academic_year_id', $this->selectedYear)
                ->get();

            $notesParSemestre = $notes->groupBy('semestre_id');
            $bulletin = [];

            foreach ($notesParSemestre as $semestreId => $notesParMatiere) {
                $notesGroupees = $notesParMatiere->groupBy('matiere_id');
                $ues = [];
                foreach ($notesGroupees as $matiereId => $notesMatiere) {
                    
                    $matiere = $notesMatiere->first()->matiere;
                    $ueId = $matiere->unite_enseignement_id;

                    if (!isset($ues[$ueId])) {
                        $ues[$ueId] = [
                            'nom' => $matiere->uniteEnseignement->nom ?? 'Non assigné',
                            'matieres' => [],
                            'credit_total' => 0
                        ];
                    }

                    $moyenneMatiere = $this->calculerMoyenneMatiere($notesMatiere);
                    $ues[$ueId]['matieres'][] = [
                        'nom' => $matiere->nom,
                        'credit' => $matiere->credit,
                        'coefficient' => $matiere->coefficient,
                        'notes' => $notesMatiere,
                        'moyenne' => $moyenneMatiere
                    ];
                    $ues[$ueId]['credit_total'] += $matiere->credit;
                }

                foreach ($ues as &$ue) {
                    $ue['moyenne'] = $this->calculerMoyenneUE($ue['matieres']);
                }

                $bulletin[$semestreId] = [
                    'semestre' => $notesParMatiere->first()->semestre,
                    'ues' => $ues
                ];
            }
        }

        $academicYear = null;
        $etudiant = null;
        $classe = null;
        $semestre = null;

        if ($this->selectedStudent && $this->selectedYear) {
            $academicYear = AcademicYear::find($this->selectedYear);
            $etudiant = User::find($this->selectedStudent);
            $classe = Classe::find($this->selectedClasse);
            $semestre = $bulletin ? array_key_first($bulletin) : null;
            if ($semestre) {
                $semestre = Semestre::find($semestre);
            }
        }

        return view('livewire.bulletin.bulletin', [
            'bulletin' => $bulletin,
            'ues' => $bulletin && $semestre ? $bulletin[$semestre->id]['ues'] : [],
            'academicYear' => $academicYear,
            'etudiant' => $etudiant,
            'classe' => $classe,
            'semestre' => $semestre
        ]);
    }
}
