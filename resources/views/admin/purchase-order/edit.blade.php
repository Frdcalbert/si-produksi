{{-- resources/views/admin/purchase-order/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Purchase Order')
@section('page-title', 'Edit Purchase Order')

@section('content')
<div class="card">
    <div class="card-body">
        @if($purchaseOrder->status_po == 'Selesai')
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle"></i> 
                PO ini sudah selesai dan tidak dapat diubah. 
                <a href="{{ route('admin.purchase-order.show', $purchaseOrder->id) }}" class="alert-link">Lihat detail</a>
            </div>
        @endif

        <form action="{{ route('admin.purchase-order.update', $purchaseOrder->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="no_po" class="form-label">No PO <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('no_po') is-invalid @enderror" 
                           id="no_po" name="no_po" value="{{ old('no_po', $purchaseOrder->no_po) }}" 
                           {{ $purchaseOrder->status_po == 'Selesai' ? 'readonly' : '' }} required>
                    @error('no_po')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label for="project_id" class="form-label">Project <span class="text-danger">*</span></label>
                    <select class="form-select select2 @error('project_id') is-invalid @enderror" 
                            id="project_id" name="project_id" 
                            {{ $purchaseOrder->status_po == 'Selesai' ? 'disabled' : '' }} required>
                        <option value="">Pilih Project</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id', $purchaseOrder->project_id) == $project->id ? 'selected' : '' }}>
                                {{ $project->no_project }}
                            </option>
                        @endforeach
                    </select>
                    @error('project_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    @if($purchaseOrder->status_po == 'Selesai')
                        <input type="hidden" name="project_id" value="{{ $purchaseOrder->project_id }}">
                    @endif
                </div>
                <div class="col-md-4">
                    <label for="supplier_id" class="form-label">Supplier <span class="text-danger">*</span></label>
                    <select class="form-select select2 @error('supplier_id') is-invalid @enderror" 
                            id="supplier_id" name="supplier_id" 
                            {{ $purchaseOrder->status_po == 'Selesai' ? 'disabled' : '' }} required>
                        <option value="">Pilih Supplier</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id', $purchaseOrder->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->nama_supplier }}
                            </option>
                        @endforeach
                    </select>
                    @error('supplier_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    @if($purchaseOrder->status_po == 'Selesai')
                        <input type="hidden" name="supplier_id" value="{{ $purchaseOrder->supplier_id }}">
                    @endif
                </div>
                <div class="col-md-6">
                    <label for="tanggal_po" class="form-label">Tanggal PO <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('tanggal_po') is-invalid @enderror" 
                           id="tanggal_po" name="tanggal_po" value="{{ old('tanggal_po', date('Y-m-d', strtotime($purchaseOrder->tanggal_po))) }}" 
                           {{ $purchaseOrder->status_po == 'Selesai' ? 'readonly' : '' }} required>
                    @error('tanggal_po')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="deadline_po" class="form-label">Deadline PO <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('deadline_po') is-invalid @enderror" 
                           id="deadline_po" name="deadline_po" value="{{ old('deadline_po', date('Y-m-d', strtotime($purchaseOrder->deadline_po))) }}" 
                           {{ $purchaseOrder->status_po == 'Selesai' ? 'readonly' : '' }} required>
                    @error('deadline_po')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label for="catatan" class="form-label">Catatan</label>
                    <textarea class="form-control @error('catatan') is-invalid @enderror" 
                              id="catatan" name="catatan" rows="2" 
                              {{ $purchaseOrder->status_po == 'Selesai' ? 'readonly' : '' }}>{{ old('catatan', $purchaseOrder->catatan) }}</textarea>
                    @error('catatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <hr>
            <h5 class="mb-3"><i class="bi bi-box me-2"></i>Detail Produk</h5>
            
            {{-- Tabel Detail PO --}}
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Produk</th>
                            <th>Nama Produk</th>
                            <th>Qty PO</th>
                            <th>Qty Selesai</th>
                            @if($purchaseOrder->status_po != 'Selesai')
                                <th>Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchaseOrder->detailPo as $key => $detail)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $detail->produk->kode_produk ?? '-' }}</td>
                                <td>{{ $detail->produk->nama_produk ?? '-' }}</td>
                                <td>{{ $detail->qty_po }}</td>
                                <td>{{ $detail->qty_selesai }}</td>
                                @if($purchaseOrder->status_po != 'Selesai')
                                    <td>
                                        <div class="d-flex gap-1">
                                            {{-- Tombol Edit Detail --}}
                                            <button type="button" class="btn btn-warning btn-sm" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editDetailModal{{ $detail->id }}">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            
                                            {{-- Tombol Hapus Detail --}}
                                            <form action="{{ route('admin.purchase-order.destroy-detail', [$purchaseOrder->id, $detail->id]) }}" 
                                                  method="POST" class="d-inline"
                                                  onsubmit="return confirm('Yakin ingin menghapus detail ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Tombol Tambah Produk --}}
            @if($purchaseOrder->status_po != 'Selesai')
                <div class="mt-2">
                    <button type="button" class="btn btn-success" id="addDetailProduk">
                        <i class="bi bi-plus-circle"></i> Tambah Produk
                    </button>
                </div>
                
                {{-- Container untuk detail baru --}}
                <div id="detailProdukContainer" class="mt-3"></div>
            @endif

            <hr>
            @if($purchaseOrder->status_po != 'Selesai')
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-warning"><i class="bi bi-save"></i> Update PO</button>
                    <a href="{{ route('admin.purchase-order.show', $purchaseOrder->id) }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
                </div>
            @else
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.purchase-order.show', $purchaseOrder->id) }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali ke Detail</a>
                </div>
            @endif
        </form>
    </div>
</div>

{{-- Modal Edit Detail PO --}}
@foreach($purchaseOrder->detailPo as $detail)
    <div class="modal fade" id="editDetailModal{{ $detail->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.purchase-order.update-detail', [$purchaseOrder->id, $detail->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Detail Produk</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="produk_id_{{ $detail->id }}" class="form-label">Produk <span class="text-danger">*</span></label>
                            <select class="form-select select2" name="produk_id" id="produk_id_{{ $detail->id }}" required>
                                <option value="">Pilih Produk</option>
                                @foreach($produks as $produk)
                                    <option value="{{ $produk->id }}" {{ $detail->produk_id == $produk->id ? 'selected' : '' }}>
                                        {{ $produk->kode_produk }} - {{ $produk->nama_produk }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="qty_po_{{ $detail->id }}" class="form-label">Qty PO <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="qty_po" id="qty_po_{{ $detail->id }}" 
                                   value="{{ $detail->qty_po }}" min="1" required>
                            <small class="text-muted">Qty PO tidak boleh kurang dari qty selesai ({{ $detail->qty_selesai }})</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('detailProdukContainer');
    const addButton = document.getElementById('addDetailProduk');
    let detailCount = 0;
    
    if (addButton) {
        addButton.addEventListener('click', function() {
            const newDetail = `
                <div class="row g-2 mb-2 detail-produk align-items-end">
                    <div class="col-md-5">
                        <label class="form-label">Produk <span class="text-danger">*</span></label>
                        <select class="form-select select2 produk-select" name="produk_id[]" required>
                            <option value="">Pilih Produk</option>
                            @foreach($produks as $produk)
                                <option value="{{ $produk->id }}">{{ $produk->kode_produk }} - {{ $produk->nama_produk }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Qty PO <span class="text-danger">*</span></label>
                        <input type="number" class="form-control qty-po" name="qty_po[]" min="1" placeholder="Jumlah" required>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-danger remove-detail">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', newDetail);
            detailCount++;
        });
    }
    
    // Event delegation untuk hapus detail baru
    container.addEventListener('click', function(e) {
        if (e.target.closest('.remove-detail')) {
            e.target.closest('.detail-produk').remove();
        }
    });
});
</script>
@endpush