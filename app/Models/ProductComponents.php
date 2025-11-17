<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductComponents extends Model
{
    public function productionQuantity()
    {
        return $this->hasMany(BOMParts::class, 'production_bom_id', 'id');
    }
}
