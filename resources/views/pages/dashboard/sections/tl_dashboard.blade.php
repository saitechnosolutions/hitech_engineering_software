
 <div class="row">
        <div class="col-lg-12">

            <div class="accordion" id="accordionExample">

                @php
                    $userId = Auth::user()->id;
                    if($roleName == 'Team Leader - Dispatch Team')
                    {
                        $quotations = $quotations->where('dispatch_team_id', $userId);
                    }
                    else
                    {
                        $quotations = $quotations;
                    }
                    @endphp

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

        <div class="d-flex" style="justify-content: space-between">
            <h2 class="mb-0">
                <button class="btn btn-link accordion-button" style="font-size:18px;color:#ec1c24" type="button" data-toggle="collapse"
                        data-target="#collapse{{ $quotation->id }}" aria-expanded="true" aria-controls="collapseOne">
                        Quotation No : {{ $quotation?->quotation_no }} / Batch : {{ formatDate($batchDetails?->batch_date) }} / Priority : {{ $batchDetails?->priority }} / Customer : {{ $quotation?->customer?->customer_name }}
                </button>
            </h2>

            @if($roleName == 'Team Leader - Packing Team' )
                <div class="btn-group dropleft">
                <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-sliders" aria-hidden="true"></i>
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="/invoice-request/{{ $quotation?->id }}"><i class="fa fa-paper-plane" aria-hidden="true"></i> &nbsp;&nbsp; Invoice Request</a>
                    <a class="dropdown-item allocateDispatchTeam" data-quotationid="{{ $quotation?->id }}"><i class="fa fa-user" aria-hidden="true"></i> &nbsp;&nbsp; Allocate Dispatch Team</a>
                </div>
                </div>
            @endif
                                    <!-- Camera Modal -->
<div id="cameraModal" style="display:none; z-index:1111;position:fixed; top:0; left:0; width:100%; height:100%; background:#000000a0; justify-content:center; align-items:center;">
    <div style="background:white; padding:20px; border-radius:10px;">
        <video id="cameraStream" width="400" autoplay></video>
        <br>
        <button class="btn btn-warning" id="captureBtn">Capture</button>
        <button class="btn btn-danger" onclick="closeCamera()">Close</button>
    </div>
</div>
                @if($roleName == 'Team Leader - Dispatch Team' )
                <div class="btn-group dropleft">
                <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-sliders" aria-hidden="true"></i>
                </button>
                <div class="dropdown-menu">

<canvas id="cameraCanvas" width="400" height="300" style="display:none;"></canvas>

<form id="captureForm" action="{{ route('quotation.capture.photo') }}" method="POST">
    @csrf
    <input type="hidden" name="quotation_id" id="quotation_id">
    <input type="hidden" name="image_data" id="capturedImage">
