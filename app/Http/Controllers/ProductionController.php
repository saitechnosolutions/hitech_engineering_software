<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\BOMParts;
use App\Models\Quotation;
use Illuminate\Http\Request;
use App\Models\InvoiceRequest;
use App\Models\QuotationBatch;
use App\Models\DeliveryChallan;
use App\Models\ProductionHistory;
use App\Models\QuotationProducts;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\Models\InvoiceRequestProducts;
use App\Models\QuotationProductionStages;
use App\Models\StockInwardOutwardDetails;

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

     $roleId = Auth::user()->roles->first()->id;
        $role = Role::find($roleId);
        $roleName = $role->name;

    foreach ($request->received_qty as $bomId => $qty) {

        if ($qty === null || $qty === '' || $qty <= 0) {
        continue;
    }

            $quotationProduct = QuotationProducts::where('quotation_id', $request->quotationid)->where('product_id', $request->product_id)->first();



  $productionType = $request->productionType;


if ($productionType == 'bom') {

     $bomParts = BOMParts::find($bomId);

    $requiredQty = $bomParts->bom_qty * $quotationProduct->quantity;

    // Already Completed Qty for THIS bom
    $completedQty = ProductionHistory::where('bom_id', $bomId)
        ->where('quotation_id', $request->quotationid)
        ->where('product_id', $request->product_id)
        ->where('production_type', 'bom')
        ->sum('completed_qty');

    $remainingQty = $requiredQty - $completedQty;

    if ($qty > $remainingQty) {
        return response()->json([
            'status' => 'error',
            'message' => "BOM ({$bomParts->bom_name}) remaining qty is only {$remainingQty},
                          but you entered {$qty}."
        ], 400);
    }

      $teamId = Auth::user()->team_id;
    $newCompletedQty = $completedQty + $qty;
    $status = ($newCompletedQty == $requiredQty)
    ? 'completed'
    : 'pending';

       $quotationProductionStage = QuotationProductionStages::where('quotation_id', $request->quotationid) ->where('product_id', $request->product_id) ->where('bom_id', $bomId) ->where('team_id', $teamId)
       ->update(
        [
            "completed_quantity" => $completedQty + $qty,
            "production_status" => $status
         ]);
       $quotationProductionStage = QuotationProductionStages::where('quotation_id', $request->quotationid) ->where('product_id', $request->product_id) ->where('bom_id', $bomId) ->where('stage', "stage_2")
       ->update(
        [
            "completed_quantity" => $completedQty + $qty,
            "production_status" => $status
        ]);

} elseif ($productionType == 'product') {



    $query = ProductionHistory::where('quotation_id', $request->quotationid)
    ->where('product_id', $request->product_id)
    ->where('production_type', 'product');

if ($request->team == 'packing') {
    $query->where('team_name', 'product');
}

$productCompletedQty = $query->sum('completed_qty');


    $productRemainingQty = $quotationProduct->quantity - $productCompletedQty;


    $quotationBatch = QuotationBatch::whereJsonContains(
    'quotation_ids',
    (int) $request->quotationid
)->first();

        $existProduct = StockInwardOutwardDetails::where('product_id', $request->product_id)->where('quotation_id', $request->quotationid)->where('quotation_batch_id', $quotationBatch->id)->exists();

        if(!$existProduct)
        {
        if($roleName == 'Team Leader - Packing Team')
        {

                $stockInwardAndOutwardDetails = new StockInwardOutwardDetails();
                $stockInwardAndOutwardDetails->product_id = $request->product_id;
                $stockInwardAndOutwardDetails->inward_qty = $quotationProduct->quantity;
                $stockInwardAndOutwardDetails->inward_date = date("Y-m-d");
                $stockInwardAndOutwardDetails->quotation_id = $request->quotationid;
                $stockInwardAndOutwardDetails->quotation_batch_id = $quotationBatch->id;
                $stockInwardAndOutwardDetails->stock_status = 'stock_in';
                $stockInwardAndOutwardDetails->save();

        }


        }


    // if ($qty > $productRemainingQty) {
    //     return response()->json([
    //         'status' => 'error',
    //         'message' => "Product remaining qty is only {$productRemainingQty},
    //                       but you entered {$qty}."
    //     ], 400);
    // }
}


    // Save new record
    $history = new ProductionHistory();
    $history->bom_id = $bomId;
    $history->product_id = $request->product_id;
    $history->quotation_id = $request->quotationid;
    $history->entry_date = date("Y-m-d");
    $history->completed_qty = $qty;
    $history->stage = $qty;
    $history->production_type = $request->productionType;
    $history->team_name = $request->team;
    $history->save();

    // if($productionType == 'product')
    // {

    //     $lastNumber = DB::table('delivery_challans')->max('id');
    //     $nextNumber = $lastNumber + 1;
    //     $formattedNumber = str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    //      $financialYear = getFinancialYear();
    //     $deliveryChallanId = "HT-DC-{$financialYear}/{$formattedNumber}";

    //     $deliveryChallan = new DeliveryChallan();
    //     $deliveryChallan->delivery_challan_id = $deliveryChallanId;
    //     $deliveryChallan->delivery_challan_date = date("Y-m-d");
    //     $deliveryChallan->save();
    // }

    // $history->update([
    //     "delivery_challan_id" => $deliveryChallan->id,
    //     "delivery_challan_status" => 'created',
    // ]);

}


    return response()->json([
        "status" => 'success',
        "message" => 'Production Updated',
        "redirectTo" => '/dashboard'
    ]);
}

