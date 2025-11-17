<?php

namespace App\Http\Controllers;

use App\Models\ProcessTeam;
use Illuminate\Http\Request;
use App\DataTables\ProcessTeamDataTable;

class ProcessTeamController extends Controller
{
    public function index(ProcessTeamDataTable $dataTable)
    {
        return $dataTable->render('pages.process_teams.index');
    }

    public function store(Request $request)
    {
        $processTeam = new ProcessTeam();
        $processTeam->team_name = $request->team_name;
        $processTeam->team_slug = strtolower(str_replace(' ', '-', $request->team_name));
        $processTeam->save();

        return response()->json([
            "status" => 'success',
            "message" => 'Team Created Successfully',
            "redirectTo" => '/process-team'
        ]);
    }
}
