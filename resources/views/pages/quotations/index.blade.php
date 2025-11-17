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
                    <div class="col-md-3 col-lg-3 col-xl-2" >
                        <div class="card m-b-30 option-box" id="option_1">
                            <div class="card-body">
                                <div class="d-flex flex-row">
                                    <div class="col-12 align-self-center text-center">
                                        <div class="m-l-10">
                                            <h5 class="mt-0 ">{{ $totalQuotationsCount }}</h5>
                                            <p class="mb-0 "><b>Today Quotation Count</b></p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-2" >
                        <div class="card m-b-30 option-box" id="option_2">
                            <div class="card-body">
                                <div class="d-flex flex-row">

                                    <div class="col-12 align-self-center text-center">
                                        <div class="m-l-10">
                                            <h5 class="mt-0 ">{{ $productionMovedQuotations->count() }}</h5>
                                            <p class="mb-0 "><b>Production Moved</b></p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                      <div class="col-md-6 col-lg-6 col-xl-2" >
                        <div class="card m-b-30 option-box" id="option_3">
                            <div class="card-body">
                                <div class="d-flex flex-row">

                                    <div class="col-12 text-center align-self-center">
                                        <div class="m-l-10 ">
                                            <h5 class="mt-0 ">{{ $productionOngoingQuotations->count() }}</h5>
                                            <p class="mb-0 "><b>Production Ongoing</b></p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-6 col-xl-2" >
                        <div class="card m-b-30 option-box" id="option_4">
                            <div class="card-body">
                                <div class="d-flex flex-row">

                                    <div class="col-12 text-center align-self-center">
                                        <div class="m-l-10 ">
                                            <h5 class="mt-0 ">{{ $productionPendingQuotations->count() }}</h5>
                                            <p class="mb-0 "><b>Production Pending</b></p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                     <div class="col-md-6 col-lg-6 col-xl-2" >
                        <div class="card m-b-30 option-box" id="option_5">
                            <div class="card-body">
                                <div class="d-flex flex-row">

                                    <div class="col-12 text-center align-self-center">
                                        <div class="m-l-10 ">
                                            <h5 class="mt-0">{{ $productionCompletedQuotations->count() }}</h5>
                                            <p class="mb-0 "><b>Production Completed</b></p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-6 col-xl-2 " >
                        <div class="card m-b-30 option-box" id="option_6">
                            <div class="card-body">
                                <div class="d-flex flex-row">
                                    <div class="col-12 align-self-center text-center">
                                        <div class="m-l-10 ">
                                            <h5 class="mt-0 ">{{ $quotationBatches->count() }}</h5>
                                            <p class="mb-0 "><b>Batches</b></p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
            </div>
                     <div class="row mt-3" id="option_1_data">
                        <div class="col-12">
                            <div class="card m-b-30">
                                <div class="card-header">
                                    <div style="display:flex;justify-content:space-between">
                                        <h4 class="mt-0 header-title">Quotations</h4>
                                        <div>
                                            <a href="{{ route('quotation.create') }}" class="btn btn-primary btn-sm" style="width:150px">Create Quotation</a>
                                            <a data-toggle="modal" data-target="#createBatchModal" class="btn btn-danger btn-sm" style="width:150px;color:white">Create Batch</a>
                                        </div>

                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        {{ $dataTable->table(['class' => 'table table-striped table-bordered dt-responsive nowrap w-100']) }}
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end col -->
                    </div>

                    <div class="row mt-3" id="option_2_data">
                        <div class="col-12">
                            <div class="card m-b-30">
                                <div class="card-header">
                                    <div style="display:flex;justify-content:space-between">
                                        <h4 class="mt-0 header-title">Production Moved</h4>
                                        <div>
                                            <a href="{{ route('quotation.create') }}" class="btn btn-primary btn-sm" style="width:150px">Create Quotation</a>
                                            <a data-toggle="modal" data-target="#createBatchModal" class="btn btn-danger btn-sm" style="width:150px;color:white">Create Batch</a>
                                        </div>

                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered dt-responsive nowrap w-100" id="dataTable_two">
                                            <thead>
                                                <tr>
                                                    <th>S.No</th>
                                                    <th>Quotation No</th>
                                                    <th>Quotation Date</th>
                                                    <th>Customer Name</th>
                                                    <th>Download</th>
                                                    <th>Production Moved?</th>
                                                    <th>Batch</th>
                                                    <th>Priority</th>
                                                    <th>Production Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $i=1;
                                                @endphp
                                                @if($productionMovedQuotations)
                                                    @foreach ($productionMovedQuotations as $productionMovedQuotation)
                                                        <tr>
                                                            <td>{{ $i++ }}</td>
                                                            <td>{{ $productionMovedQuotation->quotation_no }}</td>
                                                            <td>{{ formatDate($productionMovedQuotation->quotation_date) }}</td>
                                                            <td>{{ $productionMovedQuotation->customer->customer_name }}</td>
                                                            <td><a href="/quotation_format/{{ $productionMovedQuotation->id }}">Download</a></td>
                                                            <td>{{ $productionMovedQuotation->is_production_moved }}</td>
                                                            <td>{{ formatDate($productionMovedQuotation->batch_date) }}</td>
                                                            <td>{{ $productionMovedQuotation->priority }}</td>
                                                            <td>{{ removeUnderscoreText($productionMovedQuotation->production_status) }}</td>
                                                            <td>
                                                                 <div class='dropdown'>
                                                                    <button class='btn btn-secondary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                                                        <i class='fa fa-cog' aria-hidden='true'></i>
                                                                    </button>
                                                                    <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>

                                                                        <a class='dropdown-item' href='/quotation/edit/'>Edit</a>
                                                                        <a class='dropdown-item deleteBtn' data-url='/quotation/delete/{$query->id}'>Delete</a>
                                                                    </div>
                                                                    </div>
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

                    <div class="row mt-3" id="option_3_data">
                        <div class="col-12">
                            <div class="card m-b-30">
                                <div class="card-header">
                                    <div style="display:flex;justify-content:space-between">
                                        <h4 class="mt-0 header-title">Production Ongoing</h4>
                                        <div>
                                            <a href="{{ route('quotation.create') }}" class="btn btn-primary btn-sm" style="width:150px">Create Quotation</a>
                                            <a data-toggle="modal" data-target="#createBatchModal" class="btn btn-danger btn-sm" style="width:150px;color:white">Create Batch</a>
                                        </div>

                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                       <table class="table table-striped table-bordered dt-responsive nowrap w-100" id="dataTable_three">
                                            <thead>
                                                <tr>
                                                    <th>S.No</th>
                                                    <th>Quotation No</th>
                                                    <th>Quotation Date</th>
                                                    <th>Customer Name</th>
                                                    <th>Download</th>
                                                    <th>Production Moved?</th>
                                                    <th>Batch</th>
                                                    <th>Priority</th>
                                                    <th>Production Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $i=1;
                                                @endphp
                                                @if($productionOngoingQuotations)
                                                    @foreach ($productionOngoingQuotations as $productionOngoingQuotation)
                                                        <tr>
                                                            <td>{{ $i++ }}</td>
                                                            <td>{{ $productionOngoingQuotation->quotation_no }}</td>
                                                            <td>{{ formatDate($productionOngoingQuotation->quotation_date) }}</td>
                                                            <td>{{ $productionOngoingQuotation->customer->customer_name }}</td>
                                                            <td><a href="/quotation_format/{{ $productionOngoingQuotation->id }}">Download</a></td>
                                                            <td>{{ $productionOngoingQuotation->is_production_moved }}</td>
                                                            <td>{{ formatDate($productionOngoingQuotation->batch_date) }}</td>
                                                            <td>{{ $productionOngoingQuotation->priority }}</td>
                                                            <td>{{ removeUnderscoreText($productionOngoingQuotation->production_status) }}</td>
                                                            <td>
                                                                 <div class='dropdown'>
                                                                    <button class='btn btn-secondary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                                                        <i class='fa fa-cog' aria-hidden='true'></i>
                                                                    </button>
                                                                    <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>

                                                                        <a class='dropdown-item' href='/quotation/edit/'>Edit</a>
                                                                        <a class='dropdown-item deleteBtn' data-url='/quotation/delete/{$query->id}'>Delete</a>
                                                                    </div>
                                                                    </div>
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

                    <div class="row mt-3" id="option_4_data">
                        <div class="col-12">
                            <div class="card m-b-30">
                                <div class="card-header">
                                    <div style="display:flex;justify-content:space-between">
                                        <h4 class="mt-0 header-title">Production Pending</h4>
                                        <div>
                                            <a href="{{ route('quotation.create') }}" class="btn btn-primary btn-sm" style="width:150px">Create Quotation</a>
                                            <a data-toggle="modal" data-target="#createBatchModal" class="btn btn-danger btn-sm" style="width:150px;color:white">Create Batch</a>
                                        </div>

                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                       <table class="table table-striped table-bordered dt-responsive nowrap w-100" id="dataTable_four">
                                            <thead>
                                                <tr>
                                                    <th>S.No</th>
                                                    <th>Quotation No</th>
                                                    <th>Quotation Date</th>
                                                    <th>Customer Name</th>
                                                    <th>Download</th>
                                                    <th>Production Moved?</th>
                                                    <th>Batch</th>
                                                    <th>Priority</th>
                                                    <th>Production Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $i=1;
                                                @endphp
                                                @if($productionPendingQuotations)
                                                    @foreach ($productionPendingQuotations as $productionPendingQuotation)
                                                        <tr>
                                                            <td>{{ $i++ }}</td>
                                                            <td>{{ $productionPendingQuotation->quotation_no }}</td>
                                                            <td>{{ formatDate($productionPendingQuotation->quotation_date) }}</td>
                                                            <td>{{ $productionPendingQuotation->customer->customer_name }}</td>
                                                            <td><a href="/quotation_format/{{ $productionPendingQuotation->id }}">Download</a></td>
                                                            <td>{{ $productionPendingQuotation->is_production_moved }}</td>
                                                            <td>{{ formatDate($productionPendingQuotation->batch_date) }}</td>
                                                            <td>{{ $productionPendingQuotation->priority }}</td>
                                                            <td>{{ removeUnderscoreText($productionPendingQuotation->production_status) }}</td>
                                                            <td>
                                                                 <div class='dropdown'>
                                                                    <button class='btn btn-secondary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                                                        <i class='fa fa-cog' aria-hidden='true'></i>
                                                                    </button>
                                                                    <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>

                                                                        <a class='dropdown-item' href='/quotation/edit/'>Edit</a>
                                                                        <a class='dropdown-item deleteBtn' data-url='/quotation/delete/{$query->id}'>Delete</a>
                                                                    </div>
                                                                    </div>
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

                    <div class="row mt-3" id="option_5_data">
                        <div class="col-12">
                            <div class="card m-b-30">
                                <div class="card-header">
                                    <div style="display:flex;justify-content:space-between">
                                        <h4 class="mt-0 header-title">Production Completed</h4>
                                        <div>
                                            <a href="{{ route('quotation.create') }}" class="btn btn-primary btn-sm" style="width:150px">Create Quotation</a>
                                            <a data-toggle="modal" data-target="#createBatchModal" class="btn btn-danger btn-sm" style="width:150px;color:white">Create Batch</a>
                                        </div>

                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                       <table class="table table-striped table-bordered dt-responsive nowrap w-100" id="dataTable_five">
                                            <thead>
                                                <tr>
                                                    <th>S.No</th>
                                                    <th>Quotation No</th>
                                                    <th>Quotation Date</th>
                                                    <th>Customer Name</th>
                                                    <th>Download</th>
                                                    <th>Production Moved?</th>
                                                    <th>Batch</th>
                                                    <th>Priority</th>
                                                    <th>Production Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $i=1;
                                                @endphp
                                                @if($productionCompletedQuotations)
                                                    @foreach ($productionCompletedQuotations as $productionCompletedQuotation)
                                                        <tr>
                                                            <td>{{ $i++ }}</td>
                                                            <td>{{ $productionCompletedQuotation->quotation_no }}</td>
                                                            <td>{{ formatDate($productionCompletedQuotation->quotation_date) }}</td>
                                                            <td>{{ $productionCompletedQuotation?->customer?->customer_name }}</td>
                                                            <td><a href="/quotation_format/{{ $productionCompletedQuotation->id }}">Download</a></td>
                                                            <td>{{ $productionCompletedQuotation->is_production_moved }}</td>
                                                            <td>{{ formatDate($productionCompletedQuotation->batch_date) }}</td>
                                                            <td>{{ $productionCompletedQuotation->priority }}</td>
                                                            <td>{{ removeUnderscoreText($productionCompletedQuotation->production_status) }}</td>
                                                            <td>
                                                                 <div class='dropdown'>
                                                                    <button class='btn btn-secondary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                                                        <i class='fa fa-cog' aria-hidden='true'></i>
                                                                    </button>
                                                                    <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>

                                                                        <a class='dropdown-item' href='/quotation/edit/'>Edit</a>
                                                                        <a class='dropdown-item deleteBtn' data-url='/quotation/delete/{$query->id}'>Delete</a>

                                                                    </div>
                                                                    </div>
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

                     <div class="row mt-3" id="option_6_data">
                        <div class="col-12">
                            <div class="card m-b-30">
                                <div class="card-header">
                                    <div style="display:flex;justify-content:space-between">
                                        <h4 class="mt-0 header-title">Batches</h4>
                                        <div>
                                            <a href="{{ route('quotation.create') }}" class="btn btn-primary btn-sm" style="width:150px">Create Quotation</a>
                                            <a data-toggle="modal" data-target="#createBatchModal" class="btn btn-danger btn-sm" style="width:150px;color:white">Create Batch</a>
                                        </div>

                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered dt-responsive nowrap w-100" id="datatable">
                                            <thead>
                                                <tr>
                                                    <th>S.No</th>
                                                    <th>Batch</th>
                                                    <th>Priority</th>
                                                    <th>Quotations</th>
                                                    <th>Quotation Count</th>
                                                    <th>Batch Status</th>
                                                    <th>Show Process</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $i=1;
                                                @endphp
                                                @if($quotationBatches)
                                                    @foreach ($quotationBatches as $batch)
                                                        <tr>
                                                            <td>{{ $i++ }}</td>
                                                            <td>{{ formatDate($batch->batch_date) }}</td>
                                                            <td>
                                                                @if($batch->priority == 'priority_1')
                                                                    <span class="badge badge-success">{{ $batch->priority }}</span>
                                                                    @elseif($batch->priority == 'priority_2')
                                                                    <span class="badge badge-info">{{ $batch->priority }}</span>
                                                                    @elseif($batch->priority == 'priority_3')
                                                                    <span class="badge badge-primary">{{ $batch->priority }}</span>
                                                                    @elseif($batch->priority == 'priority_4')
                                                                    <span class="badge badge-warning">{{ $batch->priority }}</span>
                                                                    @elseif($batch->priority == 'priority_5')
                                                                    <span class="badge badge-danger">{{ $batch->priority }}</span>
                                                                @endif
                                                                </td>
                                                                <td>
                                                                    @php
                                                                        $quotationIds = App\Models\Quotation::whereIn('id', $batch->quotation_ids)->pluck('quotation_no')->toArray();
                                                                    @endphp
                                                                    {{ implode(", ", $quotationIds) }}
                                                                </td>
                                                                <td>{{ $batch->quotation_count }}</td>
                                                                <td>
                                                                    @if($batch->batch_status == 'pending')
                                                                        <span class="badge badge-danger" style="text-transform:capitalize">{{ $batch->batch_status }}</span>
                                                                        @elseif($batch->batch_status == 'processing')
                                                                        <span class="badge badge-info" style="text-transform:capitalize">{{ $batch->batch_status }}</span>
                                                                        @else
                                                                        <span class="badge badge-success" style="text-transform:capitalize">{{ $batch->batch_status }}</span>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <a href="/production_view/{{ $batch->id }}">Show</a>
                                                                </td>
                                                                <td>
                                                                    <div class='dropdown'>
                                                                        <button class='btn btn-secondary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                                                            <i class='fa fa-cog' aria-hidden='true'></i>
                                                                        </button>
                                                                        <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>

                                                                            <a class='dropdown-item' href='/quotation/edit/{$query->id}'>Edit</a>
                                                                            <a class='dropdown-item deleteBtn' data-url='/quotation/delete/{$query->id}'>Delete</a>
                                                                            <a class='dropdown-item moveToProduction' data-id="{{ $batch->id }}" >Move to Production</a>
                                                                        </div>
                                                                        </div>
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
@include('pages.quotations.modal.create_batch_modal')
@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    <script src="/assets/js/quotation.js"></script>

@endpush

