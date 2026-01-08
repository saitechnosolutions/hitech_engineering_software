<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockInwardOutwardDetails extends Model
{
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'quotation_id', 'id');
    }

    public function quotationBatch()
    {
        return $this->belongsTo(QuotationBatch::class, 'quotation_batch_id', 'id');
    }
}