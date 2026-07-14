{{-- resources/views/admin/supplier/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Data Supplier')
@section('page-title', 'Data Supplier')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <span class="text-muted">Kelola Data Supplier</span>
    </div>
    <a href="{{ route('admin.supplier.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Supplier
    </a>
</div>

<div class="filter-section">
    <form action="{{ route('admin.supplier.index') }}" method="GET" class="row g-3 align-items-end">
        <div class="col-md-6">
            <label class="filter-label">Cari Supplier</label>
            <div class="input-group">
                <input type="text" name="search" class="form-control" 
                       placeholder="Cari nama / alamat / no HP..." value="{{ $search ?? '' }}">
                <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
                @if(isset($search) && $search)
                    <a href="{{ route('admin.supplier.index') }}" class="btn btn-secondary"><i class="bi bi-x-circle"></i></a>
                @endif
            </div>
        </div>
        <div class="col-md-6 text-end">
            <span class="badge-custom badge-secondary"><i class="bi bi-truck me-1"></i>Total: {{ $suppliers->count() }}</span>
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
                        <th>Nama Supplier</th>
                        <th>Alamat</th>
                        <th>No HP</th>
                        <th>Email</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suppliers as $key => $supplier)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td><strong>{{ $supplier->nama_supplier }}</strong></td>
                            <td>{{ Str::limit($supplier->alamat, 40) }}</td>
                            <td>{{ $supplier->no_hp }}</td>
                            <td>{{ $supplier->email ?? '-' }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.supplier.edit', $supplier->id) }}" class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.supplier.destroy', $supplier->id) }}" method="POST" class="d-inline">
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
                        <tr><td colspan="6" class="text-center py-4 text-muted">Tidak ada data supplier</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
{{-- PAGINATION --}}
{{-- PAGINATION --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center p-3 border-top gap-2">
            <div class="pagination-info">
                <i class="bi bi-info-circle me-1"></i>
                Menampilkan {{ $suppliers->firstItem() ?? 0 }} - {{ $suppliers->lastItem() ?? 0 }} 
                dari {{ $suppliers->total() }} data
            </div>
            <div>
                {{ $suppliers->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection