{{-- resources/views/admin/laporan/produk.blade.php --}}
@extends('layouts.app')

@section('title', 'Laporan Data Produk')
@section('page-title', 'Laporan Data Produk')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <span class="text-muted">Total Produk: {{ $totalProduk ?? 0 }} </span>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.laporan.produk.export') }}" class="btn btn-danger">
            <i class="bi bi-file-earmark-pdf"></i> Cetak PDF
        </a>
    </div>
</div>

<div class="filter-section">
    <form action="{{ route('admin.laporan.produk') }}" method="GET" class="row g-3 align-items-end">
        <div class="col-md-8">
            <label class="filter-label">Cari Produk</label>
            <div class="input-group">
                <input type="text" name="search" class="form-control" 
                       placeholder="Cari kode / nama produk..." value="{{ $search ?? '' }}">
                <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
                @if(isset($search) && $search)
                    <a href="{{ route('admin.laporan.produk') }}" class="btn btn-secondary"><i class="bi bi-x-circle"></i></a>
                @endif
            </div>
        </div>
        <div class="col-md-4 text-end">
            <span class="badge-custom badge-secondary"><i class="bi bi-box me-1"></i>Total: {{ $produk->count() }}</span>
        </div>
    </form>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>KODE</th>
                        <th>NAMA PRODUK</th>
                        <th>UKURAN</th>
                        <th>BAHAN</th>
                        <th>SATUAN</th>
                        <th>KETERANGAN</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($produk as $p)
                        <tr>
                            <td><strong>{{ $p->kode_produk }}</strong></td>
                            <td>{{ $p->nama_produk }}</td>
                            <td>{{ $p->ukuran }}</td>
                            <td>{{ $p->bahan }}</td>
                            <td>{{ $p->satuan }}</td>
                            <td>{{ $p->keterangan ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-4 text-muted">Tidak ada data produk</td></tr>
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
                Menampilkan {{ $produk->firstItem() ?? 0 }} - {{ $produk->lastItem() ?? 0 }} 
                dari {{ $produk->total() }} data
            </div>
            <div>
                {{ $produk->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection