<?php

namespace App\Policies;

use App\Models\User;

class TaskPolicy
{
    public function update($user, $task)
    {
        return $user->id == $task->user_id;
    }

    public function delete($user, $task)
    {
        return $user->id == $task->user_id;
    }

    public function complete($user, $task)
    {
        return $user->id == $task->user_id;
    }

    public function before($user)//
    {
    if ($user->role && $user->role->name == 'admin') {
        return true;
    }

    return null;
    }
    
    public function performAsTaskOwner($user, $task)//
    {
        return $user->id == $task->user_id;
    }

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

    public function viewAnyTask($user)//
    {
        $permissions = $this->getUserPermissions($user);

        if ($permissions->contains('view-any-task')) {
            return true;
        }
        
        return false;
    }


    public function updateAnyTask($user)//
    {
    $permissions = $this->getUserPermissions($user);

    if ($permissions->contains('update-any-tasks')) {
        return true;
    }

    return false;
    }

    public function deleteAnyTask($user)//
    {
    $permissions = $this->getUserPermissions($user);

    if ($permissions->contains('delete-any-tasks')) {
        return true;
    }

    return false;
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

    if ($permissions->contains('view-users-and-role')) {
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
