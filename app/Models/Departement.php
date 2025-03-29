<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departement extends Model
{
    use HasFactory;

    protected $table = "departements";

    protected $fillable = [
        "nom",
        "is_deleting",
        "description",
        "campus_id",
        "user_id",
    ];

    public function campus(){
        return $this->belongsTo(Campus::class, "campus_id");
    }

    public function responsable(){
        return $this->belongsTo(User::class, "user_id");
    }



public function professeurs()
{
    return User::whereHas('cours', function($query) {
        $query->whereHas('classe', function($query) {
            $query->whereHas('filiere', function($query) {
                $query->where('departement_id', $this->id);
            });
        });
    })
    ->where('role', 'professeur')
    ->distinct();
}

public function cours()
{
    return $this->hasManyThrough(
        Cour::class,
        Filiere::class,
        'departement_id', // Clé étrangère sur filieres
        'classe_id', // Clé étrangère sur cours
        'id',
        'id'
    );
}

public function filieres()
{
    return $this->hasMany(Filiere::class, 'departement_id');
}

public function classes()
{
    return $this->hasManyThrough(
        Classe::class,
        Filiere::class,
        'departement_id',
        'filiere_id',
        'id',
        'id'
    );
}
}
