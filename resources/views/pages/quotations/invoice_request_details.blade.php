@extends('layouts.app')
@section('main-content')
<x-breadcrumb
    :items="[
        ['label' => 'Hi-tech Engineering', 'url' => '#'],
        ['label' => 'Pages', 'url' => '#'],
        ['label' => 'Users']
    ]"
    title="" />

            <div class="row mt-3">
                    <div class="col-md-4 col-lg-4 col-xl-4" >
                        <div class="card m-b-30 option-box" id="option_1">
                            <div class="card-body">
                                <div class="d-flex flex-row">
                                    <div class="col-12 align-self-center text-center">
                                        <div class="m-l-10">
                                            <h5 class="mt-0 ">{{ $invoiceRequestPending->count() }}</h5>
                                            <p class="mb-0 "><b>Invoice Request Pending</b></p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-4" >
                        <div class="card m-b-30 option-box" id="option_2">
                            <div class="card-body">
                                <div class="d-flex flex-row">

                                    <div class="col-12 align-self-center text-center">
                                        <div class="m-l-10">
                                            <h5 class="mt-0 ">{{ $invoiceRequestCompleted->count() }}</h5>
                                            <p class="mb-0 "><b>Invoice Request Completed</b></p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>


            </div>
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
                                                    <th>Request Quotation No</th>
                                                    <th>Requested Date</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                 @if($invoiceRequestPending)
                                                @php
                                                    $i=1;
                                                @endphp
                                                    @foreach ($invoiceRequestPending as $pending)
                                                        <tr>
                                                            <td class="text-center">{{ $i++ }}</td>
                                                            <td class="text-center">{{ $pending?->quotation?->quotation_no }}</td>
                                                            <td class="text-center">{{ formatDate($pending?->invoice_request_date) }}</td>
                                                            <td class="text-center"><span class="badge bg-success p-2 text-white" style="text-transform:capitalize">{{ $pending->status }}</span></td>
                                                            <td class="text-center">
                                                                <a href="/invoice-request-information/{{ $pending->id }}" class="btn btn-primary"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                                                <a class="btn btn-warning invoiceComplete" data-id="{{ $pending->id }}"><i class="fa fa-check" aria-hidden="true"></i></a>
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
                                                            <td class="text-center">{{ $completed?->quotation?->quotation_no }}</td>
                                                            <td class="text-center">{{ formatDate($completed->invoice_request_date) }}</td>
                                                            <td class="text-center"><span class="badge bg-success p-2 text-white" style="text-transform:capitalize">{{ $completed->status }}</span></td>
                                                            <td>
                                                                <a href="/invoice-request-information/{{ $completed->id }}" class="btn btn-primary"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                                                <a href="{{ $completed->upload_documents[0] }}" download class="btn btn-warning"><i class="fa fa-download" aria-hidden="true"></i></a>
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

@include('pages.quotations.modal.invoice_approve_modal')

@push('scripts')
    <script src="/assets/js/quotation.js"></script>
@endpush

