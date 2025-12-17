@extends('layouts.app')
@section('main-content')
<x-breadcrumb
    :items="[
        ['label' => 'Hi-tech Engineering', 'url' => '#'],
        ['label' => 'Pages', 'url' => '#'],
        ['label' => 'Customers']
    ]"
    title="" />

 <div class="row mt-3">
                        <div class="col-12">
                            <div class="card m-b-30">
                                <div class="card-header">
                                    <div style="display:flex;justify-content:space-between">
                                        <h4 class="mt-0 header-title">Customers</h4>
                                    </div>
                                </div>
                                <form id="createFormSubmit" class="form-horizontal m-t-10" method="POST" action="{{ route('customers.update', $customer->id) }}">



                                <div class="card-body">
                                    <div class="row">
              <div class="col-lg-6">
                <div class="form-group">
                                        <label>Customer Type</label>
                                        <div>
                                            <select class="form-control" name="customer_type">
                                                <option value="">-- Choose Option --</option>
                                                <option value="against_delivery" @if($customer->customer_type == "against_delivery") selected @endif>Against Delivery</option>
                                                <option value="after_delivery" @if($customer->customer_type == "after_delivery") selected @endif>After Delivery</option>
                                                <option value="full_advance_customer" @if($customer->customer_type == "full_advance_customer") selected @endif>Full Advance Customer</option>
                                                <option value="part_advance_customer" @if($customer->customer_type == "part_advance_customer") selected @endif>Part Advance Customer</option>
                                            </select>
                                        </div>
                                    </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                                        <label>Customer Name</label>
                                        <div>
                                            <input type="text" class="form-control" required
                                                    parsley-type="text" name="customer_name" value="{{ $customer->customer_name }}" placeholder=""/>
                                        </div>
                                    </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                                        <label>E-mail</label>
                                        <div>
                                            <input type="text" class="form-control" required
                                                    parsley-type="text" name="email" value="{{ $customer->email }}" placeholder=""/>
                                        </div>
                                    </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                                        <label>Mobile Number</label>
                                        <div>
                                            <input type="text" class="form-control" required
                                                    parsley-type="text" value="{{ $customer->mobile_number }}" name="mobile_number" placeholder=""/>
                                        </div>
                                    </div>
            </div>
            <div class="col-lg-6">
                 <div class="form-group">
                                        <label>GST Number</label>
                                        <div>
                                            <input type="text" class="form-control" required
                                                    parsley-type="text" value="{{ $customer->gst_number }}" name="gst_number" placeholder=""/>
                                        </div>
                                    </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group">
                                        <label>Pincode</label>
                                        <div>
                                            <input type="text" class="form-control" required
                                                    parsley-type="text" value="{{ $customer->pincode }}" name="pincode" placeholder=""/>
                                        </div>
                                    </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                                        <label>Address</label>
                                        <div>
                                            <textarea class="form-control" name="address">{{ $customer->address }}</textarea>
                                        </div>
                                    </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group">
                                        <label>State</label>
                                        <div>
                                            <select class="form-control" name="state_id">
                                                <option value="">-- Choose State --</option>
                                                @if($states = App\Models\State::get())
                                                    @foreach($states as $state)
                                                        <option value="{{ $state->id }}" @if($customer->state_id == $state->id) selected @endif>{{ $state->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>

                                        </div>
                                    </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group">
                                        <label>Wholesale Price</label>
                                        <div>
                                             <input type="text" class="form-control" required
                                                    parsley-type="text" name="wholesale_price" value="{{ $customer->wholesale_price }}" placeholder=""/>
                                        </div>
                                    </div>
            </div>

            <div class="col-lg-6">
                 <div class="form-group">
                                        <label>Discount</label>
                                        <div>
                                             <input type="text" class="form-control" required
                                                    parsley-type="text" name="discount" value="{{ $customer->discount }}" placeholder=""/>
                                        </div>
                                    </div>
            </div>
                                            <button type="submit" class="btn btn-primary" style="width:250px">Submit</button>
                                </div>
                                </form>
                            </div>
                        </div> <!-- end col -->
                    </div>
@endsection

@include('pages.bom.modal.create_modal')

@push('scripts')
    <script>
        $(document).on("click", ".addMainRow", function(){
    var rowCount = $("#mainTable > tbody > tr.mainRow").length; // only main rows
    var newRow = $("#mainTable > tbody > tr.mainRow:first").clone(true, true);


    newRow.find("input[type='text']").val("");
    newRow.find("select").val("");

    // reset stagesTable inside this new main row
    var stageTable = newRow.find(".stagesTable");
    stageTable.find("tbody tr:gt(0)").remove(); // keep only first stage row
    stageTable.find("select").val("");          // reset dropdown
    stageTable.find("select").attr("name", "process_team[" + rowCount + "][]");

    // ✅ append only to the mainTable tbody, not anywhere else
    $("#mainTable > tbody").append(newRow);
});

$(document).on("click", ".addStageRow", function(){
    var stageTable = $(this).closest(".stagesTable");
    var newStageRow = stageTable.find("tbody tr.stageRow:first").clone();
    newStageRow.find("select").val("");
    stageTable.find("tbody").append(newStageRow);
});

$(document).on("click", ".removeStageRow", function(){
    var row = $(this).closest("tr");
    if(row.siblings().length > 0){
        row.remove();
    }
});

    $(".bom_category").hide();
    $(".production_bom").hide();
    $(".packing_bom").hide();



   $(document).on("change", ".bomtype", function () {

    var bomType = $(this).val();
    var row = $(this).closest(".mainRow");   // Get this row

    var productionBom = row.find(".production_bom");
    var packingBom = row.find(".packing_bom");

    if (bomType == 1) {
        productionBom.show();
        packingBom.hide();
    } else {
        packingBom.show();
        productionBom.hide();
    }
});




    </script>


@endpush
