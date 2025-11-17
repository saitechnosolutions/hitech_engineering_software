 <div class="row">
        <div class="col-lg-12">
            <div class="accordion" id="accordionExample">

                @if($quotations)
                @php
                    $i=1;
                @endphp
                    @foreach ($quotations as $quotation)

                        @php
                            $batchDetails = App\Models\QuotationBatch::whereJsonContains('quotation_ids', $quotation->id)->first();
                            $quotationCount = App\Models\QuotationProductionStages::with('bom')->where('quotation_id', $quotation->id)->whereIn('team_id', $teamIds)->where('status', 'pending')->get()->count();
                        @endphp

                @if($quotationCount != 0)
                        <div class="card">
    <div class="card-header" id="headingOne" style="background-color:#ffd2d4">
      <h2 class="mb-0">
        <button class="btn btn-link accordion-button" style="font-size:18px;color:#ec1c24" type="button" data-toggle="collapse"
                data-target="#collapse{{ $quotation->id }}" aria-expanded="true" aria-controls="collapseOne">
                Quotation No : {{ $quotation?->quotation_no }} / Batch : {{ formatDate($batchDetails?->batch_date) }} / Priority : {{ $batchDetails?->priority }} / Customer : {{ $quotation?->customer?->customer_name }}
        </button>
      </h2>
    </div>

    <div id="collapse{{ $quotation->id }}" class="collapse @if($i++ ==1) show @endif" aria-labelledby="headingOne" data-parent="#accordionExample">
      <div class="container-fluid p-0 m-0 w-100">
        <div class="row">
<div class="col-lg-12">
                    <ul class="list-group">
  <li class="list-group-item active bg-success" style="font-size:18px;color:#fff;" aria-current="true">
    <div class="row">
        <div class="col-lg-4">Product Details</div>
        <div class="col-lg-6">BOM Details</div>
        <div class="col-lg-2">Status</div>
    </div>
  </li>
                @if($quotation->quotationProducts)

                    @foreach ($quotation->quotationProducts as $quotationProduct)
                    @php
                $bomCount = App\Models\QuotationProductionStages::with('bom')->where('quotation_id', $quotation->id)->where('product_id', $quotationProduct->product_id)->whereIn('team_id', $teamIds)->where('status', 'pending')->get()->count();
            @endphp


                @if($bomCount != 0)

  <li class="list-group-item ">
    <div class="row">
        <div class="col-lg-4">
            <div style="display:flex">
                @php
                    $productImage = '';
                    if($quotationProduct?->product?->product_image == null)
                    {
                        $productImage = '/assets/images/No-Product-Image-Available.webp';
                    }
                    else
                    {
                        $productImage = $quotationProduct?->product?->product_image;
                    }
                @endphp
                <img src="{{ $productImage }}" class="img-fluid" style="width:65px">
                <div class="p-2" style="line-height:1.3;margin-left:15px;font-size:13px">
                    <span>{{ $quotationProduct->product->part_number }}</span><br>
                    <span>{{ $quotationProduct->product->product_name }}</span> / <span>{{ $quotationProduct->product->variation }}</span><br>
                    <a href="{{ $quotationProduct->product->data_sheet }}" download>DATA SHEET</a><br>
                    <span style="color:#ec1c24"><b>Product Qty :</b> {{ $quotationProduct->quantity }}</span>
                </div>
            </div>

        </div>
        <div class="col-lg-6">
            @php
                $allocatedTeams = App\Models\QuotationProductionStages::where('product_id', $quotationProduct->product_id)->groupBy('team_id')->pluck('team_id');
                $teamNames = App\Models\ProcessTeam::whereIn('id', $allocatedTeams)->get();
            @endphp
            {{--  @if($boms = App\Models\QuotationProductionStages::with('bom')->where('quotation_id', $quotation->id)->where('product_id', $quotationProduct->product_id)->whereIn('team_id', $teamIds)->where('status', 'pending')->get())
            <ul class="p-0" style="display:flex;flex-wrap: wrap">
                @foreach ($boms as $bom)
                <li style="display:flex;padding:10px;font-size:13px;border-radius:50px;">
                    {{ $bom?->bom?->bom_name }}
                </li>
                @endforeach
            </ul>
            @endif  --}}
            @if($boms = App\Models\QuotationProductionStages::with('bom')
        ->where('quotation_id', $quotation->id)
        ->where('product_id', $quotationProduct->product_id)
        ->whereIn('team_id', $teamIds)
        ->where('status', 'pending')
        ->get())

    @php

        if($roleName == 'Team Leader - Packing Team' || $roleName == 'Team Leader - Dispatch Team'){

            $list = $boms->unique(function($item){
                return $item->bom->bom_category;
            });
        } else {
            $list = $boms;
        }
    @endphp

    <ul class="p-0" style="display:flex;flex-wrap: wrap;list-style-type:circle">
        @foreach ($list as $bom)
            <li style="display:flex;padding:10px;font-size:13px;border-radius:50px;">
                @if($roleName == 'Team Leader - Packing Team' || $roleName == 'Team Leader - Dispatch Team')
                    {{ $bom?->bom?->bom_category }}
                    ({{ $bom?->bom->bom_qty *  $quotationProduct->quantity}} Qty)
                    @php
$stageValue = $bom->stage;

$stage = str_replace("stage_", "", $bom->stage);

$bomProductionStage = App\Models\QuotationProductionStages::with('bom')
    ->where('quotation_id', $quotation->id)
    ->where('product_id', $quotationProduct->product_id)
    ->where('bom_id', $bom?->bom->id)
    ->where('stage', "stage_".$stage-1)
    ->first();
