<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DeliveryChallan;
use Barryvdh\DomPDF\Facade\Pdf;


use Illuminate\Support\Facades\DB;
use App\DataTables\DeliveryChallanDataTable;
use App\Models\ProductionHistory;

class DeliveryChallanController extends Controller
{
    public function index(DeliveryChallanDataTable $dataTable)
{
    return $dataTable->render('pages.delivery_challan.index');
}


    public function downloadDeliveryChallan($id)
    {

        $deliveryChallanDetails = DeliveryChallan::with(['productionHistorys', 'productionHistorys.quotation'])->find($id);

        $pdf = Pdf::loadView('pages.delivery_challan.download_delivery_challan', [
            'deliveryChallanDetails' => $deliveryChallanDetails
        ])->setPaper('A5', 'landscape');

        return $pdf->stream('delivery_challan_' . date('Y-m-d_H-i-s') . '.pdf');
    }

    public function generateDc(Request $request)
    {

            $lastNumber = DB::table('delivery_challans')->max('id');
            $nextNumber = $lastNumber + 1;
            $formattedNumber = str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
             $financialYear = getFinancialYear();
            $deliveryChallanId = "HT-DC-{$financialYear}/{$formattedNumber}";

            $deliveryChallan = new DeliveryChallan();
            $deliveryChallan->delivery_challan_id = $deliveryChallanId;
            $deliveryChallan->delivery_challan_date = date("Y-m-d");
            $deliveryChallan->save();

            $productionHistorys = ProductionHistory::where('team_name', $request->teamName)->where('delivery_challan_status', 'pending')->get();

            foreach($productionHistorys as $history)
            {
                $history->update([
                    "delivery_challan_id" => $deliveryChallan->id,
                    "delivery_challan_status" => 'created',
                ]);
            }

        return response()->json([
            "status" => 'success',
            "message" => 'Delivery Challan Created Successfully',
        ]);
    }

    public function updateChallan(Request $request)
    {

        $deliveryChallan = DeliveryChallan::find($request->challanId);
        $deliveryChallan->update([
            "status" => 'completed'
        ]);

        return response()->json([
            "status" => 'success',
            "message" => 'Delivery Challan Updated Successfully',
            "redirectTo" => '/delivery-challan'
        ]);
    }
}
