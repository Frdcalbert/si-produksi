<?php
// app/Models/Project.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $table = 'project';

    protected $fillable = [
        'no_project',
        'tanggal_project',
        'deadline_project',
        'status_project',
        'keterangan'
    ];

    protected $casts = [
        'tanggal_project' => 'date',
        'deadline_project' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class, 'project_id');
    }
}