<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\NavbarSection;
use App\DataTables\RoleDataTable;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index(RoleDataTable $dataTable)
    {
        return $dataTable->render('pages.roles.index');
    }

    public function store(Request $request)
    {
        $role = new Role();
        $role->name = $request->name;
        $role->guard_name = 'web';
        $role->save();

        return response()->json([
            "status" => 'success',
            "message" => 'Role Created Successfully',
            "redirectTo" => '/roles'
        ]);
    }

    public function edit($id)
    {
        $role = Role::find($id);

        return view('pages.roles.edit', compact('role'));
    }

    public function delete($id)
    {
        $role = Role::find($id);
        $role->delete();

        return response()->json([
            "status" => 'success',
            "message" => 'Role Deleted Successfully',
            "redirectTo" => '/roles'
        ]);
    }

    public function addPermissionToRole($roleId)
    {
       $role = Role::findOrFail($roleId);
       $permissions = Permission::get();
       $departments = NavbarSection::get();
       $rolePermissions = DB::table('role_has_permissions')
       ->where('role_id', $roleId)
       ->pluck('permission_id')->all();

       return view('pages.roles.addPermission', compact('role', 'permissions', 'rolePermissions', 'departments'));
    }

    public function givePermissionToRole(Request $request, $roleId)
    {

        $role = Role::findOrFail($roleId);
        $permissions = Permission::whereIn('name', $request->permission)->where('guard_name', 'web')->get();
        $role->syncPermissions($permissions);

        return redirect()->back()->with('success', 'Permissions added');
    }
  
}