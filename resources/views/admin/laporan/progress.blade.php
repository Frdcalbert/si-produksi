{{-- resources/views/admin/laporan/progress.blade.php --}}
@extends('layouts.app')

@section('title', 'Laporan Progress Produksi')
@section('page-title', 'Laporan Progress Produksi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <span class="text-muted">Total Laporan : {{ $totalRecords ?? 0 }}</span>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.laporan.progress.export', request()->all()) }}" class="btn btn-danger">
            <i class="bi bi-file-earmark-pdf"></i> Cetak PDF
        </a>
    </div>
</div>

<div class="filter-section">
    <form action="{{ route('admin.laporan.progress') }}" method="GET" class="row g-3 align-items-end">
        <div class="col-md-3">
            <label class="filter-label">Status</label>
            <select name="filter" class="form-select">
                <option value="Semua" {{ ($filter ?? 'Semua') == 'Semua' ? 'selected' : '' }}>Semua</option>
                <option value="Menunggu" {{ ($filter ?? '') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                <option value="Diproses" {{ ($filter ?? '') == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                <option value="Selesai" {{ ($filter ?? '') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
        </div>
        <div class="col-md-6">
            <label class="filter-label">Cari</label>
            <div class="input-group">
                <input type="text" name="search" class="form-control" 
                       placeholder="Cari No PO / Project / Produk..." value="{{ $search ?? '' }}">
                <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
                @if(isset($search) && $search)
                    <a href="{{ route('admin.laporan.progress', ['filter' => $filter ?? 'Semua']) }}" class="btn btn-secondary"><i class="bi bi-x-circle"></i></a>
                @endif
            </div>
        </div>
        <div class="col-md-3 text-end">
            <span class="badge-custom badge-secondary"><i class="bi bi-list me-1"></i>Total: {{ $totalRecords ?? 0 }}</span>
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
                        <th>KODE PRODUK</th>
                        <th>NAMA PRODUK</th>
                        <th>TANGGAL PO</th>
                        <th>DEADLINE</th>
                        <th>PROJECT</th>
                        <th>STATUS</th>
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($detailPos as $detail)
                        <tr>
                            <td><strong>{{ $detail->purchaseOrder->no_po ?? '-' }}</strong></td>
                            <td>{{ $detail->produk->kode_produk ?? '-' }}</td>
                            <td>{{ $detail->produk->nama_produk ?? '-' }}</td>
                            <td>{{ date('d-m-Y', strtotime($detail->purchaseOrder->tanggal_po ?? now())) }}</td>
                            <td>{{ date('d-m-Y', strtotime($detail->purchaseOrder->deadline_po ?? now())) }}</td>
                            <td>{{ $detail->purchaseOrder->project->no_project ?? '-' }}</td>
                            <td>
                                <span class="badge-custom badge-{{ ($detail->purchaseOrder->status_po ?? '') == 'Selesai' ? 'success' : 
                                    (($detail->purchaseOrder->status_po ?? '') == 'Diproses' ? 'info' : 'warning') }}">
                                    {{ $detail->purchaseOrder->status_po ?? '-' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.laporan.detail_progress', $detail->id) }}" class="btn btn-info btn-sm">
                                    <i class="bi bi-eye"></i> Lihat
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">Tidak ada data progress</td>
                        </tr>
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
        Menampilkan {{ $detailPos->firstItem() ?? 0 }} - {{ $detailPos->lastItem() ?? 0 }} dari {{ $detailPos->total() ?? 0 }} laporan
    </div>
    <div>
        {{ $detailPos->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection