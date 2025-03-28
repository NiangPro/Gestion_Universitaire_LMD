<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Permission;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Title("Gestion des Accès")]
class Acces extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $selectedUser = null;
    public $modules = [
        'users' => 'Utilisateurs',
        'paiements' => 'Paiements',
        'inscriptions' => 'Inscriptions',
        'classes' => 'Classes',
        'cours' => 'Cours',
        'notes' => 'Notes',
        'absences' => 'Absences',
        'messages' => 'Messages'
    ];
    
    public $permissions = [];

    public function mount()
    {
        $this->resetPermissions();
    }

    public function resetPermissions()
    {
        foreach ($this->modules as $module => $label) {
            $this->permissions[$module] = [
                'can_view' => false,
                'can_create' => false,
                'can_edit' => false,
                'can_delete' => false
            ];
        }
    }

    public function selectUser($userId)
    {
        $this->selectedUser = User::find($userId);
        $this->loadUserPermissions();
    }

    public function loadUserPermissions()
    {
        $this->resetPermissions();
        
        if ($this->selectedUser) {
            foreach ($this->selectedUser->permissions as $permission) {
                $this->permissions[$permission->module] = [
                    'can_view' => $permission->can_view,
                    'can_create' => $permission->can_create,
                    'can_edit' => $permission->can_edit,
                    'can_delete' => $permission->can_delete
                ];
            }
        }
    }

    public function savePermissions()
    {
        if (!$this->selectedUser) {
            return;
        }

        foreach ($this->modules as $module => $label) {
            Permission::updateOrCreate(
                [
                    'user_id' => $this->selectedUser->id,
                    'module' => $module
                ],
                [
                    'can_view' => $this->permissions[$module]['can_view'],
                    'can_create' => $this->permissions[$module]['can_create'],
                    'can_edit' => $this->permissions[$module]['can_edit'],
                    'can_delete' => $this->permissions[$module]['can_delete']
                ]
            );
        }

        session()->flash('success', 'Permissions mises à jour avec succès');
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        $users = User::where('campus_id', Auth::user()->campus_id)
            ->where('id', '!=', Auth::id())
            ->where(function($query) {
                $query->where('nom', 'like', '%' . $this->search . '%')
                    ->orWhere('prenom', 'like', '%' . $this->search . '%')
                    ->orWhere('matricule', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);

        return view('livewire.acces', [
            'users' => $users
        ]);
    }
}
