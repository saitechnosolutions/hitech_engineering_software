@extends('layouts.app')
@section('main-content')
<x-breadcrumb
    :items="[
        ['label' => 'Hi-tech Engineering', 'url' => '#'],
        ['label' => 'Pages', 'url' => '#'],
        ['label' => 'Payment Details']
    ]"
    title="" />

                     <div class="row mt-3" id="option_1_data">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <div style="display:flex;justify-content:space-between">
                                        <h4 class="mt-0 header-title">Today Payment Details</h4>
                                        <h4 class="mt-0 header-title" style="color:blue">Total Received Amount : <span>₹{{ number_format($paymentDetails->sum('amount'), 2) }}</span></h4>
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
                                                    <th>Received Amount</th>
                                                    <th>Remarks</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                 @if($paymentDetails)
                                                @php
                                                    $i=1;
                                                @endphp
                                                    @foreach ($paymentDetails as $paymentDetail)
                                                        <tr>
                                                            <td class="text-center">{{ $i++ }}</td>
                                                            <td class="text-center">{{ formatDate($paymentDetail->payment_date) }}</td>
                                                            <td class="text-center">{{ $paymentDetail->quotation->quotation_no }}</td>
                                                            <td class="text-center">{{ $paymentDetail?->quotation?->customer?->customer_name }}</td>
                                                            <td class="text-center">₹{{ number_format($paymentDetail->amount, 2) }}</td>
                                                            <td class="text-center">{{ $paymentDetail->remarks }}</td>
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