@endphp
                    @if($bomProductionStage?->production_status == "completed") &nbsp;<pre class="badge badge-success"> Received</pre> @else  &nbsp;<pre class="badge badge-warning"> Pending</pre> @endif
                @else
               @php
$stageValue = $bom->stage;

$stage = str_replace("stage_", "", $bom->stage);

$bomProductionStage = App\Models\QuotationProductionStages::with('bom')
    ->where('quotation_id', $quotation->id)
    ->where('product_id', $quotationProduct->product_id)
    ->where('bom_id', $bom?->bom->id)
    ->where('stage', "stage_".$stage-1)
    ->first();
@endphp

                    {{ $bom?->bom?->bom_name }} - {{ $bom->stage }} @if($bomProductionStage?->production_status == "completed") &nbsp;<pre class="badge badge-success"> Received</pre> @else  &nbsp;<pre class="badge badge-warning"> Pending</pre> @endif
                @endif
            </li>
        @endforeach
    </ul>
@endif

        </div>
        <div class="col-lg-2 text-center">

                @if($roleName == 'Team Leader - MS FABRICATION TEAM' || $roleName == 'Team Leader - SS Fabrication Team')
                        <button class="btn btn-primary btn-sm allocateEmployee mb-1" style="width:170px;" data-quotationid="{{ $quotation->id }}" data-productid="{{ $quotationProduct->product_id }}" data-bom-id="{{ $bom->bom_id }}" data-stage="{{ $bom->stage }}" ><i class="fa fa-user-plus" aria-hidden="true"></i> Employee</button>
                @endif

                 @if($roleName == 'Team Leader - Dispatch Team')
                    <button class="btn btn-primary btn-sm completeProduct btn-sm" style="width:170px" data-quotationid="{{ $quotation->id }}" data-productid="{{ $quotationProduct->product_id }}" data-bom-id="{{ $bom->bom_id }}" data-stage="{{ $bom->stage }}" > Complete Product</button>
                    @else
                    <button class="btn btn-primary btn-sm statusUpdate btn-sm" style="width:170px" data-quotationid="{{ $quotation->id }}" data-productid="{{ $quotationProduct->product_id }}" data-bom-id="{{ $bom->bom_id }}" data-stage="{{ $bom->stage }}" ><i class="fa fa-wrench" aria-hidden="true"></i> Update</button>
                 @endif


            @if($roleName == 'Team Leader - Packing Team')
                <br><a href="/invoice-request/{{ $quotation->id }}" class="btn-sm btn btn-primary btn-sm mt-2" style="width:170px"><i class="fa fa-paper-plane" aria-hidden="true"></i> &nbsp;&nbsp;Invoice Request</a>
            @endif

            @if($roleName == 'Team Leader - MS FABRICATION TEAM')
                @if(!empty($quotationProduct->ms_fabrication_emp_id))
                    <span class="badge bg-warning w-100">MS Team : {{ $quotationProduct?->msFabricationEmployee?->name }}</span>
                @endif
            @endif

            @if($roleName == 'Team Leader - SS Fabrication Team')
                @if(!empty($quotationProduct->ss_fabrication_emp_id))
                    <span class="badge bg-warning w-100">SS Team : {{ $quotationProduct?->ssFabricationEmployee?->name }}</span>
                @endif
            @endif

        </div>
    </div>
</li>


                    {{--  <div class="card m-4">
                    <h6 class="card-header m-0" style="background-color:bisque">{{ $quotationProduct->product->product_name }} / Quantity : {{ $quotationProduct->quantity }} / {{ $quotationProduct->product->variation }}</h6>
                    <div class="card-body">


                        <div class="row">
                             <div class="col-md-12 col-lg-12 col-xl-12">
                        <div class="card m-b-30 shadow-none">
                            <div class="card-body p-0">
                                <div class="d-flex flex-row">
                                    <div class="col-12 align-self-center text-center">
                                        <div class="row">

                                        </div>

                                        <div class="table-responsive">


                                        <table class="table table-bordered table-success">
                                            <thead class="bg-success text-white">
                                                <tr >
                                                    <th>BOM Name</th>
                                                    <th>Required Quantity</th>
                                                    <th>Completed Quantity</th>
                                                    <th>Need to achieve</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>

                                            <tbody>

                                                @if($boms = App\Models\QuotationProductionStages::with('bom')->where('quotation_id', $quotation->id)->where('product_id', $quotationProduct->product_id)->whereIn('team_id', $teamIds)->where('status', 'pending')->get())

                                                    @foreach ($boms as $bom)
                                                    <tr>
                                                    <td>{{ $bom?->bom?->bom_name }}</td>
                                                    <td>{{ $bom?->bom_required_quantity }}</td>
                                                    <td>{{ $bom?->completed_quantity ?? 0 }}</td>
                                                    <td>{{ $bom?->bom_required_quantity - $bom?->completed_quantity }}</td>
                                                    <td>
                                                        @php
                                                            $requiredQty = $bom?->bom_required_quantity - $bom?->completed_quantity;
                                                        @endphp
                                                        @if($requiredQty != 0)
                                                            <button class="btn btn-dark addProductionQty" data-id="{{ $bom->id }}" data-completedqty="{{ $bom->completed_quantity }}">Add</button>
                                                            @else
                                                            <span class="badge bg-info">Target Achieved</span>
                                                        @endif

                                                        <button class="btn btn-danger showProductionHistory" data-id="{{ $bom->id }}">Show</button>
                                                    </td>
                                                </tr>
                                                    @endforeach
                                                @endif

                                            </tbody>
                                        </table>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                        </div>
                    </div>
                </div>  --}}

            @endif
                    @endforeach
                    </div>
                    </ul>
                @endif

        </div>
      </div>
    </div>
  </div>


                @endif

                    @endforeach
                @endif

</div>
        </div>
</div>
