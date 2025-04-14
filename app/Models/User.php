<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasPermissions;


/**
 * App\Models\User
 *
 * @property int $id
 * @property string $prenom
 * @property string $nom
 * @property string $username
 * @property string $tel
 * @property string $role
 * @property string $sexe
 * @property int $campus_id
 * @property string $adresse
 * @property string $image
 * @property string $email
 * @property string $password
 * @property int $is_deleting
 * @property string $acces
 * @property string $statut
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Classe[] $classes
 * @property-read int|null $classes_count
 * @property-read \App\Models\Campus|null $campus
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Cour[] $coursProf
 * @property-read int|null $coursProf_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Historique[] $historiques
 * @property-read int|null $historiques_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Message[] $receivedMessages
 * @property-read int|null $receivedMessages_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Message[] $sentMessages
 * @property-read int|null $sentMessages_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User findSimilarSlugs($attribute, $config, $slug)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAcces($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAdresse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCampusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsDeleting($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePrenom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSexe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStatut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User withUniqueSlugConstraints(\Illuminate\Database\Eloquent\Model $model, $attribute, $config, $slug)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, TwoFactorAuthenticatable, HasPermissions;

    /**
     * Check if the user is a professor
     *
     * @return bool
     */
    public function estProfesseur()
    {
        return $this->role === 'professeur';
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'prenom',
        'username',
        'email',
        'tel',
        'sexe',
        'image',
        'date_naissance',
        'lieu_naissance',
        'nationalite',
        'adresse',
        'ville',
        'etablissement_precedant',
        'role',
        'password',
        'is_deleting',
        'acces',
        'statut',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
        'campus_id',
        'matricule',
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

    public function cours()
    {
        return $this->hasMany(Cour::class, 'professeur_id');
    }

    public function departement(){
        return $this->hasOne(Departement::class, "user_id");
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
        return $this->role === 'superadmin';
    }

    public function estAdmin()
    {
        return $this->role === 'admin';
    }

    public function estParent()
    {
        return $this->role == "parent";
    }

    public function estEtudiant()
    {
        return $this->role == "etudiant";
    }


    public function historiques(){
        return $this->hasMany(Historique::class);
    }

    public function informationMedicale()
    {
        return $this->hasOne(InformationMedicale::class);
    }

    /**
     * Get the inscriptions for the user.
     */
    public function inscriptions(): HasMany
    {
        return $this->hasMany(Inscription::class, 'user_id');
    }

    public function tuteur()
    {
        return $this->hasOne(User::class, 'user_id');
    }
    
    public function medical()
    {
        return $this->hasOne(Medical::class);
    }
    /**
     * Get all payments for the user.
     */
    public function paiements(): HasMany
    {
        return $this->hasMany(Paiement::class, 'user_id');
    }

    /**
     * Get total payments for the user.
     */
    public function totalPaiements()
    {
        return $this->paiements()
            ->selectRaw('user_id, SUM(montant) as total_montant, COUNT(*) as nombre_paiements')
            ->groupBy('user_id')
            ->first();
    }

    /**
     * Get current academic year payments for the user.
     */
    public function paiementsAnneeEnCours()
    {
        return $this->paiements()
            ->where('academic_year_id', $this->campus->currentAcademicYear()->id);
    }

    /**
     * Get payments for a specific academic year.
     */
    public function paiementsParAnnee($academic_year_id)
    {
        return $this->paiements()
            ->where('academic_year_id', $academic_year_id);
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    public function notes()
    {
        return $this->hasManyThrough(
            Note::class,
            Cour::class,
            'professeur_id',
            'matiere_id',
            'id',
            'matiere_id'
        )->select('notes.*')
        ->join('cours', 'cours.matiere_id', '=', 'notes.matiere_id')
        ->where('cours.academic_year_id', function($query) {
            $query->select('id')
                  ->from('academic_years')
                  ->whereIn('id', function($subquery) {
                      $subquery->select('academic_year_id')
                              ->from('cours')
                              ->where('professeur_id', $this->id);
                  });
        });
    }
}
