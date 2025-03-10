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
        "is_deleting",
        "couleur"
    ];

    public function campuses()
    {
        return $this->hasMany(Campus::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
