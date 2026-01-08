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
                        <div class="col-12">

                        <form id="createFormSubmit" class="form-horizontal m-t-10" method="POST" action="{{ route('lr-documents.update', $lrDocument->id) }}">
                            @csrf

                            <div class="card m-b-30">
                                <div class="card-header">
                                    <div style="display:flex;justify-content:space-between">
                                        <h4 class="mt-0 header-title">LR Edit</h4>

                                    </div>
                                </div>

                                <div class="card-body">

                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                            <label>Select Quotation</label>
                                            <select class="form-control js-example-basic-single" name="quotation_id" style="width:100%">
                                                    <option>-- Choose Quotation No --</option>
                                                    @if($quotations = App\Models\Quotation::get())
                                                        @foreach ($quotations as $quotation)
                                                            <option value="{{ $quotation->id }}" @if($quotation->id == $lrDocument->quotation_id) selected @endif>{{ $quotation->quotation_no }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                        </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group">
                                            <label>Upload Documents</label>
                                            <input type="file" name="reference_images[]" class="form-control" multiple>

                                       @php
    $docs = $lrDocument->upload_documents ?? [];

    // If it's a JSON string, decode it
    if (is_string($docs)) {
        $decoded = json_decode($docs, true);
        $docs = is_array($decoded) ? $decoded : [];
    }
@endphp

@if(!empty($docs))
    @foreach ($docs as $file)
        @if(!empty($file))
            <a href="{{ asset($file) }}" download>
                {{ basename($file) }}
            </a><br>
        @endif
    @endforeach
@else
    <span>No files uploaded</span>
@endif




                                        </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group">
                                            <label>Remarks</label>
                                            <textarea class="form-control" name="remarks">{{ $lrDocument->remarks }}</textarea>
                                        </div>
                                        </div>
                                    </div>

                                </div>
                                 <div class="card-footer ">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                            </div>
                            </form>
                        </div>
                    </div>
@endsection



