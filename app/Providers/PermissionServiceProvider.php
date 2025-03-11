<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class PermissionServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Gate::define('manage-subscriptions', function (User $user) {
            // Vérifiez si l'utilisateur est administrateur ou a les droits nécessaires
            return $user->campus->is_admin || $user->hasRole('admin');
            // Adaptez cette logique selon votre système de rôles
        });
    }
}
