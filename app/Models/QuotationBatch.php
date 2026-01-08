<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationBatch extends Model
{
    protected $casts = [
        "quotation_ids" => 'array'
    ];

    protected $fillable = [
        "batch_date",
        "priority",
        "quotation_ids",
        "quotation_count",
        "batch_status"
    ];

}
