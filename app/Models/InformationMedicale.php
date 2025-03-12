<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InformationMedicale extends Model
{
    protected $table = 'information_medicales';

    protected $fillable = [
        'user_id',
        'description',
        'traitement',
        'nom_medecin',
        'telephone_medecin'
    ];

    protected $casts = [
        'traitement' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
