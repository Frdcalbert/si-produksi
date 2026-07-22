<?php
// app/Http/Controllers/PurchaseOrderController.php
namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Project;
use App\Models\Supplier;
use App\Models\Produk;
use App\Models\DetailPo;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
    $filter = $request->get('filter', 'Semua');
    $search = $request->get('search');
    
    $query = PurchaseOrder::with(['project', 'supplier', 'detailPo.produk']);
    
    // Filter status
    if ($filter !== 'Semua') {
        $query->where('status_po', $filter);
    }
    
    // Search
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
    
    return view('admin.purchase-order.index', compact('purchaseOrders', 'filter', 'search'));
    }

    public function create()
    {
        $projects = Project::all();
        $suppliers = Supplier::all();
        $produks = Produk::all();
        return view('admin.purchase-order.create', compact('projects', 'suppliers', 'produks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:project,id',
            'supplier_id' => 'required|exists:supplier,id',
            'no_po' => 'required|unique:purchase_order',
            'tanggal_po' => 'required|date',
            'deadline_po' => 'required|date|after:tanggal_po',
            'produk_id' => 'required|array|min:1',
            'produk_id.*' => 'exists:produk,id',
            'qty_po' => 'required|array|min:1',
            'qty_po.*' => 'integer|min:1'
        ]);

        $po = PurchaseOrder::create([
            'project_id' => $request->project_id,
            'supplier_id' => $request->supplier_id,
            'no_po' => $request->no_po,
            'tanggal_po' => $request->tanggal_po,
            'deadline_po' => $request->deadline_po,
            'status_po' => 'Menunggu',
            'catatan' => $request->catatan
        ]);

        foreach ($request->produk_id as $key => $produkId) {
            DetailPo::create([
                'purchase_order_id' => $po->id,
                'produk_id' => $produkId,
                'qty_po' => $request->qty_po[$key],
                'qty_selesai' => 0
            ]);
        }

        return redirect()->route('admin.purchase-order.index')->with('success', 'Purchase Order berhasil dibuat');
    }

    public function edit(PurchaseOrder $purchaseOrder)
    {
        $projects = Project::all();
        $suppliers = Supplier::all();
        $produks = Produk::all();
        return view('admin.purchase-order.edit', compact('purchaseOrder', 'projects', 'suppliers', 'produks'));
    }

    public function update(Request $request, PurchaseOrder $purchaseOrder)
{
    $request->validate([
        'project_id' => 'required|exists:project,id',
        'supplier_id' => 'required|exists:supplier,id',
        'no_po' => 'required|unique:purchase_order,no_po,' . $purchaseOrder->id,
        'tanggal_po' => 'required|date',
        'deadline_po' => 'required|date|after:tanggal_po',
        // ✅ Tambah validasi untuk detail produk
        'produk_id' => 'required|array|min:1',
        'produk_id.*' => 'exists:produk,id',
        'qty_po' => 'required|array|min:1',
        'qty_po.*' => 'integer|min:1'
    ]);

    if ($purchaseOrder->status_po === 'Selesai') {
        return back()->with('error', 'PO yang sudah selesai tidak dapat diubah');
    }

    // ✅ Update header PO
    $purchaseOrder->update($request->only(['project_id', 'supplier_id', 'no_po', 'tanggal_po', 'deadline_po', 'catatan']));

    // ✅ Update detail PO
    // Ambil semua ID detail yang ada di form
    $existingDetailIds = $purchaseOrder->detailPo->pluck('id')->toArray();
    $submittedIds = $request->input('detail_id', []);

    // Hapus detail yang tidak ada di form
    $toDelete = array_diff($existingDetailIds, $submittedIds);
    if (!empty($toDelete)) {
        DetailPo::whereIn('id', $toDelete)->delete();
    }

    // Update atau tambah detail
    foreach ($request->produk_id as $index => $produkId) {
        $detailId = $request->detail_id[$index] ?? null;
        $qtyPo = $request->qty_po[$index] ?? 0;

        if ($detailId) {
            // Update detail yang sudah ada
            DetailPo::where('id', $detailId)->update([
                'produk_id' => $produkId,
                'qty_po' => $qtyPo
            ]);
        } else {
            // Tambah detail baru
            DetailPo::create([
                'purchase_order_id' => $purchaseOrder->id,
                'produk_id' => $produkId,
                'qty_po' => $qtyPo,
                'qty_selesai' => 0
            ]);
        }
    }

    // ✅ Update status PO
    $this->updateStatusPo($purchaseOrder->id);

    return redirect()->route('admin.purchase-order.index')
        ->with('success', 'Purchase Order berhasil diupdate');
}

    public function destroy(PurchaseOrder $purchaseOrder)
    {        
        $purchaseOrder->delete();
        return redirect()->route('admin.purchase-order.index')->with('success', 'Purchase Order berhasil dihapus');
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['project', 'supplier', 'detailPo.produk', 'detailPo.progressProduksi.user']);
        return view('admin.purchase-order.show', compact('purchaseOrder'));
    }

    /**
 * Update detail PO
 */
public function updateDetail(Request $request, PurchaseOrder $purchaseOrder, DetailPo $detailPo)
{
    $request->validate([
        'produk_id' => 'required|exists:produk,id',
        'qty_po' => 'required|integer|min:1'
    ]);
    
    // Validasi: qty baru tidak boleh kurang dari qty selesai
    if ($request->qty_po < $detailPo->qty_selesai) {
        return back()->with('error', 'Qty PO tidak boleh kurang dari qty selesai (' . $detailPo->qty_selesai . ').');
    }
    
    if ($purchaseOrder->status_po === 'Selesai') {
        return back()->with('error', 'PO sudah selesai, tidak dapat mengubah detail.');
    }
    
    $detailPo->update([
        'produk_id' => $request->produk_id,
        'qty_po' => $request->qty_po
    ]);
    
    // Update status PO
    $this->updateStatusPo($purchaseOrder->id);
    
    return redirect()->route('admin.purchase-order.edit', $purchaseOrder->id)
        ->with('success', 'Detail produk berhasil diupdate.');
}

/**
 * Hapus detail PO
 */
public function destroyDetail(PurchaseOrder $purchaseOrder, DetailPo $detailPo)
{
    // Cek apakah ada progress
    if ($detailPo->progressProduksi()->count() > 0) {
        return back()->with('error', 'Tidak dapat menghapus detail karena sudah ada progress. Hapus progress terlebih dahulu.');
    }
    
    if ($purchaseOrder->status_po === 'Selesai') {
        return back()->with('error', 'PO sudah selesai, tidak dapat menghapus detail.');
    }
    
    $detailPo->delete();
    $this->updateStatusPo($purchaseOrder->id);
    
    return redirect()->route('admin.purchase-order.edit', $purchaseOrder->id)
        ->with('success', 'Detail produk berhasil dihapus.');
}

/**
 * Update status PO
 */
private function updateStatusPo($purchaseOrderId)
{
    $purchaseOrder = PurchaseOrder::find($purchaseOrderId);
    if (!$purchaseOrder) return;
    
    $allDetails = DetailPo::where('purchase_order_id', $purchaseOrderId)->get();
    
    if ($allDetails->isEmpty()) {
        $purchaseOrder->status_po = 'Menunggu';
        $purchaseOrder->save();
        return;
    }
    
    $allCompleted = $allDetails->every(function ($detail) {
        return $detail->qty_selesai >= $detail->qty_po;
    });
    
    if ($allCompleted) {
        $purchaseOrder->status_po = 'Selesai';
    } else {
        $purchaseOrder->status_po = 'Diproses';
    }
    
    $purchaseOrder->save();
}
}