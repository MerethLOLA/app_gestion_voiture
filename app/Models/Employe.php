<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employe extends Model
{
    use HasFactory;

    protected $table = 'employes';

    protected $fillable = [
        'nom',
        'prenom',
        'poste',
        'adresse',
        'email',
        'salaire',
        'date_embauche',
        'contrat',
    ];

    protected $casts = [
        'date_embauche' => 'date',
        'salaire' => 'decimal:2',
    ];

    /**
     * Relation : Un employé peut avoir un compte user
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id_employe');
    }

    /**
     * Relation : Un employé réalise plusieurs ventes
     */
    public function ventes()
    {
        return $this->hasMany(Vente::class, 'id_employe');
    }

    /**
     * Accesseur : Nom complet
     */
    public function getNomCompletAttribute()
    {
        return "{$this->prenom} {$this->nom}";
    }

    /**
     * Scope : Employés avec compte
     */
    public function scopeAvecCompte($query)
    {
        return $query->has('user');
    }

    /**
     * Scope : Employés sans compte
     */
    public function scopeSansCompte($query)
    {
        return $query->doesntHave('user');
    }

    /**
     * Scope : Par poste
     */
    public function scopeParPoste($query, $poste)
    {
        return $query->where('poste', $poste);
    }
}
