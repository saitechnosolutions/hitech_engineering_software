<?php

namespace App\Http\Controllers;

use App\Models\InvoiceRequest;
use App\Models\InvoiceRequestProducts;
use App\Models\Quotation;
use Illuminate\Http\Request;
use App\Models\QuotationBatch;
use App\Models\ProductionHistory;
use App\Models\QuotationProducts;
use Illuminate\Support\Facades\Auth;
use App\Models\QuotationProductionStages;

class ProductionController extends Controller
{
    public function index()
    {

        $quotationBatches = QuotationBatch::orderby('id', 'desc')->get();
        $closedQuotationBatches = QuotationBatch::orderby('id', 'desc')->where('batch_status', 'completed')->get();

        return view('pages.production.index', compact('quotationBatches', 'closedQuotationBatches'));
    }

    public function productionView($id)
    {

        $batch = QuotationBatch::find($id);

        $quotations = Quotation::with('quotationProducts')->whereIn('id', $batch->quotation_ids)->get();

                 return view('pages.production.production_view', compact('quotations', 'batch'));
    }

   public function updateProductionStatus(Request $request)
{
    $quotationProduction = QuotationProductionStages::find($request->production_id);

    $totalQty = $quotationProduction->bom_required_quantity;
    $completedQty = $quotationProduction->completed_quantity;
    $newCompletedQty = $request->completed_quantity;

    $remainingQty = $totalQty - $completedQty;

    if ($newCompletedQty > $remainingQty) {
        return response()->json([
            "status" => 'error',
            "message" => "You can only complete maximum {$remainingQty} quantity."
        ], 400);
    }

    $quotationProduction->completed_quantity = $completedQty + $newCompletedQty;

    if ($quotationProduction->completed_quantity >= $totalQty) {
        $quotationProduction->status = 'completed';
    }

    $quotationProduction->save();

    // Save production history
    $quotationProductionHistory = new ProductionHistory();
    $quotationProductionHistory->quantity_production_id = $quotationProduction->id;
    $quotationProductionHistory->entry_date = $request->date;
    $quotationProductionHistory->completed_qty = $newCompletedQty;
    $quotationProductionHistory->save();

    return response()->json([
        "status" => 'success',
        "message" => 'Production Updated',
        "redirectTo" => '/dashboard'
    ]);
}


public function allocateEmployee(Request $request)
{
    $teamId = $request->team_id;
    $quotationProduct = QuotationProducts::where('quotation_id', $request->production_quotationid)->where('product_id', $request->product_id_employee)->first();
    if($teamId == 1)
    {
        $quotationProduct->update([
            "ms_fabrication_emp_id" => $request->employee_id
        ]);
    }
    else
    {
        $quotationProduct->update([
        "ss_fabrication_emp_id" => $request->employee_id
    ]);
    }

    return response()->json([
        "status" => 'success',
        "message" => 'Employee Allocated Successfully',
        "redirectTo" => '/dashboard'
    ]);

}


public function getProductionDetails($id)
{
    $getProductionHistory = ProductionHistory::with('productionStage', 'productionStage.product', 'productionStage.quotation', 'productionStage.bom')->where('quantity_production_id', $id)->get();

    return $getProductionHistory;
}

public function productionUpdate(Request $request)
{
    $teamId = Auth::user()->team_id;

    $productionUpdate = QuotationProductionStages::with('bom')
    ->where('quotation_id', $request->quotation_id)
    ->where('product_id', $request->product_id)
    ->where('team_id', $teamId)
    ->where('stage', $request->stage)
    ->get();

foreach ($productionUpdate as $item) {
    $item->update([
        "production_status" => 'completed'
    ]);
}


    return response()->json([
        "status" => 'success',
        "message" => 'Production Status Updated'
    ]);
}

    public function invoiceRequest($invoiceId)
    {
        $quotationDetails = Quotation::with('quotationProducts')->find($invoiceId);
        return view('pages.quotations.invoice_request', compact('quotationDetails'));
    }

    public function invoiceRequestSubmit(Request $request)
    {

        $invoiceRequest = new InvoiceRequest();
        $invoiceRequest->quotation_id = $request->invoice_id;
        $invoiceRequest->invoice_request_date = date("Y-m-d");
        $invoiceRequest->status = 'pending';
        $invoiceRequest->save();


        foreach($request->product_id as $index => $productId)
        {
            $invoiceProducts = new InvoiceRequestProducts();
            $invoiceProducts->invoice_request_id = $invoiceRequest->id;
            $invoiceProducts->product_id = $productId;
            $invoiceProducts->quantity = $request->quantity[$index];

            $invoiceProducts->uom = $request->per[$index];
            $invoiceProducts->save();
        }

        return response()->json([
            "status" => 'success',
            "message" => 'Invoice Request Send Successfully',
            "redirectTo" => '/dashboard'
        ]);
    }

    public function invoiceRequestDetails()
    {
        $invoiceRequestPending = InvoiceRequest::where('status','pending')->get();
        $invoiceRequestCompleted = InvoiceRequest::where('status','completed')->get();

        return view('pages.quotations.invoice_request_details', compact('invoiceRequestPending', 'invoiceRequestCompleted'));
    }

    public function invoiceStatusUpdate(Request $request)
    {
        $invoiceRequest = InvoiceRequest::find($request->id);
        $invoiceRequest->update([
            "status" => 'completed'
        ]);

        return response()->json([
            "status" => 'success',
            "message" => 'Invoice Completed',
            "redirectTo" => '/invoice-request-details'
        ]);
    }

    public function invoiceRequestinformation($requestid)
    {
        $invoiceRequest = InvoiceRequest::with('invoiceProducts')->find($requestid);

        return view('pages.quotations.invoice_view', compact('invoiceRequest'));
    }

    public function productComplete(Request $request)
    {
        $productionUpdate = QuotationProducts::where('quotation_id', $request->quotation_id)
        ->where('product_id', $request->product_id)
        ->get();

        foreach($productionUpdate as $product)
        {
            $product->update([
                "production_status" => 'dispatched'
            ]);
        }

        return response()->json([
            "status" => 'success',
            "message" => 'Product Dispatched'
        ]);
    }

    public function activeOrderDetails(Request $request)
    {
        $activeOrderDetails = Quotation::whereIn('production_status', ['production_moved', 'ongoing'])->get();

        return view('pages.quotations.active_order_details', compact('activeOrderDetails'));
    }
}