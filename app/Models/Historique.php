<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Historique extends Model
{
    use HasFactory;

    protected $table = "historiques";

    protected $fillable = [
        "type",
        "table",
        "element_id",
        "description",
        "ip",
        "device",
        "navigateur",
        "user_id",
        "campus_id",
    ];

    public function user(){
        return $this->belongsTo(User::class, "user_id");
    }

    public function campus(){
        return $this->belongsTo(Campus::class, "campus_id");
    }
}
