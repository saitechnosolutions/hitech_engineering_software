<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationProductionStages extends Model
{

    protected $fillable = [
        "quotation_id",
        "product_id",
        "bom_id",
        "team_id",
        "stage",
        "production_status",
        "accept_date",
        "next_stage_move_date",
        "product_quantity",
        "bom_required_quantity",
        "completed_quantity",
        "status"
    ];

    public function bom()
    {
        return $this->belongsTo(BOMParts::class, 'bom_id', 'id');
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