<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
use App\Livewire\ThemeSwitch;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        setlocale(LC_TIME, 'fr_FR.utf8', 'fra');
        Carbon::setLocale('fr');
        Livewire::component('theme-switch', ThemeSwitch::class);
    }
}
