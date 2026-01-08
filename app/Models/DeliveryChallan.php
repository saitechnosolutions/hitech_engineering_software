<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryChallan extends Model
{

    protected $fillable = [
        "status",
        "delivery_challan_id",
        "delivery_challan_date"
    ];

    public function productionHistorys()
    {
        return $this->hasMany(ProductionHistory::class, 'delivery_challan_id', 'id');
    }
}