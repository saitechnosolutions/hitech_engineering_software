<?php

namespace App\Http\Controllers;

use App\Models\BOMParts;
use Illuminate\Http\Request;
use App\Models\BomProcessTeams;
use App\DataTables\BomPartsDataTable;

class BomController extends Controller
{
    public function index(BomPartsDataTable $dataTable)
    {
        return $dataTable->render('pages.bom.index');
    }

    public function store(Request $request)
    {


        foreach($request->bom_part_name as $index => $productName)
        {
            $bom = new BOMParts();
            $bom->product_id = $request->product_id;
            $bom->bom_name = $productName;
            $bom->bom_unit = $request->bom_unit[$index];
            $bom->bom_qty = $request->quantity[$index];
            $bom->bom_price = $request->price[$index];
            $bom->save();

            $processTeams = $request->process_team[$index] ?? [];

            if(!is_array($processTeams)) {
                $processTeams = [$processTeams];
            }

            foreach($processTeams as $row => $processTeam)
            {
                $bomProcessTeam = new BomProcessTeams();
                $bomProcessTeam->stage = "stage_".($row + 1);
                $bomProcessTeam->bom_id = $bom->id;
                $bomProcessTeam->product_id = $request->product_id;
                $bomProcessTeam->team_id = $processTeam;
                $bomProcessTeam->save();
            }
        }


        return response()->json([
            "status" => 'success',
            "message" => 'BOM Parts Created Successfully',
            "redirectTo" => '/manage-bom'
        ]);
    }

    public function create()
    {
        return view('pages.bom.create');
    }
}