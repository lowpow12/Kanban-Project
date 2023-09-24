<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Response;
use App\Models\Role;
use App\Models\Permission;
use App\Models\RolePermission;

class RoleController extends Controller
{
    public function index()
    {   Gate::authorize('viewUsersAndRole', Role::class);
        $pageTitle = 'Role Lists';
        $roles = Role::all();

        return response()->json([
            'message' => 'Roles List',
            'data' => $roles
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'permissionIds' => ['required'],
        ]);

        Gate::authorize('createNewRoles', Role::class);

        DB::beginTransaction();
        try {
            $role = Role::create([
                'name' => $request->name,
            ]);

            $role->permissions()->sync($request->permissionIds);

            DB::commit();

            return response()->json([
                'message' => 'Role has been created',
                'data' => $role
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function update(Request $request, $id)//
    {
        $role = Role::findOrFail($id);
        Gate::authorize('updateAnyRoles', Role::class);

        DB::beginTransaction();
        try {
            $role->update([
                'name' => $request->name,
            ]);

            $role->permissions()->sync($request->permissionIds);

            DB::commit();

            return response()->json([
                'message' => 'Role has been updated',
                'data' => $role
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function destroy($id)//
    {
        $role = Role::findOrFail($id);
        Gate::authorize('deleteAnyRoles', Role::class);
        $role->delete();
        return response()->json([
            'message' => 'Role has been deleted',
        ], Response::HTTP_OK);
    }
}
