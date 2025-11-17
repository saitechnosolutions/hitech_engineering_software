<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcessTeam extends Model
{
    public function quotationProductionStages()
    {
        return $this->hasMany(QuotationProductionStages::class, 'team_id', 'id');
    }
}