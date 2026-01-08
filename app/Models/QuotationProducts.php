<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuotationProducts extends Model
{

    use SoftDeletes;

    protected $fillable = [
        "quotation_id",
        "product_id",
        "quantity",
        "rate",
        "uom",
        "discount_percentage",
        "discount_amount",
        "total_amount",
        "ms_fabrication_emp_id",
        "ss_fabrication_emp_id",
        "production_status",
        "partial_qty",
        "available_stock",
        "production_stock",

    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function msFabricationEmployee()
    {
        return $this->belongsTo(Employee::class, 'ms_fabrication_emp_id', 'id');
    }

    public function ssFabricationEmployee()
    {
        return $this->belongsTo(Employee::class, 'ss_fabrication_emp_id', 'id');
    }

    public function quotation(){
        return $this->belongsTo(Quotation::class, 'quotation_id', 'id');
    }
}