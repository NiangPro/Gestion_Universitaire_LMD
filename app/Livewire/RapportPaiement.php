<?php

namespace App\Livewire;

use App\Models\Paiement;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Title('Rapport Paiement')]
class RapportPaiement extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $periode = 'mensuel';
    public $dateDebut;
    public $dateFin;
    public $selectedMonth;
    public $selectedYear;
    public $searchMatricule = '';
    public $selectedEtudiant = null;
    public $suggestions = [];

    public function mount()
    {
        $this->suggestions = collect([]);
        $this->selectedMonth = now()->format('Y-m');
        $this->selectedYear = now()->year;
        $this->dateDebut = now()->startOfMonth()->format('Y-m-d');
        $this->dateFin = now()->endOfMonth()->format('Y-m-d');
    }

    public function updatedSearchMatricule()
    {
        if (strlen($this->searchMatricule) >= 2) {
            $this->suggestions = User::where('campus_id', Auth::user()->campus_id)
                ->where('role', 'etudiant')
                ->where(function($query) {
                    $query->where('matricule', 'like', '%' . $this->searchMatricule . '%')
                          ->orWhere('nom', 'like', '%' . $this->searchMatricule . '%')
                          ->orWhere('prenom', 'like', '%' . $this->searchMatricule . '%');
                })
                ->limit(5)
                ->get();
        } else {
            $this->suggestions = collect([]);
        }
    }

    public function selectEtudiant($etudiantId)
    {
        $this->selectedEtudiant = User::find($etudiantId);
        $this->searchMatricule = $this->selectedEtudiant->matricule;
        $this->suggestions = collect([]);
    }

    public function resetEtudiant()
    {
        $this->selectedEtudiant = null;
        $this->searchMatricule = '';
        $this->suggestions = collect([]);
    }

    public function updatedPeriode()
    {
        switch ($this->periode) {
            case 'mensuel':
                $this->dateDebut = Carbon::parse($this->selectedMonth)->startOfMonth()->format('Y-m-d');
                $this->dateFin = Carbon::parse($this->selectedMonth)->endOfMonth()->format('Y-m-d');
                break;
            case 'annuel':
                $this->dateDebut = Carbon::createFromDate($this->selectedYear)->startOfYear()->format('Y-m-d');
                $this->dateFin = Carbon::createFromDate($this->selectedYear)->endOfYear()->format('Y-m-d');
                break;
        }
        
        // Dispatch l'événement pour mettre à jour les graphiques
        $this->dispatch('refreshCharts', $this->getStatistiques());
    }

    public function updatedSelectedMonth()
    {
        $this->dateDebut = Carbon::parse($this->selectedMonth)->startOfMonth()->format('Y-m-d');
        $this->dateFin = Carbon::parse($this->selectedMonth)->endOfMonth()->format('Y-m-d');
        $this->dispatch('refreshCharts', $this->getStatistiques());
    }

    public function updatedSelectedYear()
    {
        $this->dateDebut = Carbon::createFromDate($this->selectedYear)->startOfYear()->format('Y-m-d');
        $this->dateFin = Carbon::createFromDate($this->selectedYear)->endOfYear()->format('Y-m-d');
        $this->dispatch('refreshCharts', $this->getStatistiques());
    }

    public function updatedDateDebut()
    {
        $this->dispatch('refreshCharts', $this->getStatistiques());
    }

    public function updatedDateFin()
    {
        $this->dispatch('refreshCharts', $this->getStatistiques());
    }

    public function getStatistiques()
    {
        $query = Paiement::where('campus_id', Auth::user()->campus_id)
            ->whereBetween('date_paiement', [$this->dateDebut, $this->dateFin]);

        if ($this->selectedEtudiant) {
            $query->where('user_id', $this->selectedEtudiant->id);
        }

        $paiements = $query->get();

        return [
            'total' => $paiements->sum('montant'),
            'count' => $paiements->count(),
            'moyenne' => $paiements->avg('montant'),
            'par_type' => $paiements->groupBy('type_paiement')
                ->map(fn($group) => $group->sum('montant')),
            'par_mode' => $paiements->groupBy('mode_paiement')
                ->map(fn($group) => $group->sum('montant')),
            'par_jour' => $paiements->groupBy(fn($item) => Carbon::parse($item->date_paiement)->format('Y-m-d'))
                ->map(fn($group) => $group->sum('montant')),
        ];
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        $stats = $this->getStatistiques();
        
        $paiements = Paiement::with(['user', 'academicYear'])
            ->where('campus_id', Auth::user()->campus_id)
            ->whereBetween('date_paiement', [$this->dateDebut, $this->dateFin])
            ->when($this->selectedEtudiant, function($query) {
                return $query->where('user_id', $this->selectedEtudiant->id);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.paiement.rapport-paiement', [
            'paiements' => $paiements,
            'stats' => $stats
        ]);
    }
}
