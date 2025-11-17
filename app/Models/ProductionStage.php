<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionStage extends Model
{
    public function processTeam()
    {
        return $this->belongsTo(ProcessTeam::class, 'production_team_id', 'id');
    }
}
