@extends('layouts.app')
@section('main-content')
<x-breadcrumb
    :items="[
        ['label' => 'Hi-tech Engineering', 'url' => '#'],
        ['label' => 'Pages', 'url' => '#'],
        ['label' => 'Quotations']
    ]"
    title="" />

    @if($invoiceRequestCheck)
        <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="alert alert-danger mb-0 mt-2" role="alert">
                    Invoice Request Already Created
                </div>
            </div>
        </div>
    </div>
    @endif


    <div class="container-fluid">
        <div class="row mt-1">

                    <form id="createFormSubmit" class="form-horizontal m-t-10" method="POST" action="{{ route('invoice.request') }}">
                    @csrf

                <div class="col-lg-12">
                    <div class="card">
                    <div class="card-header">
                        <h6>Create Quotations</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-3">
                                 <div class="form-group">
                                        <label>Quotation No</label>
                                        <div>
                                            <input type="text" class="form-control" required
                                                    parsley-type="text" readonly name="quotation_no" value="{{ $quotationDetails->quotation_no }}" placeholder="" />
                                                    <input type="hidden" name="invoice_id" value="{{ $quotationDetails->id }}">
                                        </div>
                                    </div>
                            </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                        <label>Quotation Date</label>
                                        <div>
                                            <input type="date" class="form-control" required
                                                    parsley-type="text" name="quotation_date" value="{{ $quotationDetails->quotation_date }}" readonly placeholder=""/>
                                        </div>
                                    </div>
                            </div>
                            <div class="col-lg-3">
                                 <div class="form-group">
                                        <label>Customer</label>
                                        <div>
                                            <select class="form-control js-example-basic-single" name="customer_id" id="customer_id" disabled>
                                                <option value="">-- Choose Customer --</option>
                                                @if($customers = App\Models\Customer::get())
                                                    @foreach ($customers as $customer)
                                                        <option value="{{ $customer->id }}" @if($quotationDetails->customer_id == $customer->id) selected @endif>{{ $customer->customer_name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                            </div>
                            <div class="col-lg-3">
                                 <div class="form-group">
                                        <label>Mode Terms of Payment </label>
                                        <div>
                                            <input type="text" class="form-control" required
                                                    parsley-type="text" name="mode_terms_of_payment" value="{{ $quotationDetails->mode_terms_of_payment }}" readonly placeholder=""/>
                                        </div>
                                    </div>
                            </div>
                             <div class="col-lg-3">
                                 <div class="form-group">
                                        <label>Buyer Reference Order No </label>
                                        <div>
                                            <input type="text" class="form-control" required
                                                    parsley-type="text" name="buyer_reference_order_no" value="{{ $quotationDetails->buyer_reference_order_no }}" readonly placeholder=""/>
                                        </div>
                                    </div>
                            </div>
                            <div class="col-lg-3">
                                 <div class="form-group">
                                        <label>Other References </label>
                                        <div>
                                            <input type="text" class="form-control" required
                                                    parsley-type="text" name="other_references" value="{{ $quotationDetails->other_references }}" readonly placeholder=""/>
                                        </div>
                                    </div>
                            </div>
                            <div class="col-lg-3">
                                 <div class="form-group">
                                        <label>Dispatch Through </label>
                                        <div>
                                            <input type="text" class="form-control" required
                                                    parsley-type="text" name="dispatch_through" value="{{ $quotationDetails->dispatch_through }}" readonly placeholder=""/>
                                        </div>
                                    </div>
                            </div>
                             <div class="col-lg-3">
                                 <div class="form-group">
                                        <label>Destination </label>
                                        <div>
                                            <input type="text" class="form-control" required
                                                    parsley-type="text" name="destination" value="{{ $quotationDetails->destination }}" readonly placeholder=""/>
                                        </div>
                                    </div>
                            </div>
                            <div class="col-lg-6">
                                 <div class="form-group">
                                        <label>Terms Of Delivery </label>
                                        <div>
                                            <textarea readonly class="form-control" name="terms_of_delivery">{{ $quotationDetails->terms_of_delivery }}</textarea>

                                        </div>
                                    </div>
                            </div>
                             <div class="col-lg-3">
                                 <div class="form-group">
                                        <label>Customer Type </label>
                                        <div>
                                            <select class="form-control" name="customer_type" id="customer_type" disabled>
                                                <option value="">-- Choose Option --</option>
                                                <option value="mrp_customer" @if($quotationDetails->customer_type == 'mrp_customer') selected @endif>MRP Customer</option>
                                                <option value="wholesale_customer" @if($quotationDetails->customer_type == 'wholesale_customer') selected @endif>Wholesale Customer</option>
                                            </select>

                                        </div>
                                    </div>
                            </div>

                            <div class="col-lg-3">
                                 <div class="form-group">
                                        <label>Quotation Type </label>
                                        <div>
                                            <select class="form-control" name="quotation_type" id="quotation_type" disabled>
                                                <option value="">-- Choose Option --</option>
                                                <option value="b2b"  @if($quotationDetails->quotation_type == 'b2b') selected @endif>B2B</option>
                                                <option value="retail"  @if($quotationDetails->quotation_type == 'retail') selected @endif>Retail</option>
                                            </select>

                                        </div>
                                    </div>
                            </div>

                            <div class="col-lg-12">
                                <table class="table table-bordered" id="stagesTable">
                                    <thead>
                                        <tr>
                                            <th>Description of goods</th>
                                            <th>HSN/SAC</th>
                                            <th>Part No</th>
                                            <th>Previous Requested Qty</th>
                                            <th>Quantity</th>
                                            {{--  <th>Rate</th>  --}}
                                            {{--  <th>Wholesale Price</th>  --}}
                                            <th>Per</th>
                                            {{--  <th>Disc. %</th>  --}}
                                            {{--  <th>Amount</th>  --}}
                                            {{--  <th><button type="button" class="btn btn-success" id="addRow">+</button></th>  --}}
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @if($quotationDetails->quotationProducts)
                                            @foreach ($quotationDetails->quotationProducts as $quotationProducts)

                                            @php
                                                $previousCreatedProductQty = App\Models\InvoiceRequestProducts::where('quotation_id', $quotationDetails->id)->where('product_id', $quotationProducts->product_id)->sum('quantity');
                                            @endphp

                                        <tr>
                                            <td>

                                                <select class="form-control quotationproduct js-example-basic-single" name="product_id[]" style="width:350px" >
                                                    <option>-- Choose Product --</option>
                                                    @if($products = App\Models\Product::get())
                                                        @foreach ($products as $product)
                                                            <option value="{{ $product->id }}" @if($quotationProducts->product_id == $product->id) selected @endif>{{ $product->product_name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="hsn_no[]" class="form-control hsn_no" value="{{ $quotationProducts->product->hsn_code }}" readonly>
                                            </td>
                                            <td>
                                                <input type="text" name="part_no[]" class="form-control part_no" value="{{ $quotationProducts->product->part_number }}" readonly>
                                            </td>
                                            <td>
                                                <input type="text" name="previous_quantity[]" class="form-control quantity" value="{{ $previousCreatedProductQty ?? 0 }}" readonly>
                                            </td>
                                            <td>
                                                <input type="text" name="quantity[]" class="form-control quantity" value="{{ $quotationProducts->quantity - $previousCreatedProductQty }}" >
                                            </td>
                                            {{--  <td>
                                                <input type="text" name="rate[]" class="form-control rate" readonly value="{{ $quotationProducts->rate }}">
                                            </td>  --}}
                                            {{--  <td>
                                                <input type="text" name="wholesale_price[]" class="form-control wholesale_price" value="{{ $quotationProducts->wholesale_price }}">
                                            </td>  --}}
                                            <td>
                                                <input type="text" name="per[]" class="form-control per" value="{{ $quotationProducts->uom }}">
                                            </td>
                                            {{--  <td>
                                                <input type="text" name="disc_percentage[]" class="form-control disc_percentage" value="{{ $quotationProducts->discount_percentage }}">
                                            </td>  --}}
                                            {{--  <td>
                                                <input type="text" name="amount[]" class="form-control amount" readonly value="{{ $quotationProducts->total_amount }}">
                                            </td>  --}}
                                            {{--  <td>
                                                <button type="button" class="btn btn-danger removeRow">-</button>
                                            </td>  --}}
                                        </tr>
                                            @endforeach
                                        @endif


                                    </tbody>
                                </table>
                            </div>
                            <div class="col-lg-6"></div>
                            <div class="col-lg-6 text-right">
                                {{--  <table class="table table-bordered">
                                    <tr>
                                            <td><b>SUB TOTAL (₹)</b></td>
                                            <td colspan="2"><input type="text" name="sub_total" class="form-control subtotal" readonly></td>
                                        </tr>
                                    <tr>
                                        <th>CGST (%)</th>
                                        <td><input type="text" class="form-control cgst_percentage" name="cgst_percentage"></td>
                                        <td>
                                            <input type="text" class="form-control cgst_amount" name="cgst_amount" readonly>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>SGST (%)</th>
                                        <td><input type="text" class="form-control sgst_percentage" name="sgst_percentage"></td>
                                        <td>
                                            <input type="text" class="form-control sgst_amount" name="sgst_amount" readonly>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>IGST (%)</th>
                                        <td><input type="text" class="form-control igst_percentage" name="igst_percentage"></td>
                                        <td>
                                            <input type="text" class="form-control igst_amount" name="igst_amount" readonly>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>TOTAL AMOUNT (₹)</th>
                                        <td colspan="2"><input type="text" class="form-control total_amount" name="total_amount" readonly value="{{ $quotationDetails->total_actual_amount }}"></td>
                                    </tr>
                                    <tr>
                                        <th>COLLECTION AMOUNT (₹)</th>
                                        <td colspan="2"><input type="text" class="form-control" name="collection_amount"></td>
                                    </tr>

                                </table>  --}}
                                <button type="submit" class="btn btn-danger ">Send Invoice Request</button>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                </form>

            </div>
    </div>

@endsection

@push('scripts')
    <script src="/assets/js/quotation.js"></script>
@endpush
