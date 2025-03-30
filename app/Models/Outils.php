<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;

class Outils extends Model
{
    use HasFactory;

    public function anneeAcademique()
    {
        return AcademicYear::where("campus_id", Auth::user()->campus_id)->where("encours", true)->first() ?: null;
    }
    public function addHistorique($description, $type)
    {
        $agent = new Agent();

        $navigateur = $agent->browser();
        $version = $agent->version($navigateur);
        $os = $agent->platform();

        Historique::create([
            'user_id' => Auth::user()->id,
            'type' => $type,
            'description' => $description,
            'device' => $agent->isPhone() ? 'Téléphone' : ($agent->isTablet() ? 'Tablette' : 'Ordinateur'),
            'ip' => request()->ip(),
            'navigateur' => "$navigateur $version ($os)",
            'campus_id' => Auth::user()->campus_id
        ]);
    }

    public function createSuperAdmin()
    {
        $user = User::where("role", "superadmin")->first();
        if (!$user) {
            User::create([
                "prenom" => "Super",
                "nom" => "Admin",
                "role" => "superadmin",
                "username" => "superadmin",
                "sexe" => "homme",
                "email" => "superadmin@gmail.com",
                "tel" => "777457575",
                "image" => "profil.jpg",
                "adresse" => "PA U17",
                "password" => '$2y$12$t89ESRTMVlScrILmxeD0NuAZcGYMRdIZ2.xCFXe60fw4vBwhshjT6',
            ]);
        }

        $se = Semaine::get();

        if (count($se) == 0) {
            $tab = ["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche"];
            foreach ($tab as $val) {
                Semaine::create(["jour" => $val]);
            }
        }
    }

    public function isLogged()
    {
        if (!Auth::user()) {
            redirect(route("login"));
        }
    }

    public function initActivation()
    {

        $tables = DB::select('SHOW TABLES');
        $databaseName = config('database.connections.' . config('database.default') . '.database');
        $nom = "Tables_in_" . $databaseName;

        foreach ($tables as $t) {
            if (!in_array($t->$nom, ["historiques", "activations", "messages", "cache", "cache_locks", "failed_jobs", "job_batches", "jobs", "migrations", "password_reset_tokens", "sessions"])) {
                $act = Activation::where("nom", $t->$nom)->first();

                $trouve = strpos($t->$nom, "_");

                if ($trouve) {
                    $left = ucfirst(substr($t->$nom, 0, $trouve));
                    $right = ucfirst(substr($t->$nom, $trouve + 1, strlen($t->$nom) - ($trouve + 2)));
                    $model = $left . $right;
                } else {
                    $model = ucfirst(substr($t->$nom, 0, strlen($t->$nom) - 1));
                }

                if (!$act) {
                    Activation::create([
                        "nom" => $t->$nom,
                        "model" => $model == "Campuse" ? "Campus" : $model
                    ]);
                }
            }
        }
    }

    public function searchUser($value)
    {
        $value = '%' . $value . '%'; // Assurez-vous que le format est prêt pour la requête LIKE
        $users = [];
        if (Auth::user()->estSuperAdmin()) {
            $users = User::where(function ($query) use ($value) {
                $query->where('prenom', 'LIKE', $value)
                    ->orWhere('email', 'LIKE', $value);
            })
                ->where(function ($query) {
                    $query->where('role', 'admin')
                        ->orWhere('role', 'superadmin');
                })
                ->where('id', '!=', Auth::id()) // Exclure l'utilisateur connecté
                ->get();
        } elseif (Auth::user()->estAdmin()) {

            $users = User::where(function ($query) use ($value) {
                $query->where('prenom', 'LIKE', $value)
                    ->orWhere('email', 'LIKE', $value);
            })
                ->where(function ($query) {
                    $query->where('campus_id', Auth::user()->campus_id)
                        ->orWhere('role', 'superadmin');
                })
                ->where('id', '!=', Auth::id()) // Exclure l'utilisateur connecté
                ->get();
        } elseif (Auth::user()->estParent()) {
            $users = User::where(function ($query) use ($value) {
                $query->where('prenom', 'LIKE', $value)
                    ->orWhere('email', 'LIKE', $value);
            })
                ->where('campus_id', Auth::user()->campus_id)
                ->where('role', 'admin')
                ->where('id', '!=', Auth::id()) // Exclure l'utilisateur connecté
                ->get();
        } else {
            $users = User::where(function ($query) use ($value) {
                $query->where('prenom', 'LIKE', $value)
                    ->orWhere('email', 'LIKE', $value);
            })
                ->where('campus_id', Auth::user()->campus_id)
                ->where('id', '!=', Auth::id()) // Exclure l'utilisateur connecté
                ->get();
        }

        return $users;
    }
}
