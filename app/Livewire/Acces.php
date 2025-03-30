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
    public $selectedRole = null;
    public $isRoleMode = false;

    public $modules = [
        'academic_years' => 'Années académiques',
        'absences' => 'Absences',
        'classes' => 'Classes',
        'comptabilite' => 'Comptabilité',
        'cours' => 'Cours',
        'departements' => 'Départements',
        'etudiants' => 'Étudiants',
        'filieres' => 'Filières',
        'messages' => 'Messages',
        'notes' => 'Notes',
        'paiements' => 'Paiements',
        'professeurs' => 'Professeurs',
        'rapports' => 'Rapports',
        'retards' => 'Retards',
        'ue' => 'Unités d\'enseignement'
    ];

    public $roles = [
        'admin' => 'Administrateur',
        'professeur' => 'Professeur',
        'etudiant' => 'Étudiant',
        'parent' => 'Parent',
        'surveillant' => 'Surveillant',
        'comptable' => 'Comptable'
    ];
    
    public $permissions = [];

    public function updatedIsRoleMode($value)
    {
        $this->selectedUser = null;
        $this->selectedRole = null;
        $this->resetPermissions();
    }

    public function mount()
    {
        $this->resetPermissions();
    }

    public function resetPermissions()
    {
        $this->permissions = collect($this->modules)->mapWithKeys(function ($label, $module) {
            return [$module => [
                'can_view' => false,
                'can_create' => false,
                'can_edit' => false,
                'can_delete' => false
            ]];
        })->toArray();
    }

    public function selectUser($userId)
    {
        $this->selectedUser = User::find($userId);
        $this->loadUserPermissions();
    }

    public function selectRole($role)
    {
        $this->selectedRole = $role;
        $this->loadRolePermissions();
    }

    public function loadUserPermissions()
    {
        $this->resetPermissions();
        if ($this->selectedUser) {
            $userPermissions = Permission::where('user_id', $this->selectedUser->id)
                ->where('campus_id', Auth::user()->campus_id)
                ->get();

            foreach ($userPermissions as $permission) {
                if (isset($this->permissions[$permission->module])) {
                    $this->permissions[$permission->module] = [
                        'can_view' => $permission->can_view,
                        'can_create' => $permission->can_create,
                        'can_edit' => $permission->can_edit,
                        'can_delete' => $permission->can_delete
                    ];
                }
            }
        }
    }

    public function loadRolePermissions()
    {
        $this->resetPermissions();
        if ($this->selectedRole) {
            $rolePermissions = Permission::where('role', $this->selectedRole)
                ->where('campus_id', Auth::user()->campus_id)
                ->get();

            foreach ($rolePermissions as $permission) {
                if (isset($this->permissions[$permission->module])) {
                    $this->permissions[$permission->module] = [
                        'can_view' => $permission->can_view,
                        'can_create' => $permission->can_create,
                        'can_edit' => $permission->can_edit,
                        'can_delete' => $permission->can_delete
                    ];
                }
            }
        }
    }

    public function saveRolePermissions()
    {
        if (!$this->selectedRole) {
            return;
        }

        foreach ($this->modules as $module => $label) {
            Permission::updateOrCreate(
                [
                    'role' => $this->selectedRole,
                    'module' => $module,
                    'campus_id' => Auth::user()->campus_id
                ],
                [
                    'can_view' => $this->permissions[$module]['can_view'],
                    'can_create' => $this->permissions[$module]['can_create'],
                    'can_edit' => $this->permissions[$module]['can_edit'],
                    'can_delete' => $this->permissions[$module]['can_delete']
                ]
            );
        }

        // Mettre à jour les permissions de tous les utilisateurs ayant ce rôle
        $users = User::where('role', $this->selectedRole)
            ->where('campus_id', Auth::user()->campus_id)
            ->get();

        foreach ($users as $user) {
            foreach ($this->modules as $module => $label) {
                Permission::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'module' => $module
                    ],
                    [
                        'can_view' => $this->permissions[$module]['can_view'],
                        'can_create' => $this->permissions[$module]['can_create'],
                        'can_edit' => $this->permissions[$module]['can_edit'],
                        'can_delete' => $this->permissions[$module]['can_delete'],
                        'campus_id' => Auth::user()->campus_id
                    ]
                );
            }
        }

        session()->flash('success', 'Permissions du rôle mises à jour avec succès');
        return redirect()->route('acces');
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
                    'can_delete' => $this->permissions[$module]['can_delete'],
                    'campus_id' => Auth::user()->campus_id
                ]
            );
        }

        session()->flash('success', 'Permissions mises à jour avec succès');
        return redirect()->route('acces');
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        $users = [];
        if (!$this->isRoleMode) {
            $users = User::where('campus_id', Auth::user()->campus_id)
                ->where('id', '!=', Auth::id())
                ->where(function($query) {
                    $query->where('nom', 'like', '%' . $this->search . '%')
                        ->orWhere('prenom', 'like', '%' . $this->search . '%');
                })
                ->paginate(10);
        }

        return view('livewire.acces', [
            'users' => $users
        ]);
    }
}
