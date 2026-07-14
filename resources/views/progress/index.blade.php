{{-- resources/views/progress/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Progress Produksi')
@section('page-title', 'Progress Produksi')

@section('content')
<div class="filter-section">
    <form action="{{ route(auth()->user()->role === 'Admin' ? 'admin.progress.index' : 'staff.progress.index') }}" method="GET" class="row g-3 align-items-end">
        <div class="col-md-3">
            <label class="filter-label">Filter Status Project</label>
            <select name="filter" class="form-select" onchange="this.form.submit()">
                <option value="Berjalan" {{ ($filter ?? 'Berjalan') == 'Berjalan' ? 'selected' : '' }}>Berjalan</option>
                <option value="Selesai" {{ ($filter ?? '') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="Semua" {{ ($filter ?? '') == 'Semua' ? 'selected' : '' }}>Semua</option>
            </select>
        </div>
        <div class="col-md-6">
            <label class="filter-label">Cari</label>
            <div class="input-group">
                <input type="text" name="search" class="form-control" 
                       placeholder="Cari Project / PO / Supplier / Produk..." value="{{ $search ?? '' }}">
                <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
                @if(isset($search) && $search)
                    <a href="{{ route(auth()->user()->role === 'Admin' ? 'admin.progress.index' : 'staff.progress.index', ['filter' => $filter ?? 'Berjalan']) }}" class="btn btn-secondary"><i class="bi bi-x-circle"></i></a>
                @endif
            </div>
        </div>
        <div class="col-md-3 text-end">
            <span class="badge-custom badge-secondary"><i class="bi bi-folder me-1"></i>Total: {{ $projects->count() }}</span>
        </div>
    </form>
</div>

@forelse($projects as $project)
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center {{ $project->status_project == 'Selesai' ? 'bg-success text-white' : 'bg-primary text-white' }}">
            <div>
                <i class="bi bi-folder me-2"></i>
                <strong>{{ $project->no_project }}</strong>
                <span class="badge bg-light text-dark ms-2">{{ $project->status_project }}</span>
            </div>
            <div>
                <span class="badge bg-light text-dark">Deadline: {{ date('d-m-Y', strtotime($project->deadline_project)) }}</span>
            </div>
        </div>
        <div class="card-body">
            @foreach($project->purchaseOrders as $po)
                <div class="card mb-3">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bi bi-cart me-2"></i>
                            <strong>{{ $po->no_po }}</strong>
                            <span class="badge-custom badge-{{ $po->status_po == 'Selesai' ? 'success' : ($po->status_po == 'Diproses' ? 'info' : 'warning') }} ms-2">
                                {{ $po->status_po }}
                            </span>
                            <span class="text-muted ms-2">Supplier: {{ $po->supplier->nama_supplier ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-muted">Deadline: {{ date('d-m-Y', strtotime($po->deadline_po)) }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        @foreach($po->detailPo as $detail)
                            <div class="border rounded p-3 mb-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $detail->produk->nama_produk ?? '-' }}</strong>
                                        <span class="badge-custom badge-secondary ms-2">{{ $detail->produk->kode_produk ?? '-' }}</span>
                                        <div class="text-muted small">Ukuran: {{ $detail->produk->ukuran ?? '-' }} | Bahan: {{ $detail->produk->bahan ?? '-' }}</div>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge-custom badge-info">Qty: {{ $detail->qty_selesai }} / {{ $detail->qty_po }}</span>
                                        <div class="text-muted small">{{ $detail->progressProduksi->count() }} progress</div>
                                    </div>
                                </div>
                                
                                <div class="mt-2 d-flex gap-2">
                                    @if(auth()->user()->role === 'Staff')
                                        <a href="{{ route('staff.progress.create', $detail->id) }}" class="btn btn-primary btn-sm">
                                            <i class="bi bi-plus-circle"></i> Input Progress
                                        </a>
                                        <a href="{{ route('staff.progress.show', $detail->id) }}" class="btn btn-info btn-sm">
                                            <i class="bi bi-list"></i> Riwayat
                                        </a>
                                    @else
                                        <a href="{{ route('admin.progress.show', $detail->id) }}" class="btn btn-info btn-sm">
                                            <i class="bi bi-list"></i> Lihat Riwayat
                                        </a>
                                    @endif
                                </div>
                                
                                @if($detail->progressProduksi->count() > 0)
                                    <div class="mt-2 text-muted small">
                                        Progress terakhir: 
                                        {{ date('d-m-Y', strtotime($detail->progressProduksi->last()->tanggal_progress)) }} - 
                                        {{ $detail->progressProduksi->last()->tahap_produksi }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@empty
    <div class="empty-state text-center py-5">
        <div class="empty-icon"><i class="bi bi-inbox"></i></div>
        <div class="empty-title">Belum Ada Data Progress</div>
        <div class="empty-desc">Silakan buat project dan purchase order terlebih dahulu</div>
        @if(auth()->user()->role === 'Admin')
            <a href="{{ route('admin.project.create') }}" class="btn btn-primary mt-3">
                <i class="bi bi-plus-circle"></i> Tambah Project
            </a>
        @endif
    </div>
@endforelse
{{-- PAGINATION --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center p-3 border-top gap-2">
            <div class="pagination-info">
                <i class="bi bi-info-circle me-1"></i>
                Menampilkan {{ $projects->firstItem() ?? 0 }} - {{ $projects->lastItem() ?? 0 }} 
                dari {{ $projects->total() }} Ref Project
            </div>
            <div>
                {{ $projects->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection