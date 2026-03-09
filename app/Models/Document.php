<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $table = 'documents';

    const UPDATED_AT = null;

    protected $fillable = [
        'id_voiture',
        'type_document',
        'date_emission',
        'numero_document',
        'date_expiration',
    ];

    protected $casts = [
        'date_emission' => 'date',
        'date_expiration' => 'date',
        'created_at' => 'datetime',
    ];

    /**
     * Relation : Un document appartient à une voiture
     */
    public function voiture()
    {
        return $this->belongsTo(Voiture::class, 'id_voiture');
    }

    /**
     * Accesseur : Est expiré
     */
    public function getEstExpireAttribute()
    {
        return $this->date_expiration && $this->date_expiration->isPast();
    }

    /**
     * Scope : Documents expirés
     */
    public function scopeExpires($query)
    {
        return $query->where('date_expiration', '<', now());
    }

    /**
     * Scope : Documents valides
     */
    public function scopeValides($query)
    {
        return $query->where('date_expiration', '>=', now())
            ->orWhereNull('date_expiration');
    }
}
