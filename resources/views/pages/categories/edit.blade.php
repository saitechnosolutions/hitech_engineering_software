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
                                        <h4 class="mt-0 header-title">Categories</h4>


                                    </div>
                                </div>
                                <form id="createFormSubmit" class="form-horizontal" method="POST" action="{{ route('categories.update', $category->id) }}">

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label>Category Name</label>
                                                <div>
                                                    <input type="text" class="form-control" name="name" required
                                                            parsley-type="text" value="{{ $category->name }}" placeholder=""/>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label>Category Code</label>
                                                    <div>
                                                        <input type="text" class="form-control" name="category_code" required
                                                                parsley-type="text" value="{{ $category->category_code }}" placeholder=""/>
                                                    </div>
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
