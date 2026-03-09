<?php

namespace App\Policies;

use App\Models\Employe;
use App\Models\User;

class EmployePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view_employes');
    }

    public function view(User $user, Employe $employe): bool
    {
        return $user->hasPermission('view_employes');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('manage_employes');
    }

    public function update(User $user, Employe $employe): bool
    {
        return $user->hasPermission('manage_employes');
    }

    public function delete(User $user, Employe $employe): bool
    {
        return $user->hasPermission('manage_employes');
    }
}
