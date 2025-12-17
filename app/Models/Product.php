<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    protected $fillable = [
        "stock_qty"
    ];
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function bomParts()
    {
        return $this->hasMany(BOMParts::class, 'product_id', 'id');
    }
}
