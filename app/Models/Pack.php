<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pack extends Model
{
    use HasFactory;

    protected $table = "packs";

    protected $fillable = [
        "nom",
        "mensuel",
        "annuel",
        "limite",
        "text",
        "couleur"
    ];

    public function campuses(){
        return $this->hasMany(Campus::class);
    }
}
