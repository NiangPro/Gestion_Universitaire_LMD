<?php

namespace App\Livewire;

use App\Models\Historique;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title("Historiques")]
class Historiques extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $typeFilter = '';
    public $dateDebut = '';
    public $dateFin = '';
    public $perPage = 10;
    public $selectedHistorique = null;
    public $showModal = false;

    public function mount()
    {
        // Vérifier les permissions
        if (!Auth::user()->estSuperAdmin() && Auth::user()->role != 'admin') {
            session()->flash('error', 'Vous n\'avez pas accès à cette page');
            return redirect()->route('dashboard');
        }
    }

    public function showDetails($id)
    {
        $historique = Historique::with(['user', 'campus'])->find($id);
        
        // Vérifier si l'admin a le droit de voir cet historique
        if (!Auth::user()->estSuperAdmin() && $historique->campus_id !== Auth::user()->campus_id) {
            return;
        }

        $this->selectedHistorique = $historique;
        $this->showModal = true;
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->typeFilter = '';
        $this->dateDebut = '';
        $this->dateFin = '';
    }

    #[Layout("components.layouts.app")]
    public function render()
    {
        $query = Historique::query()
            ->with(['user', 'campus']);

        if (Auth::user()->estSuperAdmin()) {
            // Pour les superadmins :
            $query->where(function($q) {
                $q->where(function($subQuery) {
                    // Historiques liés aux abonnements, campus et packs
                    $subQuery->whereIn('table', [
                        'subscriptions',  // Abonnements
                        'campuses',       // Création/modification de campus
                        'packs'          // Changements de formule
                    ]);
                })
                ->orWhere(function($subQuery) {
                    // Historiques des superadmins
                    $subQuery->whereHas('user', function($userQuery) {
                        $userQuery->where('role', 'superadmin');
                    });
                })
                ->orWhere('user_id', Auth::id()); // Ses propres historiques
            });
        } else {
            // Pour les admins, tous les historiques de leur campus
            $query->where('campus_id', Auth::user()->campus_id)
                ->whereNotIn('table', ['subscriptions', 'campuses', 'packs']); // Exclure les historiques système
        }

        // Appliquer les filtres
        $query->when($this->search, function($q) {
            return $q->where(function($query) {
                $query->where('description', 'like', '%' . $this->search . '%')
                    ->orWhere('ip', 'like', '%' . $this->search . '%')
                    ->orWhereHas('user', function($q) {
                        $q->where('nom', 'like', '%' . $this->search . '%')
                            ->orWhere('prenom', 'like', '%' . $this->search . '%')
                            ->orWhere('tel', 'like', '%' . $this->search . '%');
                    });
            });
        })
        ->when($this->typeFilter, function($q) {
            return $q->where('type', $this->typeFilter);
        })
        ->when($this->dateDebut, function($q) {
            return $q->whereDate('created_at', '>=', $this->dateDebut);
        })
        ->when($this->dateFin, function($q) {
            return $q->whereDate('created_at', '<=', $this->dateFin);
        });

        $historiques = $query->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.historique.historiques', [
            'historiques' => $historiques
        ]);
    }
}
