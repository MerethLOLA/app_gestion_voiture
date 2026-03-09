<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageVoiture extends Model
{
    use HasFactory;

    protected $table = 'images_voitures';

    protected $fillable = [
        'id_voiture',
        'chemin',
        'type_image',
        'description',
        'est_principale',
        'ordre_affichage',
        'taille_octets',
        'largeur',
        'hauteur',
    ];

    protected $casts = [
        'est_principale' => 'boolean',
        'ordre_affichage' => 'integer',
        'taille_octets' => 'integer',
        'largeur' => 'integer',
        'hauteur' => 'integer',
    ];

    /**
     * Relation : Une image appartient à une voiture
     */
    public function voiture()
    {
        return $this->belongsTo(Voiture::class, 'id_voiture');
    }

    /**
     * Accesseur : URL complète
     */
    public function getUrlAttribute()
    {
        return asset($this->chemin);
    }

    /**
     * Scope : Images principales
     */
    public function scopePrincipales($query)
    {
        return $query->where('est_principale', true);
    }

    /**
     * Scope : Par type
     */
    public function scopeParType($query, $type)
    {
        return $query->where('type_image', $type);
    }
}
