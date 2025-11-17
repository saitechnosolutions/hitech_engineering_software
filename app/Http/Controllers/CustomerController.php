<?php

namespace App\Http\Controllers;

use App\DataTables\CustomerDataTable;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(CustomerDataTable $dataTable)
    {
        return $dataTable->render('pages.customers.index');
    }

    public function store(Request $request)
    {
        $customer = new Customer();
        $customer->customer_name = $request->customer;
        $customer->email = $request->email;
        $customer->mobile_number = $request->mobile_number;
        $customer->gst_number = $request->gst_number;
        $customer->pincode = $request->pincode;
        $customer->address = $request->address;
        $customer->state = $request->state;
        $customer->wholesale_price = $request->wholesale_price;
        $customer->discount = $request->discount;
        $customer->state_id = $request->state_id;
        $customer->customer_type = $request->customer_type;
        $customer->save();

        return response()->json([
            "status" => 'success',
            "message" => 'Customer Created Successfully',
            "redirectTo" => '/customers'
        ]);
    }

    public function getCustomerDetails($customerId)
    {
        $customer = Customer::find($customerId);

        return $customer;
    }

}