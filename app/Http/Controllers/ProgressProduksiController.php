<?php
// app/Http/Controllers/ProgressProduksiController.php

namespace App\Http\Controllers;

use App\Models\ProgressProduksi;
use App\Models\DetailPo;
use App\Models\PurchaseOrder;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgressProduksiController extends Controller
{
    /**
     * Display a listing of the resource with filter.
     */
    public function index(Request $request)
    {
    $filter = $request->get('filter', 'Berjalan');
    $search = $request->get('search');
    
    $query = Project::with(['purchaseOrders.detailPo.produk', 'purchaseOrders.detailPo.progressProduksi.user']);
    
    // Filter status
    if ($filter === 'Berjalan') {
        $query->where('status_project', 'Berjalan');
    } elseif ($filter === 'Selesai') {
        $query->where('status_project', 'Selesai');
    }
    
    // Search
    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('no_project', 'like', "%{$search}%")
                ->orWhereHas('purchaseOrders', function ($q2) use ($search) {
                    $q2->where('no_po', 'like', "%{$search}%")
                        ->orWhereHas('supplier', function ($q3) use ($search) {
                            $q3->where('nama_supplier', 'like', "%{$search}%");
                        })
                        ->orWhereHas('detailPo.produk', function ($q3) use ($search) {
                            $q3->where('nama_produk', 'like', "%{$search}%")
                                ->orWhere('kode_produk', 'like', "%{$search}%");
                        });
                });
        });
    }
    
    $projects = $query->paginate(2);
    
    return view('progress.index', compact('projects', 'filter', 'search'));
    }

    public function show(DetailPo $detailPo)
    {
        $detailPo->load(['produk', 'purchaseOrder.project', 'progressProduksi.user']);
        return view('progress.show', compact('detailPo'));
    }

    public function create(DetailPo $detailPo)
    {
        if (Auth::user()->role !== 'Staff') {
            abort(403);
        }
        return view('staff.progress.create', compact('detailPo'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'Staff') {
            abort(403);
        }

        $request->validate([
            'detail_po_id' => 'required|exists:detail_po,id',
            'tanggal_progress' => 'required|date',
            'tahap_produksi' => 'required|in:Tahap 1,Tahap 2,Tahap 3,Tahap 4,Tahap 5,Tahap 6,Finishing,QC,Masuk Gudang',
            'qty_progress' => 'required|integer|min:1',
            'dokumentasi' => 'nullable|url',
            'catatan' => 'nullable|string',
            'status_progress' => 'required|in:Proses,Selesai'
        ]);

        $detailPo = DetailPo::find($request->detail_po_id);
        $sisaQty = $detailPo->qty_po - $detailPo->qty_selesai;
        
        if ($request->qty_progress > $sisaQty) {
            return back()->withErrors([
                'qty_progress' => "Qty progress tidak boleh melebihi sisa qty PO. Sisa qty: {$sisaQty}"
            ])->withInput();
        }

        ProgressProduksi::create([
            'detail_po_id' => $request->detail_po_id,
            'user_id' => Auth::user()->id,
            'tanggal_progress' => $request->tanggal_progress,
            'tahap_produksi' => $request->tahap_produksi,
            'qty_progress' => $request->qty_progress,
            'dokumentasi' => $request->dokumentasi,
            'catatan' => $request->catatan,
            'status_progress' => $request->status_progress
        ]);

        $this->updateQtySelesai($request->detail_po_id);

        return redirect()->route('staff.progress.show', $request->detail_po_id)
            ->with('success', 'Progress berhasil ditambahkan');
    }

    public function edit(ProgressProduksi $progress)
    {
        // ✅ Staff bisa mengedit semua progress
        if (Auth::user()->role !== 'Staff') {
            abort(403);
        }
        
        return view('staff.progress.edit', compact('progress'));
    }

    public function update(Request $request, ProgressProduksi $progress)
    {
        // ✅ Staff bisa mengupdate semua progress
        if (Auth::user()->role !== 'Staff') {
            abort(403);
        }

        $request->validate([
            'tanggal_progress' => 'required|date',
            'tahap_produksi' => 'required|in:Tahap 1,Tahap 2,Tahap 3,Tahap 4,Tahap 5,Tahap 6,Finishing,QC,Masuk Gudang',
            'qty_progress' => 'required|integer|min:1',
            'dokumentasi' => 'nullable|url',
            'catatan' => 'nullable|string',
            'status_progress' => 'required|in:Proses,Selesai'
        ]);

        $oldTahap = $progress->tahap_produksi;
        $oldStatus = $progress->status_progress;
        $detailPoId = $progress->detail_po_id;
        
        // ✅ Update data + user_id menjadi staff yang sedang login
        $progress->update([
            'tanggal_progress' => $request->tanggal_progress,
            'tahap_produksi' => $request->tahap_produksi,
            'qty_progress' => $request->qty_progress,
            'dokumentasi' => $request->dokumentasi,
            'catatan' => $request->catatan,
            'status_progress' => $request->status_progress,
            'user_id' => Auth::user()->id  // ✅ OTOMATIS BERUBAH
        ]);

        // Jika tahap atau status berubah, update qty_selesai
        if ($oldTahap === 'Masuk Gudang' || $request->tahap_produksi === 'Masuk Gudang' ||
            $oldStatus === 'Selesai' || $request->status_progress === 'Selesai') {
            $this->updateQtySelesai($detailPoId);
        } else {
            $this->updateStatusPo($detailPoId);
        }

        return redirect()->route('staff.progress.show', $detailPoId)
            ->with('success', 'Progress berhasil diupdate. Pelapor: ' . Auth::user()->nama);
    }

    public function destroy(ProgressProduksi $progress)
    {
        // ✅ Staff bisa menghapus semua progress
        if (Auth::user()->role !== 'Staff') {
            abort(403);
        }

        $detailPoId = $progress->detail_po_id;
        $tahap = $progress->tahap_produksi;
        $status = $progress->status_progress;
        
        $progress->delete();

        if ($tahap === 'Masuk Gudang' && $status === 'Selesai') {
            $this->updateQtySelesai($detailPoId);
        } else {
            $this->updateStatusPo($detailPoId);
        }

        return redirect()->route('staff.progress.show', $detailPoId)
            ->with('success', 'Progress berhasil dihapus.');
    }

    private function updateQtySelesai($detailPoId)
    {
        $detailPo = DetailPo::find($detailPoId);
        if (!$detailPo) return;

        $totalQtySelesai = ProgressProduksi::where('detail_po_id', $detailPoId)
            ->where('tahap_produksi', 'Masuk Gudang')
            ->where('status_progress', 'Selesai')
            ->sum('qty_progress');

        $detailPo->qty_selesai = min($totalQtySelesai, $detailPo->qty_po);
        $detailPo->save();

        $this->updateStatusPo($detailPo->purchase_order_id);
    }

    private function hasValidProgress($purchaseOrderId)
    {
        $details = DetailPo::where('purchase_order_id', $purchaseOrderId)->get();
        
        foreach ($details as $detail) {
            $count = ProgressProduksi::where('detail_po_id', $detail->id)
                ->where('tahap_produksi', 'Masuk Gudang')
                ->where('status_progress', 'Selesai')
                ->count();
            if ($count > 0) {
                return true;
            }
        }
        
        return false;
    }

    private function hasAnyProgress($purchaseOrderId)
    {
        $details = DetailPo::where('purchase_order_id', $purchaseOrderId)->get();
        
        foreach ($details as $detail) {
            $count = ProgressProduksi::where('detail_po_id', $detail->id)->count();
            if ($count > 0) {
                return true;
            }
        }
        return false;
    }

    private function updateStatusPo($purchaseOrderId)
    {
        $purchaseOrder = PurchaseOrder::find($purchaseOrderId);
        if (!$purchaseOrder) return;

        $allDetails = DetailPo::where('purchase_order_id', $purchaseOrderId)->get();
        
        if ($allDetails->isEmpty()) {
            return;
        }

        $allCompleted = true;
        $hasValidProgress = false;

        foreach ($allDetails as $detail) {
            $validProgressCount = ProgressProduksi::where('detail_po_id', $detail->id)
                ->where('tahap_produksi', 'Masuk Gudang')
                ->where('status_progress', 'Selesai')
                ->count();
            
            if ($validProgressCount > 0) {
                $hasValidProgress = true;
            }

            if ($detail->qty_selesai < $detail->qty_po) {
                $allCompleted = false;
            }
        }

        $hasAnyProgress = $this->hasAnyProgress($purchaseOrderId);

        if ($allCompleted && $allDetails->count() > 0) {
            $purchaseOrder->status_po = 'Selesai';
        } 
        elseif ($hasAnyProgress) {
            $purchaseOrder->status_po = 'Diproses';
        }
        else {
            $purchaseOrder->status_po = 'Menunggu';
        }

        $purchaseOrder->save();
    }
}