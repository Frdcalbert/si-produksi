{{-- resources/views/admin/laporan/po.blade.php --}}
@extends('layouts.app')

@section('title', 'Laporan Purchase Order')
@section('page-title', 'Laporan Purchase Order')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <span class="text-muted">Total PO: {{ $totalPO ?? 0 }} | Selesai: {{ $totalSelesai ?? 0 }} | Proses: {{ $totalProses ?? 0 }} | Menunggu: {{ $totalMenunggu ?? 0 }}</span>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.laporan.po.export', request()->all()) }}" class="btn btn-danger">
            <i class="bi bi-file-earmark-pdf"></i> Cetak PDF
        </a>
    </div>
</div>

<div class="filter-section">
    <form action="{{ route('admin.laporan.po') }}" method="GET" class="row g-3 align-items-end">
        <div class="col-md-4">
            <label class="filter-label">Status</label>
            <select name="filter" class="form-select">
                <option value="Semua" {{ ($filter ?? 'Semua') == 'Semua' ? 'selected' : '' }}>Semua</option>
                <option value="Menunggu" {{ ($filter ?? '') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                <option value="Diproses" {{ ($filter ?? '') == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                <option value="Selesai" {{ ($filter ?? '') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="filter-label">Tanggal Awal</label>
            <input type="date" name="tanggal_awal" class="form-control" value="{{ $tanggal_awal ?? '' }}">
        </div>
        <div class="col-md-4">
            <label class="filter-label">Tanggal Akhir</label>
            <input type="date" name="tanggal_akhir" class="form-control" value="{{ $tanggal_akhir ?? '' }}">
        </div>
        <div class="col-md-8">
            <label class="filter-label">Cari</label>
            <div class="input-group">
                <input type="text" name="search" class="form-control" 
                       placeholder="Cari No PO / Project / Supplier..." value="{{ $search ?? '' }}">
                <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
                @if(isset($search) && $search)
                    <a href="{{ route('admin.laporan.po', ['filter' => $filter ?? 'Semua', 'tanggal_awal' => $tanggal_awal ?? '', 'tanggal_akhir' => $tanggal_akhir ?? '']) }}" class="btn btn-secondary"><i class="bi bi-x-circle"></i></a>
                @endif
            </div>
        </div>
        <div class="col-md-4 text-end">
            <span class="badge-custom badge-secondary"><i class="bi bi-cart me-1"></i>Total: {{ $purchaseOrders->sum(function($po) { return $po->detailPo->count(); }) ?? 0 }}</span>
        </div>
    </form>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>NO PO</th>
                        <th>PROJECT</th>
                        <th>TGL PO</th>
                        <th>DEADLINE</th>
                        <th>SUPPLIER</th>
                        <th>TOTAL PRODUK</th>
                        <th>TOTAL QTY</th>
                        <th>STATUS</th>
                        <th>KETERANGAN</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchaseOrders as $po)
                            <tr>
                                <td><strong>{{ $po->no_po }}</strong></td>
                                <td>{{ $po->project->no_project ?? '-' }}</td>
                                <td>{{ date('d-m-Y', strtotime($po->tanggal_po)) }}</td>
                                <td>{{ date('d-m-Y', strtotime($po->deadline_po)) }}</td>
                                <td>{{ $po->supplier->nama_supplier ?? '-' }}</td>
                                <td>{{ $po->detailPo->count() }}</td>
                                <td>{{ $po->detailPo->sum('qty_po') }}</td>
                                <td>
                                    <span class="badge-custom badge-{{ $po->status_po == 'Selesai' ? 'success' : ($po->status_po == 'Diproses' ? 'info' : ($po->status_po == 'Menunggu' ? 'warning' : 'secondary')) }}">
                                        {{ $po->status_po }}
                                    </span>
                                </td>
                                <td>{{ $po->catatan ?? '-' }}</td>
                            </tr>
                    @empty
                        <tr><td colspan="10" class="text-center py-4 text-muted">Tidak ada data purchase order</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
{{-- PAGINATION --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center p-3 border-top gap-2">
            <div class="pagination-info">
                <i class="bi bi-info-circle me-1"></i>
                Menampilkan {{ $purchaseOrders->firstItem() ?? 0 }} - {{ $purchaseOrders->lastItem() ?? 0 }} 
                dari {{ $purchaseOrders->total() }} data
            </div>
            <div>
                {{ $purchaseOrders->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection