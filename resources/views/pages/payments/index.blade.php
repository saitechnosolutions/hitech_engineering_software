@extends('layouts.app')
@section('main-content')
    <x-breadcrumb :items="[
            ['label' => 'Hi-tech Engineering', 'url' => '#'],
            ['label' => 'Pages', 'url' => '#'],
            ['label' => 'Payments']
        ]" title="" />


    <div class="row mt-3">

        <div class="col-12 m-0">
            <div class="card m-b-30">

                <div class="card-header">

                    <div style="display:flex;justify-content:space-between">
                        <h6>Filter</h6>

                    </div>

                </div>
                <div class="card-body m-0">
                    <form class="reportSubmission" action="{{ route('paymentReportFilter') }}" id="paymentReportFilter">
                        @csrf
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>Quotation No</label>
                                    <div>
                                        <select class="form-control js-example-basic-single" name="quotationId"
                                            id="quotationId" style="width:350px">
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


                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>From Date</label>
                                    <input type="date" class="form-control" name="fromdate" id="fromDate">
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>Todate</label>
                                    <input type="date" class="form-control" name="todate" id="toDate">
                                </div>
                            </div>
                            <div class="col-lg-3">
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
                            <div class="col-lg-3">
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
    </div>
    </div>
    <div class="col-12">
        <div class="card m-b-30">
            <div class="card-header">
                <div style="display:flex;justify-content:space-between">
                    <h4 class="mt-0 header-title">Payment Details</h4>
                    <button type="button" class="btn btn-primary btn-sm" style="width:100px" data-toggle="modal"
                        data-target="#createPaymentModal">
                        Create
                    </button>
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

@include('pages.payments.modals.create_payment_modal')
@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}

    <script src="/assets/js/payment.js"></script>
@endpush
