{{-- resources/views/staff/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard Staff')
@section('page-title', 'Dashboard')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-cart"></i></div>
            <div class="stat-label">PO Aktif</div>
            <div class="stat-value">{{ $total_po_aktif ?? 0 }}</div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="stat-card">
            <div class="stat-icon yellow"><i class="bi bi-clock"></i></div>
            <div class="stat-label">PO Menunggu</div>
            <div class="stat-value">{{ $total_po_menunggu ?? 0 }}</div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-arrow-repeat"></i></div>
            <div class="stat-label">PO Diproses</div>
            <div class="stat-value">{{ $total_po_diproses ?? 0 }}</div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-check-circle"></i></div>
            <div class="stat-label">PO Selesai</div>
            <div class="stat-value">{{ $total_po_selesai ?? 0 }}</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-clock me-2"></i>PO Deadline Terdekat</span>
                <span class="badge-custom badge-secondary">{{ count($deadline_po ?? []) }} PO</span>
            </div>
            <div class="card-body p-0">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No PO</th>
                                <th>Project</th>
                                <th>Supplier</th>
                                <th>Deadline</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($deadline_po ?? [] as $po)
                                <tr>
                                    <td><strong>{{ $po->no_po }}</strong></td>
                                    <td>{{ $po->project->no_project ?? '-' }}</td>
                                    <td>{{ $po->supplier->nama_supplier ?? '-' }}</td>
                                    <td>{{ date('d-m-Y', strtotime($po->deadline_po)) }}</td>
                                    <td>
                                        <span class="badge-custom badge-{{ $po->status_po == 'Selesai' ? 'success' : ($po->status_po == 'Diproses' ? 'info' : 'warning') }}">
                                            {{ $po->status_po }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center py-4 text-muted">Tidak ada PO aktif</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-clock-history me-2"></i>Progress Terbaru Saya</span>
                <span class="badge-custom badge-secondary">{{ count($progress_terbaru ?? []) }} aktivitas</span>
            </div>
            <div class="card-body p-0">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>PO</th>
                                <th>Produk</th>
                                <th>Tahap</th>
                                <th>Qty</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($progress_terbaru ?? [] as $progress)
                                <tr>
                                    <td>{{ date('d-m-Y', strtotime($progress->tanggal_progress)) }}</td>
                                    <td><strong>{{ $progress->detailPo->purchaseOrder->no_po ?? '-' }}</strong></td>
                                    <td>{{ $progress->detailPo->produk->nama_produk ?? '-' }}</td>
                                    <td>{{ $progress->tahap_produksi }}</td>
                                    <td>{{ $progress->qty_progress }}</td>
                                    <td>
                                        <span class="badge-custom badge-{{ $progress->status_progress == 'Selesai' ? 'success' : 'warning' }}">
                                            {{ $progress->status_progress }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center py-4 text-muted">Belum ada progress yang dilaporkan</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection