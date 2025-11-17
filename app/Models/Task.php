<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{

    protected $fillable = [
        "task_details",
        "task_date",
        "created_by",
        "assigned_to",
        "status",
        "task_type",
        "repeating_task_date",
        "priority",
        "create_task_image",
        "comple_task_image",
        "completed_task_timestamp",
        "next_run_date",
        "title",
        "created_task_images",
        "completed_task_images",
        "end_date",
        "repeating_type"
    ];

    protected $casts = [
        "created_task_images" => 'json',
        "completed_task_images" => 'json'
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to', 'id');
    }
}
