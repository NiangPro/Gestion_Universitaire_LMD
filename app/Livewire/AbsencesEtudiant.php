<?php

namespace App\Livewire;

use App\Models\Absence;
use App\Models\Cour;
use App\Models\Semestre;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title("Absences")]
class AbsencesEtudiant extends Component
{
    use WithPagination;

    #[Layout('components.layouts.app')]
    public $selectedSemestre = null;
    public $selectedMatiere = null;
    public $selectedEtat = null;
    public $selectedDate = null;

    public function mount()
    {
        $this->selectedDate = Carbon::now()->format('Y-m-d');
    }

    public function render()
    {
        $query = Absence::where('etudiant_id', Auth::id())
            ->with(['cours', 'cours.professeur']);

        if ($this->selectedSemestre) {
            $query->where('semestre_id', $this->selectedSemestre);
        }

        if ($this->selectedMatiere) {
            $query->whereHas('cours', function($q) {
                $q->where('id', $this->selectedMatiere);
            });
        }

        if ($this->selectedEtat === 'Justifié') {
            $query->where('justifie', true);
        } elseif ($this->selectedEtat === 'Non justifié') {
            $query->where('justifie', false);
        }

        if ($this->selectedDate) {
            $query->whereDate('date', $this->selectedDate);
        }

        $absences = $query->latest()->paginate(10);
        $semestres = Semestre::where('is_active', true)->get();
        $matieres = Cour::whereHas('absences', function($q) {
            $q->where('etudiant_id', Auth::id());
        })->get();

        $totalAbsences = Absence::where('etudiant_id', Auth::id())->count();
        $absencesJustifiees = Absence::where('etudiant_id', Auth::id())
            ->where('justifie', true)
            ->count();
        $absencesNonJustifiees = $totalAbsences - $absencesJustifiees;
        $tauxAssiduite = $totalAbsences > 0 
            ? round(($absencesJustifiees / $totalAbsences) * 100)
            : 100;

        return view('livewire.etudiant.absences-etudiant', [
            'absences' => $absences,
            'semestres' => $semestres,
            'matieres' => $matieres,
            'totalAbsences' => $totalAbsences,
            'absencesJustifiees' => $absencesJustifiees,
            'absencesNonJustifiees' => $absencesNonJustifiees,
            'tauxAssiduite' => $tauxAssiduite
        ]);
    }

    public function updatedSelectedSemestre()
    {
        $this->resetPage();
    }

    public function updatedSelectedMatiere()
    {
        $this->resetPage();
    }

    public function updatedSelectedEtat()
    {
        $this->resetPage();
    }

    public function updatedSelectedDate()
    {
        $this->resetPage();
    }
}
