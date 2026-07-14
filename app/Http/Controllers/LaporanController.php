<?php
// app/Http/Controllers/LaporanController.php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\PurchaseOrder;
use App\Models\Project;
use App\Models\DetailPo;
use App\Models\ProgressProduksi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    /**
     * Halaman laporan produk
     */
    public function produkIndex(Request $request)
    {
        $search = $request->get('search');
        
        $produk = Produk::when($search, function ($query, $search) {
            return $query->where('kode_produk', 'like', "%{$search}%")
                ->orWhere('nama_produk', 'like', "%{$search}%");
        })->paginate(10);
        
        $totalProduk = Produk::count();
        
        return view('admin.laporan.produk', compact('produk', 'search', 'totalProduk'));
    }

    /**
     * Export PDF laporan produk
     */
    public function produkExport()
    {
        $produk = Produk::all();
        $totalProduk = Produk::count();
        $pdf = PDF::loadView('admin.laporan.produk_pdf', compact('produk', 'totalProduk'));
        return $pdf->download('laporan-produk-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Halaman laporan purchase order
     */
    public function poIndex(Request $request)
    {
        $filter = $request->get('filter', 'Semua');
        $search = $request->get('search');
        $tanggal_awal = $request->get('tanggal_awal');
        $tanggal_akhir = $request->get('tanggal_akhir');
        
        $query = PurchaseOrder::with(['project', 'supplier', 'detailPo.produk']);
        
        if ($filter !== 'Semua') {
            $query->where('status_po', $filter);
        }
        if ($tanggal_awal) {
            $query->whereDate('tanggal_po', '>=', $tanggal_awal);
        }
        if ($tanggal_akhir) {
            $query->whereDate('tanggal_po', '<=', $tanggal_akhir);
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('no_po', 'like', "%{$search}%")
                    ->orWhereHas('project', function ($q2) use ($search) {
                        $q2->where('no_project', 'like', "%{$search}%");
                    })
                    ->orWhereHas('supplier', function ($q2) use ($search) {
                        $q2->where('nama_supplier', 'like', "%{$search}%");
                    });
            });
        }
        
        $purchaseOrders = $query->paginate(10);
        
        // STATISTIK MENGGUNAKAN QUERY YANG SAMA (TANPA SEARCH & PAGINATION)
        $statsQuery = PurchaseOrder::with(['detailPo']);
        
        if ($filter !== 'Semua') {
            $statsQuery->where('status_po', $filter);
        }
        if ($tanggal_awal) {
            $statsQuery->whereDate('tanggal_po', '>=', $tanggal_awal);
        }
        if ($tanggal_akhir) {
            $statsQuery->whereDate('tanggal_po', '<=', $tanggal_akhir);
        }
        // ✅ SEARCH TIDAK DITERAPKAN DI STATISTIK
        
        $statsPOs = $statsQuery->get();
        
        // Hitung statistik berdasarkan data yang sudah difilter
        $totalPO = $statsPOs->count();
        $totalSelesai = $statsPOs->where('status_po', 'Selesai')->count();
        $totalProses = $statsPOs->where('status_po', 'Diproses')->count();
        $totalMenunggu = $statsPOs->where('status_po', 'Menunggu')->count();
        
        // Hitung total qty dari detail po yang difilter
        $totalQty = 0;
        foreach ($statsPOs as $po) {
            $totalQty += $po->detailPo->sum('qty_po');
        }
        
        return view('admin.laporan.po', compact(
            'purchaseOrders', 'filter', 'search', 'tanggal_awal', 'tanggal_akhir',
            'totalPO', 'totalSelesai', 'totalProses', 'totalMenunggu', 'totalQty'
        ));
    }

    /**
     * Export PDF laporan purchase order
     */
    public function poExport(Request $request)
    {
        $tanggal_awal = $request->get('tanggal_awal');
        $tanggal_akhir = $request->get('tanggal_akhir');
        $filter = $request->get('filter', 'Semua');
        
        // Query untuk data
        $query = PurchaseOrder::with(['project', 'supplier', 'detailPo.produk']);
        
        if ($filter !== 'Semua') {
            $query->where('status_po', $filter);
        }
        if ($tanggal_awal) {
            $query->whereDate('tanggal_po', '>=', $tanggal_awal);
        }
        if ($tanggal_akhir) {
            $query->whereDate('tanggal_po', '<=', $tanggal_akhir);
        }
        
        $purchaseOrders = $query->get();
        
        // ✅ STATISTIK MENGGUNAKAN DATA YANG SAMA
        $totalPO = $purchaseOrders->count();
        $totalSelesai = $purchaseOrders->where('status_po', 'Selesai')->count();
        $totalProses = $purchaseOrders->where('status_po', 'Diproses')->count();
        $totalMenunggu = $purchaseOrders->where('status_po', 'Menunggu')->count();
        
        $totalQty = 0;
        foreach ($purchaseOrders as $po) {
            $totalQty += $po->detailPo->sum('qty_po');
        }
        
        $pdf = PDF::loadView('admin.laporan.po_pdf', compact(
            'purchaseOrders', 'tanggal_awal', 'tanggal_akhir', 'filter',
            'totalPO', 'totalSelesai', 'totalProses', 'totalMenunggu', 'totalQty'
        ));
        return $pdf->download('laporan-po-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Halaman laporan progress produksi
     */
    public function progressIndex(Request $request)
    {
    $filter = $request->get('filter', 'Semua');
    $search = $request->get('search');
    
    $query = DetailPo::with([
        'purchaseOrder.project', 
        'purchaseOrder.supplier', 
        'produk'
    ]);
    
    // ✅ FILTER BERDASARKAN STATUS PO
    if ($filter !== 'Semua') {
        $query->whereHas('purchaseOrder', function ($q) use ($filter) {
            $q->where('status_po', $filter);
        });
    }
    
    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->whereHas('purchaseOrder', function ($q2) use ($search) {
                $q2->where('no_po', 'like', "%{$search}%")
                    ->orWhereHas('project', function ($q3) use ($search) {
                        $q3->where('no_project', 'like', "%{$search}%");
                    });
            })
            ->orWhereHas('produk', function ($q2) use ($search) {
                $q2->where('nama_produk', 'like', "%{$search}%")
                    ->orWhere('kode_produk', 'like', "%{$search}%");
            });
        });
    }
    
    $detailPos = $query->paginate(10);
    
    // ✅ TOTAL QUERY DENGAN FILTER YANG SAMA
    $totalQuery = DetailPo::with([
        'purchaseOrder.project', 
        'purchaseOrder.supplier', 
        'produk'
    ]);
    
    if ($filter !== 'Semua') {
        $totalQuery->whereHas('purchaseOrder', function ($q) use ($filter) {
            $q->where('status_po', $filter);
        });
    }
    
    if ($search) {
        $totalQuery->where(function ($q) use ($search) {
            $q->whereHas('purchaseOrder', function ($q2) use ($search) {
                $q2->where('no_po', 'like', "%{$search}%")
                    ->orWhereHas('project', function ($q3) use ($search) {
                        $q3->where('no_project', 'like', "%{$search}%");
                    });
            })
            ->orWhereHas('produk', function ($q2) use ($search) {
                $q2->where('nama_produk', 'like', "%{$search}%")
                    ->orWhere('kode_produk', 'like', "%{$search}%");
            });
        });
    }
    
    $totalRecords = $totalQuery->count();
    
    return view('admin.laporan.progress', compact('detailPos', 'filter', 'search', 'totalRecords'));
    }

    /**
     * Export PDF laporan progress produksi
     */
    public function progressExport(Request $request)
    {
    $filter = $request->get('filter', 'Semua');
    
    $query = DetailPo::with([
        'purchaseOrder.project', 
        'purchaseOrder.supplier', 
        'produk'
    ]);
    
    // ✅ FILTER BERDASARKAN STATUS PO
    if ($filter !== 'Semua') {
        $query->whereHas('purchaseOrder', function ($q) use ($filter) {
            $q->where('status_po', $filter);
        });
    }
    
    $detailPos = $query->get();
    $totalRecords = $detailPos->count();
    
    $pdf = PDF::loadView('admin.laporan.progress_pdf', compact('detailPos', 'filter', 'totalRecords'));
    return $pdf->download('laporan-progress-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Halaman detail progress produksi per detail PO (1 produk)
     */
    public function detailProgress(DetailPo $detailPo)
    {
        $detailPo->load(['produk', 'purchaseOrder.project', 'purchaseOrder.supplier', 'progressProduksi.user']);
        
        // Ambil progress untuk detail PO ini
        $progressList = [];
        foreach ($detailPo->progressProduksi as $progress) {
            $progressList[] = [
                'tanggal' => $progress->tanggal_progress,
                'tahap_1' => $progress->tahap_produksi == 'Tahap 1' ? $progress->qty_progress : '-',
                'tahap_2' => $progress->tahap_produksi == 'Tahap 2' ? $progress->qty_progress : '-',
                'tahap_3' => $progress->tahap_produksi == 'Tahap 3' ? $progress->qty_progress : '-',
                'tahap_4' => $progress->tahap_produksi == 'Tahap 4' ? $progress->qty_progress : '-',
                'tahap_5' => $progress->tahap_produksi == 'Tahap 5' ? $progress->qty_progress : '-',
                'tahap_6' => $progress->tahap_produksi == 'Tahap 6' ? $progress->qty_progress : '-',
                'finishing' => $progress->tahap_produksi == 'Finishing' ? $progress->qty_progress : '-',
                'qc' => $progress->tahap_produksi == 'QC' ? $progress->qty_progress : '-',
                'gudang' => $progress->tahap_produksi == 'Masuk Gudang' ? $progress->qty_progress : '-',
                'catatan' => $progress->catatan ?? '-'
            ];
        }
        
        return view('admin.laporan.detail_progress', compact('detailPo', 'progressList'));
    }

    /**
     * Export PDF detail progress per detail PO (1 produk)
     */
    public function detailProgressExport(DetailPo $detailPo)
    {
        $detailPo->load(['produk', 'purchaseOrder.project', 'purchaseOrder.supplier', 'progressProduksi.user']);
        
        $progressList = [];
        foreach ($detailPo->progressProduksi as $progress) {
            $progressList[] = [
                'tanggal' => $progress->tanggal_progress,
                'tahap_1' => $progress->tahap_produksi == 'Tahap 1' ? $progress->qty_progress : '-',
                'tahap_2' => $progress->tahap_produksi == 'Tahap 2' ? $progress->qty_progress : '-',
                'tahap_3' => $progress->tahap_produksi == 'Tahap 3' ? $progress->qty_progress : '-',
                'tahap_4' => $progress->tahap_produksi == 'Tahap 4' ? $progress->qty_progress : '-',
                'tahap_5' => $progress->tahap_produksi == 'Tahap 5' ? $progress->qty_progress : '-',
                'tahap_6' => $progress->tahap_produksi == 'Tahap 6' ? $progress->qty_progress : '-',
                'finishing' => $progress->tahap_produksi == 'Finishing' ? $progress->qty_progress : '-',
                'qc' => $progress->tahap_produksi == 'QC' ? $progress->qty_progress : '-',
                'gudang' => $progress->tahap_produksi == 'Masuk Gudang' ? $progress->qty_progress : '-',
                'catatan' => $progress->catatan ?? '-'
            ];
        }
        
        $pdf = PDF::loadView('admin.laporan.detail_progress_pdf', compact('detailPo', 'progressList'))->setPaper('a4', 'landscape');
        return $pdf->download('detail-progress-' . $detailPo->purchaseOrder->no_po . '-' . $detailPo->produk->kode_produk . '-' . date('Y-m-d') . '.pdf');
    }
}