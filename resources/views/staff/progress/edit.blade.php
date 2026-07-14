{{-- resources/views/staff/progress/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Progress')
@section('page-title', 'Edit Progress')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> 
            <strong>Perhatian:</strong> Nama pelapor akan otomatis berubah menjadi <strong>{{ auth()->user()->nama }}</strong> setelah Anda menyimpan perubahan.
        </div>
        <form action="{{ route('staff.progress.update', $progress->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="tanggal_progress" class="form-label">Tanggal Progress <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('tanggal_progress') is-invalid @enderror" 
                           id="tanggal_progress" name="tanggal_progress" value="{{ date('Y-m-d', strtotime($progress->tanggal_progress)) }}" required>
                    @error('tanggal_progress')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="tahap_produksi" class="form-label">Tahap Produksi <span class="text-danger">*</span></label>
                    <select class="form-select @error('tahap_produksi') is-invalid @enderror" 
                            id="tahap_produksi" name="tahap_produksi" required>
                        <option value="Tahap 1" {{ $progress->tahap_produksi == 'Tahap 1' ? 'selected' : '' }}>Tahap 1</option>
                        <option value="Tahap 2" {{ $progress->tahap_produksi == 'Tahap 2' ? 'selected' : '' }}>Tahap 2</option>
                        <option value="Tahap 3" {{ $progress->tahap_produksi == 'Tahap 3' ? 'selected' : '' }}>Tahap 3</option>
                        <option value="Tahap 4" {{ $progress->tahap_produksi == 'Tahap 4' ? 'selected' : '' }}>Tahap 4</option>
                        <option value="Tahap 5" {{ $progress->tahap_produksi == 'Tahap 5' ? 'selected' : '' }}>Tahap 5</option>
                        <option value="Tahap 6" {{ $progress->tahap_produksi == 'Tahap 6' ? 'selected' : '' }}>Tahap 6</option>
                        <option value="Finishing" {{ $progress->tahap_produksi == 'Finishing' ? 'selected' : '' }}>Finishing</option>
                        <option value="QC" {{ $progress->tahap_produksi == 'QC' ? 'selected' : '' }}>QC</option>
                        <option value="Masuk Gudang" {{ $progress->tahap_produksi == 'Masuk Gudang' ? 'selected' : '' }}>Masuk Gudang</option>
                    </select>
                    @error('tahap_produksi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="qty_progress" class="form-label">Qty Progress <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('qty_progress') is-invalid @enderror" 
                           id="qty_progress" name="qty_progress" min="1" value="{{ $progress->qty_progress }}" required>
                    @error('qty_progress')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="status_progress" class="form-label">Status Progress <span class="text-danger">*</span></label>
                    <select class="form-select @error('status_progress') is-invalid @enderror" 
                            id="status_progress" name="status_progress" required>
                        <option value="Proses" {{ $progress->status_progress == 'Proses' ? 'selected' : '' }}>Proses</option>
                        <option value="Selesai" {{ $progress->status_progress == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                    @error('status_progress')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label for="dokumentasi" class="form-label">Dokumentasi (Link Google Drive)</label>
                    <input type="url" class="form-control @error('dokumentasi') is-invalid @enderror" 
                           id="dokumentasi" name="dokumentasi" placeholder="https://drive.google.com/..." value="{{ $progress->dokumentasi }}">
                    @error('dokumentasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label for="catatan" class="form-label">Catatan</label>
                    <textarea class="form-control @error('catatan') is-invalid @enderror" 
                              id="catatan" name="catatan" rows="3">{{ $progress->catatan }}</textarea>
                    @error('catatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <hr>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-warning"><i class="bi bi-save"></i> Update Progress</button>
                <a href="{{ route('staff.progress.show', $progress->detail_po_id) }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection