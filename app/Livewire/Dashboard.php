<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Cour;
use App\Models\Note;
use App\Models\Absence;
use App\Models\Retard;
use App\Models\AcademicYear;
use App\Models\Campus;
use App\Models\Classe;
use App\Models\Inscription;
use App\Models\Outils;
use App\Models\Pack;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Title("Tableau de bord")]
class Dashboard extends Component
{
    // Propriétés générales
    public $outil;
    public $user;
    public $currentAcademicYear;
    
    // Propriétés pour le SuperAdmin
    public $totalCampus = 0;
    public $revenusMensuels = 0;
    public $totalPacks = 0;
    public $totalUtilisateurs = 0;
    public $activitesRecentes = [];
    public $campusList = [];
    
    // Propriétés pour la gestion des campus
    public $showCampusModal = false;
    public $selectedCampus = null;
    public $campusForm = [
        'nom' => '',
        'pack_id' => '',
        'date_expiration' => '',
        'statut' => 'actif'
    ];
    
    // Propriétés existantes
    public $currentSemestre;
    public $semestres = [];
    public $totalCours = 0;
    public $totalAbsences = 0;
    public $totalRetards = 0;
    public $totalNotes = 0;
    public $recentActivities = [];
    public $showEmploiModal = false;
    public $showNotesModal = false;
    public $emploiDuTemps;
    public $moyennesParMatiere = [];
    public $moyennesParUE = [];
    public $totalEtudiants = 0;
    public $currentClasse;
    public $moyenneGenerale;
    public $totalProfesseurs = 0;
    public $totalClasses = 0;
    public $inscriptionsRecentes = [];
    public $totalInscriptions = 0;
    public $montantTotal = 0;
    public $coursAujourdhui = [];
    public $creditsValides = 0;
    public $creditsTotaux = 0;
    public $progressionCredits = 0;
    public $selectedSemestre = null;

    public function mount()
    {
        $this->user = Auth::user();
        $this->outil = new Outils();

        if ($this->user->estSuperAdmin()) {
            $this->loadSuperAdminData();
        } else {
            $campus = Auth::user()->campus;
            if (!$campus->currentAcademicYear()) {
                return;
            }
            $currentAcademicYear = $campus->currentAcademicYear();
            $this->currentAcademicYear = $currentAcademicYear;
            
            // Calcul du nombre d'étudiants pour l'année académique en cours
            $this->totalEtudiants = User::where('campus_id', $campus->id)
            ->where('role', 'etudiant')
            ->whereHas('inscriptions', function($query) use ($currentAcademicYear) {
                $query->where('academic_year_id', $currentAcademicYear->id)
                    ->where('status', 'en_cours');
            })
            ->count();
        }
        
        if ($this->user->estProfesseur()) {
            $this->loadProfesseurData();
        }else if($this->user->estAdmin()){
            if ($this->currentAcademicYear) {
                $this->loadStatistiques();
                $this->loadInscriptionsRecentes();
                $this->loadCoursAujourdhui();
            }
        } else if ($this->user->estEtudiant()) {
            $this->loadEleveData();
        }

       $this->outil->createTypeEvaluation();

        
    }

