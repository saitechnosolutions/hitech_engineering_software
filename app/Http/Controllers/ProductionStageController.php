<?php

namespace App\Http\Controllers;

use App\DataTables\ProductionStageDataTable;
use App\Models\ProductionStage;
use Illuminate\Http\Request;

class ProductionStageController extends Controller
{
    public function index(ProductionStageDataTable $dataTable)
    {
        return $dataTable->render('pages.production_stages.index');
    }

    public function store(Request $request)
    {
        $productionStage = new ProductionStage();
        $productionStage->production_team_id = $request->process_team_id;
        $productionStage->stage_name = $request->stage_name;
        $productionStage->save();

        return response()->json([
            "status" => 'success',
            "message" => 'Production Stage Successfully',
            "redirectTo" => '/production-stages'
        ]);
    }
}
