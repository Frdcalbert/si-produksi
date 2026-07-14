{{-- resources/views/progress/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Riwayat Progress')
@section('page-title', 'Riwayat Progress')

@section('content')
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
        <div>
            <i class="bi bi-box me-2"></i>
            <strong>{{ $detailPo->produk->nama_produk ?? '-' }}</strong>
            <span class="badge bg-light text-dark ms-2">{{ $detailPo->produk->kode_produk ?? '-' }}</span>
        </div>
        <div>
            <span class="badge bg-light text-dark">Qty: {{ $detailPo->qty_selesai }} / {{ $detailPo->qty_po }}</span>
        </div>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <span class="text-muted">PO: {{ $detailPo->purchaseOrder->no_po }}</span>
            <span class="text-muted ms-3">Project: {{ $detailPo->purchaseOrder->project->no_project ?? '-' }}</span>
        </div>

        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Tahap</th>
                        <th>Qty</th>
                        <th>Dokumentasi</th>
                        <th>Catatan</th>
                        <th>Status</th>
                        <th>Pelapor</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($detailPo->progressProduksi as $key => $progress)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ date('d-m-Y', strtotime($progress->tanggal_progress)) }}</td>
                            <td>
                                <span class="badge-custom badge-{{ 
                                    $progress->tahap_produksi == 'Masuk Gudang' ? 'success' : 
                                    ($progress->tahap_produksi == 'QC' ? 'info' : 'secondary') 
                                }}">
                                    {{ $progress->tahap_produksi }}
                                </span>
                            </td>
                            <td>{{ $progress->qty_progress }}</td>
                            <td>
                                @if($progress->dokumentasi)
                                    <a href="{{ $progress->dokumentasi }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-link"></i> Lihat
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $progress->catatan ?? '-' }}</td>
                            <td>
                                <span class="badge-custom badge-{{ $progress->status_progress == 'Selesai' ? 'success' : 'warning' }}">
                                    {{ $progress->status_progress }}
                                </span>
                            </td>
                            <td>{{ $progress->user->nama ?? '-' }}</td>
                            <td>
                                @if(auth()->user()->role === 'Staff')
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('staff.progress.edit', $progress->id) }}" class="btn btn-warning btn-sm">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('staff.progress.destroy', $progress->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center py-4 text-muted">Belum ada progress</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            <a href="{{ route(auth()->user()->role === 'Admin' ? 'admin.progress.index' : 'staff.progress.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>
@endsection