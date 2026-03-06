<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Employe extends Model
{
    use HasFactory;

    protected $connection = 'mysql';

    protected $table = 'Employes';

    protected $primaryKey = 'id';

    public $incrementing = true;

    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'poste',
        'adresse',
        'salaire',
        'date_embauche',
        'contrat',
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id_employe', 'id');
    }
}
