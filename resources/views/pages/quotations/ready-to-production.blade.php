@extends('layouts.app')
@section('main-content')
<x-breadcrumb
    :items="[
        ['label' => 'Hi-tech Engineering', 'url' => '#'],
        ['label' => 'Pages', 'url' => '#'],
        ['label' => 'Ready to Production']
    ]"
    title="" />

    <div class="container-fluid">
        <div class="row mt-3">

            <div class="col-lg-12">

                <form class="moveToProduction" action="{{ route('move_to_production') }}">
                    @csrf
                <div class="card">
                    <h6 class="card-header m-0">Move to Production</h6>

                    <div class="card-body">
                        <div class="accordion" id="accordionExample">
                            <input type="hidden" name="batch_id" value="{{ $batchId }}">
                        @if($quotations)
                            @foreach($quotations as $quotation)
                                <div class="card">
                                    <div class="card-header" id="headingOne" style="background-color:#ffd2d4">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link accordion-button" style="font-size:18px;color:#ec1c24" type="button" data-toggle="collapse"
                            data-target="#collapse{{ $quotation->id }}" aria-expanded="true" aria-controls="collapseOne">
                            Quotation No : {{ $quotation?->quotation_no }} / Customer : {{ $quotation?->customer?->customer_name }}
                            </button>
                                    </h2>
                                    </div>

                                    <div id="collapse{{ $quotation->id }}" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <table class="table table-bordered table-success" >
                                            <thead>
                                                <tr>
                                                    <th>S.No</th>
                                                    <th>Product Name</th>
                                                    <th>Available Stock Qty</th>
                                                    <th>Quotation Qty</th>
                                                    <th>Stock Usage Qty Entry</th>
                                                    <th>Production Qty Entry</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $i=1;
                                                @endphp
                                                <input type="hidden" name="quotation_id[]" class="form-control" value="{{ $quotation->id }}">
                                                    @foreach ($quotation->quotationProducts as $quoteProducts)
                                                        <tr>
                                                            <td>{{ $i++ }}</td>
                                                            <td>{{ $quoteProducts?->product?->product_name }}</td>
                                                            <td>{{ $quoteProducts?->product?->stock_qty ?? 0 }}</td>
                                                            <td>{{ $quoteProducts?->quantity ?? 0 }}</td>
                                                            <td>
                                                                <input type="hidden" name="product_id[]" class="product_id" value="{{ $quoteProducts->product_id }}">
                                                                <input type="hidden" name="product_available_stock[]" class="product_available_stock" value="{{ $quoteProducts->product->stock_qty }}">
                                                                <input type="number" name="stock_entry[]" class="form-control stockentry" placeholder="Enter Qty" max="{{ $quoteProducts?->product?->stock_qty }}">

                                                                <span class="error-message"></span>
                                                            </td>
                                                            <td>
                                                                <input type="number" name="production_stock_entry[]" class="form-control production_stock_entry" placeholder="Enter Qty" max="{{ $quoteProducts?->quantity }}">
                                                            </td>
                                                        </tr>
                                                    @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif


                </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-success justify-content-end">Move to Production</button>
                    </div>
                </div>
                </form>
            </div>

            </div>
    </div>

@endsection

@push('scripts')
    <script src="/assets/js/quotation.js"></script>
@endpush
