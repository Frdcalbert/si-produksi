{{-- resources/views/admin/purchase-order/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Detail Purchase Order')
@section('page-title', 'Detail Purchase Order')

@section('content')
<div class="card mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3">
                <div class="text-muted small">No PO</div>
                <h5>{{ $purchaseOrder->no_po }}</h5>
            </div>
            <div class="col-md-3">
                <div class="text-muted small">Project</div>
                <h5>{{ $purchaseOrder->project->no_project ?? '-' }}</h5>
            </div>
            <div class="col-md-3">
                <div class="text-muted small">Supplier</div>
                <h5>{{ $purchaseOrder->supplier->nama_supplier ?? '-' }}</h5>
            </div>
            <div class="col-md-3">
                <div class="text-muted small">Status</div>
                <h5>
                    <span class="badge-custom badge-{{ $purchaseOrder->status_po == 'Selesai' ? 'success' : ($purchaseOrder->status_po == 'Diproses' ? 'info' : 'warning') }}">
                        {{ $purchaseOrder->status_po }}
                    </span>
                </h5>
            </div>
            <div class="col-md-3">
                <div class="text-muted small">Tanggal PO</div>
                <h5>{{ date('d-m-Y', strtotime($purchaseOrder->tanggal_po)) }}</h5>
            </div>
            <div class="col-md-3">
                <div class="text-muted small">Deadline</div>
                <h5>
                    {{ date('d-m-Y', strtotime($purchaseOrder->deadline_po)) }}
                    @if($purchaseOrder->deadline_po < now() && $purchaseOrder->status_po != 'Selesai')
                        <span class="badge-custom badge-danger ms-1">Terlambat</span>
                    @endif
                </h5>
            </div>
            <div class="col-md-6">
                <div class="text-muted small">Catatan</div>
                <h5>{{ $purchaseOrder->catatan ?? '-' }}</h5>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-box me-2"></i>Detail Produk</span>
        @php
            $totalQty = $purchaseOrder->detailPo->sum('qty_po');
            $totalSelesai = $purchaseOrder->detailPo->sum('qty_selesai');
            $percentage = $totalQty > 0 ? round(($totalSelesai / $totalQty) * 100) : 0;
        @endphp
        <span class="badge-custom badge-secondary">
            Progress: {{ $percentage }}% ({{ $totalSelesai }}/{{ $totalQty }})
        </span>
    </div>
    <div class="card-body p-0">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Produk</th>
                        <th>Nama Produk</th>
                        <th>Ukuran</th>
                        <th>Bahan</th>
                        <th>Qty PO</th>
                        <th>Qty Selesai</th>
                        <th>Sisa</th>
                        <th>Progress</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchaseOrder->detailPo as $key => $detail)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td><strong>{{ $detail->produk->kode_produk ?? '-' }}</strong></td>
                            <td>{{ $detail->produk->nama_produk ?? '-' }}</td>
                            <td>{{ $detail->produk->ukuran ?? '-' }}</td>
                            <td>{{ $detail->produk->bahan ?? '-' }}</td>
                            <td class="text-center">{{ $detail->qty_po }}</td>
                            <td class="text-center">{{ $detail->qty_selesai }}</td>
                            <td class="text-center">{{ $detail->qty_po - $detail->qty_selesai }}</td>
                            <td>
                                @php
                                    $detailPercentage = $detail->qty_po > 0 ? round(($detail->qty_selesai / $detail->qty_po) * 100) : 0;
                                @endphp
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress" style="width:80px; height:6px;">
                                        <div class="progress-bar" role="progressbar" 
                                             style="width: {{ $detailPercentage }}%; background: {{ $detailPercentage == 100 ? '#16a34a' : '#1d4ed8' }};"></div>
                                    </div>
                                    <span class="text-muted" style="font-size:11px;">{{ $detailPercentage }}%</span>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($purchaseOrder->detailPo->count() > 0)
    <div class="card mt-3">
        <div class="card-header">
            <i class="bi bi-clock-history me-2"></i>Riwayat Progress
        </div>
        <div class="card-body">
            @foreach($purchaseOrder->detailPo as $detail)
                @if($detail->progressProduksi->count() > 0)
                    <div class="card mb-2">
                        <div class="card-header bg-light">
                            <strong>{{ $detail->produk->nama_produk ?? '-' }}</strong>
                            <span class="badge-custom badge-secondary float-end">
                                {{ $detail->progressProduksi->count() }} progress
                            </span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-container">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Tahap</th>
                                            <th>Qty</th>
                                            <th>Status</th>
                                            <th>Pelapor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($detail->progressProduksi as $progress)
                                            <tr>
                                                <td>{{ date('d-m-Y', strtotime($progress->tanggal_progress)) }}</td>
                                                <td>
                                                    <span class="badge-custom badge-{{ 
                                                        $progress->tahap_produksi == 'Masuk Gudang' ? 'success' : 
                                                        ($progress->tahap_produksi == 'QC' ? 'info' : 'secondary') 
                                                    }}">
                                                        {{ $progress->tahap_produksi }}
                                                    </span>
                                                </td>
                                                <td>{{ $progress->qty_progress }}</td>
                                                <td>
                                                    <span class="badge-custom badge-{{ $progress->status_progress == 'Selesai' ? 'success' : 'warning' }}">
                                                        {{ $progress->status_progress }}
                                                    </span>
                                                </td>
                                                <td>{{ $progress->user->nama ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info">Belum ada progress untuk produk {{ $detail->produk->nama_produk ?? '-' }}</div>
                @endif
            @endforeach
        </div>
    </div>
@endif

<div class="mt-3 d-flex gap-2">
    <a href="{{ route('admin.purchase-order.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
    @if($purchaseOrder->status_po != 'Selesai')
        <a href="{{ route('admin.purchase-order.edit', $purchaseOrder->id) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
    @endif
</div>
@endsection