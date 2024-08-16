<?php

use App\Livewire\Dashboard;
use App\Livewire\Etablissements;
use App\Livewire\Login;
use Illuminate\Support\Facades\Route;

Route::get("/", Login::class)->name("connexion");
Route::get("/tableau_de_bord", Dashboard::class)->name("dashboard");
Route::get("/etablissements", Etablissements::class)->name("etablissement");
