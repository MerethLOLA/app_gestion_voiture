<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrigineMarque extends Model
{
    use HasFactory;

    protected $table = 'origines_marques';

    protected $fillable = [
        'nom',
        'description',
    ];

    const UPDATED_AT = null;

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Relation : Une origine a plusieurs voitures
     */
    public function voitures()
    {
        return $this->hasMany(Voiture::class, 'id_origine_marque');
    }

    /**
     * Accesseur : Nombre de voitures
     */
    public function getNombreVoituresAttribute()
    {
        return $this->voitures()->count();
    }
}
