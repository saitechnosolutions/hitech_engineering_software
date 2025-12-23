<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use Illuminate\Http\Request;
use App\Models\PaymentDetails;
use Illuminate\Support\Facades\Auth;
use App\DataTables\PaymentEntryDataTable;
use App\Http\Resources\PaymentFilterCollection;

class PaymentController extends Controller
{
    public function index(PaymentEntryDataTable $dataTable)
    {
        return $dataTable->render('pages.payments.index');
    }

    public function store(Request $request)
    {
        $multipleImages = $request->reference_images;

        $paymentImageUrl = [];
        if($multipleImages)
        {
            foreach($multipleImages as $image)
            {
                    $uploadImage = $image;
                    $originalFilename = time() . "-" . str_replace(' ', '_', $uploadImage->getClientOriginalName());
                    $destinationPath = 'payment_images/';
                    $uploadImage->move($destinationPath, $originalFilename);
                    $paymentImageUrl[] = '/payment_images/' . $originalFilename;
            }
        }
        else
        {
            $paymentImageUrl = null;
        }

        $quotation = Quotation::find($request->quotation_id);
        $collectableAmount = $quotation->total_collectable_amount;

        if($request->amount > $collectableAmount){

            return response()->json([
            "status" => 'error',
            "message" => 'Payment amount cannot exceed Collectable Amount',

        ]);
        }

        $paymentEntry = new PaymentDetails();
        $paymentEntry->quotation_id = $request->quotation_id;
        $paymentEntry->payment_date = $request->payment_date;
        $paymentEntry->amount = $request->amount;
        $paymentEntry->remarks = $request->remarks;
        $paymentEntry->reference_images = $paymentImageUrl;
        $paymentEntry->entered_by = Auth::user()->id;
        $paymentEntry->save();

        $totalReceivedAmount = $paymentEntry->where('quotation_id', $request->quotation_id)->sum('amount');

        if($collectableAmount === $totalReceivedAmount)
        {
            $quotation->update([
                "payment_status" => 'completed'
            ]);
        }

        return response()->json([
            "status" => 'success',
            "message" => 'Payment Saved Successfully',
            "redirectTo" => '/payments'
        ]);
    }

   public function paymentReportFilter(Request $request)
{
    $quotationId = $request->quotationId;
    $fromDate = $request->fromdate;
    $toDate = $request->todate;

    $payments = PaymentDetails::with('quotation')

        ->when($quotationId, function ($q) use ($quotationId) {
            $q->where('quotation_id', $quotationId);
        })

        ->when($fromDate && $toDate, function ($q) use ($fromDate, $toDate) {
            $q->whereBetween('payment_date', [$fromDate, $toDate]);
        })

        ->orderBy('payment_date', 'desc')
        ->get();

       $paymentDetails = new PaymentFilterCollection($payments); 
       
    return response()->json([
        'status' => 'success',
        'data' => $paymentDetails
    ]);
}


}