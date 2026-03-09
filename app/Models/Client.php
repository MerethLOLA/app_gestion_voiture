<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $table = 'clients';

    protected $fillable = [
        'nom',
        'prenom',
        'adresse',
        'contact',
        'email',
        'piece_identite',
        'numero_piece',
    ];

    /**
     * Relation : Un client effectue plusieurs ventes
     */
    public function ventes()
    {
        return $this->hasMany(Vente::class, 'id_client');
    }

    /**
     * Relation : Un client effectue plusieurs paiements
     */
    public function paiements()
    {
        return $this->hasMany(Paiement::class, 'id_client');
    }

    /**
     * Accesseur : Nom complet
     */
    public function getNomCompletAttribute()
    {
        return "{$this->prenom} {$this->nom}";
    }

    /**
     * Scope : Recherche par nom ou prénom
     */
    public function scopeRecherche($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('nom', 'like', "%{$term}%")
                ->orWhere('prenom', 'like', "%{$term}%")
                ->orWhere('email', 'like', "%{$term}%");
        });
    }
}
