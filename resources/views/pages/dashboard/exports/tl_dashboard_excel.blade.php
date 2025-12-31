<table>
    <thead>
        <tr>
            <th>Product Details</th>
            <th>BOM Name</th>
            <th>BOM Category</th>
            <th>Unit</th>
            <th>Qty</th>
            <th>Progress</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @if($quotations->count() > 0)
            @foreach($quotations as $quotation)
                @php
                    $batchDetails = App\Models\QuotationBatch::whereJsonContains('quotation_ids', $quotation->id)->first();
                    $quotationCount = App\Models\QuotationProductionStages::with('bom')
                        ->where('quotation_id', $quotation->id)
                        ->whereIn('team_id', $teamIds)
                        ->where('status', 'pending')
                        ->get()
                        ->count();
                @endphp

                @if($quotationCount != 0)
                    @php
                        $quotationHeader = 'Quotation No: ' . $quotation->quotation_no . ' | Batch: ' . (formatDate($batchDetails?->batch_date) ?? 'N/A') . ' | Priority: ' . ($batchDetails?->priority ?? 'N/A') . ' | Customer: ' . ($quotation->customer?->customer_name ?? 'N/A');
                    @endphp

                    @if($quotation->quotationProducts)
                        @foreach($quotation->quotationProducts as $quotationProduct)
                            @php
                                $bomCount = App\Models\QuotationProductionStages::with('bom')
                                    ->where('quotation_id', $quotation->id)
                                    ->where('product_id', $quotationProduct->product_id)
                                    ->whereIn('team_id', $teamIds)
                                    ->where('status', 'pending')
                                    ->get()
                                    ->count();
                            @endphp

                            @if($bomCount != 0)
                                @php
                                    $boms = App\Models\QuotationProductionStages::with('bom')
                                        ->where('quotation_id', $quotation->id)
                                        ->where('product_id', $quotationProduct->product_id)
                                        ->whereIn('team_id', $teamIds)
                                        ->where('status', 'pending')
                                        ->orderBy('id')
                                        ->get();

                                    if ($roleName == 'Team Leader - Packing Team' || $roleName == 'Team Leader - Dispatch Team') {
                                        $list = $boms->unique(function ($item) {
                                            return $item->bom->bom_category;
                                        });
                                    } else {
                                        $list = $boms;
                                    }

                                    $firstBom = true;
                                @endphp

                                @foreach($list as $bom)
                                    <tr>
                                        @if($firstBom)
                                            @php $firstBom = false; @endphp
                                            <td>
                                                {{ $quotationHeader }}<br><br>
                                                <strong>{{ $quotationProduct->product->part_number }}</strong><br>
                                                {{ $quotationProduct->product->product_name }}<br>
                                                Variation: {{ $quotationProduct->product->variation }}<br>
                                                <strong>Qty: {{ $quotationProduct->quantity }}</strong>
                                            </td>
                                        @else
                                            <td></td>
                                        @endif

                                        <td>
                                            @if($roleName == 'Team Leader - Packing Team' || $roleName == 'Team Leader - Dispatch Team')
                                                {{ $bom->bom->bom_category }}
                                            @else
                                                {{ $bom?->bom?->bom_name }}
                                            @endif
                                        </td>

                                        <td>
                                            @if($roleName == 'Team Leader - Packing Team' || $roleName == 'Team Leader - Dispatch Team')
                                                -
                                            @else
                                                {{ $bom?->bom?->bom_category }}
                                            @endif
                                        </td>

                                        <td>
                                            @if($roleName == 'Team Leader - Packing Team' || $roleName == 'Team Leader - Dispatch Team')
                                                -
                                            @else
                                                {{ $bom?->bom?->bom_unit }}
                                            @endif
                                        </td>

                                        <td>
                                            @if($roleName == 'Team Leader - Packing Team' || $roleName == 'Team Leader - Dispatch Team')
                                                @php
                                                    $receivedCompletedQty = App\Models\ProductionHistory::where('quotation_id', $quotation->id)
                                                        ->where('product_id', $quotationProduct->product_id)
                                                        ->where('bom_id', $bom->bom_id)
                                                        ->where('production_type', 'product')
                                                        ->where('team_name', null)
                                                        ->sum('completed_qty');

                                                    $packingQty = App\Models\ProductionHistory::where('quotation_id', $quotation->id)
                                                        ->where('product_id', $quotationProduct->product_id)
                                                        ->where('bom_id', $bom->bom_id)
                                                        ->where('production_type', 'product')
                                                        ->where('team_name', 'packing')
                                                        ->sum('completed_qty');

                                                    $totqty = $bom->bom->bom_qty * $quotationProduct->quantity;
                                                @endphp
                                                R: {{ $receivedCompletedQty }}
                                                A: {{ $packingQty }}
                                                T: {{ $totqty }}
                                            @else
                                                {{ $bom?->bom?->bom_qty }}
                                            @endif
                                        </td>

                                        <td>
                                            @if($roleName == 'Team Leader - Packing Team' || $roleName == 'Team Leader - Dispatch Team')
                                                -
                                            @else
                                                {{ $bom->completed_quantity ?? 0 }} / {{ $bom->bom_required_quantity }}
                                            @endif
                                        </td>

                                        <td>
                                            @php
                                                $statusText = '';
                                                $badgeClass = 'badge-warning';

                                                if ($roleName == 'Team Leader - Packing Team' || $roleName == 'Team Leader - Dispatch Team') {
                                                    $statusText = 'In Progress';
                                                    $badgeClass = 'badge-info';
                                                } elseif ($roleName == 'Team Leader - PIPE BENDING TEAM' || $roleName == 'Team Leader - LASER CUTTING TEAM' || $roleName == 'Team Leader - MACHINE SHOP TEAM') {
                                                    $getStageOneStatus = App\Models\QuotationProductionStages::where('quotation_id', $quotation->id)
                                                        ->where('bom_id', $bom->bom->id)
                                                        ->where('stage', 'stage_1')
                                                        ->first()->production_status ?? null;

                                                    if ($getStageOneStatus == 'completed') {
                                                        $statusText = 'Completed';
                                                        $badgeClass = 'badge-success';
                                                    } else {
                                                        $statusText = 'Pending';
                                                        $badgeClass = 'badge-warning';
                                                    }
                                                } elseif ($roleName == 'Team Leader - MS FABRICATION TEAM' || $roleName == 'Team Leader - SS Fabrication Team' || $roleName == 'Team Leader - FITTING TEAM') {
                                                    $getStageOneStatus = App\Models\QuotationProductionStages::where('quotation_id', $quotation->id)
                                                        ->where('bom_id', $bom->bom->id)
                                                        ->where('stage', 'stage_1')
                                                        ->first()->production_status ?? null;

                                                    $getStageTwoStatus = App\Models\QuotationProductionStages::where('quotation_id', $quotation->id)
                                                        ->where('bom_id', $bom->bom->id)
                                                        ->where('stage', 'stage_2')
                                                        ->first()->production_status ?? null;

                                                    if ($getStageOneStatus == 'completed' && $getStageTwoStatus == 'completed') {
                                                        $statusText = 'Completed';
                                                        $badgeClass = 'badge-success';
                                                    } elseif ($getStageOneStatus == 'completed' && $getStageTwoStatus == 'pending') {
                                                        $statusText = 'Received';
                                                        $badgeClass = 'badge-info';
                                                    } else {
                                                        $statusText = 'Pending';
                                                        $badgeClass = 'badge-warning';
                                                    }
                                                }
                                            @endphp

                                            @if($firstBom && ($roleName == 'Team Leader - MS FABRICATION TEAM' || $roleName == 'Team Leader - SS Fabrication Team' || $roleName == 'Team Leader - FITTING TEAM'))
                                                @if(!empty($quotationProduct->ms_fabrication_emp_id))
                                                    MS: {{ $quotationProduct->msFabricationEmployee?->name }}
                                                @endif
                                                @if(!empty($quotationProduct->ss_fabrication_emp_id))
                                                    SS: {{ $quotationProduct->ssFabricationEmployee?->name }}
                                                @endif
                                            @endif

                                            @if($firstBom && $roleName == 'Team Leader - Packing Team')
                                                @if($quotationProduct->packing_team_accept_status == 0)
                                                    Accept Pending
                                                @else
                                                    Accepted
                                                @endif
                                            @endif

                                            @if($firstBom && $roleName == 'Team Leader - Dispatch Team')
                                                Print Barcode
                                            @endif

                                            @if(!$firstBom || !in_array($roleName, ['Team Leader - MS FABRICATION TEAM', 'Team Leader - SS Fabrication Team', 'Team Leader - FITTING TEAM', 'Team Leader - Packing Team', 'Team Leader - Dispatch Team']))
                                                {{ $statusText }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        @endforeach
                    @endif
                @endif
            @endforeach
        @endif
    </tbody>
</table>