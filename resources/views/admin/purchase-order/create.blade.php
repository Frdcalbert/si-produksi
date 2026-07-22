{{-- resources/views/admin/purchase-order/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Tambah Purchase Order')
@section('page-title', 'Tambah Purchase Order')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.purchase-order.store') }}" method="POST" id="poForm">
            @csrf
            
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="no_po" class="form-label">No PO <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('no_po') is-invalid @enderror" 
                           id="no_po" name="no_po" value="{{ old('no_po') }}" placeholder="PO-0001" required>
                    @error('no_po')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <small class="text-muted">Format: PO-XXXX</small>
                </div>
                <div class="col-md-4">
                    <label for="project_id" class="form-label">Project <span class="text-danger">*</span></label>
                    <select class="form-select select2 @error('project_id') is-invalid @enderror" 
                            id="project_id" name="project_id" required>
                        <option value="">Pilih Project</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                {{ $project->no_project }}
                            </option>
                        @endforeach
                    </select>
                    @error('project_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label for="supplier_id" class="form-label">Supplier <span class="text-danger">*</span></label>
                    <select class="form-select select2 @error('supplier_id') is-invalid @enderror" 
                            id="supplier_id" name="supplier_id" required>
                        <option value="">Pilih Supplier</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->nama_supplier }}
                            </option>
                        @endforeach
                    </select>
                    @error('supplier_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="tanggal_po" class="form-label">Tanggal PO <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('tanggal_po') is-invalid @enderror" 
                           id="tanggal_po" name="tanggal_po" value="{{ old('tanggal_po', date('Y-m-d')) }}" required>
                    @error('tanggal_po')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="deadline_po" class="form-label">Deadline PO <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('deadline_po') is-invalid @enderror" 
                           id="deadline_po" name="deadline_po" value="{{ old('deadline_po') }}" required>
                    @error('deadline_po')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label for="catatan" class="form-label">Catatan</label>
                    <textarea class="form-control @error('catatan') is-invalid @enderror" 
                              id="catatan" name="catatan" rows="2">{{ old('catatan') }}</textarea>
                    @error('catatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <hr>
            <h5 class="mb-3"><i class="bi bi-box me-2"></i>Detail Produk</h5>
            
            <div id="detailProdukContainer">
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
                        <button type="button" class="btn btn-danger remove-detail" style="display:none;">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </div>
                </div>
            </div>

            <div class="mt-2">
                <button type="button" class="btn btn-success" id="addDetailProduk">
                    <i class="bi bi-plus-circle"></i> Tambah Produk
                </button>
            </div>

            <hr>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan PO</button>
                <a href="{{ route('admin.purchase-order.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('detailProdukContainer');
    const addButton = document.getElementById('addDetailProduk');
    const form = document.getElementById('poForm');
    
    // ========== TEMPLATE ==========
    function getNewDetailTemplate() {
        return `
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
    }
    
    // ========== SELECT2 INIT ==========
    function initSelect2(element) {
        $(element).find('.select2').each(function() {
            var placeholder = $(this).data('placeholder') || 'Pilih...';
            try {
                $(this).select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder: placeholder,
                    allowClear: true,
                    dropdownParent: $('body')
                });
            } catch(e) {
                // ignore
            }
        });
    }
    
    // ========== UPDATE TOMBOL HAPUS ==========
    function updateRemoveButtons() {
        const details = container.querySelectorAll('.detail-produk');
        details.forEach((detail, index) => {
            const removeBtn = detail.querySelector('.remove-detail');
            if (removeBtn) {
                if (details.length > 1 && index > 0) {
                    removeBtn.style.display = 'inline-block';
                } else {
                    removeBtn.style.display = 'none';
                }
            }
        });
    }
    
    // ========== TAMBAH PRODUK ==========
    addButton.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const newDetail = document.createElement('div');
        newDetail.innerHTML = getNewDetailTemplate().trim();
        const newRow = newDetail.firstElementChild;
        
        newRow.querySelectorAll('input').forEach(el => {
            el.value = '';
        });
        newRow.querySelectorAll('select').forEach(el => {
            el.selectedIndex = 0;
        });
        
        container.appendChild(newRow);
        initSelect2(newRow);
        updateRemoveButtons();
    });
    
    // ========== HAPUS PRODUK ==========
    container.addEventListener('click', function(e) {
        const btn = e.target.closest('.remove-detail');
        if (btn) {
            e.preventDefault();
            e.stopPropagation();
            
            const detail = btn.closest('.detail-produk');
            if (!detail) return;
            
            const totalDetails = container.querySelectorAll('.detail-produk').length;
            if (totalDetails <= 1) {
                alert('Minimal harus ada 1 produk!');
                return;
            }
            
            if (!confirm('Yakin ingin menghapus produk ini?')) {
                return;
            }
            
            $(detail).find('.select2').each(function() {
                try {
                    $(this).select2('destroy');
                } catch(e) {
                    // ignore
                }
            });
            
            detail.remove();
            updateRemoveButtons();
        }
    });
    
    // ========== INISIALISASI ==========
    initSelect2(container);
    updateRemoveButtons();
});
</script>
@endpush