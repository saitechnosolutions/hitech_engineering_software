@extends('layouts.app')
@section('main-content')
    <x-breadcrumb :items="[
            ['label' => 'Hi-tech Engineering', 'url' => '#'],
            ['label' => 'Pages', 'url' => '#'],
            ['label' => 'Production']
        ]" title="" />

    <div class="row mt-5">
        <div class="col-md-3 col-lg-3 col-xl-2">
            <div class="card m-b-30 option-box" id="option_1">
                <div class="card-body">
                    <div class="d-flex flex-row">
                        <div class="col-12 align-self-center text-center">
                            <div class="m-l-10">
                                <h5 class="mt-0 ">{{ $quotationBatches->count() }}</h5>
                                <p class="mb-0 "><b>Open Batches</b></p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-6 col-xl-2">
            <div class="card m-b-30 option-box" id="option_2">
                <div class="card-body">
                    <div class="d-flex flex-row">

                        <div class="col-12 align-self-center text-center">
                            <div class="m-l-10">
                                <h5 class="mt-0 ">{{ $closedQuotationBatches->count() }}</h5>
                                <p class="mb-0 "><b>Closed Batches</b></p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row mt-5" id="option_1_data">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-header">
                    <div style="display:flex;justify-content:space-between">
                        <h4 class="mt-0 header-title">Open Batches</h4>
                    </div>
                </div>
                <div class="card-body">
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
                                $i = 1;
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
                                                <span class="badge badge-danger"
                                                    style="text-transform:capitalize">{{ $batch->batch_status }}</span>
                                            @elseif($batch->batch_status == 'processing')
                                                <span class="badge badge-info"
                                                    style="text-transform:capitalize">{{ $batch->batch_status }}</span>
                                            @else
                                                <span class="badge badge-success"
                                                    style="text-transform:capitalize">{{ $batch->batch_status }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="/production_view/{{ $batch->id }}">Show</a>
                                        </td>
                                        <td>
                                            <div class='dropdown'>
                                                <button class='btn btn-secondary dropdown-toggle' type='button'
                                                    id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true'
                                                    aria-expanded='false'>
                                                    <i class='fa fa-cog' aria-hidden='true'></i>
                                                </button>
                                                <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>

                                                    <a class='dropdown-item' href='/quotation/edit/{$query->id}'>Edit</a>
                                                    <a class='dropdown-item deleteBtn'
                                                        data-url='/quotation/delete/{$query->id}'>Delete</a>
                                                    <a class='dropdown-item moveToProduction' data-id="{{ $batch->id }}">Move to
                                                        Production</a>
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
        </div> <!-- end col -->
    </div>

    <div class="row mt-3" id="option_2_data">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-header">
                    <div style="display:flex;justify-content:space-between">
                        <h4 class="mt-0 header-title">Closed Batches</h4>
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
                                    $i = 1;
                                @endphp
                                @if($closedQuotationBatches)
                                    @foreach ($closedQuotationBatches as $batch)
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
                                                    <span class="badge badge-danger"
                                                        style="text-transform:capitalize">{{ $batch->batch_status }}</span>
                                                @elseif($batch->batch_status == 'processing')
                                                    <span class="badge badge-info"
                                                        style="text-transform:capitalize">{{ $batch->batch_status }}</span>
                                                @else
                                                    <span class="badge badge-success"
                                                        style="text-transform:capitalize">{{ $batch->batch_status }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="/production_view/{{ $batch->id }}">Show</a>
                                            </td>
                                            <td>
                                                <div class='dropdown'>
                                                    <button class='btn btn-secondary dropdown-toggle' type='button'
                                                        id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true'
                                                        aria-expanded='false'>
                                                        <i class='fa fa-cog' aria-hidden='true'></i>
                                                    </button>
                                                    <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>

                                                        <a class='dropdown-item' href='/quotation/edit/{$query->id}'>Edit</a>
                                                        <a class='dropdown-item deleteBtn'
                                                            data-url='/quotation/delete/{$query->id}'>Delete</a>
                                                        <a class='dropdown-item moveToProduction' data-id="{{ $batch->id }}">Move to
                                                            Production</a>
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



@push('scripts')
    <script src="/assets/js/quotation.js"></script>
@endpush