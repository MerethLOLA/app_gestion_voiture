<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voiture extends Model
{
    use HasFactory;

    protected $table = 'voitures';

    protected $fillable = [
        'marque',
        'model',
        'annee',
        'couleur',
        'prix',
        'kilometrage',
        'numero_de_chassis',
        'etat',
        'date_d_acquisition',
        'id_type_vehicule',
        'id_origine_marque',
        'id_fournisseur',
    ];

    protected $casts = [
        'date_d_acquisition' => 'date',
        'prix' => 'decimal:2',
        'annee' => 'integer',
        'kilometrage' => 'integer',
    ];

    /**
     * Relation : Type de véhicule
     */
    public function typeVehicule()
    {
        return $this->belongsTo(TypeVehicule::class, 'id_type_vehicule');
    }

    /**
     * Relation : Origine/Marque
     */
    public function origineMarque()
    {
        return $this->belongsTo(OrigineMarque::class, 'id_origine_marque');
    }

    /**
     * Relation : Fournisseur
     */
    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class, 'id_fournisseur');
    }

    /**
     * Relation : Images
     */
    public function images()
    {
        return $this->hasMany(ImageVoiture::class, 'id_voiture')->orderBy('ordre_affichage');
    }

    /**
     * Relation : Image principale
     */
    public function imagePrincipale()
    {
        return $this->hasOne(ImageVoiture::class, 'id_voiture')->where('est_principale', true);
    }

    /**
     * Relation : Documents
     */
    public function documents()
    {
        return $this->hasMany(Document::class, 'id_voiture');
    }

    /**
     * Relation : Garantie
     */
    public function garantie()
    {
        return $this->hasOne(Garantie::class, 'id_voiture');
    }

    /**
     * Relation : Vente
     */
    public function vente()
    {
        return $this->hasOne(Vente::class, 'id_voiture');
    }

    /**
     * Accesseur : URL image principale
     */
    public function getImagePrincipaleUrlAttribute()
    {
        $image = $this->imagePrincipale;
        return $image ? asset($image->chemin) : asset('images/default-car.jpg');
    }

    /**
     * Accesseur : Nom complet
     */
    public function getNomCompletAttribute()
    {
        return "{$this->marque} {$this->model} ({$this->annee})";
    }

    /**
     * Accesseur : Est vendue
     */
    public function getEstVendueAttribute()
    {
        return $this->vente()->exists();
    }

    /**
     * Accesseur : Est disponible
     */
    public function getEstDisponibleAttribute()
    {
        return !$this->est_vendue;
    }

    /**
     * Scope : Voitures disponibles
     */
    public function scopeDisponibles($query)
    {
        return $query->doesntHave('vente');
    }

    /**
     * Scope : Voitures vendues
     */
    public function scopeVendues($query)
    {
        return $query->has('vente');
    }

    /**
     * Scope : Voitures neuves
     */
    public function scopeNeuves($query)
    {
        return $query->where('etat', 'neuf');
    }

    /**
     * Scope : Voitures d'occasion
     */
    public function scopeOccasion($query)
    {
        return $query->where('etat', 'occasion');
    }

    /**
     * Scope : Par origine
     */
    public function scopeParOrigine($query, $origine)
    {
        return $query->whereHas('origineMarque', function ($q) use ($origine) {
            $q->where('nom', $origine);
        });
    }

    /**
     * Scope : Par type
     */
    public function scopeParType($query, $type)
    {
        return $query->whereHas('typeVehicule', function ($q) use ($type) {
            $q->where('nom', $type);
        });
    }

    /**
     * Scope : Prix entre
     */
    public function scopePrixEntre($query, $min, $max)
    {
        return $query->whereBetween('prix', [$min, $max]);
    }

    /**
     * Scope : Recherche
     */
    public function scopeRecherche($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('marque', 'like', "%{$term}%")
                ->orWhere('model', 'like', "%{$term}%")
                ->orWhere('numero_de_chassis', 'like', "%{$term}%");
        });
    }
}
