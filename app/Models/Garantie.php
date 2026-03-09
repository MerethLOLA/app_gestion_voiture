<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Garantie extends Model
{
    use HasFactory;

    protected $table = 'garanties';

    const UPDATED_AT = null;

    protected $fillable = [
        'id_voiture',
        'duree_garantie',
        'type_garantie',
        'date_debut',
        'date_fin',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'duree_garantie' => 'integer',
        'created_at' => 'datetime',
    ];

    /**
     * Relation : Une garantie appartient à une voiture
     */
    public function voiture()
    {
        return $this->belongsTo(Voiture::class, 'id_voiture');
    }

    /**
     * Accesseur : Est active
     */
    public function getEstActiveAttribute()
    {
        $now = now();
        return $this->date_debut <= $now && $this->date_fin >= $now;
    }

    /**
     * Accesseur : Est expirée
     */
    public function getEstExpireeAttribute()
    {
        return $this->date_fin && $this->date_fin->isPast();
    }

    /**
     * Scope : Garanties actives
     */
    public function scopeActives($query)
    {
        return $query->where('date_debut', '<=', now())
            ->where('date_fin', '>=', now());
    }

    /**
     * Scope : Garanties expirées
     */
    public function scopeExpirees($query)
    {
        return $query->where('date_fin', '<', now());
    }
}
