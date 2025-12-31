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
                                    <a class='dropdown-item' id='excelExport' href='#'>Excel</a>
                                    <a class='dropdown-item' id='pdfExport' href='#' target='_blank'>PDF</a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="card-body">
                    <form class="collectionSubmission" action="{{ route('collectionReportFilter') }}"
                        id="collectionReportFilter  ">
                        @csrf
                        <div class="row">

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Quotation</label>
                                    <div>
                                        <select class="form-control js-example-basic-single" name="quotationIds"
                                            id="quotationIds" style="width:350px">
                                            <option value="">-- Choose Option --</option>
                                            @if($quotations = App\Models\Quotation::get())
                                                @foreach ($quotations as $quotation)
                                                    <option value="{{ $quotation->id }}">{{ $quotation->quotation_no }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>


                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>From Date</label>
                                    <input type="date" class="form-control" name="fromdate" id="fromDate">
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Todate</label>
                                    <input type="date" class="form-control" name="todate" id="toDate">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Batch </label>
                                    <input type="date" class="form-control" name="batch" id="batch">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Company Name </label>
                                    {{-- <input type="text" class="form-control" name="company_name" id="companyName"> --}}
                                    @php
                                        $customers = App\Models\Customer::all();
                                    @endphp
                                    <select name="company_name" id="companyName"
                                        class="form-control js-example-basic-single" style="width:350px">
                                        <option value="">-- Choose Customer --</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->customer_name }}">{{ $customer->customer_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 mt-4">
                                <div class="form-group">
                                    <label>RM </label>
                                    {{-- <input type="text" class="form-control" name="rm" id="rm"> --}}
                                    @php
                                        $emp = App\Models\Employee::all();
                                    @endphp
                                    <select name="rm" id="rm" class="form-control js-example-basic-single"
                                        style="width:350px">
                                        <option value="">-- Choose RM --</option>
                                        @foreach($emp as $employee)
                                            <option value="{{ $employee->name }}">{{ $employee->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-3">
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
                        <h4 class="mt-0 header-title">Collection Report</h4>
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
    <script src="/assets/reports/collection-report.js"></script>

@endpush