</form>


                        <a class="dropdown-item takePhoto" data-quotationid="{{ $quotation?->id }}" ><i class="fa fa-camera" aria-hidden="true"></i> &nbsp;&nbsp; Capture Photo</a>

                        {{--  <a class="dropdown-item productDispatched" data-quotationid="{{ $quotation?->id }}" ><i class="fa fa-paper-plane" aria-hidden="true"></i> &nbsp;&nbsp; Quotation Dispatched</a>  --}}
                        <a class="dropdown-item dispatchModal"  data-quotationid="{{ $quotation?->id }}"><i class="fa fa-paper-plane" aria-hidden="true"></i> &nbsp;&nbsp; Quotation Dispatched</a>


                </div>
                </div>
                @endif

        </div>

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
        ->orderBy('id')
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
            <li style="display:flex; padding:10px; font-size:11px; border-radius:10px;
           align-items:center; gap:6px; background:#def2ff;margin:5px;font-weight:500">

           @if($roleName == 'Team Leader - Packing Team' || $roleName == 'Team Leader - Dispatch Team')
                    {{ $bom?->bom?->bom_category }}
                    @php
        echo $bom->bom_id;
                        $receivedCompletedQty = App\Models\ProductionHistory::where('quotation_id', $quotation->id)->where('product_id', $quotationProduct->product_id)->where('bom_id', $bom->bom_id)->where('production_type', 'product')->where('team_name', null)->sum('completed_qty');
                        $packingQty = App\Models\ProductionHistory::where('quotation_id', $quotation->id)->where('product_id', $quotationProduct->product_id)->where('bom_id', $bom->bom_id)->where('production_type', 'product')->where('team_name', 'packing')->sum('completed_qty');
                        $totqty = $bom?->bom->bom_qty * $quotationProduct->quantity;

                         $color = ($receivedCompletedQty == $totqty) ? 'green' : 'red';
                    @endphp

                        <div style="background-color:{{ $color }};padding:2px 5px;border-radius:5px;color:white"><span>R : {{ $receivedCompletedQty }} / A : {{ $packingQty }}</span> / <span > T : {{ $totqty }}</span> Qty</div>
                    @php
                        $stageValue = $bom->stage;

                        $stage = str_replace("stage_", "", $bom->stage);

                        $bomProductionStage = App\Models\QuotationProductionStages::with('bom')
                            ->where('quotation_id', $quotation->id)
                            ->where('product_id', $quotationProduct->product_id)
                            ->where('bom_id', $bom?->bom->id)
                            ->first();
                        @endphp

                        @php
                        $getStageOneStatus = $bomProductionStage->where('quotation_id', $quotation->id)->where('bom_id', $bom->bom->id)->where('stage', 'stage_1')->first()->production_status ?? null;
                        $getStageTwoStatus = $bomProductionStage->where('quotation_id', $quotation->id)->where('bom_id', $bom->bom->id)->where('stage', 'stage_2')->first()->production_status ?? null;
                        $getStageThreeStatus = $bomProductionStage->where('quotation_id', $quotation->id)->where('bom_id', $bom->bom->id)->where('stage', 'stage_3')->first()->production_status ?? null;
                        $getStageFourStatus = $bomProductionStage->where('quotation_id', $quotation->id)->where('bom_id', $bom->bom->id)->where('stage', 'stage_4')->first()->production_status ?? null;
                    @endphp


                            {{--  @if($roleName == 'Team Leader - Packing Team')

                                @if($getStageTwoStatus == 'completed' && ($getStageThreeStatus == 'pending' || $getStageOneStatus == 'pending'))
                                    <pre class="badge badge-info"> Received</pre>
                                        @elseif($getStageTwoStatus == 'completed' && ($getStageOneStatus == 'completed' || $getStageThreeStatus == 'completed'))
                                    <pre class="badge badge-success"> Completed</pre>
                                    @else
                                    <pre class="badge badge-warning"> Pending</pre>
                                 @endif
                                @endif  --}}

                    {{--  @if($bomProductionStage?->production_status == "completed") &nbsp;<pre class="badge badge-info"> Received</pre> @else  &nbsp;<pre class="badge badge-warning"> Pending</pre> @endif  --}}
                @else
                        @php
                    $stageValue = $bom->stage;
                    $stage = str_replace("stage_", "", $bom->stage);
                    $status = "";

                    $query = App\Models\QuotationProductionStages::with('bom')
                        ->where('quotation_id', $quotation->id)
                        ->where('product_id', $quotationProduct->product_id)
                        ->where('bom_id', $bom?->bom->id);

                    $bomProductionStage = $query->first();

                    @endphp

                    {{ $bom?->bom?->bom_name }} / {{ $bom?->bom?->bom_unit }} / {{ $bom?->bom?->bom_qty }} Qty - &nbsp;<span style="color:red">QTY : {{ $bom?->completed_quantity ?? 0 }} / {{ $bom->bom_required_quantity }}</span>

                    @php

                        $getStageOneStatus = $bomProductionStage->where('quotation_id', $quotation->id)->where('bom_id', $bom->bom->id)->where('stage', 'stage_1')->first()->production_status;
                        $getStageTwoStatus = $bomProductionStage->where('quotation_id', $quotation->id)->where('bom_id', $bom->bom->id)->where('stage', 'stage_2')->first()->production_status;
                    @endphp


                            @if($roleName == 'Team Leader - PIPE BENDING TEAM' || $roleName == 'Team Leader - LASER CUTTING TEAM' || $roleName == 'Team Leader - MACHINE SHOP TEAM')
                                @if($getStageOneStatus == 'completed')
                                    <pre class="badge badge-success"> Completed</pre>
                                    @else
                                    <pre class="badge badge-warning"> Pending</pre>
                                @endif
                            @endif

                            @if($roleName == 'Team Leader - MS FABRICATION TEAM' || $roleName == 'Team Leader - SS Fabrication Team' || $roleName == 'Team Leader - FITTING TEAM')
                                @if($getStageOneStatus == 'completed' && $getStageTwoStatus == 'pending')
                                    <pre class="badge badge-info"> Received</pre>
                                        @elseif($getStageOneStatus == 'completed' && $getStageTwoStatus == 'completed')
                                    <pre class="badge badge-success"> Received</pre>
                                    @else
                                    <pre class="badge badge-warning"> Pending</pre>
                                 @endif
                                @endif


                @endif
            </li>
        @endforeach
    </ul>
