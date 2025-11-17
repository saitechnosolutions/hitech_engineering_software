@extends('layouts.app')
@section('main-content')
<x-breadcrumb
    :items="[
        ['label' => 'Hi-tech Engineering', 'url' => '#'],
        ['label' => 'Pages', 'url' => '#'],
        ['label' => 'Active Orders']
    ]"
    title="" />

                     <div class="row mt-3" id="option_1_data">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <div style="display:flex;justify-content:space-between">
                                        <h4 class="mt-0 header-title">Invoice Request Pending</h4>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered dt-responsive nowrap w-100" id="dataTable_three">
                                            <thead>
                                                <tr>
                                                    <th>S.No</th>
                                                    <th>Date</th>
                                                    <th>Quotation No</th>
                                                    <th>Customer Name</th>
                                                    <th>Batch</th>
                                                    <th>Quotation Value</th>
                                                    <th>Balance</th>
                                                    <th>RM</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                 @if($activeOrderDetails)
                                                @php
                                                    $i=1;
                                                @endphp
                                                    @foreach ($activeOrderDetails as $activeOrderDetail)
                                                        <tr>
                                                            <td class="text-center">{{ $i++ }}</td>
                                                            <td class="text-center">{{ formatDate($activeOrderDetail->quotation_date) }}</td>
                                                            
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end col -->
                    </div>

                    <div class="row mt-3" id="option_2_data">
                        <div class="col-12">
                            <div class="card ">
                                <div class="card-header">
                                    <div style="display:flex;justify-content:space-between">
                                        <h4 class="mt-0 header-title">Invoice Request Completed</h4>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered dt-responsive nowrap w-100" id="dataTable_two">
                                            <thead>
                                                <tr>
                                                    <th>S.No</th>
                                                    <th>Request Quotation No</th>
                                                    <th>Requested Date</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if($invoiceRequestCompleted)
                                                @php
                                                    $i=1;
                                                @endphp
                                                    @foreach ($invoiceRequestCompleted as $completed)
                                                        <tr>
                                                            <td class="text-center">{{ $i++ }}</td>
                                                            <td class="text-center">{{ $completed->quotation->quotation_no }}</td>
                                                            <td class="text-center">{{ formatDate($completed->invoice_request_date) }}</td>
                                                            <td class="text-center"><span class="badge bg-success p-2 text-white" style="text-transform:capitalize">{{ $completed->status }}</span></td>
                                                            <td>
                                                                <a href="/invoice-request-information/{{ $completed->id }}" class="btn btn-primary"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end col -->
                    </div>





@endsection

@push('scripts')
    <script src="/assets/js/quotation.js"></script>
@endpush

