<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UniteEnseignement extends Model
{
    use HasFactory;

    protected $table = "unite_enseignements";

    protected $fillable = [
        "nom",
        "is_deleting",
        "credit",
        "filiere_id",
        "campus_id",
    ];
    
    public function matieres(){
        return $this->hasMany(Matiere::class);
    }
    

    public function filiere(){
        return $this->belongsTo(Filiere::class, "filiere_id");
    }

    public function campus(){
        return $this->belongsTo(Campus::class, "campus_id");
    }

}
