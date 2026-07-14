{{-- resources/views/admin/produk/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Produk')
@section('page-title', 'Edit Produk')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.produk.update', $produk->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="kode_produk" class="form-label">Kode Produk <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('kode_produk') is-invalid @enderror" 
                           id="kode_produk" name="kode_produk" value="{{ old('kode_produk', $produk->kode_produk) }}" required>
                    @error('kode_produk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="nama_produk" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nama_produk') is-invalid @enderror" 
                           id="nama_produk" name="nama_produk" value="{{ old('nama_produk', $produk->nama_produk) }}" required>
                    @error('nama_produk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label for="ukuran" class="form-label">Ukuran <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('ukuran') is-invalid @enderror" 
                           id="ukuran" name="ukuran" value="{{ old('ukuran', $produk->ukuran) }}" required>
                    @error('ukuran')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label for="bahan" class="form-label">Bahan <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('bahan') is-invalid @enderror" 
                           id="bahan" name="bahan" value="{{ old('bahan', $produk->bahan) }}" required>
                    @error('bahan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label for="satuan" class="form-label">Satuan <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('satuan') is-invalid @enderror" 
                           id="satuan" name="satuan" value="{{ old('satuan', $produk->satuan) }}" required>
                    @error('satuan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label for="keterangan" class="form-label">Keterangan</label>
                    <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                              id="keterangan" name="keterangan" rows="3">{{ old('keterangan', $produk->keterangan) }}</textarea>
                    @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <hr>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-warning"><i class="bi bi-save"></i> Update</button>
                <a href="{{ route('admin.produk.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection