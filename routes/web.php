<?php

use App\Livewire\Dashboard;
use App\Livewire\Etablissements;
use App\Livewire\Home;
use App\Livewire\Login;
use App\Livewire\Packs;
use App\Livewire\Professeur;
use App\Livewire\Register;
use Illuminate\Support\Facades\Route;

Route::get("/", Home::class)->name("home");
Route::get("/connexion", Login::class)->name("login");
Route::get("/tableau_de_bord", Dashboard::class)->name("dashboard");
Route::get("/etablissements", Etablissements::class)->name("etablissement");
Route::get("/professeurs", Professeur::class)->name("professeur");
Route::get("/packs", Packs::class)->name("pack");
Route::get("/inscription", Register::class)->name("register");
