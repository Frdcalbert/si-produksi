<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\PurchaseOrder;
use App\Models\Produk;
use App\Models\Supplier;
use App\Models\ProgressProduksi;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::user()->role === 'Admin') {
            $data = [
                'total_project' => Project::count(),
                'total_po_menunggu' => PurchaseOrder::where('status_po', 'Menunggu')->count(),
                'total_po_diproses' => PurchaseOrder::where('status_po', 'Diproses')->count(),
                'total_po_selesai' => PurchaseOrder::where('status_po', 'Selesai')->count(),
                'total_produk' => Produk::count(),
                'total_supplier' => Supplier::count(),
                'deadline_po' => PurchaseOrder::with(['project', 'supplier'])
                    ->where('status_po', '!=', 'Selesai')
                    ->orderBy('deadline_po')
                    ->take(5)
                    ->get(),
                'progress_terbaru' => ProgressProduksi::with(['detailPo.purchaseOrder', 'user'])
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get()
            ];
            return view('admin.dashboard', $data);
        } else {
            $user = Auth::user();
            $data = [
                'total_po_aktif' => PurchaseOrder::where('status_po', '!=', 'Selesai')->count(),
                'total_po_menunggu' => PurchaseOrder::where('status_po', 'Menunggu')->count(),
                'total_po_diproses' => PurchaseOrder::where('status_po', 'Diproses')->count(),
                'total_po_selesai' => PurchaseOrder::where('status_po', 'Selesai')->count(),
                'deadline_po' => PurchaseOrder::with(['project', 'supplier'])
                    ->where('status_po', '!=', 'Selesai')
                    ->orderBy('deadline_po')
                    ->take(5)
                    ->get(),
                'progress_terbaru' => ProgressProduksi::with(['detailPo.purchaseOrder', 'user'])
                    ->where('user_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get()
            ];
            return view('staff.dashboard', $data);
        }
    }
}