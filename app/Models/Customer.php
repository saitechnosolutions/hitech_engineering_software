<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        "customer_name",
        "email",
        "mobile_number",
        "gst_number",
        "pincode",
        "address",
        "wholesale_price",
        "discount",
        "customer_type",
        "state_id"
    ];

    public function employee(){
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}