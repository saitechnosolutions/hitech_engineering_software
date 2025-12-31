@extends('layouts.app')
@section('main-content')
    <x-breadcrumb :items="[
            ['label' => 'Hi-tech Engineering', 'url' => '#'],
            ['label' => 'Pages', 'url' => '#'],
            ['label' => 'Users']
        ]" title="" />

    <div class="row mt-3">
        <div class="col-12">
            <div class="card m-b-30">

                <div class="card-header">

                    <div style="display:flex;justify-content:space-between">
                        <h6>Filter</h6>
                        <div>
                            <div class='dropdown'>
                                <button class='btn btn-secondary dropdown-toggle' type='button' id='dropdownMenuButton'
                                    data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                    <i class='fa fa-download' aria-hidden='true'></i>
                                </button>
                                <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                                    <a class='dropdown-item export-excel' href="#" data-type="excel">Excel</a>
                                    <a class='dropdown-item export-pdf' href="#" data-type="pdf">PDF</a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="card-body">
                    <form class="prodstockSubmission" action="{{ route('prostockReportFilter') }}"
                        id="productstockReportFilter">
                        @csrf
                        <div class="row">

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Product</label>
                                    <div>
                                        <select class="form-control js-example-basic-single" name="productIds"
                                            id="productIds" style="width:350px">
                                            <option value="">-- Choose Option --</option>
                                            @if($product = App\Models\Product::get())
                                                @foreach ($product as $products)
                                                    <option value="{{ $products->id }}">{{ $products->product_name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Stock Wise</label>
                                    <div>
                                        <select class="form-control js-example-basic-single" name="stocks" id="stocks"
                                            style="width:350px">
                                            <option value="">-- Choose Status --</option>
                                            <option value="low">Low Stock</option>
                                            <option value="high">Available</option>
                                        </select>
                                    </div>
                                </div>
                            </div>


                            <div class="col-lg-4">
                                <button type="submit" class="btn btn-danger mt-4">Filter</button>
                            </div>

                        </div>
                    </form>
                </div>

            </div>
        </div>
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-header">
                    <div style="display:flex;justify-content:space-between">
                        <h4 class="mt-0 header-title">Product Stock Report</h4>
                    </div>
                </div>


                <div class="card-body">

                    <div class="table-responsive">
                        {{ $dataTable->table(['class' => 'table table-striped table-bordered dt-responsive nowrap']) }}
                    </div>


                </div>
            </div>
        </div> <!-- end col -->
    </div>


@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    <script src="/assets/reports/product-stock-report.js"></script>

@endpush