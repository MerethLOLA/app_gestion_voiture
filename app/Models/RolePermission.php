<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    use HasFactory;

    protected $table = 'role_permissions';

    public $timestamps = false;

    protected $fillable = [
        'role',
        'permission_id',
    ];

    /**
     * Relation : Permission
     */
    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }
}
