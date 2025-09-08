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

    public $date = '';
    public $justification = '';

    protected $queryString = [
        'date' => ['except' => ''],
        'justification' => ['except' => '']
    ];

    public $retardStats = [
        'total' => 0,
        'justifies' => 0,
        'non_justifies' => 0,
        'mois_courant' => 0
    ];

    public function mount()
    {
        $this->loadRetardStats();
    }

    public function updatedDate($value)
    {
        $this->resetPage();
    }

    public function updatedJustification($value)
    {
        $this->resetPage();
    }

    private function loadRetardStats()
    {
        $user = Auth::user();

        if (!$user || !$user->estEtudiant()) {
            $this->retardStats = [
                'total' => 0,
                'justifies' => 0,
                'non_justifies' => 0,
                'mois_courant' => 0
            ];
            return;
        }

        // Vérifier si l'étudiant a une inscription valide pour l'année en cours
        $inscription = $user->inscriptions()
            ->where('academic_year_id', $user->campus->currentAcademicYear()->id)
            ->where('status', 'en_cours')
            ->first();

        if (!$inscription) {
            $this->retardStats = [
                'total' => 0,
                'justifies' => 0,
                'non_justifies' => 0,
                'mois_courant' => 0
            ];
            return;
        }

        $currentAcademicYearId = $user->campus->currentAcademicYear()->id;

        $this->retardStats = [
            'total' => Retard::where('etudiant_id', $user->id)
                ->where('academic_year_id', $currentAcademicYearId)
                ->count(),
            'justifies' => Retard::where('etudiant_id', $user->id)
                ->where('academic_year_id', $currentAcademicYearId)
                ->where('justifie', true)
                ->count(),
            'non_justifies' => Retard::where('etudiant_id', $user->id)
                ->where('academic_year_id', $currentAcademicYearId)
                ->where('justifie', false)
                ->count(),
            'mois_courant' => Retard::where('etudiant_id', $user->id)
                ->where('academic_year_id', $currentAcademicYearId)
                ->whereMonth('date', now()->month)
                ->count()
        ];
    }

    public function render()
    {
        $user = Auth::user();

        if (!$user || !$user->estEtudiant()) {
            $query = Retard::where('id', 0); // Crée une requête vide
            $retards = $query->paginate(10);
            return view('livewire.etudiant.retard-etudiant', [
                'retards' => $retards
            ]);
        }

        // Vérifier si l'étudiant a une inscription valide pour l'année en cours
        $inscription = $user->inscriptions()
            ->where('academic_year_id', $user->campus->currentAcademicYear()->id)
            ->where('status', 'en_cours')
            ->first();

        if (!$inscription) {
            $query = Retard::where('id', 0); // Crée une requête vide
            $retards = $query->paginate(10);
            return view('livewire.etudiant.retard-etudiant', [
                'retards' => $retards
            ]);
        }

        $query = Retard::where('etudiant_id', $user->id)
            ->where('academic_year_id', $user->campus->currentAcademicYear()->id)
            ->with(['cours.matiere'])
            ->orderBy('date', 'desc');

        if ($this->date) {
            $query->whereDate('date', $this->date);
        }

        if ($this->justification !== '') {
            $query->where('justifie', $this->justification === 'justifie');
        }

        $retards = $query->paginate(10);
        $this->loadRetardStats();

        return view('livewire.etudiant.retard-etudiant', [
            'retards' => $retards
        ]);
    }
}