    private function loadProfesseurData()
    {
        // Récupération des cours du professeur pour l'année en cours
        $this->totalCours = Cour::where('professeur_id', $this->user->id)
            ->where('academic_year_id', $this->currentAcademicYear->id)
            ->count();

        // Nombre total d'élèves dans le campus du professeur
        $campus = Auth::user()->campus;
        if ($campus) {
            $this->totalEtudiants = User::where('campus_id', $campus->id)
                ->where('role', 'etudiant')
                ->count();
        } else {
            $this->totalEtudiants = 0;
        }

        // Dernières activités (notes)
        $this->recentActivities = Note::with(['etudiant', 'cours', 'cours.matiere'])
            ->where('academic_year_id', $this->currentAcademicYear->id)
            ->whereHas('cours', function($query) {
                $query->where('professeur_id', $this->user->id);
            })
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($note) {
                return [
                    'id' => $note->id,
                    'note' => $note->note,
                    'created_at' => $note->created_at,
                    'etudiant' => $note->etudiant ? [
                        'id' => $note->etudiant->id,
                        'nom' => $note->etudiant->nom,
                        'prenom' => $note->etudiant->prenom
                    ] : null,
                    'cours' => $note->cours ? [
                        'id' => $note->cours->id,
                        'matiere' => $note->cours->matiere ? [
                            'id' => $note->cours->matiere->id,
                            'nom' => $note->cours->matiere->nom
                        ] : null
                    ] : null
                ];
            })->toArray();
    }

    private function loadEleveData()
    {
        // Récupération de la classe actuelle de l'élève
        $this->currentClasse = $this->user->getCurrentClass();

        if ($this->currentClasse) {
            // Chargement des semestres disponibles
            $this->semestres = $this->user->campus->semestres;

            // Définir le semestre actif
            $this->currentSemestre = $this->selectedSemestre 
                ? $this->semestres->firstWhere('id', $this->selectedSemestre)
                : $this->user->campus->currentSemestre();

            if (!$this->selectedSemestre && $this->currentSemestre) {
                $this->selectedSemestre = $this->currentSemestre->id;
            }

            // Total des cours de sa classe pour le semestre
            $this->totalCours = Cour::where('classe_id', $this->currentClasse->id)
                ->where('academic_year_id', $this->currentAcademicYear->id)
                ->whereHas('matiere.semestres', function($query) {
                    $query->where('semestre_id', $this->selectedSemestre);
                })
                ->count();

            // Absences pour le semestre sélectionné
            $this->totalAbsences = Absence::where('etudiant_id', $this->user->id)
                ->where('academic_year_id', $this->currentAcademicYear->id)
                ->whereHas('cours', function($query) {
                    $query->whereHas('matiere.semestres', function($q) {
                        $q->where('semestre_id', $this->selectedSemestre);
                    });
                })
                ->where('status', 'absent')
                ->count();

            // Retards pour le semestre sélectionné
            $this->totalRetards = Retard::where('etudiant_id', $this->user->id)
                ->where('academic_year_id', $this->currentAcademicYear->id)
                ->whereHas('cours', function($query) {
                    $query->whereHas('matiere.semestres', function($q) {
                        $q->where('semestre_id', $this->selectedSemestre);
                    });
                })
                ->count();

            // Notes et statistiques
            $notes = Note::where('etudiant_id', $this->user->id)
                ->where('academic_year_id', $this->currentAcademicYear->id)
                ->whereHas('cours', function($query) {
                    $query->whereHas('matiere.semestres', function($q) {
                        $q->where('semestre_id', $this->selectedSemestre);
                    });
                })
                ->with(['cours.matiere', 'cours.ue', 'typeEvaluation'])
                ->get();

            // Notes récentes
            $this->recentActivities = $notes->sortByDesc('created_at')->take(5);

            // Calcul des moyennes par matière
            $this->moyennesParMatiere = $notes->groupBy('cours.matiere.nom')
                ->map(function ($notesMatiere) {
                    $moyenne = $notesMatiere->avg('note');
                    $credits = $notesMatiere->first()->cours->matiere->credits;
                    return [
                        'notes' => $notesMatiere,
                        'moyenne' => round($moyenne, 2),
                        'credits' => $credits,
                        'valides' => $moyenne >= 10
                    ];
                });

            // Calcul des moyennes par UE
            $this->moyennesParUE = $notes->groupBy('cours.ue.nom')
                ->map(function ($notesUE) {
                    return [
                        'moyenne' => round($notesUE->avg('note'), 2),
                        'credits' => $notesUE->sum('cours.matiere.credits')
                    ];
                });

            // Calcul de la moyenne générale
            $this->moyenneGenerale = $notes->count() > 0 ? round($notes->avg('note'), 2) : 0;

            // Calcul des crédits
            $this->creditsTotaux = $this->moyennesParMatiere->sum('credits');
            $this->creditsValides = $this->moyennesParMatiere
                ->where('valides', true)
                ->sum('credits');
            
            $this->progressionCredits = $this->creditsTotaux > 0
                ? round(($this->creditsValides / $this->creditsTotaux) * 100)
                : 0;
        }
    }

    public function toggleEmploiModal()
    {
        $this->showEmploiModal = !$this->showEmploiModal;
        if ($this->showEmploiModal) {
            $this->loadEmploiDuTemps();
        }
    }

    public function toggleNotesModal()
    {
        $this->showNotesModal = !$this->showNotesModal;
        if ($this->showNotesModal) {
            $this->loadNotes();
        }
    }

    private function loadEmploiDuTemps()
    {
        if ($this->currentClasse) {
            $currentSemestre = $this->user->campus->currentSemestre();
            $coursQuery = Cour::where('classe_id', $this->currentClasse->id)
                ->where('academic_year_id', $this->currentAcademicYear->id)
                ->where('statut', 'actif')
                ->with([
                    'professeur:id,nom,prenom',
                    'matiere:id,nom,code',
                    'salle:id,nom',
                    'semaine:id,nom'
                ])
                ->orderBy('semaine_id')
                ->orderBy('heure_debut');

            if ($currentSemestre) {
                $coursQuery->whereHas('matiere', function($query) use ($currentSemestre) {
                    $query->whereHas('semestres', function($q) use ($currentSemestre) {
                        $q->where('semestre_id', $currentSemestre->id);
                    });
                });
            }

            $this->emploiDuTemps = $coursQuery->get()->groupBy('semaine.nom');
        }
    }

    private function loadNotes()
    {
        if ($this->user->estEtudiant()) {
            $currentSemestre = $this->user->campus->currentSemestre();
            $notesQuery = Note::where('etudiant_id', $this->user->id)
                ->where('academic_year_id', $this->currentAcademicYear->id)
                ->with(['cours.matiere', 'typeEvaluation']);

            if ($currentSemestre) {
                $notesQuery->where('semestre_id', $currentSemestre->id);
            }

            $notes = $notesQuery->get();

            // Grouper les notes par matière et calculer les moyennes
            $this->moyennesParMatiere = $notes->groupBy('cours.matiere.nom')
                ->map(function ($notesMatiere) {
                    return [
                        'notes' => $notesMatiere,
                        'moyenne' => $notesMatiere->count() > 0 ? round($notesMatiere->avg('note'), 2) : 0
                    ];
                });
        }
    }

    private function loadStatistiques()
    {
        // Statistiques des étudiants
        $this->totalEtudiants = User::where('role', 'eleve')
            ->where('campus_id', $this->user->campus_id)
            ->count();

        // Statistiques des professeurs
        $this->totalProfesseurs = User::where('role', 'professeur')
            ->where('campus_id', $this->user->campus_id)
            ->count();

        // Statistiques des classes
        $this->totalClasses = Classe::where('campus_id', $this->user->campus_id)
            // ->where('academic_year_id', $this->currentAcademicYear->id)
            ->count();

        // Statistiques des cours
        $this->totalCours = Cour::where('campus_id', $this->user->campus_id)
            ->where('academic_year_id', $this->currentAcademicYear->id)
            ->count();

        // Statistiques des inscriptions et montants
        $inscriptions = Inscription::where('campus_id', $this->user->campus_id)
            ->where('academic_year_id', $this->currentAcademicYear->id)
            ->get();

        $this->totalInscriptions = $inscriptions->count();
        $this->montantTotal = $inscriptions->sum('montant');
    }

    private function loadInscriptionsRecentes()
    {
        $this->inscriptionsRecentes = Inscription::where('campus_id', $this->user->campus_id)
            ->where('academic_year_id', $this->currentAcademicYear->id)
            ->with(['etudiant', 'classe'])
            ->latest()
            ->take(5)
            ->get();
    }

    public function loadCoursAujourdhui()
    {
        $campus = Auth::user()->campus;
        $currentAcademicYear = $campus->currentAcademicYear();
        
        if ($currentAcademicYear) {
            $jourSemaine = now()->format('l'); // Récupère le jour actuel en anglais (Monday, Tuesday, etc.)
            
            // Convertir en français
            $joursFr = [
                'Monday' => 'Lundi',
                'Tuesday' => 'Mardi',
                'Wednesday' => 'Mercredi',
                'Thursday' => 'Jeudi',
                'Friday' => 'Vendredi',
                'Saturday' => 'Samedi',
                'Sunday' => 'Dimanche'
            ];
            
            $jourFr = $joursFr[$jourSemaine];

            $this->coursAujourdhui = Cour::where('campus_id', $campus->id)
                ->where('academic_year_id', $currentAcademicYear->id)
                ->whereHas('semaine', function($query) use ($jourFr) {
                    $query->where('jour', $jourFr);
                })
                ->with(['classe', 'professeur', 'matiere', 'salle', 'semaine'])
                ->orderBy('heure_debut')
                ->get();
        }
    }

    // Méthodes pour le SuperAdmin
    private function loadSuperAdminData()
    {
        // Statistiques globales
        $this->totalCampus = Campus::count();
        $this->totalPacks = Pack::count();
        $this->totalUtilisateurs = User::count();
        
        // Calcul des revenus mensuels
        $this->revenusMensuels = Subscription::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount_paid');

        // Liste des campus avec leurs informations
        $this->campusList = Campus::with(['subscriptions' => function($query) {
            $query->where('status', 'active')
                  ->where('payment_status', 'paid')
                  ->where('end_date', '>', now())
                  ->latest();
        }])->withCount('users')
            ->get()
            ->map(function ($campus) {
                $activeSubscription = $campus->activeSubscription();
                return [
                    'id' => $campus->id,
                    'nom' => $campus->nom,
                    'pack' => $activeSubscription ? $activeSubscription->pack->nom : 'Non défini',
                    'total_users' => $campus->users_count,
                    'date_expiration' => $activeSubscription ? $activeSubscription->end_date : null,
                    'statut' => $activeSubscription ? 'actif' : 'expiré'
                ];
            });

        // Activités récentes (inscriptions et abonnements)
        $this->activitesRecentes = collect();
        
        // Récupérer les abonnements avec leurs campus associés
        $subscriptions = Subscription::whereHas('campus')
            ->with('campus:id,nom')
            ->latest()
            ->take(5)
            ->get();
            
        // Ajouter les abonnements aux activités récentes
        foreach ($subscriptions as $subscription) {
            $this->activitesRecentes->push([
                'type' => 'abonnement',
                'date' => $subscription->created_at,
                'description' => "Nouvel abonnement pour " . $subscription->campus->nom,
                'montant' => $subscription->amount_paid
            ]);
        }

    }

    public function ajouterCampus()
    {
        $this->resetCampusForm();
        $this->showCampusModal = true;
    }

    public function editerCampus($campusId)
    {
        $this->selectedCampus = Campus::find($campusId);
        $this->campusForm = [
            'nom' => $this->selectedCampus->nom,
            'pack_id' => $this->selectedCampus->pack_id,
            'date_expiration' => $this->selectedCampus->date_expiration->format('Y-m-d'),
            'statut' => $this->selectedCampus->statut
        ];
        $this->showCampusModal = true;
    }

    public function sauvegarderCampus()
    {
        $this->validate([
            'campusForm.nom' => 'required|string|max:255',
            'campusForm.pack_id' => 'required|exists:packs,id',
            'campusForm.date_expiration' => 'required|date|after:today',
            'campusForm.statut' => 'required|in:actif,inactif'
        ]);

        if ($this->selectedCampus) {
            $this->selectedCampus->update($this->campusForm);
        } else {
            Campus::create($this->campusForm);
        }

        $this->showCampusModal = false;
        $this->loadSuperAdminData();
    }

    public function renouvelerAbonnement($campusId)
    {
        // Logique de renouvellement d'abonnement
        $campus = Campus::find($campusId);
        $campus->date_expiration = now()->addYear();
        $campus->save();

        $this->loadSuperAdminData();
    }

    private function resetCampusForm()
    {
        $this->selectedCampus = null;
        $this->campusForm = [
            'nom' => '',
            'pack_id' => '',
            'date_expiration' => '',
            'statut' => 'actif'
        ];
    }

    #[Layout("components.layouts.app")]
    public function render()
    {
        if (Auth::user()->estSuperAdmin()) {
            return view('livewire.dashboard.dashboardSuperAdmin');
        } else if(Auth::user()->estAdmin()) {
            return view('livewire.dashboard.dashboard-admin');
        } else if(Auth::user()->estProfesseur()) {
            return view('livewire.dashboard.dashboard-professeur');
        } else if(Auth::user()->estEtudiant()) {
            return view('livewire.dashboard.dashboard-etudiant');
        } else {
            return view('livewire.dashboard.dashboard');
        }
    }
}
