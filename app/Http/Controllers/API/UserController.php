<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function index()
    {   
        
        $pageTitle = 'Users List';
        $users = User::all();
        return response()->json([
            'message' => 'Roles List',
            'data' => $users
        ]);
    }

    public function updateRole($id, Request $request)
    {
        $user = User::findOrFail($id);
        $user->update([
            'role_id' => $request->role_id,
        ]);

        return response()->json([
            'message' => 'Role has been updated',
            'data' => $user
        ], Response::HTTP_OK);
    }
}
