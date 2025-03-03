<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'prenom',
        'nom',
        'username',
        'tel',
        'role',
        'sexe',
        'campus_id',
        'adresse',
        'image',
        'email',
        'password',
        'is_deleting',
        'acces',
        'statut',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at'
    ];

    public function campus(){
        return $this->belongsTo(Campus::class, "campus_id");
    }

    public function classes()
    {
        return $this->belongsToMany(Classe::class, 'classe_eleve')
            ->withPivot('academic_year_id')
            ->withTimestamps();
    }

    public function cours(){
        return $this->hasMany(Cour::class, "professeur_id");
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function notRead(){
        return $this->receivedMessages()->where("is_read", 0)->get();
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'two_factor_confirmed_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function estSuperAdmin()
    {
        return $this->role == "superadmin";
    }

    public function estAdmin()
    {
        return $this->role == "admin";
    }

    public function estParent()
    {
        return $this->role == "parent";
    }

    public function estEleve()
    {
        return $this->role == "eleve";
    }

    public function estProfesseur()
    {
        return $this->role == "professeur";
    }

    public function historiques(){
        return $this->hasMany(Historique::class);
    }
}
