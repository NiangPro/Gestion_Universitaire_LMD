<div class="container-fluid">
    <!-- En-tête avec switch de mode -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-lock text-primary"></i> Gestion des Accès
                        </h4>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" wire:model.live="isRoleMode" id="modeSwitch">
                            <label class="form-check-label" for="modeSwitch">
                                Mode {{ $isRoleMode ? 'Rôles' : 'Utilisateurs' }}
                            </label>
                        </div>
                    </div>
                    
                    @if(!$isRoleMode)
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" 
                                           wire:model.live="search" 
                                           class="form-control" 
                                           placeholder="Rechercher un utilisateur...">
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Liste des utilisateurs ou rôles -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        {{ $isRoleMode ? 'Rôles' : 'Utilisateurs' }}
                    </h5>
                    
                    @if($isRoleMode)
                        <div class="list-group">
                            @foreach($roles as $roleKey => $roleLabel)
                                <button type="button" 
                                        class="list-group-item list-group-item-action {{ $selectedRole === $roleKey ? 'active' : '' }}"
                                        wire:click="selectRole('{{ $roleKey }}')">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-user-tag me-2"></i>
                                        {{ $roleLabel }}
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    @else
                        <div class="list-group">
                            @forelse($users as $user)
                                <button type="button" 
                                        class="list-group-item list-group-item-action {{ $selectedUser && $selectedUser->id === $user->id ? 'active' : '' }}"
                                        wire:click="selectUser({{ $user->id }})">
                                    <div class="d-flex justify-content-between align-items-center">
<div>
                                            <strong>{{ $user->nom }} {{ $user->prenom }}</strong>
                                            <br>
                                            <small>{{ $user->role }}</small>
                                        </div>
                                        <span class="badge bg-{{ $user->acces === 'autorise' ? 'success' : 'danger' }} rounded-pill">
                                            {{ $user->acces }}
                                        </span>
                                    </div>
                                </button>
                            @empty
                                <div class="text-center py-3 text-muted">
                                    <i class="fas fa-users fa-2x mb-2"></i>
                                    <p>Aucun utilisateur trouvé</p>
                                </div>
                            @endforelse
                        </div>

                        <div class="mt-3">
                            {{ $users->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Gestion des permissions -->
        <div class="col-md-8">
            @if($selectedUser || $selectedRole)
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="card-title mb-0">
                                @if($isRoleMode)
                                    Permissions pour le rôle : {{ $roles[$selectedRole] }}
                                @else
                                    Permissions pour {{ $selectedUser->nom }} {{ $selectedUser->prenom }}
                                @endif
                            </h5>
                            <button class="btn btn-primary" 
                                    wire:click="{{ $isRoleMode ? 'saveRolePermissions' : 'savePermissions' }}">
                                <i class="fas fa-save"></i> Enregistrer
                            </button>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Module</th>
                                        <th class="text-center">Voir</th>
                                        <th class="text-center">Créer</th>
                                        <th class="text-center">Modifier</th>
                                        <th class="text-center">Supprimer</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($modules as $module => $label)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-{{ $module === 'users' ? 'users' : 
                                                                      ($module === 'paiements' ? 'money-bill' : 
                                                                      ($module === 'inscriptions' ? 'user-graduate' : 
                                                                      ($module === 'classes' ? 'chalkboard-teacher' : 
                                                                      ($module === 'cours' ? 'book' : 
                                                                      ($module === 'notes' ? 'star' : 
                                                                      ($module === 'absences' ? 'calendar-times' : 
                                                                      'envelope')))))) }} text-primary me-2"></i>
                                                    {{ $label }}
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="form-check form-switch d-flex justify-content-center">
                                                    <input class="form-check-input" 
                                                           type="checkbox" 
                                                           wire:model="permissions.{{ $module }}.can_view">
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="form-check form-switch d-flex justify-content-center">
                                                    <input class="form-check-input" 
                                                           type="checkbox" 
                                                           wire:model="permissions.{{ $module }}.can_create">
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="form-check form-switch d-flex justify-content-center">
                                                    <input class="form-check-input" 
                                                           type="checkbox" 
                                                           wire:model="permissions.{{ $module }}.can_edit">
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="form-check form-switch d-flex justify-content-center">
                                                    <input class="form-check-input" 
                                                           type="checkbox" 
                                                           wire:model="permissions.{{ $module }}.can_delete">
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-user-lock fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">
                            Sélectionnez un {{ $isRoleMode ? 'rôle' : 'utilisateur' }} pour gérer ses permissions
                        </h5>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    .list-group-item {
        border: none;
        margin-bottom: 0.5rem;
        border-radius: 0.5rem !important;
    }

    .list-group-item:hover {
        background-color: #f8f9fa;
    }

    .list-group-item.active {
        background-color: #0d6efd;
    }

    .form-check-input {
        cursor: pointer;
    }

    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    .table td {
        vertical-align: middle;
    }
</style>
@endpush
