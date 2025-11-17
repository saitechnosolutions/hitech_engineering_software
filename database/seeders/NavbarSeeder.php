<?php

namespace Database\Seeders;

use App\Models\NavbarSection;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NavbarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            "Dashboard",
            "Masters",
            "Category",
            "Products",
            "Customers",
            "Employees",
            "Teams",
            "Authentication",
            "Roles",
            "Users",
            "Permissions",
            "Production",
            "Quotations",
            "Tasks",
            "Reports"
        ];

        foreach($datas as $data)
        {
            $navbar = new NavbarSection();
            $navbar->navbar_section = $data;
            $navbar->save();
        }


    }
}
