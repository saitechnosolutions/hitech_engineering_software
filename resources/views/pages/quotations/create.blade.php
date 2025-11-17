@extends('layouts.app')
@section('main-content')
<x-breadcrumb
    :items="[
        ['label' => 'Hi-tech Engineering', 'url' => '#'],
        ['label' => 'Pages', 'url' => '#'],
        ['label' => 'Quotations']
    ]"
    title="" />

    <div class="container-fluid">
        <div class="row mt-3">

                    <form id="createFormSubmit" class="form-horizontal m-t-10" method="POST" action="{{ route('quotation.store') }}">
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
                                                    parsley-type="text" readonly name="quotation_no" value="{{ $quotationId }}" placeholder=""/>
                                        </div>
                                    </div>
                            </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                        <label>Quotation Date</label>
                                        <div>
                                            <input type="date" class="form-control" required
                                                    parsley-type="text" name="quotation_date" placeholder=""/>
                                        </div>
                                    </div>
                            </div>
                            <div class="col-lg-3">
                                 <div class="form-group">
                                        <label>Customer</label>
                                        <div>
                                            <select class="form-control js-example-basic-single" name="customer_id" id="customer_id">
                                                <option value="">-- Choose Customer --</option>
                                                @if($customers = App\Models\Customer::get())
                                                    @foreach ($customers as $customer)
                                                        <option value="{{ $customer->id }}">{{ $customer->customer_name }}</option>
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
                                                    parsley-type="text" name="mode_terms_of_payment" placeholder=""/>
                                        </div>
                                    </div>
                            </div>
                             <div class="col-lg-3">
                                 <div class="form-group">
                                        <label>Buyer Reference Order No </label>
                                        <div>
                                            <input type="text" class="form-control" required
                                                    parsley-type="text" name="buyer_reference_order_no" placeholder=""/>
                                        </div>
                                    </div>
                            </div>
                            <div class="col-lg-3">
                                 <div class="form-group">
                                        <label>Other References </label>
                                        <div>
                                            <input type="text" class="form-control" required
                                                    parsley-type="text" name="other_references" placeholder=""/>
                                        </div>
                                    </div>
                            </div>
                            <div class="col-lg-3">
                                 <div class="form-group">
                                        <label>Dispatch Through </label>
                                        <div>
                                            <input type="text" class="form-control" required
                                                    parsley-type="text" name="dispatch_through" placeholder=""/>
                                        </div>
                                    </div>
                            </div>
                             <div class="col-lg-3">
                                 <div class="form-group">
                                        <label>Destination </label>
                                        <div>
                                            <input type="text" class="form-control" required
                                                    parsley-type="text" name="destination" placeholder=""/>
                                        </div>
                                    </div>
                            </div>
                            <div class="col-lg-6">
                                 <div class="form-group">
                                        <label>Terms Of Delivery </label>
                                        <div>
                                            <textarea class="form-control" name="terms_of_delivery"></textarea>

                                        </div>
                                    </div>
                            </div>
                             <div class="col-lg-3">
                                 <div class="form-group">
                                        <label>Customer Type </label>
                                        <div>
                                            <select class="form-control" name="customer_type" id="customer_type">
                                                <option value="">-- Choose Option --</option>
                                                <option value="mrp_customer">MRP Customer</option>
                                                <option value="wholesale_customer">Wholesale Customer</option>
                                            </select>

                                        </div>
                                    </div>
                            </div>

                            <div class="col-lg-3">
                                 <div class="form-group">
                                        <label>Quotation Type </label>
                                        <div>
                                            <select class="form-control" name="quotation_type" id="quotation_type">
                                                <option value="">-- Choose Option --</option>
                                                <option value="b2b">B2B</option>
                                                <option value="retail">Retail</option>
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
                                            <th>Quantity</th>
                                            <th>Rate</th>
                                            <th>Wholesale Price</th>
                                            <th>Per</th>
                                            <th>Disc. %</th>
                                            <th>Amount</th>
                                            <th><button type="button" class="btn btn-success" id="addRow">+</button></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <select class="form-control quotationproduct js-example-basic-single" name="product_id[]" style="width:350px">
                                                    <option>-- Choose Product --</option>
                                                    @if($products = App\Models\Product::get())
                                                        @foreach ($products as $product)
                                                            <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="hsn_no[]" class="form-control hsn_no" readonly>
                                            </td>
                                            <td>
                                                <input type="text" name="part_no[]" class="form-control part_no" readonly>
                                            </td>
                                            <td>
                                                <input type="text" name="quantity[]" class="form-control quantity">
                                            </td>
                                            <td>
                                                <input type="text" name="rate[]" class="form-control rate" readonly>
                                            </td>
                                            <td>
                                                <input type="text" name="wholesale_price[]" class="form-control wholesale_price">
                                            </td>
                                            <td>
                                                <input type="text" name="per[]" class="form-control per">
                                            </td>
                                            <td>
                                                <input type="text" name="disc_percentage[]" class="form-control disc_percentage">
                                            </td>
                                            <td>
                                                <input type="text" name="amount[]" class="form-control amount" readonly>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger removeRow">-</button>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                            <div class="col-lg-6"></div>
                            <div class="col-lg-6 text-right">
                                <table class="table table-bordered">
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
                                        <td colspan="2"><input type="text" class="form-control total_amount" name="total_amount" readonly></td>
                                    </tr>
                                    <tr>
                                        <th>COLLECTION AMOUNT (₹)</th>
                                        <td colspan="2"><input type="text" class="form-control" name="collection_amount"></td>
                                    </tr>

                                </table>
                                <button type="submit" class="btn btn-danger ">Save Quotation</button>
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
