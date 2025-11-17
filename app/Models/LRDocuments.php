<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LRDocuments extends Model
{
    protected $casts = [
        "upload_documents" => 'json'
    ];

    public function quotation(){
        return $this->belongsTo(Quotation::class, 'quotation_id', 'id');
        
    }
}