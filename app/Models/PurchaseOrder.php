<?php
// app/Models/PurchaseOrder.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $table = 'purchase_order';

    protected $fillable = [
        'project_id',
        'supplier_id',
        'no_po',
        'tanggal_po',
        'deadline_po',
        'status_po',
        'catatan'
    ];

    protected $casts = [
        'tanggal_po' => 'date',
        'deadline_po' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function detailPo()
    {
        return $this->hasMany(DetailPo::class, 'purchase_order_id');
    }
}