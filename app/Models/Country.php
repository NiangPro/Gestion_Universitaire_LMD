<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table="countries";

    protected $fillable = [
        'alpha2',
        'alpha3',
        'nom_en',
        'nom_fr',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
