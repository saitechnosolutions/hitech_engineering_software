<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\QuotationProductionStages;

class ProductionHistory extends Model
{
    public function productionStage()
    {
        return $this->belongsTo(QuotationProductionStages::class, 'quantity_production_id', 'id');
    }
}
