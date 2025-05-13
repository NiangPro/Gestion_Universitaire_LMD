<?php

namespace App\Livewire;

use App\Models\Retard;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title("Mes Retards")]
class RetardEtudiant extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $filterDate = '';
    public $filterJustifie = '';
    public $retardStats = [];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterDate()
    {
        $this->resetPage();
    }

    public function updatedFilterJustifie()
    {
        $this->resetPage();
    }

    private function loadRetardStats()
    {
        $etudiant = Auth::user()->etudiant;
        
        if (!$etudiant) {
            $this->retardStats = [
                'total' => 0,
                'justifies' => 0,
                'non_justifies' => 0,
                'mois_courant' => 0
            ];
            return;
        }

        $this->retardStats = [
            'total' => Retard::where('etudiant_id', $etudiant->id)->count(),
            'justifies' => Retard::where('etudiant_id', $etudiant->id)->where('justifie', true)->count(),
            'non_justifies' => Retard::where('etudiant_id', $etudiant->id)->where('justifie', false)->count(),
            'mois_courant' => Retard::where('etudiant_id', $etudiant->id)
                ->whereMonth('date', now()->month)
                ->count()
        ];
    }

    public function mount()
    {
        $this->loadRetardStats();
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        $etudiant = Auth::user()->etudiant;
        
        if (!$etudiant) {
            $query = Retard::where('id', 0); // Crée une requête vide
            $retards = $query->paginate(10);
            return view('livewire.etudiant.retard-etudiant', [
                'retards' => $retards
            ]);
        }

        $query = Retard::where('etudiant_id', $etudiant->id)
            ->with(['cours' => function($query) {
                $query->withDefault();
            }, 'cours.matiere' => function($query) {
                $query->withDefault(['nom' => 'Non spécifié']);
            }]);

        if ($this->search) {
            $query->whereHas('cours.matiere', function($q) {
                $q->where('nom', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterDate) {
            $query->whereDate('date', $this->filterDate);
        }

        if ($this->filterJustifie !== '') {
            $query->where('justifie', $this->filterJustifie == '1');
        }

        $retards = $query->latest()->paginate(10);

        return view('livewire.etudiant.retard-etudiant', [
            'retards' => $retards
        ]);
    }
}
