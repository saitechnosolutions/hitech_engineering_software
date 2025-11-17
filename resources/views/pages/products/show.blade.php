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
                                        <h4 class="mt-0 header-title">Show Product</h4>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th style="width:25%;color:#ec1c24">Category Name</th>
                                                <td style="width:25%;">{{ $product->category->name }}</td>
                                                <th style="width:15%;color:#ec1c24">Product Name</th>
                                                <td style="width:35%;">{{ $product->product_name }}</td>
                                            </tr>
                                            <tr>
                                                <th style="width:25%;color:#ec1c24">Product Image</th>
                                                <td style="width:25%;">
                                                    <img src="{{ $product->product_image }}" class="img-thumbnail" style="width:100px">
                                                </td>
                                                <th style="width:15%;color:#ec1c24">Brand</th>
                                                <td style="width:35%;">{{ $product->brand }}</td>
                                            </tr>
                                            <tr>
                                                <th style="width:25%;color:#ec1c24">Bike Model</th>
                                                <td style="width:25%;">
                                                    {{ $product->bike_model }}
                                                </td>
                                                <th style="width:15%;color:#ec1c24">MRP Price</th>
                                                <td style="width:35%;">{{ $product->mrp_price }}</td>
                                            </tr>
                                            <tr>
                                                <th style="width:25%;color:#ec1c24">Part Number</th>
                                                <td style="width:25%;">
                                                    {{ $product->part_number }}
                                                </td>
                                                <th style="width:15%;color:#ec1c24">Quantity</th>
                                                <td style="width:35%;">{{ $product->quantity }}</td>
                                            </tr>
                                            <tr>
                                                <th style="width:25%;color:#ec1c24">Variation</th>
                                                <td style="width:25%;">
                                                    {{ $product->variation }}
                                                </td>
                                                <th style="width:15%;color:#ec1c24">HSN Code</th>
                                                <td style="width:35%;">{{ $product->hsn_code }}</td>
                                            </tr>
                                            <tr>
                                                <th style="width:25%;color:#ec1c24">Stock Quantity</th>
                                                <td style="width:25%;">
                                                    {{ $product->stock_qty }}
                                                </td>
                                                <th style="width:15%;color:#ec1c24">Design Sheet</th>
                                                <td style="width:35%;">
                                                    <a href="{{ $product->design_sheet }}">Show</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="width:25%;color:#ec1c24">Data Sheet</th>
                                                <td style="width:25%;">
                                                    <a href="{{ $product->data_sheet }}">Show</a>
                                                </td>

                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card m-b-30">
                                <div class="card-header">
                                    <div style="display:flex;justify-content:space-between">
                                        <h4 class="mt-0 header-title">BOM Process</h4>
                                    </div>
                                </div>

                                @if($product->bomParts)
                                    @foreach ($product->bomParts as $bomParts)
                                        <div class="card-body">
                                    <div class="accordion" id="accordionExample">
  <div class="card">
    <div class="card-header" style="background-color:beige" id="headingOne">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse{{ $bomParts->id }}" aria-expanded="true" aria-controls="collapseOne">
          <b>{{ $bomParts->bom_name }} / {{ $bomParts->bom_qty }} {{ $bomParts->bom_unit }}</b>
        </button>
      </h2>
    </div>

    <div id="collapse{{ $bomParts->id }}" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
      <div class="card-body">
        <ul>

            @foreach ($bomParts->processTeam as $processTeam)
                @php
                    $processTeamName = App\Models\ProcessTeam::find($processTeam->team_id);
                @endphp
            <li>{{ $processTeamName->team_name ?? null }}</li>
        @endforeach
        </ul>
      </div>
    </div>
  </div>

</div>
                                </div>
                                    @endforeach
                                @endif

                            </div>
                        </div>
                    </div>
@endsection


