<?php

namespace App\Livewire;

use App\Models\Retard;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

#[Title("Mes Retards")]
class RetardEtudiant extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $selectedSemestre = null;
    public $dateDebut = null;
    public $dateFin = null;
    public $retards = [];
    public $totalRetards = 0;
    public $retardsJustifies = 0;
    public $retardsNonJustifies = 0;
    public $moyenneRetardMinutes = 0;

    public function updatedSearch()
    {
        $this->loadRetards();
    }

    public function updatedSelectedSemestre()
    {
        $this->loadRetards();
    }

    public function updatedDateDebut()
    {
        $this->loadRetards();
    }

    public function updatedDateFin()
    {
        $this->loadRetards();
    }

    private function loadRetards()
    {
        $query = Retard::where('etudiant_id', Auth::id());

        if ($this->selectedSemestre) {
            $query->where('semestre_id', $this->selectedSemestre);
        }

        if ($this->dateDebut) {
            $query->whereDate('date', '>=', $this->dateDebut);
        }

        if ($this->dateFin) {
            $query->whereDate('date', '<=', $this->dateFin);
        }

        if ($this->search) {
            $query->whereHas('cours', function($q) {
                $q->whereHas('matiere', function($q) {
                    $q->where('nom', 'like', '%' . $this->search . '%');
                });
            });
        }

        $this->retards = $query->with(['cours.matiere'])->latest()->get();
        
        // Calcul des statistiques
        $this->totalRetards = $this->retards->count();
        $this->retardsJustifies = $this->retards->where('justifie', true)->count();
        $this->retardsNonJustifies = $this->retards->where('justifie', false)->count();
        $this->moyenneRetardMinutes = $this->retards->avg('minutes_retard');
    }

    public function mount()
    {
        $this->loadRetards();
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.etudiant.retard-etudiant', [
            'semestres' => Auth::user()->campus->semestres
        ]);
    }
}
