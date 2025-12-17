<?php

namespace App\Models;

use App\Models\InvoiceRequestProducts;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{

    protected $fillable = [
        "quotation_no",
        "quotation_date",
        "mode_terms_of_payment",
        "buyer_reference_order_no",
        "other_references",
        "dispatch_through",
        "destination",
        "terms_of_delivery",
        "customer_id",
        "number_of_rows",
        "cgst_percentage",
        "cgst_value",
        "sgst_percentage",
        "sgst_value",
        "igst_percentage",
        "igst_value",
        "is_production_moved",
        "batch_date",
        "priority",
        "production_status",
        "dispatch_team_id",
        "payment_status"
    ]
    ;
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function quotationProducts()
    {
        return $this->hasMany(QuotationProducts::class, 'quotation_id', 'id');
    }

    public function payments()
    {
        return $this->hasMany(PaymentDetails::class, 'quotation_id', 'id');
    }

    public function invoiceRequest()
    {
        return $this->hasMany(InvoiceRequest::class, 'quotation_id', 'id');
    }

    public function invoiceRequestProducts()
    {
        return $this->hasMany(InvoiceRequestProducts::class, 'quotation_id', 'id');
    }
}