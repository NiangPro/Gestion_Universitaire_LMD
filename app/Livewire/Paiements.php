<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Paiement;
use App\Models\AcademicYear;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Title('Paiements')]
class Paiements extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $showModal = false;
    public $academic_year_id;
    public $matricule;
    public $montant;
    public $type_paiement;
    public $mode_paiement;
    public $observation;
    public $etudiant_id;
    public $searchMatricule = '';
    public $suggestions = [];
    public $selectedEtudiant = null;
    public $isEditing = false;
    public $editingPaiement = null;
    public $showDetailModal = false;
    public $selectedPaiement = null;

    protected $rules = [
        'etudiant_id' => 'required',
        'montant' => 'required|numeric|min:0',
        'type_paiement' => 'required',
        'mode_paiement' => 'required',
    ];

    public function mount()
    {
        $this->academic_year_id = Auth::user()->campus->currentAcademicYear()->id;
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
            $this->suggestions = [];
        }
    }

    public function selectEtudiant($etudiantId)
    {
        $this->selectedEtudiant = User::find($etudiantId);
        $this->etudiant_id = $etudiantId;
        $this->searchMatricule = $this->selectedEtudiant->matricule;
        $this->suggestions = [];
        
        session()->flash('info', '👤 Étudiant sélectionné : ' . $this->selectedEtudiant->nom . ' ' . $this->selectedEtudiant->prenom);
    }

    public function savePaiement()
    {
        $this->validate();

        try {
            $paiement = Paiement::create([
                'user_id' => $this->etudiant_id,
                'montant' => $this->montant,
                'type_paiement' => $this->type_paiement,
                'mode_paiement' => $this->mode_paiement,
                'observation' => $this->observation,
                'status' => 'en_attente',
                'campus_id' => Auth::user()->campus_id,
                'academic_year_id' => $this->academic_year_id,
                'date_paiement' => now(),
                'reference' => Paiement::genererReference(),
            ]);

            session()->flash('success', '✅ Nouveau paiement enregistré avec succès (Référence: ' . $paiement->reference . ')');
            $this->resetForm();
        } catch (\Exception $e) {
            session()->flash('error', '❌ Erreur lors de l\'enregistrement du paiement : ' . $e->getMessage());
        }
    }

    public function resetEtudiant()
    {
        $this->selectedEtudiant = null;
        $this->etudiant_id = null;
        $this->searchMatricule = '';
        $this->suggestions = [];
        
        // session()->flash('warning', '🔄 Sélection de l\'étudiant réinitialisée');
    }

    public function startEdit(Paiement $paiement)
    {
        if (!$paiement->isEditable()) {
            session()->flash('error', '❌ Ce paiement ne peut plus être modifié car il date de plus de 24 heures');
            return;
        }

        $this->isEditing = true;
        $this->editingPaiement = $paiement;
        $this->etudiant_id = $paiement->user_id;
        $this->selectedEtudiant = $paiement->user;
        $this->searchMatricule = $paiement->user->matricule;
        $this->montant = $paiement->montant;
        $this->type_paiement = $paiement->type_paiement;
        $this->mode_paiement = $paiement->mode_paiement;
        $this->observation = $paiement->observation;
        $this->showModal = true;
        
        session()->flash('info', '📝 Vous êtes en train de modifier le paiement ' . $paiement->reference);
    }

    public function updatePaiement()
    {
        if (!$this->editingPaiement || !$this->editingPaiement->isEditable()) {
            session()->flash('error', '❌ Ce paiement ne peut plus être modifié');
            return;
        }

        $this->validate();

        try {
            $reference = $this->editingPaiement->reference;
            $this->editingPaiement->update([
                'montant' => $this->montant,
                'type_paiement' => $this->type_paiement,
                'mode_paiement' => $this->mode_paiement,
                'observation' => $this->observation
            ]);

            session()->flash('success', '✅ Le paiement ' . $reference . ' a été modifié avec succès');
            $this->resetForm();
        } catch (\Exception $e) {
            session()->flash('error', '❌ Erreur lors de la modification du paiement : ' . $e->getMessage());
        }
    }

    private function resetForm()
    {
        $this->isEditing = false;
        $this->editingPaiement = null;
        $this->showModal = false;
        $this->resetEtudiant();
        $this->montant = '';
        $this->type_paiement = '';
        $this->mode_paiement = '';
        $this->observation = '';
    }

    public function showDetails(Paiement $paiement)
    {
        $this->selectedPaiement = $paiement;
        $this->showDetailModal = true;
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        $paiements = Paiement::with(['user', 'academicYear'])
            ->where('campus_id', Auth::user()->campus_id)
            ->when($this->academic_year_id, function($query) {
                return $query->where('academic_year_id', $this->academic_year_id);
            })
            ->when($this->matricule, function($query) {
                return $query->whereHas('user', function($q) {
                    $q->where('matricule', 'like', '%' . $this->matricule . '%');
                });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.paiement.paiements', [
            'paiements' => $paiements,
            'academic_years' => AcademicYear::where('campus_id', Auth::user()->campus_id)
                              ->orderBy('created_at', 'desc')
                              ->get()
        ]);
    }
}
