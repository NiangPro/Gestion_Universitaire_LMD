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
        return $this->belongsToMany(Campus::class, 'subscriptions')
            ->withPivot(['start_date', 'end_date', 'status', 'payment_status', 'amount_paid', 'payment_method', 'payment_reference']);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
