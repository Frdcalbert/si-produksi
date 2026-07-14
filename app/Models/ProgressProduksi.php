<?php
// app/Models/ProgressProduksi.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgressProduksi extends Model
{
    use HasFactory;

    protected $table = 'progress_produksi';

    protected $fillable = [
        'detail_po_id',
        'user_id',
        'tanggal_progress',
        'tahap_produksi',
        'qty_progress',
        'dokumentasi',
        'catatan',
        'status_progress'
    ];

    protected $casts = [
        'tanggal_progress' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function detailPo()
    {
        return $this->belongsTo(DetailPo::class, 'detail_po_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
}