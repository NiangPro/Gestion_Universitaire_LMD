<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\LoginMiddleware;
use App\Http\Middleware\SuperAdminEtAdminMiddleware;
use App\Http\Middleware\SuperAdminMiddleware;
use App\Livewire\Abonnement;
use App\Livewire\AcademicYears;
use App\Livewire\Activations;
use App\Livewire\Classes;
use App\Livewire\Configurations;
use App\Livewire\Corbeille;
use App\Livewire\Cours;
use App\Livewire\Dashboard;
use App\Livewire\Departements;
use App\Livewire\EmploisDuTemps;
use App\Livewire\Etablissements;
use App\Livewire\Etudiant;
use App\Livewire\Filieres;
use App\Livewire\Historiques;
use App\Livewire\Home;
use App\Livewire\Login;
use App\Livewire\Messages;
use App\Livewire\NiveauEtudes;
use App\Livewire\Nonacces;
use App\Livewire\Packs;
use App\Livewire\Parents;
use App\Livewire\PasswordForget;
use App\Livewire\Professeur;
use App\Livewire\Profil;
use App\Livewire\Register;
use App\Livewire\Surveillant;
use App\Livewire\UniteEnseignements;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubscriptionPaymentController;
use App\Livewire\Absences;
use App\Livewire\Fonctionnalite;
use App\Livewire\Notes;
use App\Livewire\Retards;
use App\Livewire\Services;

Route::get("/", Home::class)->name("home");
Route::get("/fonctionnalites", Fonctionnalite::class)->name("fonctionnalite");
Route::get("/services", Services::class)->name("service");
Route::get("/connexion", Login::class)->name("login");
Route::get("/tableau_de_bord", Dashboard::class)->middleware(LoginMiddleware::class)->name("dashboard");
Route::get("/etablissements", Etablissements::class)->middleware(SuperAdminMiddleware::class)->name("etablissement");
Route::get("/professeurs", Professeur::class)->middleware(LoginMiddleware::class)->name("professeur");
Route::get("/packs", Packs::class)->middleware(SuperAdminMiddleware::class)->name("pack");
Route::get("/inscription/{id}", Register::class)->name("register");
Route::get("/surveillants", Surveillant::class)->middleware(LoginMiddleware::class)->name("surveillant");
Route::get("/etudiants", Etudiant::class)->middleware(LoginMiddleware::class)->name("etudiant");
Route::get("/parents", Parents::class)->middleware(LoginMiddleware::class)->name("parent");
Route::get("/activations", Activations::class)->middleware(SuperAdminMiddleware::class)->name("activation");
Route::get("/corbeille", Corbeille::class)->middleware(SuperAdminMiddleware::class)->name("corbeille");
Route::get("/non_acces", Nonacces::class)->name("nonacces");
Route::get("/message", Messages::class)->middleware(LoginMiddleware::class)->name("message");
Route::get("/mot_de_passe_oublie", PasswordForget::class)->name("forget");
Route::get("/annees_academiques", AcademicYears::class)->middleware(AdminMiddleware::class)->name("academicyear");
Route::get("/departements", Departements::class)->middleware(AdminMiddleware::class)->name("departement");
Route::get("/filieres", Filieres::class)->middleware(AdminMiddleware::class)->name("filiere");
Route::get("/niveaux_etudes", NiveauEtudes::class)->middleware(AdminMiddleware::class)->name("niveauetude");
Route::get("/unite_enseignement", UniteEnseignements::class)->middleware(AdminMiddleware::class)->name("uniteenseignement");
Route::get("/classes", Classes::class)->middleware(AdminMiddleware::class)->name("classe");
Route::get("/cours", Cours::class)->middleware(AdminMiddleware::class)->name("cours");
Route::get("/configurations", Configurations::class)->middleware(AdminMiddleware::class)->name("configuration");
Route::get("/emplois_du_temps", EmploisDuTemps::class)->middleware(AdminMiddleware::class)->name("emploisdutemps");
Route::get("/historiques", Historiques::class)->middleware(SuperAdminEtAdminMiddleware::class)->name("historique");
Route::get("/notes", Notes::class)->middleware(AdminMiddleware::class)->name("note");
Route::get("/absences", Absences::class)->middleware(AdminMiddleware::class)->name("absence");
Route::get("/retards", Retards::class)->middleware(AdminMiddleware::class)->name("retard");
Route::get("/abonnements", Abonnement::class)->middleware(AdminMiddleware::class)->name("abonnement");
Route::get("/profil", Profil::class)->middleware(LoginMiddleware::class)->name("profil");

// Route de test de sécurité (à utiliser uniquement en développement)
if (app()->environment('local')) {
    Route::get('/security-test', [App\Http\Controllers\SecurityTestController::class, 'testSecurity']);
}

// Routes 2FA
Route::middleware(['auth'])->group(function () {
    Route::get('/two-factor-auth', \App\Http\Livewire\Auth\TwoFactorAuth::class)->name('two-factor.auth');
    Route::get('/two-factor-challenge', \App\Http\Livewire\Auth\TwoFactorChallenge::class)->name('two-factor.challenge');
});

Route::prefix('subscription/payment')->name('subscription.payment.')->group(function () {
    Route::get('success', [SubscriptionPaymentController::class, 'success'])->name('success');
    Route::get('cancel', [SubscriptionPaymentController::class, 'cancel'])->name('cancel');
    Route::post('callback', [SubscriptionPaymentController::class, 'callback'])->name('callback');
});


