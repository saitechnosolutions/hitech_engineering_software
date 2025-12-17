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
                                        <h4 class="mt-0 header-title">Active Order Details</h4>
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
                                                            <td class="text-center">{{ $activeOrderDetail->quotation_no }}</td>
                                                            <td class="text-center">{{ $activeOrderDetail?->customer?->customer_name }}</td>
                                                            <td class="text-center">{{ formatDate($activeOrderDetail->batch_date) }}:{{ $activeOrderDetail->priority }}</td>
                                                            <td class="text-center">{{ number_format($activeOrderDetail->total_collectable_amount, 2) }}</td>
                                                            <td class="text-center">₹{{ number_format($activeOrderDetail->total_collectable_amount - $activeOrderDetails->sum('amount'), 2) }}</td>
                                                            <td class="text-center"></td>
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

