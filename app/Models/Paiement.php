<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    protected $table = "paiements";

    protected $fillable = [
        "status",
        "type_paiement",
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
