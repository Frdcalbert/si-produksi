<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\ProgressProduksiController;
use App\Http\Controllers\LaporanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ============================================
// GUEST ROUTES (Belum Login)
// ============================================
Route::middleware('guest')->group(function () {
    // Halaman Login
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login.show');
    
    // Proses Login
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// ============================================
// AUTHENTICATED ROUTES (Sudah Login)
// ============================================
Route::middleware('auth')->group(function () {
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // ============================================
    // ADMIN DASHBOARD
    // ============================================
    Route::middleware('admin')->get('/admin/dashboard', function () {
        return app(DashboardController::class)->index();
    })->name('admin.dashboard');
    
    // ============================================
    // STAFF DASHBOARD
    // ============================================
    Route::middleware('staff')->get('/staff/dashboard', function () {
        return app(DashboardController::class)->index();
    })->name('staff.dashboard');
    
    // ============================================
    // ADMIN ROUTES
    // ============================================
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        
        // Dashboard Admin (redirect ke /admin/dashboard)
        Route::get('/', function () {
            return redirect()->route('admin.dashboard');
        });
        
        // User Management
        Route::resource('users', UserController::class);
        
        // Master Data
        Route::resource('produk', ProdukController::class);
        Route::resource('supplier', SupplierController::class);
        
        // Transaksi
        Route::resource('project', ProjectController::class);
        Route::resource('purchase-order', PurchaseOrderController::class);
        // Detail PO - Update & Delete
        Route::put('purchase-order/{purchaseOrder}/detail/{detailPo}', [PurchaseOrderController::class, 'updateDetail'])
            ->name('purchase-order.update-detail');
        Route::delete('purchase-order/{purchaseOrder}/detail/{detailPo}', [PurchaseOrderController::class, 'destroyDetail'])
            ->name('purchase-order.destroy-detail');
        
        // Progress Produksi (View Only untuk Admin)
        Route::get('progress', [ProgressProduksiController::class, 'index'])->name('progress.index');
        Route::get('progress/{detailPo}', [ProgressProduksiController::class, 'show'])->name('progress.show');
        
        
        Route::prefix('laporan')->name('laporan.')->group(function () {
            
            // Laporan Produk
            Route::get('/produk', [LaporanController::class, 'produkIndex'])->name('produk');
            Route::get('/produk/export', [LaporanController::class, 'produkExport'])->name('produk.export');
            
            // Laporan Purchase Order
            Route::get('/purchase-order', [LaporanController::class, 'poIndex'])->name('po');
            Route::get('/purchase-order/export', [LaporanController::class, 'poExport'])->name('po.export');
            
            // Laporan Progress Produksi
            Route::get('/progress', [LaporanController::class, 'progressIndex'])->name('progress');
            Route::get('/progress/export', [LaporanController::class, 'progressExport'])->name('progress.export');
            
            // Detail Progress Produksi - 1 Detail PO (1 Produk)
            Route::get('/progress/{detailPo}/detail', [LaporanController::class, 'detailProgress'])->name('detail_progress');
            Route::get('/progress/{detailPo}/export', [LaporanController::class, 'detailProgressExport'])->name('detail_progress.export');
        });
    });
    
    // ============================================
    // STAFF ROUTES
    // ============================================
    Route::middleware('staff')->prefix('staff')->name('staff.')->group(function () {
        
        // Dashboard Staff (redirect ke /staff/dashboard)
        Route::get('/', function () {
            return redirect()->route('staff.dashboard');
        });
        
        // Progress Produksi (Full CRUD untuk Staff)
        Route::get('progress', [ProgressProduksiController::class, 'index'])->name('progress.index');
        Route::get('progress/{detailPo}', [ProgressProduksiController::class, 'show'])->name('progress.show');
        Route::get('progress/{detailPo}/create', [ProgressProduksiController::class, 'create'])->name('progress.create');
        Route::post('progress', [ProgressProduksiController::class, 'store'])->name('progress.store');
        Route::get('progress/{progress}/edit', [ProgressProduksiController::class, 'edit'])->name('progress.edit');
        Route::put('progress/{progress}', [ProgressProduksiController::class, 'update'])->name('progress.update');
        Route::delete('progress/{progress}', [ProgressProduksiController::class, 'destroy'])->name('progress.destroy');
    });
});

// ============================================
// FALLBACK ROUTE (Jika route tidak ditemukan)
// ============================================
Route::fallback(function () {
    return redirect()->route('login');
});