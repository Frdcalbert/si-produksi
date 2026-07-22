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

        <form action="{{ route('admin.purchase-order.update', $purchaseOrder->id) }}" method="POST" id="poForm">
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
            
            {{-- Container Detail Produk --}}
            <div id="detailProdukContainer">
                @foreach($purchaseOrder->detailPo as $key => $detail)
                    <div class="row g-2 mb-2 detail-produk align-items-end">
                        <div class="col-md-5">
                            <label class="form-label">Produk <span class="text-danger">*</span></label>
                            <select class="form-select select2 produk-select" name="produk_id[]" required>
                                <option value="">Pilih Produk</option>
                                @foreach($produks as $produk)
                                    <option value="{{ $produk->id }}" {{ $detail->produk_id == $produk->id ? 'selected' : '' }}>
                                        {{ $produk->kode_produk }} - {{ $produk->nama_produk }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Qty PO <span class="text-danger">*</span></label>
                            <input type="number" class="form-control qty-po" name="qty_po[]" min="1" value="{{ $detail->qty_po }}" required>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-danger remove-detail" {{ $loop->first ? 'style="display:none;"' : '' }}>
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Tombol Tambah Produk --}}
            @if($purchaseOrder->status_po != 'Selesai')
                <div class="mt-2">
                    <button type="button" class="btn btn-success" id="addDetailProduk">
                        <i class="bi bi-plus-circle"></i> Tambah Produk
                    </button>
                </div>
            @endif

            <hr>
            @if($purchaseOrder->status_po != 'Selesai')
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-warning" id="btnUpdate">
                        <i class="bi bi-save"></i> Update PO
                    </button>
                    <a href="{{ route('admin.purchase-order.show', $purchaseOrder->id) }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            @else
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.purchase-order.show', $purchaseOrder->id) }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali ke Detail
                    </a>
                </div>
            @endif
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
    const btnUpdate = document.getElementById('btnUpdate');
    
    // Jika tidak ada tombol update (PO selesai), exit
    if (!btnUpdate) return;
    
    // Fungsi untuk mendapatkan template detail produk baru (KOSONG)
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
    
    // Fungsi untuk menginisialisasi Select2 di elemen baru
    function initSelect2(element) {
        $(element).find('.select2').each(function() {
            var placeholder = $(this).data('placeholder') || 'Pilih...';
            $(this).select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: placeholder,
                allowClear: true,
                dropdownParent: $('body')
            });
        });
    }
    
    // Fungsi update tombol hapus
    function updateRemoveButtons() {
        const details = container.querySelectorAll('.detail-produk');
        details.forEach((detail, index) => {
            const removeBtn = detail.querySelector('.remove-detail');
            if (details.length > 1 && index > 0) {
                removeBtn.style.display = 'inline-block';
            } else {
                removeBtn.style.display = 'none';
            }
        });
    }
    
    // Tombol Tambah Produk (jika ada)
    if (addButton) {
        addButton.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Buat elemen baru dari template
            const newDetail = document.createElement('div');
            newDetail.innerHTML = getNewDetailTemplate().trim();
            const newRow = newDetail.firstElementChild;
            
            // Reset nilai input
            newRow.querySelectorAll('input').forEach(el => {
                el.value = '';
            });
            
            // Reset dropdown ke opsi pertama (Pilih Produk)
            newRow.querySelectorAll('select').forEach(el => {
                el.selectedIndex = 0;
            });
            
            // Tambahkan ke container
            container.appendChild(newRow);
            
            // Inisialisasi Select2 untuk elemen baru
            initSelect2(newRow);
            
            // Update tombol hapus
            updateRemoveButtons();
        });
    }
    
    // Hapus detail produk
    container.addEventListener('click', function(e) {
        if (e.target.closest('.remove-detail')) {
            const detail = e.target.closest('.detail-produk');
            if (container.children.length > 1) {
                // Hapus Select2 instance sebelum remove
                $(detail).find('.select2').each(function() {
                    $(this).select2('destroy');
                });
                detail.remove();
                updateRemoveButtons();
            } else {
                alert('Minimal harus ada 1 produk!');
            }
        }
    });
    
    // ✅ PERBAIKAN: Tombol Update
    btnUpdate.addEventListener('click', function(e) {
        // Hapus Select2 instance sebelum submit agar tidak mengganggu
        $('.select2').each(function() {
            $(this).select2('destroy');
        });
        
        // Submit form
        form.submit();
    });
    
    // ✅ PERBAIKAN: Submit form normal kalau pakai Enter
    form.addEventListener('submit', function(e) {
        // Biarkan submit normal
        console.log('Form submitted');
    });
    
    // Inisialisasi Select2 untuk elemen yang sudah ada
    initSelect2(container);
    
    // Initial update tombol hapus
    updateRemoveButtons();
});
</script>
@endpush