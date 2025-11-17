<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BomProcessTeams extends Model
{
    public function team()
    {
        return $this->belongsTo(ProcessTeam::class, 'team_id', 'id');
    }

    public function bom()
    {
        return $this->belongsTo(BOMParts::class, 'bom_id', 'id');
    }
}
