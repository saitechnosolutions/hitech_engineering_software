<?php

namespace App\Exports;

use App\Models\QuotationBatch;
use App\Models\QuotationProductionStages;
use App\Models\ProductionHistory;

class TlDashboardExport
{
    protected $quotations;
    protected $roleName;
    protected $teamIds;

    public function __construct($quotations, $roleName, $teamIds)
    {
        $this->quotations = $quotations;
        $this->roleName = $roleName;
        $this->teamIds = $teamIds;
    }

    public function getData(): array
    {
        $data = [];

        // Add header rows
        $data[] = ['Hi Tech Solutions'];
        $data[] = ['Generated Date:', date('d-m-Y H:i:s')];
        $data[] = []; // Empty row for spacing
        $data[] = ['Product Details', 'BOM Name', 'BOM Category', 'Unit', 'Qty'];

        if ($this->quotations->count() > 0) {
            foreach ($this->quotations as $quotation) {
                $batchDetails = QuotationBatch::whereJsonContains('quotation_ids', $quotation->id)->first();
                $quotationCount = QuotationProductionStages::with('bom')
                    ->where('quotation_id', $quotation->id)
                    ->whereIn('team_id', $this->teamIds)
                    ->where('status', 'pending')
                    ->get()
                    ->count();

                if ($quotationCount != 0) {
                    $quotationHeader = 'Quotation No: ' . $quotation->quotation_no . ' | Batch: ' . (formatDate($batchDetails?->batch_date) ?? 'N/A') . ' | Priority: ' . ($batchDetails?->priority ?? 'N/A') . ' | Customer: ' . ($quotation->customer?->customer_name ?? 'N/A');

                    if ($quotation->quotationProducts) {
                        foreach ($quotation->quotationProducts as $quotationProduct) {
                            $bomCount = QuotationProductionStages::with('bom')
                                ->where('quotation_id', $quotation->id)
                                ->where('product_id', $quotationProduct->product_id)
                                ->whereIn('team_id', $this->teamIds)
                                ->where('status', 'pending')
                                ->get()
                                ->count();

                            if ($bomCount != 0) {
                                $boms = QuotationProductionStages::with('bom')
                                    ->where('quotation_id', $quotation->id)
                                    ->where('product_id', $quotationProduct->product_id)
                                    ->whereIn('team_id', $this->teamIds)
                                    ->where('status', 'pending')
                                    ->orderBy('id')
                                    ->get();

                                if ($this->roleName == 'Team Leader - Packing Team' || $this->roleName == 'Team Leader - Dispatch Team') {
                                    $list = $boms->unique(function ($item) {
                                        return $item->bom->bom_category;
                                    });
                                } else {
                                    $list = $boms;
                                }

                                $firstBom = true;

                                foreach ($list as $bom) {
                                    $productDetails = '';
                                    if ($firstBom) {
                                        $firstBom = false;
                                        $productDetails = $quotationHeader . "\n\n" .
                                            $quotationProduct->product->part_number . "\n" .
                                            $quotationProduct->product->product_name . "\n" .
                                            "Variation: " . $quotationProduct->product->variation . "\n" .
                                            "Qty: " . $quotationProduct->quantity;
                                    }

                                    $bomName = '';
                                    if ($this->roleName == 'Team Leader - Packing Team' || $this->roleName == 'Team Leader - Dispatch Team') {
                                        $bomName = $bom->bom->bom_category;
                                    } else {
                                        $bomName = $bom?->bom?->bom_name;
                                    }

                                    $bomCategory = '';
                                    if ($this->roleName == 'Team Leader - Packing Team' || $this->roleName == 'Team Leader - Dispatch Team') {
                                        $bomCategory = '-';
                                    } else {
                                        $bomCategory = $bom?->bom?->bom_category;
                                    }

                                    $unit = '';
                                    if ($this->roleName == 'Team Leader - Packing Team' || $this->roleName == 'Team Leader - Dispatch Team') {
                                        $unit = '-';
                                    } else {
                                        $unit = $bom?->bom?->bom_unit;
                                    }

                                    $qty = '';
                                    if ($this->roleName == 'Team Leader - Packing Team' || $this->roleName == 'Team Leader - Dispatch Team') {
                                        $receivedCompletedQty = ProductionHistory::where('quotation_id', $quotation->id)
                                            ->where('product_id', $quotationProduct->product_id)
                                            ->where('bom_id', $bom->bom_id)
                                            ->where('production_type', 'product')
                                            ->where('team_name', null)
                                            ->sum('completed_qty');

                                        $packingQty = ProductionHistory::where('quotation_id', $quotation->id)
                                            ->where('product_id', $quotationProduct->product_id)
                                            ->where('bom_id', $bom->bom_id)
                                            ->where('production_type', 'product')
                                            ->where('team_name', 'packing')
                                            ->sum('completed_qty');

                                        $totqty = $bom->bom->bom_qty * $quotationProduct->quantity;

                                        $qty = "R: " . $receivedCompletedQty . "\nA: " . $packingQty . "\nT: " . $totqty;
                                    } else {
                                        $qty = $bom?->bom?->bom_qty;
                                    }

                                    $data[] = [
                                        $productDetails,
                                        $bomName,
                                        $bomCategory,
                                        $unit,
                                        $qty
                                    ];
                                }
                            }
                        }
                    }
                }
            }
        }

        return $data;
    }
}
