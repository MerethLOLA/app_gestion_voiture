<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voiture extends Model
{
    use HasFactory;

    protected $table = 'Voitures';

    public $timestamps = false;

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


    /**
     * Relation: Une voiture a plusieurs images
     */
    public function images()
    {
        return $this->hasMany(ImageVoiture::class, 'id_voiture')
            ->orderBy('ordre_affichage');
    }

    /**
     * Relation: Image principale
     */
    public function imagePrincipale()
    {
        return $this->hasOne(ImageVoiture::class, 'id_voiture')
            ->where('est_principale', true);
    }

    /**
     * Attribut: URL de l'image principale
     */
    public function getImagePrincipaleUrlAttribute()
    {
        $image = $this->imagePrincipale;
        return $image ? asset($image->chemin) : asset('/images/default-car.jpg');
    }

    protected $casts = [
        'est_principale' => 'boolean',
        'ordre_affichage' => 'integer',
        'taille_octets' => 'integer',
        'largeur' => 'integer',
        'hauteur' => 'integer',
    ];

    /**
     * Relation: Une image appartient à une voiture
     */
    public function voiture()
    {
        return $this->belongsTo(Voiture::class, 'id_voiture');
    }

    /**
     * Obtenir l'URL complète de l'image
     */
    public function getUrlAttribute()
    {
        return asset($this->chemin);
    }

    /**
     * Scope: Images principales uniquement
     */
    public function scopePrincipales($query)
    {
        return $query->where('est_principale', true);
    }

    /**
     * Scope: Par type d'image
     */
    public function scopeParType($query, $type)
    {
        return $query->where('type_image', $type);
    }
    public function typeVehicule()
    {
        return $this->belongsTo(TypeVehicule::class, 'id_type_vehicule');
    }

    /**
     * Relation: Origine/Marque
     */
    public function origineMarque()
    {
        return $this->belongsTo(OrigineMarque::class, 'id_origine_marque');
    }

    /**
     * Relation: Fournisseur
     */
    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class, 'id_fournisseur');
    }

    /**
     * Relation: Documents
     */
    public function documents()
    {
        return $this->hasMany(Document::class, 'id_voiture');
    }

    /**
     * Relation: Garantie
     */
    public function garantie()
    {
        return $this->hasOne(Garantie::class, 'id_voiture');
    }

    /**
     * Scope: Voitures neuves
     */
    public function scopeNeuves($query)
    {
        return $query->where('etat', 'neuf');
    }

    /**
     * Scope: Voitures d'occasion
     */
    public function scopeOccasion($query)
    {
        return $query->where('etat', 'occasion');
    }

    /**
     * Scope: Par origine
     */
    public function scopeParOrigine($query, $origine)
    {
        return $query->whereHas('origineMarque', function($q) use ($origine) {
            $q->where('nom', $origine);
        });
    }
}
