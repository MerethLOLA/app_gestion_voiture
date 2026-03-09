<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vente extends Model
{
    use HasFactory;

    protected $table = 'ventes';

    const UPDATED_AT = null;

    protected $fillable = [
        'reference_vente',
        'date',
        'id_voiture',
        'id_client',
        'id_employe',
        'prix_final',
        'mode_paiement',
    ];

    protected $casts = [
        'date' => 'date',
        'prix_final' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    /**
     * Relation : Une vente concerne une voiture
     */
    public function voiture()
    {
        return $this->belongsTo(Voiture::class, 'id_voiture');
    }

    /**
     * Relation : Une vente concerne un client
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'id_client');
    }

    /**
     * Relation : Une vente est réalisée par un employé
     */
    public function employe()
    {
        return $this->belongsTo(Employe::class, 'id_employe');
    }

    /**
     * Relation : Une vente a une facture
     */
    public function facturation()
    {
        return $this->hasOne(Facturation::class, 'id_vente');
    }

    /**
     * Relation : Une vente a des paiements
     */
    public function paiements()
    {
        return $this->hasMany(Paiement::class, 'id_vente');
    }

    /**
     * Accesseur : Montant total payé
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
        return $this->prix_final - $this->montant_paye;
    }

    /**
     * Accesseur : Est payée
     */
    public function getEstPayeeAttribute()
    {
        return $this->montant_paye >= $this->prix_final;
    }

    /**
     * Scope : Ventes du mois
     */
    public function scopeDuMois($query, $mois = null, $annee = null)
    {
        $mois = $mois ?? now()->month;
        $annee = $annee ?? now()->year;

        return $query->whereMonth('date', $mois)
            ->whereYear('date', $annee);
    }

    /**
     * Scope : Ventes par vendeur
     */
    public function scopeParVendeur($query, $employeId)
    {
        return $query->where('id_employe', $employeId);
    }

    /**
     * Scope : Ventes par client
     */
    public function scopeParClient($query, $clientId)
    {
        return $query->where('id_client', $clientId);
    }
}
