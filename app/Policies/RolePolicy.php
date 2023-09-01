<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\Permission;

class RolePolicy extends TaskPolicy
{
    protected function getUserPermissions($user)//
    {
        return $user
            ->role()
            ->with('permissions')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->pluck('name');
    }

    public function before($user)//
    {
    if ($user->role && $user->role->name == 'admin') {
        return true;
    }

    return null;
    }
    

    public function viewAnyRoles($user)//
    {
    $permissions = $this->getUserPermissions($user);

    if ($permissions->contains('view-any-roles')) {
        return true;
    }

    return false;
    }

    public function createNewRoles($user)//
    {
    $permissions = $this->getUserPermissions($user);

    if ($permissions->contains('create-new-roles')) {
        return true;
    }

    return false;
    }

    public function updateAnyRoles($user)//
    {
    $permissions = $this->getUserPermissions($user);

    if ($permissions->contains('update-any-roles')) {
        return true;
    }

    return false;
    }

    public function deleteAnyRoles($user)//
    {
    $permissions = $this->getUserPermissions($user);

    if ($permissions->contains('delete-any-roles')) {
        return true;
    }

    return false;
    }

    public function viewUsersAndRole($user)//
    {
    $permissions = $this->getUserPermissions($user);

    if ($permissions->contains('view-users-and-roles')) {
        return true;
    }

    return false;
    }

    public function manageUserRoles($user)//
    {
    $permissions = $this->getUserPermissions($user);

    if ($permissions->contains('manage-user-roles')) {
        return true;
    }

    return false;
    }
}
