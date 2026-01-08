<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockInOutResource extends JsonResource
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
            "id" => $this->id,
            "product_id" => $this->product?->product_name,
            "inward_qty" => $this->inward_qty,
            "inward_date" => $this->inward_date,
            "outward_qty" => $this->outward_qty,
            "outward_date" => $this->outward_date,
            "quotation_id" => $this->quotation?->quotation_no,
            "quotation_batch_id" => $this->quotationBatch?->batch_date,
            "stock_status" => $this->stock_status,
        ];
    }
}
