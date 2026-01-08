<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\QuotationProductionStages;

class ProductionHistory extends Model
{

    protected $fillable = [
        "quantity_production_id",
        "delivery_challan_status",
        "delivery_challan_id",
        "completed_qty"
    ];

    public function productionStage()
    {
        return $this->belongsTo(QuotationProductionStages::class, 'quantity_production_id', 'id');
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'quotation_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}