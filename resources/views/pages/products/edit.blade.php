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
                    <h4 class="mt-0 header-title">Products</h4>


                </div>
            </div>
            <form id="createFormSubmit" class="form-horizontal m-t-10" method="POST" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')


                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>Category Name</label>
                                <div>
                                    <select class="form-control" name="category_id">
                                        <option value="">-- Choose Category --</option>
                                        @if($categories = App\Models\Category::get())
                                        @foreach ($categories as $categorie)
                                        <option value="{{ $categorie->id }}"
                                            {{ $product->category_id == $categorie->id ? 'selected' : '' }}>
                                            {{ $categorie->name }}
                                        </option>

                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>Product Name</label>
                                <div>
                                    <input type="text" class="form-control" required
                                        parsley-type="text" name="product_name" value="{{ $product->product_name }}" placeholder="" />
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>Product Image</label>
                                <div>
                                    <input type="file" class="form-control" name="product_image">

                                    @if($product->product_image)
                                    <img src="{{ asset($product->product_image) }}"
                                        width="80" class="mt-2">
                                    @endif

                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>Brand</label>
                                <div>
                                    <input type="text" class="form-control" required
                                        parsley-type="text" name="brand" value="{{ $product->brand }}" placeholder="" />
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>Bike Model</label>
                                <div>
                                    <input type="text" class="form-control" required
                                        parsley-type="text" name="bike_model" value="{{ $product->bike_model }}" placeholder="" />
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>MRP Price</label>
                                <div>
                                    <input type="text" class="form-control" required
                                        parsley-type="text" name="mrp_price" value="{{ $product->mrp_price }}" placeholder="" />
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>Part Number</label>
                                <div>
                                    <input type="text" class="form-control" required
                                        parsley-type="text" name="part_number" value="{{ $product->part_number }}" placeholder="" />
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>Quantity</label>
                                <div>
                                    <input type="text" class="form-control" required
                                        parsley-type="text" name="quantity" value="{{ $product->quantity }}" placeholder="" />
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>Variation</label>
                                <div>
                                    <input type="text" class="form-control" required
                                        parsley-type="text" name="variation" value="{{ $product->variation }}" placeholder="" />
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>HSN/SAC Code</label>
                                <div>
                                    <input type="text" class="form-control" required
                                        parsley-type="text" name="hsn_code" value="{{ $product->hsn_code }}" placeholder="" />
                                </div>
                            </div>
                        </div>

                        {{-- <div class="col-lg-3">
                     <div class="form-group">
                                        <label>Stock Qty</label>
                                        <div>
                                            <input type="text" class="form-control" required
                                                    parsley-type="text" name="stock_qty" value="{{ $product->stock_qty }}" placeholder=""/>
                    </div>
                </div>
        </div> --}}
        <div class="col-lg-3">
            <div class="form-group">
                <label>Color</label>
                <div>
                    <input type="text" class="form-control" required
                        parsley-type="text" name="color" value="{{ $product->color }}" placeholder="" />
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="form-group">
                <label>Material</label>
                <div>
                    <input type="text" class="form-control" required
                        parsley-type="text" name="material" value="{{ $product->material }}" placeholder="" />
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="form-group">
                <label>Design Sheet</label>
                <div>
                    <input type="file" class="form-control" name="design_sheet">

                    @if($product->design_sheet)
                    <div class="mt-2">
                        <a href="{{ asset($product->design_sheet) }}" target="_blank" class="btn btn-sm btn-info">
                            View Existing Design Sheet
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="form-group">
                <label>Data Sheet</label>
                <div>
                    <input type="file" class="form-control" name="data_sheet">

                    @if($product->data_sheet)
                    <div class="mt-2">
                        <a href="{{ asset($product->data_sheet) }}" target="_blank" class="btn btn-sm btn-info">
                            View Existing Design Sheet
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">


            <div class="card">
                <h5 class="card-header">BOM Details</h5>
                <div class="card-body">
                    <table class="table table-bordered w-100" id="mainTable">
                        <thead>
                            <th>BOM Category</th>
                            <th>BOM Part Name</th>
                            <th>Unit</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Stages</th>
                            <th>Add</th>
                        </thead>


                        <tbody>
                            @forelse($boms as $key => $bom)

                            <tr class="mainRow">
                                <td style="width:300px;padding:0px 10px">
                                    <select class="form-control mb-2 bomtype" name="bomType[]">
                                        <option value="1" {{ $bom->bom_type == 'production' ? 'selected' : '' }}>Production</option>
                                        <option value="2" {{ $bom->bom_type == 'packing' ? 'selected' : '' }}>Packing</option>
                                    </select>

                                    <input type="text" class="form-control production_bom"
                                        name="bom_category[]" value="{{ $bom->bom_category }}"
                                        style="{{ $bom->bom_type == 'production' ? '' : 'display:none' }}">

                                    <select class="form-control packing_bom"
                                        name="packing_bom[]"
                                        style="{{ $bom->bom_type == 'packing' ? '' : 'display:none' }}">
                                        <option value="">-- Choose Packing BOM --</option>
                                        @foreach($packingBoms as $pb)
                                        <option value="{{ $pb->id }}"
                                            {{ $bom->production_bom_id == $pb->id ? 'selected' : '' }}>
                                            {{ $pb->component_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </td>

                                <td><input type="text" name="bom_part_name[]" value="{{ $bom->bom_name }}" class="form-control"></td>
                                <td><input type="text" name="bom_unit[]" value="{{ $bom->bom_unit }}" class="form-control"></td>
                                <td><input type="text" name="bom_quantity[]" value="{{ $bom->bom_qty }}" class="form-control"></td>
                                <td><input type="text" name="price[]" value="{{ $bom->bom_price }}" class="form-control"></td>

                                <td style="padding:0px 10px">
                                    <table class="table table-bordered stagesTable">
                                        <thead>
                                            <tr>
                                                <th>Stages</th>
                                                <th>
                                                    <button type="button" class="btn btn-success btn-sm addStageRow">+</button>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                           
                                            @if($bom->processTeam->count())
                                            @foreach($bom->processTeam as $stageIndex => $team)
                                            <tr class="stageRow">
                                                <td style="padding:0px">
                                                    <select class="form-control" name="process_team[{{ $key }}][]">
                                                        <option value="">-- Choose Option --</option>
                                                        @foreach(App\Models\ProcessTeam::get() as $pt)
                                                        <option value="{{ $pt->id }}"
                                                            {{ $team->team_id == $pt->id ? 'selected' : '' }}>
                                                            {{ $pt->team_name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm removeStageRow">-</button>
                                                </td>
                                            </tr>
                                            @endforeach

                                            @else
                                            <tr class="stageRow">
                                                <td style="padding:0px">
                                                    <select class="form-control" name="process_team[{{ $key }}][]">
                                                        <option value="">-- Choose Option --</option>
                                                        @foreach(App\Models\ProcessTeam::get() as $pt)
                                                        <option value="{{ $pt->id }}">{{ $pt->team_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm removeStageRow">-</button>
                                                </td>
                                            </tr>
                                            @endif

                                        </tbody>
                                    </table>
                                </td>


                                <td>
                                    <button type="button" class="btn btn-success btn-sm addMainRow">+</button>
                                    <button type="button" class="btn btn-danger btn-sm removeMaimRow">-</button>
                                </td>
                            </tr>

                            @empty

                            <tr class="mainRow">
                                <td style="width:300px;padding:0px 10px">
                                    <select class="form-control mb-2 bomtype" name="bomType[]">
                                        <option value="">-- Choose Type --</option>
                                        <option value="1">Production</option>
                                        <option value="2">Packing</option>
                                    </select>

                                    <input type="text" class="form-control production_bom" name="bom_category[]">

                                    <select class="form-control packing_bom" name="packing_bom[]" style="display:none">
                                        <option value="">-- Choose Packing BOM --</option>
                                        @foreach($packingBoms as $pb)
                                        <option value="{{ $pb->id }}">{{ $pb->component_name }}</option>
                                        @endforeach
                                    </select>
                                </td>

                                <td><input type="text" name="bom_part_name[]" class="form-control"></td>
                                <td><input type="text" name="bom_unit[]" class="form-control"></td>
                                <td><input type="text" name="bom_quantity[]" class="form-control"></td>
                                <td><input type="text" name="price[]" class="form-control"></td>

                                <td style="padding:0px 10px">
                                    <table class="table table-bordered stagesTable">
                                        <thead>
                                            <tr>
                                                <th>Stages</th>
                                                <th>
                                                    <button type="button" class="btn btn-success btn-sm addStageRow">+</button>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="stageRow">
                                                <td style="padding:0px">
                                                    <select class="form-control" name="process_team[0][]">
                                                        <option value="">-- Choose Option --</option>
                                                        @foreach(App\Models\ProcessTeam::get() as $pt)
                                                        <option value="{{ $pt->id }}">{{ $pt->team_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm removeStageRow">-</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>

                                <td>
                                    <button type="button" class="btn btn-success btn-sm addMainRow">+</button>
                                    <button type="button" class="btn btn-danger btn-sm removeMaimRow">-</button>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>

                    </table>
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
    $(document).on("click", ".addMainRow", function() {
        var rowCount = $("#mainTable > tbody > tr.mainRow").length; // only main rows
        var newRow = $("#mainTable > tbody > tr.mainRow:first").clone(true, true);


        newRow.find("input[type='text']").val("");
        newRow.find("select").val("");

        // reset stagesTable inside this new main row
        var stageTable = newRow.find(".stagesTable");
        stageTable.find("tbody tr:gt(0)").remove(); // keep only first stage row
        stageTable.find("select").val(""); // reset dropdown
        stageTable.find("select").attr("name", "process_team[" + rowCount + "][]");

        // ✅ append only to the mainTable tbody, not anywhere else
        $("#mainTable > tbody").append(newRow);
    });

    $(document).on("click", ".addStageRow", function() {
        var stageTable = $(this).closest(".stagesTable");
        var newStageRow = stageTable.find("tbody tr.stageRow:first").clone();
        newStageRow.find("select").val("");
        stageTable.find("tbody").append(newStageRow);
    });

    $(document).on("click", ".removeStageRow", function() {
        var row = $(this).closest("tr");
        if (row.siblings().length > 0) {
            row.remove();
        }
    });

    $(".bom_category").hide();
    $(".production_bom").hide();
    $(".packing_bom").hide();



    $(document).on("change", ".bomtype", function() {

        var bomType = $(this).val();
        var row = $(this).closest(".mainRow"); // Get this row

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

    $(document).on("click", ".removeMaimRow", function () {
    var row = $(this).closest("tr.mainRow");

    // Prevent removing last row
    if ($("#mainTable tbody tr.mainRow").length > 1) {
        row.remove();
    }
});


</script>



@endpush