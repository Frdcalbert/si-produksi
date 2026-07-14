{{-- resources/views/admin/project/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Tambah Project')
@section('page-title', 'Tambah Project')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.project.store') }}" method="POST">
            @csrf
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="no_project" class="form-label">No Project <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('no_project') is-invalid @enderror" 
                           id="no_project" name="no_project" value="{{ old('no_project') }}" 
                           placeholder="PRJ-0001" required>
                    @error('no_project')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <small class="text-muted">Format: PRJ-XXXX</small>
                </div>
                <div class="col-md-6">
                    <label for="status_project" class="form-label">Status <span class="text-danger">*</span></label>
                    <select class="form-select @error('status_project') is-invalid @enderror" 
                            id="status_project" name="status_project" required>
                        <option value="Berjalan" {{ old('status_project') == 'Berjalan' ? 'selected' : '' }}>Berjalan</option>
                        <option value="Selesai" {{ old('status_project') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                    @error('status_project')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="tanggal_project" class="form-label">Tanggal Project <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('tanggal_project') is-invalid @enderror" 
                           id="tanggal_project" name="tanggal_project" value="{{ old('tanggal_project', date('Y-m-d')) }}" required>
                    @error('tanggal_project')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="deadline_project" class="form-label">Deadline Project <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('deadline_project') is-invalid @enderror" 
                           id="deadline_project" name="deadline_project" value="{{ old('deadline_project') }}" required>
                    @error('deadline_project')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label for="keterangan" class="form-label">Keterangan</label>
                    <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                              id="keterangan" name="keterangan" rows="3">{{ old('keterangan') }}</textarea>
                    @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <hr>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                <a href="{{ route('admin.project.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection