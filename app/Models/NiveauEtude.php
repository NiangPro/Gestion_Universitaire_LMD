<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NiveauEtude extends Model
{
    use HasFactory;
    protected $table = "niveau_etudes";

    protected $fillable = [
        "nom",
        "is_deleting",
        "campus_id",
    ];

    public function campus(){
        return $this->belongsTo(Campus::class, "campus_id");
    }

    public function uniteEnseignements(){
        return $this->belongsTo(UniteEnseignement::class);
    }
}