public function getBomDetails($productId, $datatype)
{

    $teamId = Auth::user()->team_id;



    $query = BOMParts::where('product_id', $productId)
    ->whereHas('processTeam', function ($q) use ($teamId, $productId, $datatype) {
        $q->where('team_id', $teamId)
          ->where('product_id', $productId);

        if ($datatype === 'bom') {
            $q->whereIn('stage', ['stage_1', 'stage_2']);
        }
    })
    ->with(['processTeam' => function ($q) use ($teamId, $productId, $datatype) {
        $q->where('team_id', $teamId)
          ->where('product_id', $productId);

        if ($datatype === 'bom') {
            $q->whereIn('stage', ['stage_1', 'stage_2']);
        }
    }]);


if ($datatype === 'product') {
    $query->groupBy('bom_category');
}

    $productDetails = $query->get();


    $productName = Product::find($productId);


    return response()->json([
        "status" => 'success',
        "productName" => $productName?->product_name,
        "data" => $productDetails,
        "dataType" => $datatype
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
    // ->where('stage', $request->stage)
    ->get();

foreach ($productionUpdate as $item) {
    $item->update([
        "production_status" => 'completed',
        "status" => 'completed'
    ]);
}


    return response()->json([
        "status" => 'success',
        "message" => 'Production Status Updated'
    ]);
}

    public function invoiceRequest($invoiceId)
    {
        $quotationDetails = Quotation::with('quotationProducts', 'invoiceRequestProducts')->find($invoiceId);
        $invoiceRequestCheck = InvoiceRequest::where('quotation_id', $invoiceId)->exists();

        return view('pages.quotations.invoice_request', compact('quotationDetails', 'invoiceId', 'invoiceRequestCheck'));
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
            $invoiceProducts->quotation_id = $request->invoice_id;
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

        $multipleImages = $request->upload_documents;

        $invoiceImageUrl = [];
        if($multipleImages)
        {
            foreach($multipleImages as $image)
            {
                    $uploadImage = $image;
                    $originalFilename = time() . "-" . str_replace(' ', '_', $uploadImage->getClientOriginalName());
                    $destinationPath = 'invoice_documents/';
                    $uploadImage->move($destinationPath, $originalFilename);
                    $invoiceImageUrl[] = '/task_images/' . $originalFilename;
            }
        }
        else
            {
                $invoiceImageUrl = null;
            }

        $invoiceRequest = InvoiceRequest::find($request->invoiceid);
        $invoiceRequest->update([
            "status" => 'completed',
            "upload_documents" => $invoiceImageUrl
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

    public function allocateDispatchEmployee(Request $request)
    {

        $quotationId = $request->dispatch_quotationid;
        $quotation = Quotation::find($quotationId);

        $quotation->update([
            "dispatch_team_id" => $request->employee_id
        ]);

        return response()->json([
            "status" => 'success',
            "message" => 'Dispatch Employee Allocated',
            "redirectTo" => '/dashboard'
        ]);
    }

    public function barcodePrint($productId)
    {
        $products = Product::find($productId);

        return view('pages.products.barcode-print', compact('products'));
    }

    public function getQuotationDetails($quotationId)
    {
        $quotationDetails = Quotation::with('quotationProducts', 'quotationProducts.product')->find($quotationId);

        return response()->json([
            "status" => 'success',
            "message" => 'Quotation Details',
            "data" => $quotationDetails
        ]);
    }

   public function partialDispatch(Request $request)
{

    foreach ($request->product_id as $index => $productId) {


        $quotationProduct = QuotationProducts::where('quotation_id', $request->quotation_id)
            ->where('product_id', $productId)
            ->first();

        if(!$quotationProduct){
            continue;
        }

        $quotationProduct->update([
            "partial_qty" => $quotationProduct->partial_qty + $request->qty[$index] ?? 0
        ]);

        $quotationBatch = QuotationBatch::whereJsonContains(
    'quotation_ids',
    (int) $request->quotation_id
)->first();


        $stockInwardAndOutwardDetails = new StockInwardOutwardDetails();
        $stockInwardAndOutwardDetails->product_id = $productId;
        $stockInwardAndOutwardDetails->outward_qty = $request->qty[$index];
        $stockInwardAndOutwardDetails->outward_date = date("Y-m-d");
        $stockInwardAndOutwardDetails->quotation_id = $request->quotation_id;
        $stockInwardAndOutwardDetails->quotation_batch_id = $quotationBatch->id;
        $stockInwardAndOutwardDetails->stock_status = 'stock_out';
        $stockInwardAndOutwardDetails->save();
    }

    $quotation = Quotation::find($request->quotation_id);
    $quotation->update([
        "production_status" => 'partialy_dispatched'
    ]);

    return response()->json([
        "status" => "success",
        "message" => "Partial dispatch updated"
    ]);
}

}