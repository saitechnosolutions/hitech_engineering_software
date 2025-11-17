<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use Illuminate\Http\Request;
use App\Models\PaymentDetails;
use Illuminate\Support\Facades\Auth;
use App\DataTables\PaymentEntryDataTable;

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
            "message" => 'Payment amount cannot exceed Collectable Amount'
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

        return response()->json([
            "status" => 'success',
            "message" => 'Payment Saved Successfully'
        ]);
    }
}
