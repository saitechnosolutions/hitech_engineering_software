<?php

namespace App\Http\Controllers;

use Storage;
use App\Models\Product;
use App\Models\BOMParts;
use App\Models\Quotation;
use Illuminate\Bus\Batch;
use Illuminate\Http\Request;
use App\Models\QuotationBatch;
use App\Models\BomProcessTeams;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\QuotationProducts;
use Illuminate\Support\Facades\DB;
use App\DataTables\QuotationDataTable;
use App\Models\QuotationProductionStages;


class QuotationController extends Controller
{
    public function index(QuotationDataTable $dataTable)
    {
        $batches = QuotationBatch::orderby('id', 'desc')->get();
        $productionMovedQuotations = Quotation::where('production_status', 'production_moved')->orderBy('id', 'desc')->get();
        $productionOngoingQuotations = Quotation::where('production_status', 'ongoing')->orderBy('id', 'desc')->get();
        $productionPendingQuotations = Quotation::where('production_status', 'not_moved')->orderBy('id', 'desc')->get();
        $productionCompletedQuotations = Quotation::where('production_status', 'completed')->orderBy('id', 'desc')->get();
        $totalQuotationsCount = Quotation::count();

        return $dataTable->render('pages.quotations.index', [
            "quotationBatches" => $batches,
            "productionMovedQuotations" => $productionMovedQuotations,
            "productionOngoingQuotations" => $productionOngoingQuotations,
            "productionPendingQuotations" => $productionPendingQuotations,
            "productionCompletedQuotations" => $productionCompletedQuotations,
            "totalQuotationsCount" => $totalQuotationsCount
        ]);
    }

    public function create()
    {
        $financialYear = getFinancialYear();

        $lastNumber = DB::table('quotations')->max('id');
        $nextNumber = $lastNumber + 1;
        $formattedNumber = str_pad($nextNumber, 6, '0', STR_PAD_LEFT);

        $quotationId = "HT-{$financialYear}/{$formattedNumber}";

        return view('pages.quotations.create', compact('quotationId'));
    }

    public function quotationProducts($productId)
    {
        $product = Product::find($productId);

        return $product;
    }

    public function store(Request $request)
    {

        $quotation = new Quotation();
        $quotation->quotation_no = $request->quotation_no;
        $quotation->quotation_date = $request->quotation_date;
        $quotation->mode_terms_of_payment = $request->mode_terms_of_payment;
        $quotation->buyer_reference_order_no = $request->buyer_reference_order_no;
        $quotation->other_references = $request->other_references;
        $quotation->dispatch_through = $request->dispatch_through;
        $quotation->destination = $request->destination;
        $quotation->terms_of_delivery = $request->terms_of_delivery;
        $quotation->customer_id = $request->customer_id;
        $quotation->quotation_type = $request->quotation_type;
        $quotation->customer_type = $request->customer_type;
        $quotation->total_actual_amount = $request->total_amount;
        $quotation->total_collectable_amount = $request->collection_amount;
        $quotation->cgst_percentage = $request->cgst_percentage;
        $quotation->cgst_value = $request->cgst_amount;
        $quotation->sgst_percentage = $request->sgst_percentage;
        $quotation->sgst_value = $request->sgst_amount;
        $quotation->igst_percentage = $request->igst_percentage;
        $quotation->igst_value = $request->igst_amount;
        $quotation->save();


        foreach($request->product_id as $index => $productId)
        {
            $quotationProducts = new QuotationProducts();
            $quotationProducts->quotation_id = $quotation->id;
            $quotationProducts->product_id = $productId;
            $quotationProducts->quantity = $request->quantity[$index];
            $quotationProducts->rate = $request->rate[$index];
            $quotationProducts->wholesale_price = $request->wholesale_price[$index];
            $quotationProducts->uom = $request->per[$index];
            $quotationProducts->discount_percentage = $request->disc_percentage[$index];
            $quotationProducts->discount_amount = $request->rate[$index] / 100 * $request->disc_percentage[$index];
            $quotationProducts->total_amount = $request->amount[$index];
            $quotationProducts->save();
        }

        return response()->json([
            "status" => 'success',
            "message" => 'Quotation Created Successfully',
            "redirectTo" => '/quotations'
        ]);
    }

    public function quotationFormat($id)
    {
        $quotation = Quotation::with('quotationProducts', 'customer')->find($id);

         $pdf = Pdf::loadView('pages.quotations.quotation_format', [
            "quotation" => $quotation
         ])->setPaper('a4', 'portrait');

        return $pdf->stream('quotation.pdf');
    }

    public function storeQuotationBatch(Request $request)
    {

        $quotation_ids = array_map('intval', $request->quotation_ids);
        $quotationBatch = new QuotationBatch();
        $quotationBatch->batch_date = $request->batch_date;
        $quotationBatch->priority = $request->priority;
        $quotationBatch->quotation_ids = $quotation_ids;
        $quotationBatch->quotation_count = count($quotation_ids);;
        $quotationBatch->batch_status = 'pending';
        $quotationBatch->save();

        foreach($quotation_ids as $quotationId)
        {
            $quotation = Quotation::find($quotationId);
            $quotation->update([
                "is_production_moved" => 'Yes',
                "batch_date" => $request->batch_date,
                "priority" => $request->priority,
                "production_status" => 'not_moved',
            ]);
        }

        return response()->json([
            "status" => 'success',
            "message" => 'Batch Created Successfully',
            "redirectTo" => '/quotations'
        ]);

    }

    // public function moveToProduction(Request $request)
    // {

    //     $batchId = $request->id;

