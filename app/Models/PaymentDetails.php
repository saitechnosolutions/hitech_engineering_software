<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentDetails extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        "quotation_id",
        "amount",
        "payment_date",
        "remarks",
        "reference_images",
        "entered_by"
    ];

    protected $casts = [
        "reference_images" => 'json'
    ];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'quotation_id', 'id');
    }
    public function rm()
    {
        return $this->belongsTo(Employee::class, 'entered_by', 'id');
    }
}