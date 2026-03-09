<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fournisseur extends Model
{
    use HasFactory;

    protected $table = 'fournisseurs';

    protected $fillable = [
        'nom',
        'adresse',
        'contact',
        'vehicule_fournis',
    ];

    /**
     * Relation : Un fournisseur fournit plusieurs voitures
     */
    public function voitures()
    {
        return $this->hasMany(Voiture::class, 'id_fournisseur');
    }

    /**
     * Scope : Recherche
     */
    public function scopeRecherche($query, $term)
    {
        return $query->where('nom', 'like', "%{$term}%");
    }
}
