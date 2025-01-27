<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coefficient extends Model
{
    use HasFactory;

    protected $table = "coefficients";

    protected $fillable = [
        "valeur",
        "campus_id",
        "is_deleting"
    ];

    public function campus(){
        return $this->belongsTo(Campus::class, "campus_id");
    }
}
