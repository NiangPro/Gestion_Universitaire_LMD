<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Historique;
use Illuminate\Support\Facades\Auth;

class Historiques extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $typeFilter = '';
    public $dateDebut;
    public $dateFin;
    public $perPage = 10;
    public $showModal = false;
    public $selectedHistorique;

    protected $queryString = [
        'search' => ['except' => ''],
        'typeFilter' => ['except' => ''],
        'perPage' => ['except' => 10],
    ];

    public function mount()
    {
        if (!Auth::user()->estSuperAdmin() && Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard');
        }
    }

    public function showDetails($id)
    {
        $this->selectedHistorique = Historique::with(['user', 'campus'])->find($id);
        $this->showModal = true;
    }

    public function resetFilters()
    {
        $this->reset(['search', 'typeFilter', 'dateDebut', 'dateFin']);
        $this->perPage = 10;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingTypeFilter()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function getHistoriques()
    {
        $query = Historique::query();

        if (auth()->user()->estSuperAdmin()) {
            // Pour les superadmins, montrer uniquement les historiques système
            $query->whereIn('table', ['subscriptions', 'campuses', 'packs']);
        } else {
            // Pour les admins, montrer tous les historiques de leur campus
            $query->where('campus_id', auth()->user()->campus_id);
        }

        // Ajout des relations et filtres
        $query->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('description', 'like', '%' . $this->search . '%')
                      ->orWhereHas('user', function($q) {
                          $q->where('nom', 'like', '%' . $this->search . '%')
                            ->orWhere('prenom', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->when($this->typeFilter, function($query) {
                $query->where('type', $this->typeFilter);
            })
            ->when($this->dateDebut, function($query) {
                $query->whereDate('created_at', '>=', $this->dateDebut);
            })
            ->when($this->dateFin, function($query) {
                $query->whereDate('created_at', '<=', $this->dateFin);
            });

        // Log pour déboguer
        \Log::info('SQL Query:', [
            'sql' => $query->toSql(),
            'bindings' => $query->getBindings(),
            'user_role' => auth()->user()->role,
            'campus_id' => auth()->user()->campus_id
        ]);

        return $query->with(['user', 'campus'])
            ->latest()
            ->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.historique.historiques', [
            'historiques' => $this->getHistoriques()
        ]);
    }
}
