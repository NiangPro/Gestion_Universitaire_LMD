<?php

use App\Http\Middleware\SuperAdminMiddleware;
use App\Livewire\Activations;
use App\Livewire\Dashboard;
use App\Livewire\Etablissements;
use App\Livewire\Etudiant;
use App\Livewire\Home;
use App\Livewire\Login;
use App\Livewire\Messages;
use App\Livewire\Nonacces;
use App\Livewire\Packs;
use App\Livewire\Parents;
use App\Livewire\Professeur;
use App\Livewire\Register;
use App\Livewire\Surveillant;
use Illuminate\Support\Facades\Route;

Route::get("/", Home::class)->name("home");
Route::get("/connexion", Login::class)->name("login");
Route::get("/tableau_de_bord", Dashboard::class)->name("dashboard");
Route::get("/etablissements", Etablissements::class)->middleware(SuperAdminMiddleware::class)->name("etablissement");
Route::get("/professeurs", Professeur::class)->name("professeur");
Route::get("/packs", Packs::class)->middleware(SuperAdminMiddleware::class)->name("pack");
Route::get("/inscription/{id}", Register::class)->name("register");
Route::get("/surveillants", Surveillant::class)->name("surveillant");
Route::get("/etudiants", Etudiant::class)->name("etudiant");
Route::get("/parents", Parents::class)->name("parent");
Route::get("/activations", Activations::class)->middleware(SuperAdminMiddleware::class)->name("activation");
Route::get("/non_acces", Nonacces::class)->name("nonacces");
Route::get("/message", Messages::class)->name("message");
