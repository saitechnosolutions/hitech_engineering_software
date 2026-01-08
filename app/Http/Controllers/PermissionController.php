<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use App\DataTables\PermissionDataTable;

class PermissionController extends Controller
{
    public function index(PermissionDataTable $dataTable)
    {
        return $dataTable->render('pages.permissions.index');
    }

    public function store(Request $request)
    {
        $permission = new Permission();
        $permission->name = $request->name;
        $permission->navbar_id = $request->navbar_section;
        $permission->guard_name = 'web';
        $permission->save();

        return response()->json([
            "status" => 'success',
            "message" => 'Permission Created Successfully',
            "redirectTo" => '/permissions'
        ]);
    }

    public function edit($id)
    {
        $permission = Permission::find($id);

        return view('pages.permissions.edit', compact('permission'));
    }

    public function update(Request $request, $id)
    {
        $permission = Permission::find($id);

        $permission->update([
            "name" => $request->name,
            "navbar_id" => $request->navbar_section,
        ]);

        return response()->json([
            "status" => 'success',
            "message" => 'Permission Updated Successfully',
            "redirectTo" => '/permissions'
        ]);
    }

    public function delete($id)
    {
        $permission = Permission::findOrFail($id);

        $permission->delete();

        return response()->json([
            "status" => 'success',
            "message" => 'Permission Deleted Successfully',
            "redirectTo" => '/permissions'
        ]);
    }
}