@endif

        </div>
        <div class="col-lg-2 text-center">

                @if($roleName == 'Team Leader - MS FABRICATION TEAM' || $roleName == 'Team Leader - SS Fabrication Team' || $roleName == 'Team Leader - FITTING TEAM')
                        <button class="btn btn-primary btn-sm allocateEmployee mb-1" style="width:170px;" data-quotationid="{{ $quotation->id }}" data-productid="{{ $quotationProduct->product_id }}" data-bom-id="{{ $bom->bom_id }}" data-stage="{{ $bom->stage }}" ><i class="fa fa-user-plus" aria-hidden="true"></i> Employee</button>
  {{--  <button class="btn btn-warning btn-sm addProductionQty btn-sm" data-datatype="product" style="width:170px" data-quotationid="{{ $quotation->id }}" data-quantity="{{ $quotationProduct->quantity }}" data-productid="{{ $quotationProduct->product_id }}" ></i> Update</button>  --}}
  <button class="btn btn-warning btn-sm addProductionQty btn-sm" data-datatype="product" style="width:170px" data-quotationid="{{ $quotation->id }}" data-quantity="{{ $quotationProduct->quantity }}" data-productid="{{ $quotationProduct->product_id }}" ></i> Accept</button>
                @endif

                @if($roleName == 'Team Leader - Packing Team')
                    @if($quotationProduct->packing_team_accept_status == 0)
                            <button class="btn btn-warning btn-sm addProductionQty btn-sm" data-datatype="product" data-team="packing" style="width:170px" data-quotationid="{{ $quotation->id }}" data-quantity="{{ $quotationProduct->quantity }}" data-productid="{{ $quotationProduct->product_id }}" ></i> Accept</button>
                    @endif
                @endif
                 @if($roleName == 'Team Leader - Dispatch Team')
                    {{--  <button class="btn btn-primary btn-sm completeProduct btn-sm" style="width:170px" data-quotationid="{{ $quotation->id }}" data-productid="{{ $quotationProduct->product_id }}" data-bom-id="{{ $bom->bom_id }}" data-stage="{{ $bom->stage }}" > Done</button>  --}}
                    <a href="/barcode-print/{{ $quotationProduct->product_id }}" class="btn btn-primary" style="width:170px;margin-top:10px"><i class="fa fa-print" aria-hidden="true"></i> &nbsp;&nbsp; Print</a>
                    @else
                    {{--  <button class="btn btn-primary btn-sm statusUpdate btn-sm mt-2" style="width:170px" data-quotationid="{{ $quotation->id }}" data-productid="{{ $quotationProduct->product_id }}" data-bom-id="{{ $bom->bom_id }}" data-stage="{{ $bom->stage }}" ><i class="fa fa-check" aria-hidden="true"></i> Update</button>  --}}
                 @endif

                @if($roleName == 'Team Leader - PIPE BENDING TEAM')
                    <button class="btn btn-warning btn-sm addProductionQty btn-sm" data-datatype="bom" style="width:170px" data-quotationid="{{ $quotation->id }}" data-quantity="{{ $quotationProduct->quantity }}" data-productid="{{ $quotationProduct->product_id }}" ></i> Update</button>
                @endif
                @if($roleName == 'Team Leader - LASER CUTTING TEAM')
                    <button class="btn btn-warning btn-sm addProductionQty btn-sm" data-datatype="bom" style="width:170px" data-quotationid="{{ $quotation->id }}" data-quantity="{{ $quotationProduct->quantity }}" data-productid="{{ $quotationProduct->product_id }}" ></i> Update</button>
                @endif
                @if($roleName == 'Team Leader - MACHINE SHOP TEAM')
                    <button class="btn btn-warning btn-sm addProductionQty btn-sm" data-datatype="bom" style="width:170px" data-quotationid="{{ $quotation->id }}" data-quantity="{{ $quotationProduct->quantity }}" data-productid="{{ $quotationProduct->product_id }}" ></i> Update</button>
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


@else


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

 @include('pages.dashboard.modals.partial_dispatch_modal')
