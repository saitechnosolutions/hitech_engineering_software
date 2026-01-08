
@extends('layouts.app')
@section('main-content')
    <x-breadcrumb :items="[
            ['label' => 'Hi-tech Engineering', 'url' => '#'],
            ['label' => 'Pages', 'url' => '#'],
            ['label' => 'Payments']
        ]" title="" />



    </div>
    <div class="col-12">
        <form id="createFormSubmit" class="form-horizontal m-t-10" method="POST" action="{{ route('payments.update', $payment->id) }}">
                            @csrf
        <div class="card m-b-30">
            <div class="card-header">
                <div style="display:flex;justify-content:space-between">
                    <h4 class="mt-0 header-title">Payment Details</h4>

                </div>
            </div>
            <div class="card-body">

                <div class="container-fluid">
                    <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                            <label>Select Quotation</label>
                                            <select class="form-control js-example-basic-single" name="quotation_id" style="width:100%">
                                                    <option>-- Choose Quotation No --</option>
                                                    @if($quotations = App\Models\Quotation::get())
                                                        @foreach ($quotations as $quotation)
                                                            <option value="{{ $quotation->id }}" @if($quotation->id == $payment->quotation_id) selected @endif>{{ $quotation->quotation_no }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                        </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                            <label>Payment Date</label>
                                            <input type="date" name="payment_date" class="form-control" value="{{ $payment->payment_date }}" >
                                        </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                            <label>Amount</label>
                                            <input type="text" name="amount" class="form-control" value="{{ $payment->amount }}">
                                        </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                            <label>Images</label>
                                            <input type="file" name="reference_images[]" class="form-control" multiple>
                                        </div>

                                       @if (!empty($payment->reference_images) && count($payment->reference_images))
    @foreach ($payment->reference_images as $images)
        @if ($images)
            <a href="{{ $images }}" download>
                {{ str_replace('/payment_images/', '', $images) }}
            </a>
        @endif
    @endforeach
@else
    <span>No files uploaded</span>
@endif

                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                            <label>Remarks</label>
                                            <textarea class="form-control" name="remarks">{{ $payment->remarks }}</textarea>
                                        </div>
                            </div>

                    </div>
                </div>


            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>

        </form>
    </div> <!-- end col -->
    </div>
@endsection

@push('scripts')


    <script src="/assets/js/payment.js"></script>
@endpush
