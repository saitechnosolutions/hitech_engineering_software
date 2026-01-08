<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LRDocuments extends Model
{

    use SoftDeletes;

    protected $fillable = [
        "quotation_id",
        "entry_date",
        "upload_documents",
        "remarks"
    ];

    protected $casts = [
        "upload_documents" => 'array'
    ];

    public function quotation(){
        return $this->belongsTo(Quotation::class, 'quotation_id', 'id');

    }
}
