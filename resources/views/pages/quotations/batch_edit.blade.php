@extends('layouts.app')
@section('main-content')
<x-breadcrumb
    :items="[
        ['label' => 'Hi-tech Engineering', 'url' => '#'],
        ['label' => 'Pages', 'url' => '#'],
        ['label' => 'Quotations']
    ]"
    title="" />

    <div class="container-fluid">
        <div class="row mt-3">
            <div class="col-lg-12">
                <form id="createFormSubmit" class="form-horizontal m-t-10" method="POST" action="{{ route('quotation_batch.update', $batch->id) }}">
                    @csrf
                <div class="card ">
                    <h6 class="card-header m-0">Batch Edit</h6>

                    <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4">
                             <div class="form-group">
                                        <label>Batch Date</label>
                                        <div>
                                            <input type="date" class="form-control" name="batch_date" required value="{{ $batch->batch_date }}">
                                        </div>
                                    </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                        <label>Select Quotations</label>
                                        <div>
                                            <select class="form-control js-example-basic-single" name="quotation_ids[]" style="width:450px" multiple required>
                                                <option value="">-- Choose Quotation --</option>
                                                @php
                                                    $quotations = App\Models\Quotation::orderBy('id', 'Desc')->get();
                                                @endphp

                                                @foreach ($quotations as $quotation)
                                                    <option value="{{ $quotation->id }}" {{ in_array($quotation->id, $selectedQuotationIds) ? 'selected' : '' }}>{{ $quotation->quotation_no }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group">
                                        <label>Priority</label>
                                        <div>
                                            <select class="form-control" name="priority" required>
                                                <option value="">-- Choose Option --</option>
                                                <option value="priority_1" @if($batch->priority == 'priority_1') selected @endif>P1</option>
                                                <option value="priority_2" @if($batch->priority == 'priority_2') selected @endif>P2</option>
                                                <option value="priority_3" @if($batch->priority == 'priority_3') selected @endif>P3</option>
                                                <option value="priority_4" @if($batch->priority == 'priority_4') selected @endif>P4</option>
                                                <option value="priority_5" @if($batch->priority == 'priority_5') selected @endif>P5</option>
                                            </select>
                                        </div>
                                    </div>
                        </div>

                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success">Update</button>
                    </div>
                </div>
                </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="/assets/js/quotation.js"></script>
@endpush
