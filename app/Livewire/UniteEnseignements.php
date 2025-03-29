<?php

namespace App\Livewire;

use App\Models\Filiere;
use App\Models\Matiere;
use App\Models\UniteEnseignement;
use App\Models\Outils;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title("Unités d'Enseignement")]
class UniteEnseignements extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $status = "list";
    public $title = "Liste des unités d'enseignement";
    public $outil;
    public $id, $nom, $credit, $filiere_id;
    
    // Pour les matières
    public $matieres = [];
    public $newMatiere = [
        'nom' => '',
        'credit' => '',
        'coefficient' => '',
        'volume_horaire' => ''
    ];

    // Filtres
    public $search = '';
    public $selectedFiliere = '';
    public $creditMin = '';
    public $creditMax = '';
    public $perPage = 10;
    public $sortField = 'nom';
    public $sortDirection = 'asc';

    protected $rules = [
        'nom' => 'required|string',
        'credit' => 'required|numeric|min:1',
        'filiere_id' => 'required',
        'matieres' => 'array',
        'matieres.*.nom' => 'required|string',
        'matieres.*.credit' => 'required|numeric|min:1',
        'matieres.*.coefficient' => 'required|numeric|min:1',
        'matieres.*.volume_horaire' => 'required|numeric|min:1'
    ];

    public function addNewMatiere()
    {
        if (!empty($this->newMatiere['nom'])) {
            $this->matieres[] = $this->newMatiere;
            $this->newMatiere = [
                'nom' => '',
                'credit' => '',
                'coefficient' => '',
                'volume_horaire' => ''
            ];
        }
    }

    public function removeMatiere($index)
    {
        unset($this->matieres[$index]);
        $this->matieres = array_values($this->matieres);
    }

    public function store()
    {
        $this->validate();

        try {
            if ($this->id) {
                $ue = UniteEnseignement::findOrFail($this->id);
                $ue->update([
                    'nom' => $this->nom,
                    'credit' => $this->credit,
                    'filiere_id' => $this->filiere_id
                ]);

                // Mettre à jour ou créer les matières
                foreach ($this->matieres as $matiereData) {
                    Matiere::create([
                        'nom' => $matiereData['nom'],
                        'credit' => $matiereData['credit'],
                        'coefficient' => $matiereData['coefficient'],
                        'volume_horaire' => $matiereData['volume_horaire'],
                        'unite_enseignement_id' => $ue->id,
                        'campus_id' => Auth::user()->campus_id
                    ]);
                }

                $this->dispatch('update');
            } else {
                $ue = UniteEnseignement::create([
                    'nom' => $this->nom,
                    'credit' => $this->credit,
                    'filiere_id' => $this->filiere_id,
                    'campus_id' => Auth::user()->campus_id
                ]);

                foreach ($this->matieres as $matiereData) {
                    Matiere::create([
                        'nom' => $matiereData['nom'],
                        'credit' => $matiereData['credit'],
                        'coefficient' => $matiereData['coefficient'],
                        'volume_horaire' => $matiereData['volume_horaire'],
                        'unite_enseignement_id' => $ue->id,
                        'campus_id' => Auth::user()->campus_id
                    ]);
                }

                $this->dispatch('added');
            }

            $this->reset(['nom', 'credit', 'filiere_id', 'matieres', 'id']);
            $this->changeStatus('list');
        } catch (\Exception $e) {
            $this->dispatch('error', ['message' => 'Une erreur est survenue']);
        }
    }

    public function render()
    {
        $query = UniteEnseignement::query()
            ->where('campus_id', Auth::user()->campus_id)
            ->where('is_deleting', false);

        if ($this->search) {
            $query->where('nom', 'like', '%' . $this->search . '%');
        }

        if ($this->selectedFiliere) {
            $query->where('filiere_id', $this->selectedFiliere);
        }

        if ($this->creditMin) {
            $query->where('credit', '>=', $this->creditMin);
        }

        if ($this->creditMax) {
            $query->where('credit', '<=', $this->creditMax);
        }

        $uniteEnseignements = $query
            ->orderBy($this->sortField, $this->sortDirection)
            ->with(['filiere', 'matieres'])
            ->paginate($this->perPage);

        return view('livewire.ue.unite-enseignements', [
            'uniteEnseignements' => $uniteEnseignements,
            'filieres' => Filiere::where('campus_id', Auth::user()->campus_id)
                ->where('is_deleting', false)
                ->get()
        ]);
    }
}