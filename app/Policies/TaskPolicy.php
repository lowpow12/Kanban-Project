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

}
