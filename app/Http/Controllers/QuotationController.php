<?php

namespace App\Http\Controllers;

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
        $quotation->total_actual_amount = $request->total_amount;
        $quotation->total_collectable_amount = $request->collection_amount;
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
            "message" => 'Batch Created Successfully'
        ]);

    }

    public function moveToProduction(Request $request)
    {
        $batchId = $request->id;

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
                    $productionStage->bom_required_quantity = $bomParts->bom_qty * $quotationDetail->quantity;
                    $productionStage->save();
                }

            }

        }


        return response()->json([
            "status" => 'success',
            "message" => 'Batch Moved to Production'
        ]);
    }


}
