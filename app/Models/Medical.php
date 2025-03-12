<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medical extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'maladie',
        'description',
        'traitement',
        'nom_medecin',
        'telephone_medecin'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function inscription()
    {
        return $this->hasOne(Inscription::class);
    }
}
