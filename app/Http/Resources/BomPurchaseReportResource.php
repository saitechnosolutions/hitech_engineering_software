<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BomPurchaseReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);



        return [
            "quotation" => $this->quotation?->quotation_no,
            "bom" => $this->bom?->bom_name,
            "bom_category" => $this->bom?->bom_category,
            "batch" => $this->quotation?->batch_date,
            "unit" => $this->bom?->bom_unit,
            "total_unit" => $this->bom->bom_qty,
        ];
    }
}
