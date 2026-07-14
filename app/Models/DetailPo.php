<?php
// app/Models/DetailPo.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPo extends Model
{
    use HasFactory;

    protected $table = 'detail_po';

    protected $fillable = [
        'purchase_order_id',
        'produk_id',
        'qty_po',
        'qty_selesai'
    ];

    protected $casts = [
        'qty_po' => 'integer',
        'qty_selesai' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function progressProduksi()
    {
        return $this->hasMany(ProgressProduksi::class, 'detail_po_id');
    }
}