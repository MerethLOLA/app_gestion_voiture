<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facturation extends Model
{
    use HasFactory;

    protected $table = 'facturations';

    const UPDATED_AT = null;

    protected $fillable = [
        'numero_facture',
        'date_facture',
        'montant_ht',
        'taux_tva',
        'montant_ttc',
        'statut',
        'date_echeance',
        'observations',
        'id_vente',
    ];

    protected $casts = [
        'date_facture' => 'date',
        'date_echeance' => 'date',
        'montant_ht' => 'decimal:2',
        'taux_tva' => 'decimal:2',
        'montant_ttc' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    /**
     * Relation : Une facture appartient à une vente
     */
    public function vente()
    {
        return $this->belongsTo(Vente::class, 'id_vente');
    }

    /**
     * Relation : Une facture a plusieurs paiements
     */
    public function paiements()
    {
        return $this->hasMany(Paiement::class, 'id_facture');
    }

    /**
     * Accesseur : Montant payé
     */
    public function getMontantPayeAttribute()
    {
        return $this->paiements()->sum('montant');
    }

    /**
     * Accesseur : Reste à payer
     */
    public function getResteAPayerAttribute()
    {
        return $this->montant_ttc - $this->montant_paye;
    }

    /**
     * Accesseur : Est payée
     */
    public function getEstPayeeAttribute()
    {
        return $this->montant_paye >= $this->montant_ttc;
    }

    /**
     * Accesseur : Est en retard
     */
    public function getEstEnRetardAttribute()
    {
        return $this->date_echeance &&
            $this->date_echeance->isPast() &&
            !$this->est_payee;
    }

    /**
     * Scope : Factures payées
     */
    public function scopePayees($query)
    {
        return $query->where('statut', 'payee');
    }

    /**
     * Scope : Factures impayées
     */
    public function scopeImpayees($query)
    {
        return $query->where('statut', '!=', 'payee');
    }

    /**
     * Scope : Factures en retard
     */
    public function scopeEnRetard($query)
    {
        return $query->where('date_echeance', '<', now())
            ->where('statut', '!=', 'payee');
    }
}
