<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\NavbarSection;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $departments = NavbarSection::get();

        foreach ($departments as $department) {
            $permissionDetails = [
                "view-table",
                "delete",
                "create",
                "edit",
                "show"
            ];


            foreach ($permissionDetails as $permissionDetail) {
                $permissions = new Permission();
                $permissions->name = strtolower(str_replace(' ', '-', $department->navbar_section) . "-" . $permissionDetail);
                $permissions->guard_name = 'web';
                $permissions->navbar_id = $department->id;
                $permissions->save();
            }
        }
    }
}
