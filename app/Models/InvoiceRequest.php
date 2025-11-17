<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceRequest extends Model
{

    protected $fillable = [
        "quotation_id",
        "invoice_request_date",
        "status"
    ];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'quotation_id', 'id');
    }

    public function invoiceProducts()
    {
        return $this->hasMany(InvoiceRequestProducts::class, 'invoice_request_id', 'id');
    }
}