    //     $batch = QuotationBatch::find($batchId);
    //     $batch->update([
    //         "batch_status" => 'processing'
    //     ]);

    //     $quotation = Quotation::whereIn('id', $batch->quotation_ids)->update([
    //         "is_production_moved" => 'Yes',
    //         "batch_date" => $batch->batch_date,
    //         "priority" => $batch->priority,
    //         "production_status" => 'production_moved'
    //     ]);

    //     foreach($batch->quotation_ids as $quotationId)
    //     {
    //         $quotationDetails = Quotation::with('quotationProducts')->find($quotationId);

    //         foreach($quotationDetails->quotationProducts as $quotationDetail)
    //         {

    //             $bomProceses = BomProcessTeams::where('product_id', $quotationDetail->product_id)->get();

    //             foreach($bomProceses as $bomProces)
    //             {

    //                 $bomParts = BOMParts::find($bomProces->bom_id);

    //                 $productionStage = new QuotationProductionStages();
    //                 $productionStage->quotation_id = $quotationId;
    //                 $productionStage->product_id = $bomProces->product_id;
    //                 $productionStage->bom_id = $bomProces->bom_id;
    //                 $productionStage->team_id = $bomProces->team_id;
    //                 $productionStage->stage = $bomProces->stage;
    //                 $productionStage->production_status = 'pending';
    //                 $productionStage->product_quantity = $quotationDetail->quantity;
    //                 $productionStage->bom_required_quantity = (int) $bomParts->bom_qty * $quotationDetail->quantity;
    //                 $productionStage->save();


    //                 $quotationDetail->update([
    //                     "available_stock" => 1,
    //                     "production_stock" => 1,
    //                 ]);
    //             }

    //         }

    //     }


    //     return response()->json([
    //         "status" => 'success',
    //         "message" => 'Batch Moved to Production'
    //     ]);
    // }

   public function moveToProduction(Request $request)
{


        $batchId = $request->batch_id;

        $batch = QuotationBatch::find($batchId);
        $batch->update([
            "batch_status" => 'processing'
        ]);

        $quotation = Quotation::whereIn('id', $batch->quotation_ids)->update([
            "is_production_moved" => 'Yes',
            "batch_date" => $batch->batch_date,
            "priority" => $batch->priority,
            "production_status" => 'production_moved'
        ]);


         foreach($batch->quotation_ids as $quotationId)
        {
            $quotationDetails = Quotation::with('quotationProducts')->find($quotationId);

            foreach($quotationDetails->quotationProducts as $quotationDetail)
            {

                 $productId = $quotationDetail->product_id;

                    // Check if this product exists in request
                    $index = array_search($productId, $request->product_id);

                    if($index === false){
                        continue;
                    }

                    $availableStock = $request->stock_entry[$index];
                    $productionStock = $request->production_stock_entry[$index];

                $bomProceses = BomProcessTeams::where('product_id', $quotationDetail->product_id)->get();

                foreach($bomProceses as $bomProces)
                {

                    $bomParts = BOMParts::find($bomProces->bom_id);

                    $productionStage = new QuotationProductionStages();
                    $productionStage->quotation_id = $quotationId;
                    $productionStage->product_id = $bomProces->product_id;
                    $productionStage->bom_id = $bomProces->bom_id;
                    $productionStage->team_id = $bomProces->team_id;
                    $productionStage->stage = $bomProces->stage;
                    $productionStage->production_status = 'pending';
                    $productionStage->product_quantity = $quotationDetail->quantity;
                    $productionStage->bom_required_quantity = (int) $bomParts->bom_qty * $quotationDetail->quantity;
                    $productionStage->save();

                }

                  $quotationDetail->update([
                    "available_stock" => $availableStock,
                    "production_stock" => $productionStock
                ]);

                $productStock = Product::find($quotationDetail->product_id);

                $productStock->update([
                    "stock_qty" => $productStock->stock_qty - $availableStock
                ]);

            }

        }

          return response()->json([
            "status" => 'success',
            "message" => 'Batch Moved to Production',
            "redirectTo" => '/quotations'
        ]);

}


    public function capturePhoto(Request $request)
{
    $imageData = $request->image_data;
    $quotationId = $request->quotation_id;

    if ($imageData) {

        // Remove header
        $image = str_replace('data:image/png;base64,', '', $imageData);
        $image = str_replace(' ', '+', $image);
        $imageName = time() . '_' . uniqid() . '.png';

        // Ensure folder exists
        if (!file_exists(public_path('dispatch_images'))) {
            mkdir(public_path('dispatch_images'), 0777, true);
        }

        // Save image in public
        $publicPath = public_path('dispatch_images/' . $imageName);
        file_put_contents($publicPath, base64_decode($image));

        // Update DB
        Quotation::where('id', $quotationId)->update([
            'dispatch_image' => 'dispatch_images/' . $imageName
        ]);

        // RETURN BACK WITH SUCCESS FLASH MESSAGE
        return back()->with('success', 'Photo captured successfully!');

        // return response()->json([
        //     'status' => 'success',
        //     'message' => 'Photo Captured Successfully',
        //     'redirectTo' => '/dashboard'
        // ]);
    }

    return redirect()->back()->with('error', 'No image captured.');
}

public function getBatchDetails($batchId)
{
    $batchDetails = QuotationBatch::find($batchId);
    $quotations = Quotation::with('quotationProducts')->whereIn('id', $batchDetails->quotation_ids)->get();

    return view('pages.quotations.ready-to-production', compact('quotations', 'batchId'));
}

public function edit($id)
{
    $quotation = Quotation::with('quotationProducts')->find($id);

    return view('pages.quotations.edit', compact('quotation'));
}

}
