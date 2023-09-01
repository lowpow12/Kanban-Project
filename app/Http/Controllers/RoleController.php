<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

use App\Models\Role;
use App\Models\Permission;
use App\Models\RolePermission;


class RoleController extends Controller
{
    public function index()
    {   Gate::authorize('viewUsersAndRole', Role::class);
        $pageTitle = 'Role Lists';
        $roles = Role::all();

        return view('roles.index', [
            'pageTitle' => $pageTitle,
            'roles' => $roles,
        ]);
    }

    public function create()
    {
        $pageTitle = 'Add Role';

        Gate::authorize('createNewRoles', Role::class);
        
        $permissions = Permission::all();
        return view('roles.create', [
            'pageTitle' => $pageTitle,
            'permissions' => $permissions,
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

            return redirect()->route('roles.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function edit($id)//
    {
            $pageTitle = 'Edit Role';
            Gate::authorize('updateAnyRoles', Role::class);
            $permissions = Permission::all();
            $role = Role::findOrFail($id);
            

            return view('roles.edit', ['pageTitle' => $pageTitle, 'role' => $role, 'permissions' => $permissions]);
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

            return redirect()->route('roles.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function delete($id)//
    {
        $pageTitle = 'Delete Role';
        $role = Role::findOrFail($id);
        Gate::authorize('deleteAnyRoles', Role::class);

        

        return view('roles.delete', ['pageTitle' => $pageTitle, 'role' => $role]);
    }

    public function destroy($id)//
    {
        $role = Role::findOrFail($id);
        Gate::authorize('deleteAnyRoles', Role::class);
        $role->delete();
        return redirect()->route('roles.index');
    }
}
