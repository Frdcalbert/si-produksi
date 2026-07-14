{{-- resources/views/admin/project/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Data Project')
@section('page-title', 'Data Project')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <span class="text-muted">Kelola Data Project</span>
    </div>
    <a href="{{ route('admin.project.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Project
    </a>
</div>

<div class="filter-section">
    <form action="{{ route('admin.project.index') }}" method="GET" class="row g-3 align-items-end">
        <div class="col-md-3">
            <label class="filter-label">Filter Status</label>
            <select name="filter" class="form-select" onchange="this.form.submit()">
                <option value="Berjalan" {{ ($filter ?? 'Berjalan') == 'Berjalan' ? 'selected' : '' }}>Berjalan</option>
                <option value="Selesai" {{ ($filter ?? '') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="Semua" {{ ($filter ?? '') == 'Semua' ? 'selected' : '' }}>Semua</option>
            </select>
        </div>
        <div class="col-md-5">
            <label class="filter-label">Cari Project</label>
            <div class="input-group">
                <input type="text" name="search" class="form-control" 
                       placeholder="Cari no project..." value="{{ $search ?? '' }}">
                <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
                @if(isset($search) && $search)
                    <a href="{{ route('admin.project.index', ['filter' => $filter ?? 'Berjalan']) }}" class="btn btn-secondary"><i class="bi bi-x-circle"></i></a>
                @endif
            </div>
        </div>
        <div class="col-md-4 text-end">
            <span class="badge-custom badge-secondary"><i class="bi bi-folder me-1"></i>Total: {{ $projects->count() }}</span>
        </div>
    </form>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Project</th>
                        <th>Tanggal</th>
                        <th>Deadline</th>
                        <th>Status</th>
                        <th>Total PO</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($projects as $key => $project)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td><strong>{{ $project->no_project }}</strong></td>
                            <td>{{ date('d-m-Y', strtotime($project->tanggal_project)) }}</td>
                            <td>{{ date('d-m-Y', strtotime($project->deadline_project)) }}</td>
                            <td>
                                <span class="badge-custom badge-{{ $project->status_project == 'Selesai' ? 'success' : 'warning' }}">
                                    {{ $project->status_project }}
                                </span>
                            </td>
                            <td class="text-center">{{ $project->purchaseOrders->count() }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.project.edit', $project->id) }}" class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.project.destroy', $project->id) }}" method="POST" class="d-inline">
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
                        <tr><td colspan="7" class="text-center py-4 text-muted">Tidak ada data project</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
{{-- PAGINATION --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center p-3 border-top gap-2">
            <div class="pagination-info">
                <i class="bi bi-info-circle me-1"></i>
                Menampilkan {{ $projects->firstItem() ?? 0 }} - {{ $projects->lastItem() ?? 0 }} 
                dari {{ $projects->total() }} data
            </div>
            <div>
                {{ $projects->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection