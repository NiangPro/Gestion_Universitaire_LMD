<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenue extends Model
{
    protected $table = "tenues";

    protected $fillable = [
        "etat",
        "status",
        "mode_paiement",
        "montant",
        "user_id",
        "campus_id",
    ];

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function campus()
    {
        return $this->belongsTo(Campus::class, "campus_id");
    }
    use HasFactory;
}
