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
use App\Http\Middleware\ProfesseurMiddleware;
use App\Livewire\Absences;
use App\Livewire\AbsencesProfesseur;
use App\Livewire\Acces;
use App\Livewire\Contact;
use App\Livewire\Evaluations;
use App\Livewire\Fonctionnalite;
use App\Livewire\Notes;
use App\Livewire\Paiements;
use App\Livewire\RapportPaiement;
use App\Livewire\Retards;
use App\Livewire\Services;
use App\Livewire\EditNote;
use App\Livewire\NotesProfesseur;
use App\Livewire\NotFound;

// Routes publiques
Route::group([], function () {
    Route::get("/", Home::class)->name("home");
    Route::get("/fonctionnalites", Fonctionnalite::class)->name("fonctionnalite");
    Route::get("/services", Services::class)->name("service");
    Route::get("/connexion", Login::class)->name("login");
    Route::get("/contact", Contact::class)->name("contact");
    Route::get("/inscription/{id}", Register::class)->name("register");
    Route::get("/mot_de_passe_oublie", PasswordForget::class)->name("forget");
    Route::get("/non_acces", Nonacces::class)->name("nonacces");
});

// Routes authentifiées (nécessite connexion)
Route::middleware(['auth'])->group(function () {
    // Routes de base pour tous les utilisateurs connectés
    Route::get("/tableau_de_bord", Dashboard::class)->name("dashboard");
    Route::get("/profil", Profil::class)->name("profil");
    Route::get("/message", Messages::class)->name("message");

    // Routes 2FA
    Route::get('/two-factor-auth', \App\Http\Livewire\Auth\TwoFactorAuth::class)->name('two-factor.auth');
    Route::get('/two-factor-challenge', \App\Http\Livewire\Auth\TwoFactorChallenge::class)->name('two-factor.challenge');
});

// Routes Super Admin
Route::middleware(['auth', SuperAdminMiddleware::class])->prefix('super-admin')->group(function () {
    Route::get("/etablissements", Etablissements::class)->name("etablissement");
    Route::get("/packs", Packs::class)->name("pack");
    Route::get("/activations", Activations::class)->name("activation");
    Route::get("/corbeille", Corbeille::class)->name("corbeille");
});

// Routes Super Admin
Route::middleware(['auth', ProfesseurMiddleware::class])->prefix('professeur')->group(function () {
    Route::get("/notes", NotesProfesseur::class)->name("noteprofesseur");
    Route::get("/absences", AbsencesProfesseur::class)->name("absenceprofesseur");
});

// Routes Admin
Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->group(function () {
    Route::get("/annees_academiques", AcademicYears::class)->name("academicyear");
    Route::get("/departements", Departements::class)->name("departement");
    Route::get("/filieres", Filieres::class)->name("filiere");
    Route::get("/unite_enseignement", UniteEnseignements::class)->name("uniteenseignement");
    Route::get("/classes", Classes::class)->name("classe");
    Route::get("/cours", Cours::class)->name("cours");
    Route::get("/configurations", Configurations::class)->name("configuration");
    Route::get("/emplois_du_temps", EmploisDuTemps::class)->name("emploisdutemps");
    Route::get("/notes", Notes::class)->name("note");
    Route::get("/evaluations", Evaluations::class)->name("evaluation");
    Route::get("/absences", Absences::class)->name("absence");
    Route::get("/retards", Retards::class)->name("retard");
    Route::get("/abonnements", Abonnement::class)->name("abonnement");
    Route::get("/paiements", Paiements::class)->name("paiement");
    Route::get("/rapports", RapportPaiement::class)->name("rapport");
    Route::get("/acces", Acces::class)->name("acces");
});

// Routes communes Super Admin et Admin
Route::middleware(['auth', SuperAdminEtAdminMiddleware::class])->group(function () {
    Route::get("/historiques", Historiques::class)->name("historique");
});

// Routes pour la gestion des utilisateurs
Route::middleware(['auth', LoginMiddleware::class])->group(function () {
    Route::get("/professeurs", Professeur::class)->name("professeur");
    Route::get("/surveillants", Surveillant::class)->name("surveillant");
    Route::get("/etudiants", Etudiant::class)->name("etudiant");
    Route::get("/parents", Parents::class)->name("parent");
});

// Routes de paiement
Route::prefix('subscription/payment')->name('subscription.payment.')->group(function () {
    Route::get('success', [SubscriptionPaymentController::class, 'success'])->name('success');
    Route::get('cancel', [SubscriptionPaymentController::class, 'cancel'])->name('cancel');
    Route::post('callback', [SubscriptionPaymentController::class, 'callback'])->name('callback');
});

// Route de test de sécurité (environnement local uniquement)
if (app()->environment('local')) {
    Route::get('/security-test', [App\Http\Controllers\SecurityTestController::class, 'testSecurity']);
}

Route::get('/notes/edit/{id}', EditNote::class)->name('notes.edit');

// Route pour la page 404
Route::get('/404', NotFound::class)->name('404');

// Route fallback pour capturer toutes les URLs non définies
Route::fallback(NotFound::class);


