<?php

namespace App\Livewire;

use App\Models\Paiement;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

        // Récupérer l'année académique actuelle et précédente
        $currentAcademicYear = Auth::user()->campus->currentAcademicYear();
        $previousAcademicYear = Auth::user()->campus->academicYears()
            ->where('debut', '<', $currentAcademicYear->debut)
            ->orderBy('debut', 'desc')
            ->first();

        // Paiements de l'année académique actuelle
        $paiementsActuels = Paiement::where('campus_id', Auth::user()->campus_id)
            ->where('academic_year_id', $currentAcademicYear->id)
            ->selectRaw('MONTH(date_paiement) as mois, SUM(montant) as total')
            ->groupBy('mois')
            ->orderBy('mois')
            ->get();

        // Paiements de l'année académique précédente
        $paiementsPrecedents = collect();
        if ($previousAcademicYear) {
            $paiementsPrecedents = Paiement::where('campus_id', Auth::user()->campus_id)
                ->where('academic_year_id', $previousAcademicYear->id)
                ->selectRaw('MONTH(date_paiement) as mois, SUM(montant) as total')
                ->groupBy('mois')
                ->orderBy('mois')
                ->get();
        }

        // Préparer les données pour le graphique
        $nomMois = [
            1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
            5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
            9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
        ];

        $comparaisonMensuelle = collect($nomMois)->mapWithKeys(function ($nom, $numeroMois) use ($paiementsActuels, $paiementsPrecedents) {
            return [$nom => [
                'actuel' => $paiementsActuels->firstWhere('mois', $numeroMois)?->total ?? 0,
                'precedent' => $paiementsPrecedents->firstWhere('mois', $numeroMois)?->total ?? 0
            ]];
        });

        // Extraire les années des dates de début
        $anneeActuelle = Carbon::parse($currentAcademicYear->debut)->format('Y');
        $anneePrecedente = $previousAcademicYear ? Carbon::parse($previousAcademicYear->debut)->format('Y') : null;

        // Calcul du meilleur payeur
        $meilleurPayeur = Paiement::where('campus_id', Auth::user()->campus_id)
            ->whereBetween('date_paiement', [$this->dateDebut, $this->dateFin])
            ->with('user')
            ->select('user_id', DB::raw('SUM(montant) as total_paiements'))
            ->groupBy('user_id')
            ->orderByDesc('total_paiements')
            ->first();

        $infoMeilleurPayeur = null;
        if ($meilleurPayeur) {
            $infoMeilleurPayeur = [
                'nom' => $meilleurPayeur->user->nom,
                'prenom' => $meilleurPayeur->user->prenom,
                'matricule' => $meilleurPayeur->user->matricule,
                'total' => $meilleurPayeur->total_paiements
            ];
        }

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
            'comparaison_mensuelle' => $comparaisonMensuelle,
            'annee_actuelle' => $anneeActuelle,
            'annee_precedente' => $anneePrecedente,
            'meilleur_payeur' => $infoMeilleurPayeur
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
