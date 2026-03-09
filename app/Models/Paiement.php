<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;

    protected $table = 'paiements';

    const UPDATED_AT = null;

    protected $fillable = [
        'date',
        'mode_paiement',
        'montant',
        'id_facture',
        'id_vente',
        'id_client',
    ];

    protected $casts = [
        'date' => 'date',
        'montant' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    /**
     * Relation : Un paiement appartient à une facture
     */
    public function facturation()
    {
        return $this->belongsTo(Facturation::class, 'id_facture');
    }

    /**
     * Relation : Un paiement appartient à une vente
     */
    public function vente()
    {
        return $this->belongsTo(Vente::class, 'id_vente');
    }

    /**
     * Relation : Un paiement appartient à un client
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'id_client');
    }

    /**
     * Scope : Paiements du mois
     */
    public function scopeDuMois($query, $mois = null, $annee = null)
    {
        $mois = $mois ?? now()->month;
        $annee = $annee ?? now()->year;

        return $query->whereMonth('date', $mois)
            ->whereYear('date', $annee);
    }

    /**
     * Scope : Par mode de paiement
     */
    public function scopeParMode($query, $mode)
    {
        return $query->where('mode_paiement', $mode);
    }
}
