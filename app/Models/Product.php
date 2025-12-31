<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{

    use SoftDeletes;
    protected $fillable = ['category_id', 'product_name', 'brand', 'bike_model', 'mrp_price', 'part_number', 'quantity', 'variation', 'hsn_code', 'color', 'material', 'product_image', 'design_sheet', 'data_sheet'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function bomParts()
    {
        return $this->hasMany(BOMParts::class, 'product_id', 'id');
    }

        public function processTeam()
    {
        return $this->hasMany(BomProcessTeams::class, 'bom_id', 'id');
    }
}
