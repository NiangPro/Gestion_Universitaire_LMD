<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campus extends Model
{
    use HasFactory;

    protected $table = "campuses";

    protected $fillable = [
        "nom",
        "tel",
        "adresse",
        "email",
        "image",
        "statut",
        "date_fermeture"
    ];

    public function users(){
        return $this->hasMany(User::class);
    }
}
