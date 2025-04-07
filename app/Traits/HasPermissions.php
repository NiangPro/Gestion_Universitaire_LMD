<?php

namespace App\Traits;

use App\Models\Permission;

trait HasPermissions
{
    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    public function hasPermission($module, $action)
    {
        // Super Admin a toutes les permissions
        if ($this->estSuperAdmin()) {
            return true;
        }

        // Admin du campus a toutes les permissions sur son campus
        if ($this->estAdmin()) {
            return true;
        }

        // Vérifier les permissions spécifiques de l'utilisateur
        $userPermission = $this->permissions()
            ->where('module', $module)
            ->where('campus_id', $this->campus_id)
            ->first();

        if ($userPermission && $userPermission->{"can_$action"}) {
            return true;
        }

        // Vérifier les permissions du rôle
        $rolePermission = Permission::where('role', $this->role)
            ->where('module', $module)
            ->where('campus_id', $this->campus_id)
            ->first();

        return $rolePermission && $rolePermission->{"can_$action"};
    }

    public function getAllPermissions()
    {
        if ($this->estSuperAdmin()) {
            return collect(Permission::$modules)->mapWithKeys(function ($label, $module) {
                return [$module => [
                    'can_view' => true,
                    'can_create' => true,
                    'can_edit' => true,
                    'can_delete' => true
                ]];
            });
        }

        $permissions = [];
        foreach (Permission::$modules as $module => $label) {
            $permissions[$module] = [
                'can_view' => $this->hasPermission($module, 'view'),
                'can_create' => $this->hasPermission($module, 'create'),
                'can_edit' => $this->hasPermission($module, 'edit'),
                'can_delete' => $this->hasPermission($module, 'delete')
            ];
        }

        return $permissions;
    }

    public function canAccess($module, $action)
    {
        // Vérifier si superadmin
        if ($this->role === 'superadmin') {
            return true;
        }

        // Vérifier les permissions spécifiques de l'utilisateur
        $permission = $this->permissions()
            ->where('module', $module)
            ->where('campus_id', $this->campus_id)
            ->first();

        if ($permission && $permission->{"can_$action"}) {
            return true;
        }

        // Vérifier les permissions du rôle
        $rolePermission = Permission::where('role', $this->role)
            ->where('module', $module)
            ->where('campus_id', $this->campus_id)
            ->first();

        return $rolePermission && $rolePermission->{"can_$action"};
    }

    public function hasAnyPermission($module, array $actions)
    {
        foreach ($actions as $action) {
            if ($this->canAccess($module, $action)) {
                return true;
            }
        }
        return false;
    }

    public function hasAllPermissions($module, array $actions)
    {
        foreach ($actions as $action) {
            if (!$this->canAccess($module, $action)) {
                return false;
            }
        }
        return true;
    }
} 