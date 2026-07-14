{{-- resources/views/admin/purchase-order/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Data Purchase Order')
@section('page-title', 'Data Purchase Order')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <span class="text-muted">Kelola Data Purchase Order</span>
    </div>
    <a href="{{ route('admin.purchase-order.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah PO
    </a>
</div>

<div class="filter-section">
    <form action="{{ route('admin.purchase-order.index') }}" method="GET" class="row g-3 align-items-end">
        <div class="col-md-3">
            <label class="filter-label">Filter Status</label>
            <select name="filter" class="form-select" onchange="this.form.submit()">
                <option value="Semua" {{ ($filter ?? 'Semua') == 'Semua' ? 'selected' : '' }}>Semua</option>
                <option value="Menunggu" {{ ($filter ?? '') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                <option value="Diproses" {{ ($filter ?? '') == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                <option value="Selesai" {{ ($filter ?? '') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
        </div>
        <div class="col-md-6">
            <label class="filter-label">Cari PO</label>
            <div class="input-group">
                <input type="text" name="search" class="form-control" 
                       placeholder="Cari no PO / project / supplier..." value="{{ $search ?? '' }}">
                <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
                @if(isset($search) && $search)
                    <a href="{{ route('admin.purchase-order.index', ['filter' => $filter ?? 'Semua']) }}" class="btn btn-secondary"><i class="bi bi-x-circle"></i></a>
                @endif
            </div>
        </div>
        <div class="col-md-3 text-end">
            <span class="badge-custom badge-secondary"><i class="bi bi-cart me-1"></i>Total: {{ $purchaseOrders->count() }}</span>
        </div>
    </form>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>No PO</th>
                        <th>Project</th>
                        <th>Supplier</th>
                        <th>Tanggal</th>
                        <th>Deadline</th>
                        <th>Status</th>
                        <th>Progress</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchaseOrders as $po)
                        <tr>
                            <td><strong>{{ $po->no_po }}</strong></td>
                            <td>{{ $po->project->no_project ?? '-' }}</td>
                            <td>{{ $po->supplier->nama_supplier ?? '-' }}</td>
                            <td>{{ date('d-m-Y', strtotime($po->tanggal_po)) }}</td>
                            <td>{{ date('d-m-Y', strtotime($po->deadline_po)) }}</td>
                            <td>
                                <span class="badge-custom badge-{{ $po->status_po == 'Selesai' ? 'success' : ($po->status_po == 'Diproses' ? 'info' : 'warning') }}">
                                    {{ $po->status_po }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $totalQty = $po->detailPo->sum('qty_po');
                                    $totalSelesai = $po->detailPo->sum('qty_selesai');
                                    $percentage = $totalQty > 0 ? round(($totalSelesai / $totalQty) * 100) : 0;
                                @endphp
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress" style="width:80px; height:6px;">
                                        <div class="progress-bar" role="progressbar" 
                                             style="width: {{ $percentage }}%; background: {{ $percentage == 100 ? '#16a34a' : '#1d4ed8' }};"></div>
                                    </div>
                                    <span class="text-muted" style="font-size:11px;">{{ $percentage }}%</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.purchase-order.show', $po->id) }}" class="btn btn-info btn-sm">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.purchase-order.edit', $po->id) }}" class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.purchase-order.destroy', $po->id) }}" method="POST" class="d-inline">
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
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">Tidak ada data purchase order</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
{{-- PAGINATION --}}
        <div class="d-flex justify-content-between align-items-center p-3 border-top">
            <div class="text-muted small">
                Menampilkan {{ $purchaseOrders->firstItem() ?? 0 }} - {{ $purchaseOrders->lastItem() ?? 0 }} 
                dari {{ $purchaseOrders->total() }} data
            </div>
            <div>
                {{ $purchaseOrders->links() }}
            </div>
        </div>
    </div>
</div>
@endsection