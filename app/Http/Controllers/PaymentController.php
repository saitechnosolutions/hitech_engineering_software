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
        if ($multipleImages) {
            foreach ($multipleImages as $image) {
                $uploadImage = $image;
                $originalFilename = time() . "-" . str_replace(' ', '_', $uploadImage->getClientOriginalName());
                $destinationPath = 'payment_images/';
                $uploadImage->move($destinationPath, $originalFilename);
                $paymentImageUrl[] = '/payment_images/' . $originalFilename;
            }
        } else {
            $paymentImageUrl = null;
        }

        $quotation = Quotation::find($request->quotation_id);
        $collectableAmount = $quotation->total_collectable_amount;

        if ($request->amount > $collectableAmount) {

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

        if ($collectableAmount === $totalReceivedAmount) {
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
        $fromDate    = $request->fromdate;
        $toDate      = $request->todate;
        $companyName = $request->company_name;
        $rm          = $request->rm;

        $payments = PaymentDetails::with(['quotation.customer', 'rm'])

            ->when($quotationId, function ($q) use ($quotationId) {
                $q->where('quotation_id', $quotationId);
            })

            ->when($fromDate && $toDate, function ($q) use ($fromDate, $toDate) {
                $q->whereBetween('payment_date', [$fromDate, $toDate]);
            })

            ->when($companyName, function ($q) use ($companyName) {
                $q->whereHas('quotation.customer', function ($query) use ($companyName) {
                    $query->where('customer_name', $companyName);
                });
            })

            ->when($rm, function ($q) use ($rm) {
                $q->whereHas('rm', function ($query) use ($rm) {
                    $query->where('name', $rm);
                });
            })

            ->orderBy('payment_date', 'desc')
            ->get();

        // 🔹 Flatten data manually (IMPORTANT)
        $data = $payments->map(function ($payment) {
            return [
                'payment_date'  => $payment->payment_date,
                'quotation_no'  => optional($payment->quotation)->quotation_no,
                'customer_name' => optional($payment->quotation?->customer)->customer_name,
                'amount'        => $payment->amount,
                'remarks'       => $payment->remarks,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data'   => $data,
            'total'  => number_format($payments->sum('amount'), 2),
        ]);
    }

    public function edit($id)
    {
        $payment = PaymentDetails::find($id);

        return view('pages.payments.edit', compact('payment'));
    }

    public function update(Request $request, $id)
    {
        $payment = PaymentDetails::find($id);

         $multipleImages = $request->reference_images;

        $paymentImageUrl = [];
        if ($multipleImages) {
            foreach ($multipleImages as $image) {
                $uploadImage = $image;
                $originalFilename = time() . "-" . str_replace(' ', '_', $uploadImage->getClientOriginalName());
                $destinationPath = 'payment_images/';
                $uploadImage->move($destinationPath, $originalFilename);
                $paymentImageUrl[] = '/payment_images/' . $originalFilename;
            }
        } else {
            $paymentImageUrl = $payment->reference_images;
        }

        $payment->update([
            "amount" => $request->amount,
            "payment_date" => $request->payment_date,
            "remarks" => $request->remarks,
            "reference_images" => $paymentImageUrl,
        ]);


        return response()->json([
            "status" => 'success',
            "message" => 'Payment Updated Successfully',
            "redirectTo" => '/payments'
        ]);
    }

    public function delete($id)
    {
        $payment = PaymentDetails::find($id);

        $payment->delete();

        return response()->json([
            "status" => 'success',
            "message" => 'Payment Deleted Successfully',
            "redirectTo" => '/payments'
        ]);

    }
}
