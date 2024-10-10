<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Outils extends Model
{
    use HasFactory;

    public function createSuperAdmin(){
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
    }

    public function isLogged(){
        if (!Auth::user()) {
           redirect(route("login"));
        }
    }

    public function initActivation(){
        
        $tables = DB::select('SHOW TABLES');
        $databaseName = config('database.connections.' . config('database.default') . '.database');
        $nom = "Tables_in_".$databaseName;

        foreach($tables as $t){
            if (!in_array($t->$nom, ["activations", "cache", "cache_locks", "failed_jobs", "job_batches", "jobs", "migrations", "password_reset_tokens", "sessions"])) {
                $act = Activation::where("nom", $t->$nom)->first();
                if(!$act){
                    Activation::create([
                        "nom" => $t->$nom
                    ]);
                }
            }
        }
        
    }
}
