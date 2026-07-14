{{-- resources/views/staff/progress/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Input Progress')
@section('page-title', 'Input Progress')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="mb-3 p-3 bg-light rounded">
            <div class="row">
                <div class="col-md-4">
                    <span class="text-muted small">PO</span>
                    <div class="fw-bold">{{ $detailPo->purchaseOrder->no_po }}</div>
                </div>
                <div class="col-md-4">
                    <span class="text-muted small">Produk</span>
                    <div class="fw-bold">{{ $detailPo->produk->nama_produk ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                    <span class="text-muted small">Qty</span>
                    <div class="fw-bold">{{ $detailPo->qty_selesai }} / {{ $detailPo->qty_po }} pcs</div>
                </div>
            </div>
        </div>

        <form action="{{ route('staff.progress.store') }}" method="POST">
            @csrf
            <input type="hidden" name="detail_po_id" value="{{ $detailPo->id }}">
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="tanggal_progress" class="form-label">Tanggal Progress <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('tanggal_progress') is-invalid @enderror" 
                           id="tanggal_progress" name="tanggal_progress" value="{{ date('Y-m-d') }}" required>
                    @error('tanggal_progress')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="tahap_produksi" class="form-label">Tahap Produksi <span class="text-danger">*</span></label>
                    <select class="form-select @error('tahap_produksi') is-invalid @enderror" 
                            id="tahap_produksi" name="tahap_produksi" required>
                        <option value="">Pilih Tahap</option>
                        <option value="Tahap 1">Tahap 1</option>
                        <option value="Tahap 2">Tahap 2</option>
                        <option value="Tahap 3">Tahap 3</option>
                        <option value="Tahap 4">Tahap 4</option>
                        <option value="Tahap 5">Tahap 5</option>
                        <option value="Tahap 6">Tahap 6</option>
                        <option value="Finishing">Finishing</option>
                        <option value="QC">QC</option>
                        <option value="Masuk Gudang">Masuk Gudang</option>
                    </select>
                    @error('tahap_produksi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="qty_progress" class="form-label">Qty Progress <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('qty_progress') is-invalid @enderror" 
                           id="qty_progress" name="qty_progress" min="1" required>
                    @error('qty_progress')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="status_progress" class="form-label">Status Progress <span class="text-danger">*</span></label>
                    <select class="form-select @error('status_progress') is-invalid @enderror" 
                            id="status_progress" name="status_progress" required>
                        <option value="Proses">Proses</option>
                        <option value="Selesai">Selesai</option>
                    </select>
                    @error('status_progress')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label for="dokumentasi" class="form-label">Dokumentasi (Link Google Drive)</label>
                    <input type="url" class="form-control @error('dokumentasi') is-invalid @enderror" 
                           id="dokumentasi" name="dokumentasi" placeholder="https://drive.google.com/...">
                    @error('dokumentasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label for="catatan" class="form-label">Catatan</label>
                    <textarea class="form-control @error('catatan') is-invalid @enderror" 
                              id="catatan" name="catatan" rows="3">{{ old('catatan') }}</textarea>
                    @error('catatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <hr>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Progress</button>
                <a href="{{ route('staff.progress.show', $detailPo->id) }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection