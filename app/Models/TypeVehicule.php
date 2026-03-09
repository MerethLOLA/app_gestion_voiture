<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeVehicule extends Model
{
    use HasFactory;

    protected $table = 'types_vehicules';

    protected $fillable = [
        'nom',
        'description',
    ];

    const UPDATED_AT = null;

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Relation : Un type a plusieurs voitures
     */
    public function voitures()
    {
        return $this->hasMany(Voiture::class, 'id_type_vehicule');
    }

    /**
     * Accesseur : Nombre de voitures
     */
    public function getNombreVoituresAttribute()
    {
        return $this->voitures()->count();
    }
}
