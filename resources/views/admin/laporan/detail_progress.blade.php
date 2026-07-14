{{-- resources/views/admin/laporan/detail_progress.blade.php --}}
@extends('layouts.app')

@section('title', 'Detail Progress Produksi')
@section('page-title', 'Detail Progress Produksi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <span class="text-muted">PO: {{ $detailPo->purchaseOrder->no_po }} | Produk: {{ $detailPo->produk->nama_produk ?? '-' }}</span>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.laporan.detail_progress.export', $detailPo->id) }}" class="btn btn-danger">
            <i class="bi bi-file-earmark-pdf"></i> Cetak PDF
        </a>
        <a href="{{ route('admin.laporan.progress') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3">
                <div class="text-muted small">NO PO</div>
                <h5>{{ $detailPo->purchaseOrder->no_po }}</h5>
            </div>
            <div class="col-md-3">
                <div class="text-muted small">PROJECT</div>
                <h5>{{ $detailPo->purchaseOrder->project->no_project ?? '-' }}</h5>
            </div>
            <div class="col-md-3">
                <div class="text-muted small">SUPPLIER</div>
                <h5>{{ $detailPo->purchaseOrder->supplier->nama_supplier ?? '-' }}</h5>
            </div>
            <div class="col-md-3">
                <div class="text-muted small">STATUS PO</div>
                <h5>
                    <span class="badge-custom badge-{{ $detailPo->purchaseOrder->status_po == 'Selesai' ? 'success' : ($detailPo->purchaseOrder->status_po == 'Diproses' ? 'info' : 'warning') }}">
                        {{ $detailPo->purchaseOrder->status_po }}
                    </span>
                </h5>
            </div>
            <div class="col-md-3">
                <div class="text-muted small">KODE PRODUK</div>
                <h5><strong>{{ $detailPo->produk->kode_produk ?? '-' }}</strong></h5>
            </div>
            <div class="col-md-3">
                <div class="text-muted small">NAMA PRODUK</div>
                <h5><strong>{{ $detailPo->produk->nama_produk ?? '-' }}</strong></h5>
            </div>
            <div class="col-md-3">
                <div class="text-muted small">DEADLINE</div>
                <h5>{{ date('d-m-Y', strtotime($detailPo->purchaseOrder->deadline_po)) }}</h5>
            </div>
            <div class="col-md-3">
                <div class="text-muted small">QTY</div>
                <h5>{{ $detailPo->qty_selesai ?? 0 }} / {{ $detailPo->qty_po ?? 0 }} pcs</h5>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        @if(count($progressList) > 0)
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>TANGGAL</th>
                            <th>TAHAP 1</th>
                            <th>TAHAP 2</th>
                            <th>TAHAP 3</th>
                            <th>TAHAP 4</th>
                            <th>TAHAP 5</th>
                            <th>TAHAP 6</th>
                            <th>FINISHING</th>
                            <th>QC</th>
                            <th>MASUK GUDANG</th>
                            <th>CATATAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($progressList as $key => $progress)
                            <tr>
                                <td class="text-center">{{ $key + 1 }}</td>
                                <td>{{ date('d-m-Y', strtotime($progress['tanggal'])) }}</td>
                                <td class="text-center">{{ $progress['tahap_1'] }}</td>
                                <td class="text-center">{{ $progress['tahap_2'] }}</td>
                                <td class="text-center">{{ $progress['tahap_3'] }}</td>
                                <td class="text-center">{{ $progress['tahap_4'] }}</td>
                                <td class="text-center">{{ $progress['tahap_5'] }}</td>
                                <td class="text-center">{{ $progress['tahap_6'] }}</td>
                                <td class="text-center">{{ $progress['finishing'] }}</td>
                                <td class="text-center">{{ $progress['qc'] }}</td>
                                <td class="text-center">{{ $progress['gudang'] }}</td>
                                <td>{{ $progress['catatan'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state text-center py-5">
                <div class="empty-icon"><i class="bi bi-inbox"></i></div>
                <div class="empty-title">Belum Ada Progress</div>
                <div class="empty-desc">Belum ada progress untuk produk ini</div>
            </div>
        @endif
    </div>
</div>
@endsection