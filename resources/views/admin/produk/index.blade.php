{{-- resources/views/admin/produk/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Data Produk')
@section('page-title', 'Data Produk')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <span class="text-muted">Kelola Data Produk</span>
    </div>
    <a href="{{ route('admin.produk.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Produk
    </a>
</div>

<div class="filter-section">
    <form action="{{ route('admin.produk.index') }}" method="GET" class="row g-3 align-items-end">
        <div class="col-md-6">
            <label class="filter-label">Cari Produk</label>
            <div class="input-group">
                <input type="text" name="search" class="form-control" 
                       placeholder="Cari kode / nama produk..." value="{{ $search ?? '' }}">
                <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
                @if(isset($search) && $search)
                    <a href="{{ route('admin.produk.index') }}" class="btn btn-secondary"><i class="bi bi-x-circle"></i></a>
                @endif
            </div>
        </div>
        <div class="col-md-6 text-end">
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
                        <th>No</th>
                        <th>Kode Produk</th>
                        <th>Nama Produk</th>
                        <th>Ukuran</th>
                        <th>Bahan</th>
                        <th>Satuan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($produk as $key => $p)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td><strong>{{ $p->kode_produk }}</strong></td>
                            <td>{{ $p->nama_produk }}</td>
                            <td>{{ $p->ukuran }}</td>
                            <td>{{ $p->bahan }}</td>
                            <td>{{ $p->satuan }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.produk.edit', $p->id) }}" class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.produk.destroy', $p->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-4 text-muted">Tidak ada data produk</td></tr>
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