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
                            <div class="card m-b-30">
                                <div class="card-header">
                                    <div style="display:flex;justify-content:space-between">
                                        <h4 class="mt-0 header-title">BOM</h4>

                                    <a href="{{ route('bom.create') }}" class="btn btn-primary btn-sm">Create</a>
                                    </div>
                                </div>
                                <form id="createFormSubmit" class="form-horizontal m-t-10" method="POST" action="{{ route('bom.store') }}">

                                <div class="card-body">

                                      <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                                        <label>Product Name</label>
                                                                        <div>
                                                                            <select class="form-control js-example-basic-single" name="product_id" style="width:450px">
                                                                                <option value="">-- Choose Option --</option>
                                                                                @if($products = App\Models\Product::get())
                                                                                    @foreach ($products as $product)
                                                                                        <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                                                                                    @endforeach
                                                                                @endif
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                            </div>
                                        </div>
                                            <div class="row">

                                                <table class="table table-bordered" id="mainTable">
                                        <thead>
                                            <th>BOM Part Name</th>
                                            <th>Unit</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Stages</th>
                                            <th>Add</th>
                                        </thead>
                                        <tbody>
                                            <tr class="mainRow">
                                                <td><input type="text" class="form-control" name="bom_part_name[]" /></td>
                                                <td><input type="text" class="form-control" name="bom_unit[]" /></td>
                                                <td><input type="text" class="form-control" name="quantity[]" /></td>
                                                <td><input type="text" class="form-control" name="price[]" /></td>
                                                <td>
                                                    <table class="table table-bordered stagesTable">
                                                        <thead>
                                                            <tr>
                                                                <th>Stages</th>
                                                                <th><button type="button" class="btn btn-success addStageRow">+</button></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr class="stageRow">
                                                                <td>
                                                                    <select class="form-control" name="process_team[]">
                                                                        <option value="">-- Choose Option --</option>
                                                                        @foreach(App\Models\ProcessTeam::get() as $processTeam)
                                                                            <option value="{{ $processTeam->id }}">{{ $processTeam->team_name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <button type="button" class="btn btn-danger removeStageRow">-</button>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-success addMainRow">+</button>
                                                    <button type="button" class="btn btn-danger removeMaimRow">-</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>


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




    </script>
@endpush
