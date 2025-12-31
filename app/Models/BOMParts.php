<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BOMParts extends Model
{
    protected $table = 'b_o_m_parts';

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function processTeam()
    {
        return $this->hasMany(BomProcessTeams::class, 'bom_id', 'id');
    }
}