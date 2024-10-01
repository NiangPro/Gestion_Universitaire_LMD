<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
}
