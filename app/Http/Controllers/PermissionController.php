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
}
