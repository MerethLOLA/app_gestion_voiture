<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $connection = 'mysql';

    protected $fillable = [
        'username',
        'email',
        'password_hash',
        'role',
        'statut',
        'last_login',
        'id_employe',
    ];

    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login' => 'datetime',
        ];
    }

    public function getAuthPassword(): string
    {
        return $this->password_hash ?? $this->password ?? '';
    }

    public function getNameAttribute(): string
    {
        return $this->attributes['name'] ?? $this->attributes['username'] ?? '';
    }

    /**
     * Relation: Employé lié
     */
    public function employe()
    {
        return $this->belongsTo(Employe::class, 'id_employe');
    }

    /**
     * Obtenir toutes les permissions de l'utilisateur
     */
    public function permissions()
    {
        return DB::table('permissions')
            ->join('role_permissions', 'permissions.id', '=', 'role_permissions.permission_id')
            ->where('role_permissions.role', $this->role)
            ->pluck('permissions.nom')
            ->toArray();
    }

    /**
     * Vérifier si l'utilisateur a une permission spécifique
     */
    public function hasPermission(string $permissionName): bool
    {
        return in_array($permissionName, $this->permissions());
    }

    /**
     * Vérifier si l'utilisateur a l'une des permissions données
     */
    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Vérifier si l'utilisateur a toutes les permissions données
     */
    public function hasAllPermissions(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Vérifier si l'utilisateur est un cadre dirigeant
     */
    public function isExecutive(): bool
    {
        return in_array($this->role, ['pdg', 'directeur_general', 'directeur_commercial']);
    }

    /**
     * Vérifier si l'utilisateur peut voir les fournisseurs
     */
    public function canViewFournisseurs(): bool
    {
        return $this->hasPermission('view_fournisseurs');
    }

    /**
     * Vérifier si l'utilisateur peut voir les employés
     */
    public function canViewEmployes(): bool
    {
        return $this->hasPermission('view_employes');
    }

    /**
     * Vérifier si l'utilisateur peut voir les salaires
     */
    public function canViewSalaires(): bool
    {
        return $this->hasPermission('view_salaires');
    }
}
